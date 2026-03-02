-- SQL untuk membuat tabel products
-- Jalankan query ini di phpMyAdmin atau MySQL client

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10, 2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data (optional)
INSERT INTO `products` (`name`, `description`, `price`, `category`, `image`) VALUES
('Laptop Dell', 'Laptop gaming dengan spesifikasi tinggi', 12000000, 'Electronics', 'image/sample1.jpg'),
('Mouse Wireless', 'Mouse ergonomis dengan baterai tahan lama', 150000, 'Accessories', 'image/sample2.jpg'),
('Monitor 24 inch', 'Monitor Full HD dengan response time cepat', 2500000, 'Display', 'image/sample3.jpg');
