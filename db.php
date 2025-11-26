<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "login_register";  

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $checkColumn = $conn->query("SHOW COLUMNS FROM `users` LIKE 'role'");
    if ($checkColumn && $checkColumn->num_rows == 0) {
        $alterSql = "ALTER TABLE `users` ADD COLUMN `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer' AFTER `password`";
        $conn->query($alterSql);
    }
} catch (mysqli_sql_exception $e) {
    $conn = new mysqli($servername, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql) === TRUE) {
        $conn->select_db($dbname);
        
        $tableCheck = $conn->query("SHOW TABLES LIKE 'users'");
        if ($tableCheck->num_rows == 0) {
            $sql = "CREATE TABLE `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `full_name` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer',
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if (!$conn->query($sql)) {
                die("Error creating table: " . $conn->error);
            }
        } else {
            $checkColumn = $conn->query("SHOW COLUMNS FROM `users` LIKE 'role'");
            if ($checkColumn && $checkColumn->num_rows == 0) {
                $alterSql = "ALTER TABLE `users` ADD COLUMN `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer' AFTER `password`";
                $conn->query($alterSql);
            }
        }
        
        $ordersTableCheck = $conn->query("SHOW TABLES LIKE 'orders'");
        if ($ordersTableCheck->num_rows == 0) {
            $ordersSql = "CREATE TABLE `orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `shop_name` varchar(255) NOT NULL,
                `customer_name` varchar(255) NOT NULL,
                `customer_email` varchar(255) NOT NULL,
                `customer_phone` varchar(50) NOT NULL,
                `customer_address` text NOT NULL,
                `services` text NOT NULL,
                `item_count` int(11) NOT NULL,
                `urgency` varchar(50) NOT NULL DEFAULT 'normal',
                `special_instructions` text,
                `pickup_date` date NOT NULL,
                `pickup_time` time NOT NULL,
                `status` enum('pending','confirmed','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
                `total_price` decimal(10,2) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `status` (`status`),
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if (!$conn->query($ordersSql)) {
                $ordersSql = str_replace(', FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE', '', $ordersSql);
                $conn->query($ordersSql);
            }
        }
    } else {
        die("Error creating database: " . $conn->error);
    }
}

if (!$conn->connect_error) {
    $ordersTableCheck = $conn->query("SHOW TABLES LIKE 'orders'");
    if ($ordersTableCheck->num_rows == 0) {
        $ordersSql = "CREATE TABLE `orders` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `customer_id` int(11) NOT NULL,
            `shop_name` varchar(255) NOT NULL,
            `service_type` varchar(255) NOT NULL,
            `total_amount` decimal(10,2) NOT NULL,
            `payment_method` varchar(100) DEFAULT 'Cash on Delivery',
            `delivery_option` enum('pickup','delivery') DEFAULT 'pickup',
            `notes` text,
            `status` enum('pending','confirmed','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `customer_id` (`customer_id`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $conn->query($ordersSql);
    } else {
        // Check if delivery_option column exists, add if missing
        $checkColumn = $conn->query("SHOW COLUMNS FROM `orders` LIKE 'delivery_option'");
        if (!$checkColumn || $checkColumn->num_rows == 0) {
            $alterSql = "ALTER TABLE `orders` ADD COLUMN `delivery_option` enum('pickup','delivery') DEFAULT 'pickup'";
            $conn->query($alterSql);
        }
        
        // Check if payment_status column exists, add if missing
        $checkColumn = $conn->query("SHOW COLUMNS FROM `orders` LIKE 'payment_status'");
        if (!$checkColumn || $checkColumn->num_rows == 0) {
            $alterSql = "ALTER TABLE `orders` ADD COLUMN `payment_status` enum('pending','completed') DEFAULT 'pending'";
            $conn->query($alterSql);
        }
        
        // Check if customer_id exists, rename from user_id if needed
        $checkColumn = $conn->query("SHOW COLUMNS FROM `orders` LIKE 'customer_id'");
        if (!$checkColumn || $checkColumn->num_rows == 0) {
            $checkUserIdColumn = $conn->query("SHOW COLUMNS FROM `orders` LIKE 'user_id'");
            if ($checkUserIdColumn && $checkUserIdColumn->num_rows > 0) {
                $conn->query("ALTER TABLE `orders` CHANGE COLUMN `user_id` `customer_id` int(11) NOT NULL");
            } else {
                $conn->query("ALTER TABLE `orders` ADD COLUMN `customer_id` int(11) NOT NULL");
            }
        }
    }
}

if (!$conn->connect_error) {
    $reviewsTableCheck = $conn->query("SHOW TABLES LIKE 'reviews'");
    if ($reviewsTableCheck->num_rows == 0) {
        $reviewsSql = "CREATE TABLE `reviews` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `shop_name` varchar(255) NOT NULL,
            `user_id` int(11) DEFAULT NULL,
            `user_name` varchar(255) DEFAULT NULL,
            `rating` tinyint(1) NOT NULL,
            `comment` text,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `shop_name` (`shop_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $conn->query($reviewsSql);
    }
}
?>
