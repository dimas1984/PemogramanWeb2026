# ---------------------------------------------------------------------------
# SIMPUS-Mini - image untuk deployment ke Render (Jobsheet 14)
# Penjelasan baris per baris: Dokumentasi/02-dockerfile-php-apache.md
# ---------------------------------------------------------------------------

# Image dasar resmi PHP yang sudah berisi Apache sebagai web server.
FROM php:8.3-apache

# Pasang ekstensi pdo_pgsql. Tanpa ini PDO tidak mengenal driver "pgsql"
# dan includes/koneksi.php akan gagal dengan "could not find driver".
# libpq-dev adalah pustaka klien PostgreSQL yang dibutuhkan saat kompilasi.
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev \
 && docker-php-ext-install pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

# Pakai konfigurasi PHP bawaan untuk produksi (display_errors = Off),
# supaya pesan error PHP tidak tampil ke pengunjung.
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Salin seluruh isi folder jobsheet-14 ke document root Apache.
COPY . /var/www/html/

# Render menentukan sendiri nomor port lewat environment variable PORT.
# Nilai 10000 di bawah hanya cadangan saat image dijalankan di komputer sendiri.
ENV PORT=10000

# Tulis ulang konfigurasi Apache agar mendengarkan ${PORT}, bukan port 80.
# Tanda kutip tunggal menjaga ${PORT} tetap literal di dalam file konfigurasi,
# sehingga yang membacanya adalah Apache saat container berjalan -- bukan
# shell saat image dibangun.
RUN printf 'Listen ${PORT}\n' > /etc/apache2/ports.conf \
 && printf '<VirtualHost *:${PORT}>\n\tDocumentRoot /var/www/html\n\tErrorLog ${APACHE_LOG_DIR}/error.log\n\tCustomLog ${APACHE_LOG_DIR}/access.log combined\n</VirtualHost>\n' \
      > /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

# apache2-foreground menjalankan Apache di depan (tidak jadi daemon),
# karena sebuah container berhenti begitu proses utamanya selesai.
CMD ["apache2-foreground"]
