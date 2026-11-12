# Dokumentasi API Apotek

Panduan singkat untuk frontend.

## Informasi Umum

| Item | Nilai |
|---|---|
| Base URL | `http://localhost:8000/api` |
| Format | JSON |
| Autentikasi | JWT (Bearer Token) |
| Masa berlaku token | 60 menit (default) |

### Header wajib

Semua request:

```
Accept: application/json
Content-Type: application/json
```

Endpoint yang butuh login, tambahkan:

```
Authorization: Bearer <access_token>
```

### Role

| Role | Akses |
|---|---|
| `admin` | Semua endpoint |
| `kasir` | Login, profil sendiri, lihat data obat |

---

## Format Response

### Sukses (data tunggal / aksi)

```json
{
  "success": true,
  "message": "OK",
  "data": {}
}
```

### Sukses (list dengan pagination)

Endpoint list (`GET /medicines`, `GET /users`) memakai format pagination Laravel:

```json
{
  "data": [],
  "links": {
    "first": "http://localhost:8000/api/medicines?page=1",
    "last": "http://localhost:8000/api/medicines?page=3",
    "prev": null,
    "next": "http://localhost:8000/api/medicines?page=2"
  },
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 40
  }
}
```

### Error

| Status | Arti | Contoh body |
|---|---|---|
| 401 | Belum login / token salah / token kedaluwarsa | `{"message": "Unauthenticated."}` |
| 403 | Role tidak punya akses | `{"message": "Akses ditolak"}` |
| 404 | Data tidak ditemukan | `{"message": "No query results for model ..."}` |
| 422 | Validasi gagal | lihat di bawah |

Contoh 422:

```json
{
  "message": "The email field is required.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

Tampilkan pesan error per field dari objek `errors`.

---

## 1. Autentikasi

### Register

`POST /auth/register`

Akun baru otomatis berrole `kasir`.

Body:

```json
{
  "name": "Budi",
  "email": "budi@apotek.test",
  "password": "password123",
  "password_confirmation": "password123"
}
```

Response `201`:

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1Qi...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
      "id": 2,
      "name": "Budi",
      "email": "budi@apotek.test",
      "role": "kasir"
    }
  }
}
```

### Login

`POST /auth/login`

Body:

```json
{
  "email": "admin@apotek.test",
  "password": "password123"
}
```

Response `200`: sama seperti register, dengan `message` "Login berhasil".

Gagal login: `401` dengan `{"message": "Email atau password salah"}`.

### Profil saya

`GET /auth/me` (butuh token)

Response `200`:

```json
{
  "success": true,
  "message": "OK",
  "data": {
    "id": 1,
    "name": "Apoteker Admin",
    "email": "admin@apotek.test",
    "role": "admin",
    "created_at": "2026-11-12 09:00:00"
  }
}
```

### Refresh token

`POST /auth/refresh` (butuh token)

Panggil sebelum token kedaluwarsa. Response berisi `access_token` baru, ganti token lama dengan yang ini.

### Logout

`POST /auth/logout` (butuh token)

Token langsung tidak berlaku. Hapus token dari penyimpanan di frontend.

---

## 2. Obat (Medicines)

### Daftar obat

`GET /medicines` (semua role)

Query opsional:

| Param | Keterangan |
|---|---|
| `search` | Cari berdasarkan nama |
| `category` | Filter kategori (persis sama) |
| `page` | Nomor halaman |

Contoh: `GET /medicines?search=para&category=Analgesik&page=1`

Item di `data`:

```json
{
  "id": 1,
  "sku": "PCT-500",
  "name": "Paracetamol 500mg",
  "category": "Analgesik",
  "price": 5000,
  "stock": 200,
  "requires_prescription": false,
  "expired_at": "2028-11-12"
}
```

`price` dalam rupiah (integer), format tampilan diatur frontend.

### Detail obat

`GET /medicines/{id}` (semua role)

Response `200`: objek obat di dalam `data`.

### Tambah obat

`POST /medicines` (admin)

Body:

```json
{
  "sku": "AMX-500",
  "name": "Amoxicillin 500mg",
  "category": "Antibiotik",
  "price": 12000,
  "stock": 100,
  "requires_prescription": true,
  "expired_at": "2028-12-31"
}
```

Aturan validasi:

| Field | Aturan |
|---|---|
| `sku` | wajib, unik, maks 50 karakter (disimpan huruf besar) |
| `name` | wajib, maks 150 karakter |
| `category` | wajib, maks 100 karakter |
| `price` | wajib, integer, minimal 0 |
| `stock` | wajib, integer, minimal 0 |
| `requires_prescription` | boolean |
| `expired_at` | wajib, tanggal `YYYY-MM-DD`, harus setelah hari ini |

Response `201`.

### Ubah obat

`PUT /medicines/{id}` (admin)

Kirim semua field seperti tambah obat (bukan parsial). Response `200`.

### Hapus obat

`DELETE /medicines/{id}` (admin)

Response `200`:

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null
}
```

---

## 3. User (khusus admin)

### Daftar user

`GET /users`

Query opsional: `search` (nama atau email), `role` (`admin` / `kasir`), `page`.

Item di `data`:

```json
{
  "id": 2,
  "name": "Budi",
  "email": "budi@apotek.test",
  "role": "kasir",
  "created_at": "2026-11-12 09:30:00"
}
```

### Detail user

`GET /users/{id}`

### Tambah user

`POST /users`

```json
{
  "name": "Siti",
  "email": "siti@apotek.test",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "kasir"
}
```

`role` hanya boleh `admin` atau `kasir`. Response `201`.

### Ubah user

`PUT /users/{id}`

Boleh kirim sebagian field saja. Jika `password` kosong atau tidak dikirim, password tidak berubah.

```json
{
  "name": "Siti Aminah",
  "role": "admin"
}
```

### Hapus user

`DELETE /users/{id}`

Admin tidak bisa menghapus akunnya sendiri (response `403`).

---

## Contoh Pemakaian di Frontend

### Helper request

```js
const BASE_URL = "http://localhost:8000/api";

async function request(path, { method = "GET", body } = {}) {
  const token = localStorage.getItem("token");

  const response = await fetch(`${BASE_URL}${path}`, {
    method,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
    body: body ? JSON.stringify(body) : undefined,
  });

  const result = await response.json().catch(() => null);

  if (response.status === 401) {
    localStorage.removeItem("token");
    window.location.href = "/login";
  }

  if (!response.ok) {
    throw { status: response.status, ...result };
  }

  return result;
}
```

### Login

```js
async function login(email, password) {
  const result = await request("/auth/login", {
    method: "POST",
    body: { email, password },
  });

  localStorage.setItem("token", result.data.access_token);
  localStorage.setItem("role", result.data.user.role);

  return result.data.user;
}
```

### Ambil daftar obat

```js
async function getMedicines(search = "", page = 1) {
  const params = new URLSearchParams({ search, page });
  return request(`/medicines?${params}`);
}
```

### Tampilkan error validasi

```js
try {
  await request("/medicines", { method: "POST", body: formData });
} catch (error) {
  if (error.status === 422) {
    Object.entries(error.errors).forEach(([field, messages]) => {
      console.log(field, messages[0]);
    });
  }
}
```

---

## Catatan untuk Frontend

- Simpan token di `localStorage` (atau memori untuk keamanan lebih baik).
- Sembunyikan menu tambah, ubah, dan hapus untuk role `kasir`. Backend tetap menolak dengan 403, tapi UI sebaiknya tidak menampilkannya.
- Jika menerima `401` di endpoint selain login, arahkan user ke halaman login.
- Refresh token sebelum 60 menit habis, misalnya saat token tersisa kurang dari 5 menit.
- Kirim `password_confirmation` setiap kali mengirim `password` pada register, tambah user, atau ubah user.
