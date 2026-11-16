<?php

namespace App\Services;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Services\TransactionServiceInterface;
use App\Exceptions\InsufficientStockException;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionService implements TransactionServiceInterface
{
    public function __construct(private readonly TransactionRepositoryInterface $trans)
    {
    }

    public function create(array $data): Model
    {
        $startTime = microtime(true);
        $quantity = $data['quantity'] ?? 1;

        Log::info('Initiating transaction creation', [
            'medicine_id' => $data['medicine_id'],
            'quantity' => $quantity,
            'kasir_id' => Auth::id(),
        ]);

        try {
            $transaction = DB::transaction(function () use ($data, $quantity) {
                $medicine = Medicine::query()->where('id', $data['medicine_id'])->lockForUpdate()->firstOrFail();

                if ($medicine->stock < $quantity) {
                    Log::warning('Transaction aborted due to insufficient stock', [
                        'medicine_id' => $medicine->id,
                        'current_stock' => $medicine->stock,
                        'requested_quantity' => $quantity,
                        'kasir_id' => Auth::id(),
                    ]);

                    throw new InsufficientStockException("Stok obat '{$medicine->name}' tidak mencukupi. Tersisa {$medicine->stock}.");
                }

                $medicine->decrement('stock', $quantity);

                $data['kasir_id'] = Auth::id();
                unset($data['quantity']);

                return $this->trans->create($data);
            });

            $durationMs = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Transaction created successfully', [
                'transaction_id' => $transaction->id,
                'medicine_id' => $data['medicine_id'],
                'quantity' => $quantity,
                'duration_ms' => $durationMs,
                'kasir_id' => Auth::id(),
            ]);

            return $transaction;
        } catch (Throwable $e) {
            $durationMs = round((microtime(true) - $startTime) * 1000, 2);

            Log::error('Failed to create transaction', [
                'error_message' => $e->getMessage(),
                'duration_ms' => $durationMs,
                'medicine_id' => $data['medicine_id'],
                'kasir_id' => Auth::id(),
            ]);

            throw $e;
        }
    }
}
