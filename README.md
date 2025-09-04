# Laravel + Filament Project

Proyek ini dibangun menggunakan **Laravel 12** dan **Filament 3.3** sebagai admin panel.  
Dokumentasi ini menjelaskan cara instalasi, konfigurasi, dan menjalankan project.

---

## 🚀 Persyaratan

Pastikan environment sudah memenuhi persyaratan berikut:

-   **PHP**: `^8.2`
-   **Composer**: `^2.6`
-   **Node.js & NPM**: `^18.x` / `^20.x`
-   **Database**: MySQL / PostgreSQL / SQLite
-   **Git** (opsional, untuk clone repo)

---

## 📥 Instalasi

1. **Clone Repository**

    ```bash
    git clone https://github.com/username/nama-project.git
    cd nama-project
    ```

2. **Install Dependency PHP**

    ```bash
    composer install
    ```

3. **Install Dependency Frontend**

    ```bash
    npm install
    ```

4. **Buat File `.env`**

    ```bash
    cp .env.example .env
    ```

5. **Generate Key**

    ```bash
    php artisan key:generate
    ```

6. **Atur Database**  
   Edit konfigurasi database di file `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. **Jalankan Migrasi**

    ```bash
    php artisan migrate
    ```

---

## 🖥️ Menjalankan Project

Terdapat beberapa cara untuk menjalankan project ini:

### 1. Jalankan Secara Manual

-   Jalankan backend (Laravel server):

    ```bash
    php artisan serve
    ```

-   Jalankan frontend (Vite):

    ```bash
    npm run dev
    ```

### 2. Jalankan Semua Sekaligus (Dev Mode)

Project ini sudah menambahkan script **`dev`** di `composer.json` dengan bantuan `concurrently`.  
Jalankan dengan:

```bash
composer run dev
```

Script ini akan otomatis menjalankan:

-   Laravel server
-   Queue listener
-   Laravel Pail (real-time logs)
-   Vite (frontend dev server)

---

## 🧪 Testing

Jalankan unit test dengan perintah:

```bash
composer test
```

Atau langsung:

```bash
php artisan test
```

---

## ⚙️ Tools & Packages

-   **Laravel Framework** `^12.0`
-   **Filament Admin Panel** `3.3`
-   **Laravel Sail** (opsional untuk Docker)
-   **Laravel Pint** (formatter)
-   **PHPUnit** `^11`
-   **FakerPHP** (dummy data)

---

## 📌 Catatan

-   Gunakan `composer update` atau `composer install` setelah update dependency.
-   Jika ada perubahan schema database, jalankan:

    ```bash
    php artisan migrate:fresh --seed
    ```
