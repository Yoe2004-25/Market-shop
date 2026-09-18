CREATE TABLE `users` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL COMMENT 'User email',
  `phone` varchar(11) COMMENT 'Egyptian phone',
  `image` varchar(255),
  `address` text COMMENT 'Full address',
  `email_verified_at` timestamp,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text,
  `two_factor_recovery_codes` text,
  `two_factor_confirmed_at` timestamp,
  `remember_token` varchar(100),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) PRIMARY KEY,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp
);

CREATE TABLE `sessions` (
  `id` varchar(255) PRIMARY KEY,
  `user_id` bigint,
  `ip_address` varchar(45),
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
);

CREATE TABLE `permissions` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `roles` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`)
);

CREATE TABLE `model_has_roles` (
  `role_id` bigint,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`)
);

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint,
  `role_id` bigint,
  PRIMARY KEY (`permission_id`, `role_id`)
);

CREATE TABLE `categories` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) UNIQUE NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` boolean DEFAULT true,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `brands` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `logo` varchar(225),
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `products` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `category_id` bigint NOT NULL,
  `brand_id` bigint,
  `user_id` bigint COMMENT 'Seller',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) UNIQUE NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT 'Original price',
  `discount` decimal(10,2) DEFAULT 0 COMMENT 'Discount amount',
  `stock` int DEFAULT 1,
  `sku` bigint UNIQUE NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'active' COMMENT 'active | inactive',
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `product_images` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `product_id` bigint NOT NULL,
  `image` varchar(255) NOT NULL,
  `primary` boolean DEFAULT false,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `reviews` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `product_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `rating` tinyint NOT NULL COMMENT '1-5',
  `comment` text,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `wishlists` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `carts` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `cart_items` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `cart_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` bigint DEFAULT 1,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `coupons` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `code` varchar(225) UNIQUE NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'fixed | percentage',
  `value` decimal(10,2) NOT NULL,
  `expire_date` datetime,
  `usage_limit` int DEFAULT 1,
  `status` varchar(20) DEFAULT 'active' COMMENT 'active | nonactive',
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `coupon_user` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `coupon_id` bigint NOT NULL,
  `time_of_coupon` datetime NOT NULL,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `orders` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `coupon_id` bigint,
  `name` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'pending' COMMENT 'pending | success | failed',
  `payment_status` varchar(20) DEFAULT 'pending' COMMENT 'pending | success | failed | refunded',
  `total` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0,
  `tax` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `ordersitems` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `quantity` int DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `details` varchar(225) NOT NULL,
  `deleted_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `payments` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
  `payment_method` varchar(20) NOT NULL COMMENT 'cash | visa',
  `status` varchar(20) DEFAULT 'pending' COMMENT 'pending | completed | failed | refunded',
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(255) UNIQUE NOT NULL,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `cache` (
  `key` varchar(255) PRIMARY KEY,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
);

CREATE TABLE `cache_locks` (
  `key` varchar(255) PRIMARY KEY,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
);

CREATE TABLE `jobs` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint NOT NULL,
  `reserved_at` int,
  `available_at` int NOT NULL,
  `created_at` int NOT NULL
);

CREATE TABLE `job_batches` (
  `id` varchar(255) PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int,
  `created_at` int NOT NULL,
  `finished_at` int
);

CREATE TABLE `failed_jobs` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `uuid` varchar(255) UNIQUE NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp DEFAULT (now())
);

CREATE TABLE `personal_access_tokens` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) UNIQUE NOT NULL,
  `abilities` text,
  `last_used_at` timestamp,
  `expires_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `passkeys` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `credential_id` varchar(255) UNIQUE NOT NULL,
  `credential` json NOT NULL,
  `last_used_at` timestamp,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE UNIQUE INDEX `users_index_0` ON `users` (`email`);

CREATE INDEX `users_email_verified_idx` ON `users` (`email_verified_at`);

CREATE INDEX `sessions_user_id_idx` ON `sessions` (`user_id`);

CREATE INDEX `sessions_last_activity_idx` ON `sessions` (`last_activity`);

CREATE UNIQUE INDEX `permissions_index_4` ON `permissions` (`name`, `guard_name`);

CREATE UNIQUE INDEX `roles_index_5` ON `roles` (`name`, `guard_name`);

CREATE INDEX `mhp_model_idx` ON `model_has_permissions` (`model_id`, `model_type`);

CREATE INDEX `mhr_model_idx` ON `model_has_roles` (`model_id`, `model_type`);

CREATE INDEX `categories_name_idx` ON `categories` (`name`);

CREATE INDEX `categories_slug_idx` ON `categories` (`slug`);

CREATE INDEX `categories_status_idx` ON `categories` (`status`);

CREATE INDEX `brands_name_idx` ON `brands` (`name`);

CREATE INDEX `products_name_idx` ON `products` (`name`);

CREATE INDEX `products_slug_idx` ON `products` (`slug`);

CREATE INDEX `products_price_idx` ON `products` (`price`);

CREATE INDEX `products_stock_idx` ON `products` (`stock`);

CREATE INDEX `products_sku_idx` ON `products` (`sku`);

CREATE INDEX `products_status_idx` ON `products` (`status`);

CREATE INDEX `products_cat_brand_idx` ON `products` (`category_id`, `brand_id`);

CREATE INDEX `product_images_prod_primary_idx` ON `product_images` (`product_id`, `primary`);

CREATE UNIQUE INDEX `reviews_index_20` ON `reviews` (`user_id`, `product_id`);

CREATE INDEX `reviews_rating_idx` ON `reviews` (`rating`);

CREATE INDEX `reviews_product_idx` ON `reviews` (`product_id`);

CREATE UNIQUE INDEX `wishlists_index_23` ON `wishlists` (`user_id`, `product_id`);

CREATE INDEX `wishlists_user_idx` ON `wishlists` (`user_id`);

CREATE INDEX `wishlists_product_idx` ON `wishlists` (`product_id`);

CREATE INDEX `carts_user_idx` ON `carts` (`user_id`);

CREATE UNIQUE INDEX `cart_items_unique` ON `cart_items` (`cart_id`, `product_id`);

CREATE INDEX `cart_items_price_idx` ON `cart_items` (`price`);

CREATE INDEX `cart_items_quantity_idx` ON `cart_items` (`quantity`);

CREATE UNIQUE INDEX `coupons_index_30` ON `coupons` (`code`);

CREATE INDEX `coupons_type_idx` ON `coupons` (`type`);

CREATE INDEX `coupons_status_idx` ON `coupons` (`status`);

CREATE INDEX `coupons_expire_idx` ON `coupons` (`expire_date`);

CREATE UNIQUE INDEX `coupon_user_index_34` ON `coupon_user` (`user_id`, `coupon_id`);

CREATE INDEX `coupon_user_time_idx` ON `coupon_user` (`time_of_coupon`);

CREATE INDEX `orders_user_idx` ON `orders` (`user_id`);

CREATE INDEX `orders_status_idx` ON `orders` (`status`);

CREATE INDEX `orders_payment_status_idx` ON `orders` (`payment_status`);

CREATE INDEX `orders_created_idx` ON `orders` (`created_at`);

CREATE INDEX `order_items_order_idx` ON `orders_items` (`order_id`);

CREATE INDEX `order_items_product_idx` ON `orders_items` (`product_id`);

CREATE INDEX `order_items_quantity_idx` ON `orders_items` (`quantity`);

CREATE INDEX `payments_order_idx` ON `payments` (`order_id`);

CREATE INDEX `payments_status_idx` ON `payments` (`status`);

CREATE UNIQUE INDEX `payments_index_45` ON `payments` (`transaction_id`);

CREATE INDEX `cache_expiration_idx` ON `cache` (`expiration`);

CREATE INDEX `cache_locks_expiration_idx` ON `cache_locks` (`expiration`);

CREATE INDEX `jobs_queue_idx` ON `jobs` (`queue`);

CREATE INDEX `pat_tokenable_idx` ON `personal_access_tokens` (`tokenable_type`, `tokenable_id`);

CREATE UNIQUE INDEX `personal_access_tokens_index_50` ON `personal_access_tokens` (`token`);

CREATE INDEX `passkeys_user_idx` ON `passkeys` (`user_id`);

ALTER TABLE `sessions` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `model_has_permissions` ADD FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

ALTER TABLE `model_has_roles` ADD FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

ALTER TABLE `role_has_permissions` ADD FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

ALTER TABLE `role_has_permissions` ADD FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

ALTER TABLE `products` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `products` ADD FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

ALTER TABLE `products` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `product_images` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `wishlists` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `wishlists` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `carts` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `cart_items` ADD FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`);

ALTER TABLE `cart_items` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `cart_items` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `coupon_user` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `coupon_user` ADD FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

ALTER TABLE `orders_items` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `orders_items` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `orders_items` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `payments` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `passkeys` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
