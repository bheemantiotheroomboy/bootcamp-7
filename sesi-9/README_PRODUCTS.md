# Setup Produk - Panduan Implementasi

## 1. Setup Database

Jalankan file SQL untuk membuat tabel products:
- Buka phpMyAdmin di browser
- Pilih database yang sesuai
- Klik tab "SQL"
- Copy & paste isi dari file `setup_products_table.sql`
- Klik "Go" atau Submit

Atau gunakan command line:
```bash
mysql -u username -p database_name < setup_products_table.sql
```

## 2. File Struktur

```
sesi-9/
├── image/                    # Folder untuk menyimpan gambar produk
├── admin/
│   └── products/
│       ├── create.php        # Form tambah produk
│       └── home.php          # List & kelola produk
├── components/
│   ├── navbar.php            # Navbar bootstrap
│   └── template.php          # Template utama dengan navbar
├── home.php                  # Halaman utama produk
├── koneksi.php               # Koneksi database
└── setup_products_table.sql  # Script SQL
```

## 3. Fitur Form Tambah Produk

### Input Fields:
- **Nama Produk** (required) - Text input
- **Kategori** (required) - Text input  
- **Harga** (required) - Number input
- **Deskripsi** (required) - Textarea
- **Gambar** (required) - File input

### Validasi:
✅ Semua field wajib diisi
✅ Harga harus angka positif
✅ Format gambar: JPG, PNG, GIF
✅ Ukuran maksimal: 5MB

### Proses Upload:
1. Gambar diunggah ke folder `/image/`
2. Nama file di-generate unik dengan prefix `product_`
3. Path menyimpan di database sebagai `image/product_xxxxx.jpg`
4. Validasi dilakukan sebelum & sesudah upload

## 4. Testing

### Test Form Tambah:
```bash
php -S localhost:8000 -t sesi-9
```

Buka di browser:
- Halaman utama: http://localhost:8000/home.php
- Admin produk: http://localhost:8000/admin/products/index.php
- Tambah produk: http://localhost:8000/admin/products/create.php

### Test Upload:
1. Klik "Tambah Produk"
2. Isi semua field
3. Pilih gambar dengan format yang sesuai
4. Submit form
5. Lihat data di halaman kelola produk
6. Gambar harus tampil di tabel

## 5. Database Schema

```sql
CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  category VARCHAR(50) NOT NULL,
  image VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

## 6. Tips & Troubleshooting

### Gambar tidak terupload:
- Pastikan folder `image/` memiliki permission 755
- Cek ukuran file tidak melebihi 5MB
- Format file harus JPG, PNG, atau GIF

### Koneksi database error:
- Periksa `koneksi.php` sudah benar
- Username & password sesuai dengan server MySQL
- Database sudah ada

### Gambar tidak muncul di tabel:
- Pastikan path image di database benar
- File gambar ada di folder `image/`
- Path relatif sudah benar dari halaman yang membacanya

## 7. Next Steps

Fitur yang bisa ditambahkan:
- ✏️ Edit produk (sudah tersedia di admin/products/edit.php)
- 🗑️ Hapus produk (sudah tersedia di daftar produk)
- 📸 Preview gambar sebelum upload
- 📊 Tabel dinamis dengan DataTables pada daftar produk (sudah terpasang)
- 🔍 Search & filter yang lebih canggih
- ⭐ Rating & review produk
