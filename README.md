# Volunite Backend API

Repository ini berisi source code backend (REST API) untuk aplikasi mobile **Volunite**, dibangun menggunakan Laravel.

## 📋 Prasyarat Sistem

Pastikan di komputer Anda sudah terinstall:

* PHP >= 8.1
* Composer
* MySQL / MariaDB

## 🚀 Cara Instalasi & Menjalankan

Ikuti langkah-langkah berikut untuk menjalankan server di local:

1. **Clone Repository & Install Dependensi**
```bash
git clone https://github.com/Mayadi21/volunite_backend.git
cd volunite_backend
composer install

```


2. **Konfigurasi Environment**
Salin file konfigurasi dan generate key aplikasi.
```bash
cp .env.example .env
php artisan key:generate

```


3. **Setup Database**
* Pastikan **MySQL/XAMPP** sudah berjalan.
* Buat database baru dengan nama: `volunite`.
* Buka file `.env`, sesuaikan konfigurasi DB:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=volunite
DB_USERNAME=root
DB_PASSWORD=

```




4. **Migrasi & Setup Storage**
Jalankan migrasi database, data dummy (seed), dan setup penyimpanan file.
```bash
php artisan migrate --seed
php artisan setup:storage

```


5. **Jalankan Server**
```bash
php artisan serve

```


Server akan berjalan di `http://127.0.0.1:8000`.

## 🔑 Akun Default (Seeding)

Setelah menjalankan perintah `migrate --seed`, Anda dapat masuk menggunakan akun berikut:

**Role Admin:**
Email: masgib@gmail.com
password: password123

**Role Organizer:**
Email: educare@gmail.com
password: password123

**Role volunteer:**
Email: alfi@gmail.com
password: password123

## 📱 Catatan untuk Frontend

Server backend akan berjalan (biasanya di `http://127.0.0.1:8000`). Pastikan `BASE_URL` pada konfigurasi frontend Flutter Anda sudah mengarah ke alamat IP lokal komputer Anda (bukan `localhost` jika menggunakan emulator Android, gunakan IP LAN seperti `192.168.x.x` atau `10.0.2.2`).