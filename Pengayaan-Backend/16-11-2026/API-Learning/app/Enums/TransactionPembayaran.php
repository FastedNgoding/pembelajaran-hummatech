<?php

namespace App\Enums;

enum TransactionPembayaran: string
{
    case cash = 'cash';
    case qris = 'qris';
    case tf = 'tf';
}
