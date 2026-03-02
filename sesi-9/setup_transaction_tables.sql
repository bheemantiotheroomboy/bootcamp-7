-- SQL untuk membuat tabel transaction dan transaction_item

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `total` decimal(12, 2) NOT NULL,
  `user_id` int(11),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `transaction_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(12, 2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (transaction_id) REFERENCES `transaction`(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample transaction data (optional)
INSERT INTO `transaction` (`status`, `total`, `user_id`) VALUES
('completed', 5500000, NULL),
('pending', 2650000, NULL);

-- You can add transaction items if needed
