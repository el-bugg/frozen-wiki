# Frozen Wiki - Dota 2 Knowledge Base

Frozen Wiki adalah aplikasi web berbasis Laravel yang berfungsi sebagai ensiklopedia dan forum komunitas untuk pemain Dota 2.

## Fitur
- Manajemen user dan autentikasi
- Postingan, komentar, dan like
- Manajemen hero, item, dan build
- Kategori dan kemampuan
- Dashboard komunitas
- Jadwal esports & patch notes

## Komponen & Teknologi
**Backend:** Laravel 11 (PHP 8.2+), SQLite/MySQL, Laravel Breeze  
**Frontend:** Bootstrap 5.3, custom CSS (`frozen-theme.css`), Bootstrap Icons, Cinzel & Roboto font

## Instalasi
### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- Git

### Langkah Instalasi
1. Clone repository:
   ```bash
   git clone <repo-url>
   cd frozen-wiki
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Konfigurasi environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Setup database (SQLite):
   - Edit file `.env` dan pastikan:
     ```env
     DB_CONNECTION=sqlite
     ```
5. Migrasi database:
   ```bash
   php artisan migrate --seed
   ```
6. Setup storage:
   ```bash
   php artisan storage:link
   ```
7. Build frontend & jalankan aplikasi:
   ```bash
   npm run build
   php artisan serve
   ```

## Struktur Folder
- `app/` - Kode utama aplikasi
- `database/` - Migrasi, seeder, database
- `public/` - File publik
- `resources/` - View, CSS, JS
- `routes/` - Routing
- `tests/` - Pengujian

