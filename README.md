# Community Waste Collection API

## Teknologi dan Modul
- Framework: Laravel
- Database: MongoDB
- API Tools: Postman


## Waste Pickup API (Penjemputan Sampah)
| Endpoint                           | Deskripsi                                                                                            |
| ---------------------------------- | ---------------------------------------------------------------------------------------------------- |
| **POST /api/pickups**              | Membuat permintaan penjemputan sampah (tipe sampah wajib diisi)                                      |
| **GET /api/pickups**               | Menampilkan daftar penjemputan (bisa filter berdasarkan status, tipe, ID rumah tangga, dan paginasi) |
| **PUT /api/pickups/{id}/schedule** | Menjadwalkan penjemputan (isi tanggal penjemputan & ubah status)                                     |
| **PUT /api/pickups/{id}/complete** | Menandai penjemputan selesai                                                                         |
| **PUT /api/pickups/{id}/cancel**   | Membatalkan penjemputan                                                                              |

## Payment API (Pembayaran)
| Endpoint                           | Deskripsi                                                                                |
| ---------------------------------- | ---------------------------------------------------------------------------------------- |
| **POST /api/payments**             | Membuat data pembayaran (dihubungkan dengan rumah tangga)                                |
| **GET /api/payments**              | Menampilkan daftar pembayaran (filter berdasarkan status, rumah tangga, rentang tanggal) |
| **PUT /api/payments/{id}/confirm** | Konfirmasi pembayaran                                                                    |


## Reporting API (Laporan)
| Endpoint                                     | Deskripsi                                                        |
| -------------------------------------------- | ---------------------------------------------------------------- |
| **GET /api/reports/waste-summary**           | Ringkasan jumlah penjemputan berdasarkan jenis sampah & status   |
| **GET /api/reports/payment-summary**         | Ringkasan pembayaran berdasarkan status + total pendapatan       |
| **GET /api/reports/households/{id}/history** | Riwayat penjemputan + riwayat pembayaran untuk satu rumah tangga |

# monggo dll cari yang cocok
https://github.com/mongodb/mongo-php-driver/releases/
tambahkan dll ke
1. C:\wamp64\bin\php\php8.2.26\php.ini
2. C:\wamp64\bin\apache\apache2.x.x\bin\php.ini

# tambahkan manual
extension=php_mongodb.dll

# cek dengan perintah
php -m | findstr mongo

# library tambahan
composer require mongodb/laravel-mongodb

# membersihkan cache
php artisan config:clear
php artisan cache:clear


# contoh bikin
- php artisan make:controller HouseholdController --api
- php artisan make:request HouseholdRequest
- php artisan make:resource HouseholdResource
- composer require crestapps/laravel-code-generator --dev
- php artisan make:class HouseholdService
- php artisan make:class HouseholdRepository

# link dokumentasi
- https://documenter.getpostman.com/view/952664/2sB3dQwVwn

# Fungsi
| Layer          | Fungsi                                        | Analogi            |
| -------------- | --------------------------------------------- | ------------------ |
| **Controller** | Terima request → panggil service → kirim JSON | Resepsionis        |
| **Request**    | Validasi input                                | Satpam pintu depan |
| **Service**    | Aturan bisnis, proses aplikasi                | Manajer            |
| **Repository** | Akses database, query                         | Tukang arsip data  |
| **Resource**   | Format JSON output                            | Editor laporan     |
