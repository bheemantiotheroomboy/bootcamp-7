# Fitur Transaksi & Keranjang - Panduan Lengkap

## 1. Setup Database Transaksi

Jalankan SQL untuk membuat tabel transaksi:
```bash
mysql -u username -p database_name < setup_transaction_tables.sql
```

Atau buka `setup_transaction_tables.sql` di phpMyAdmin dan jalankan.

## 2. Struktur Tabel

### Tabel Transaction
```sql
transaction (
  id INT PRIMARY KEY AUTO_INCREMENT,
  status VARCHAR(50),         -- pending, completed, cancelled
  total DECIMAL(12,2),
  user_id INT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Tabel Transaction Item
```sql
transaction_item (
  id INT PRIMARY KEY AUTO_INCREMENT,
  transaction_id INT,
  product_id INT,
  quantity INT,
  total_price DECIMAL(12,2),
  created_at TIMESTAMP
)
```

## 3. Fitur Keranjang

### File yang Terlibat
- **cart.php** - Menampilkan & mengelola keranjang
- **checkout.php** - Form data pembeli & proses pembayaran
- **transaction-list.php** - Riwayat semua transaksi
- **transaction-detail.php** - Detail transaksi & item

### Alur Keranjang
1. User di halaman utama (`home.php`) memilih produk dengan jumlah
2. Klik tombol **Cart** → produk ditambahkan ke session PHP
3. Keranjang disimpan di `$_SESSION['cart']` dengan struktur:
   ```php
   $_SESSION['cart'] = [
       product_id => [
           'id' => ...,
           'name' => ...,
           'price' => ...,
           'image' => ...,
           'quantity' => ...
       ]
   ]
   ```

### Fitur Cart
✅ Tambah produk ke keranjang
✅ Ubah jumlah produk
✅ Hapus produk dari keranjang
✅ Lihat total & jumlah item
✅ Checkout dari keranjang

## 4. Fitur Checkout

### File: checkout.php
- Form data pembeli (Nama, Email, Phone, Alamat)
- Tampilkan ringkasan belanja
- Proses pembayaran (simpan ke database)
- Gunakan transaction untuk keamanan database

### Proses Checkout
1. User klik **Checkout** di halaman keranjang
2. Isi form data pembeli
3. Klik **Proses Pembayaran**
4. Data transaksi & item disimpan ke database
5. Keranjang session dihapus
6. Redirect ke halaman sukses dengan ID transaksi

## 5. Riwayat Transaksi

### File: transaction-list.php
- Tampilkan semua transaksi
- Tampilkan status, total, dan tanggal
- Link untuk melihat detail

### File: transaction-detail.php
- Detail transaksi (ID, status, total, tanggal)
- Daftar semua item dalam transaksi
- Unit price, jumlah, total per item

## 6. Testing

### Test Alur Keranjang
```bash
php -S localhost:8000 -t sesi-9
```

1. Buka: http://localhost:8000/home.php
2. Tambahkan beberapa produk ke keranjang
3. Klik tombol **Keranjang** (di atas),atau buka http://localhost:8000/cart.php
4. Lihat produk yang ditambahkan
5. Update jumlah atau hapus produk
6. Klik **Checkout**
7. Isi form data pembeli
8. Klik **Proses Pembayaran**
9. Data sudah tersimpan di database
10. Lihat riwayat: http://localhost:8000/transaction-list.php

### Test Query Database
```sql
-- Lihat semua transaksi
SELECT * FROM transaction;

-- Lihat item dalam transaksi
SELECT * FROM transaction_item;

-- Lihat detail transaksi lengkap
SELECT t.*, ti.quantity, ti.total_price, p.name
FROM transaction t
JOIN transaction_item ti ON t.id = ti.transaction_id
JOIN products p ON ti.product_id = p.id
WHERE t.id = 1;
```

## 7. Keamanan & Notes

### Session Management
- Cart disimpan di `$_SESSION['cart']`
- Session berlaku selama browser terbuka
- Untuk persistent storage, gunakan database

### Input Validation
✅ Semua input divalidasi
✅ XSS protection dengan `htmlspecialchars()`
✅ SQL injection protection dengan `mysqli_real_escape_string()`

### Enhancement Ideas
- [ ] Update status transaksi (pending → completed)
- [ ] Email notifikasi setelah checkout
- [ ] Shipping cost & tax calculation
- [ ] Payment gateway integration (Midtrans, etc)
- [ ] User login system & user_id tracking
- [ ] Inventory management (kurangi stock saat checkout)
- [ ] Admin dashboard untuk manage transaksi

## 8. File Struktur Lengkap

```
sesi-9/
├── home.php                       ← halaman utama dengan produk
├── cart.php                       ← halaman keranjang
├── checkout.php                   ← form checkout
├── transaction-list.php           ← riwayat transaksi
├── transaction-detail.php         ← detail transaksi
├── setup_transaction_tables.sql   ← setup database
├── admin/
│   └── products/
│       ├── index.php
│       ├── create.php
│       └── edit.php
├── image/                         ← folder gambar produk
└── components/
    ├── template.php
    └── navbar.php
```

## 9. TODO untuk Production

- [ ] Move user_id ke real user (login system)
- [ ] Add invoice generation (PDF)
- [ ] Add order tracking
- [ ] Integrate payment gateway
- [ ] Add shipping calculation
- [ ] Email notifications
- [ ] Admin panel untuk manage semua transaksi
- [ ] Customer support chat
