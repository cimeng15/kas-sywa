# Panduan Installasi KasKeluarga di aaPanel

## Prasyarat

Pastikan aaPanel sudah terinstall dengan stack berikut:
- **Nginx** (versi 1.24+)
- **PHP** (versi 8.2+) dengan ekstensi yang dibutuhkan
- **MySQL** (versi 8.0+) atau MariaDB 10.6+
- **Composer** terinstall di server

---

## Langkah 1: Persiapan di aaPanel

### 1.1 Buat Database

1. Login ke aaPanel
2. Buka menu **Database** → klik **Add Database**
3. Isi:
   - **Database Name**: `kaskeluarga`
   - **Username**: `kaskeluarga`
   - **Password**: (gunakan password kuat, catat!)
   - **Access**: `Local`
4. Klik **Submit**

### 1.2 Buat Website

1. Buka menu **Website** → klik **Add Site**
2. Isi:
   - **Type**: PHP Project
   - **Domain**: `keuangan.domainanda.com` (atau IP server)
   - **PHP Version**: 8.2 (atau lebih tinggi)
   - **Database**: Tidak perlu (sudah dibuat manual)
   - **SSL**: Aktifkan nanti setelah install
3. Klik **Submit**

---

## Langkah 2: Upload Source Code

### Opsi A: Git Clone (Rekomendasi)

```bash
# SSH ke server
cd /www/wwwroot/keuangan.domainanda.com

# Hapus file default aaPanel
rm -rf index.html 404.html

# Clone repository (jika sudah di-push ke git)
git clone https://github.com/username/kaskeluarga.git .
```

### Opsi B: Upload Manual

1. Compress folder project (kecuali `node_modules`, `vendor`, `.env`)
2. Upload `.zip` via aaPanel **File** manager
3. Extract ke `/www/wwwroot/keuangan.domainanda.com/`
4. Hapus file `.zip`

---

## Langkah 3: Install Dependencies

SSH ke server, lalu:

```bash
cd /www/wwwroot/keuangan.domainanda.com

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies & build assets
npm install
npm run build
```

> Jika `npm` tidak tersedia, install via aaPanel **App Store** → Node.js Manager, atau jalankan build di lokal lalu upload folder `public/build/`.

---

## Langkah 4: Konfigurasi .env

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:

```env
APP_NAME=KasKeluarga
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxx     # otomatis dari key:generate
APP_DEBUG=false
APP_URL=https://keuangan.domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kaskeluarga
DB_USERNAME=kaskeluarga
DB_PASSWORD=password_database_anda

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

---

## Langkah 5: Migrasi Database & Seed

```bash
php artisan migrate --seed
```

Jika ingin reset total:

```bash
php artisan migrate:fresh --seed
```

---

## Langkah 6: Storage Link

```bash
php artisan storage:link
```

---

## Langkah 7: Set Permission

```bash
cd /www/wwwroot/keuangan.domainanda.com

# Set owner ke www user (aaPanel)
chown -R www:www .

# Set permission folder
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# Permission khusus
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/storage
```

---

## Langkah 8: Konfigurasi Nginx

1. aaPanel → **Website** → klik nama domain → **Config**
2. Ganti **root directory** ke:

```
root /www/wwwroot/keuangan.domainanda.com/public;
```

3. Tambahkan konfigurasi berikut di blok `server { }`:

```nginx
# Laravel pretty URL
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# Blokir akses file sensitif
location ~ /\.(env|git|gitignore) {
    deny all;
}

# Cache static assets
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    try_files $uri =404;
}

# PHP-FPM
location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass unix:/tmp/php-cgi-82.sock;
    fastcgi_index index.php;
    include fastcgi.conf;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

> Sesuaikan `php-cgi-82.sock` dengan versi PHP yang dipakai.

4. Klik **Save**

---

## Langkah 9: Aktifkan SSL

1. aaPanel → **Website** → domain → **SSL**
2. Pilih **Let's Encrypt**
3. Klik **Apply**
4. Aktifkan **Force HTTPS**

---

## Langkah 10: Optimasi Production

```bash
cd /www/wwwroot/keuangan.domainanda.com

# Cache config & route
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Pastikan folder cache writable
chmod -R 775 storage bootstrap/cache
```

> Setiap kali ada perubahan kode, jalankan:
> ```bash
> php artisan optimize:clear
> php artisan config:cache
> php artisan route:cache
> php artisan view:cache
> ```

---

## Langkah 11: Setup Cron (Opsional)

Untuk notifikasi jatuh tempo otomatis:

```bash
php artisan schedule:run
```

Tambahkan di aaPanel → **Cron**:

```
* * * * * cd /www/wwwroot/keuangan.domainanda.com && php artisan schedule:run >> /dev/null 2>&1
```

---

## Akun Default (Setelah Seed)

| Username | Password | Role |
|----------|----------|------|
| `bapak` | `password` | Orang Tua |
| `anak1` | `password` | Anak |
| `anak2` | `password` | Anak |

> **PENTING**: Setelah login, ganti password semua akun melalui menu Profil.

---

## Troubleshooting

### 500 Internal Server Error

```bash
# Cek log
cat storage/logs/laravel.log | tail -50

# Cek permission
chown -R www:www .
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan optimize:clear
```

### Asset CSS/JS tidak muncul

```bash
npm run build
php artisan optimize:clear
```

### Database connection refused

- Cek kredensial di `.env`
- Pastikan database user punya akses ke database
- aaPanel → Database → klik **Permission** → set ke `All privileges` atau `Local`

### Upload avatar gagal

```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### Halaman blank / putih

```bash
# Aktifkan debug sementara
# Edit .env: APP_DEBUG=true
php artisan optimize:clear
# Cek error, lalu kembalikan APP_DEBUG=false
```

---

## Backup

### Database
```bash
mysqldump -u kaskeluarga -p kaskeluarga > backup_kaskeluarga_$(date +%Y%m%d).sql
```

### Files
Backup folder berikut:
- `storage/app/` (avatar, file upload)
- `.env` (konfigurasi)

---

## Catatan Keamanan

1. Ganti `APP_DEBUG=false` di production
2. Ganti semua password default setelah install
3. Aktifkan SSL/HTTPS
4. Backup database berkala
5. Update Laravel & dependencies secara rutin:
   ```bash
   composer update
   php artisan optimize:clear
   ```
