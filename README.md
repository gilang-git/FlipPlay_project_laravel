# FlipPlay REST API

Demo Modul 4 - Laravel REST API: CRUD, Eloquent ORM & Relationship.

Proyek ini melanjutkan pengembangan **FlipPlay** yang sebelumnya dibangun sebagai simulasi
OOP berbasis terminal pada Modul 3, dan kini dikembangkan menjadi REST API menggunakan
Laravel 12. Entitas **Media** (film/series) dan **Pengguna** direpresentasikan sebagai Model
Eloquent dengan relasi many-to-many (belongsToMany) untuk merepresentasikan koleksi media
milik pengguna.

## Entitas & Relasi

- **Media**: `title`, `type` (movie/series), `genre`, `release_year`, `description`
- **Pengguna**: `name`
- Relasi: `Media belongsToMany Pengguna` dan `Pengguna belongsToMany Media` melalui tabel
  pivot `media_pengguna`.

## Setup

1. `composer install`
2. Copy `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database (default: SQLite)
3. `php artisan key:generate`
4. Jika menggunakan SQLite, buat file database kosong: `touch database/database.sqlite`
5. `php artisan migrate`
6. `php artisan serve`

## Endpoint API

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/media` | Daftar seluruh media (bisa difilter `?search=judul`) |
| GET | `/api/media/{id}` | Detail satu media beserta pengguna yang mengoleksi |
| POST | `/api/media` | Tambah media baru |
| PATCH | `/api/media/{id}` | Ubah data media |
| DELETE | `/api/media/{id}` | Hapus media |
| GET | `/api/pengguna` | Daftar seluruh pengguna |
| GET | `/api/pengguna/{id}` | Detail satu pengguna beserta koleksi media |
| POST | `/api/pengguna` | Tambah pengguna baru |
| PATCH | `/api/pengguna/{id}` | Ubah data pengguna |
| DELETE | `/api/pengguna/{id}` | Hapus pengguna |
| POST | `/api/pengguna/{id}/koleksi` | Custom action: tambah media ke koleksi pengguna (body: `media_id`) |
| DELETE | `/api/pengguna/{id}/koleksi/{media_id}` | Custom action: hapus media dari koleksi pengguna |

Jalankan `php artisan route:list` untuk memverifikasi seluruh route terdaftar.

## Validasi & Error Handling

- Form Request Validation (`StoreMediaRequest`, `StorePenggunaRequest`, `AddToCollectionRequest`)
  menghasilkan status `422` jika input tidak valid, termasuk judul media kosong atau duplikat.
- Menambahkan media yang sama dua kali ke koleksi pengguna yang sama akan menghasilkan
  status `409 Conflict`.
- Mengakses `id` yang tidak ditemukan akan menghasilkan status `404 Not Found`.

## Pengujian

Seluruh endpoint diuji menggunakan Postman, mencakup skenario berhasil (happy path) dan
skenario gagal (failure path), misalnya validasi input kosong/duplikat dan penambahan media
duplikat ke koleksi pengguna.
