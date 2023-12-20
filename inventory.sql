-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2023 at 10:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFilteredProducts` (IN `p_min_price` DECIMAL(10,2), IN `p_max_price` DECIMAL(10,2), IN `p_supplier_id` INT)   BEGIN
    SELECT p.product_id, p.product_name, pd.product_quantity, pd.product_price, 
        IFNULL(DATE_FORMAT(pd.stocked_date, '%M %d, %Y'), 'Not specified') AS stocked_date, 
        p.supplier_id, s.supplier_name, s.is_active 
    FROM product p
    INNER JOIN product_details pd ON p.product_id = pd.product_id
    INNER JOIN supplier s ON p.supplier_id = s.supplier_id
    WHERE (p_min_price IS NULL OR pd.product_price >= p_min_price)
        AND (p_max_price IS NULL OR pd.product_price <= p_max_price)
        AND (p_supplier_id IS NULL OR p.supplier_id = p_supplier_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductDetails` ()   BEGIN
    SELECT p.product_id, p.product_name, pd.product_price, pd.product_quantity, p.supplier_id, s.supplier_name
    FROM product p
    INNER JOIN product_details pd ON p.product_id = pd.product_id
    INNER JOIN supplier s ON p.supplier_id = s.supplier_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTop3ProductsByQuantityForTimeFrame` (IN `startDate` DATE, IN `endDate` DATE)   BEGIN
    SELECT MAX(op.quantity) AS highest_quantity, p.product_name
    FROM order_product op 
    LEFT JOIN product p ON op.product_id = p.product_id
    LEFT JOIN `orders` o ON op.order_id = o.order_id
    WHERE 
        (o.order_date BETWEEN startDate AND endDate OR o.order_date IS NULL)
    GROUP BY p.product_name
    ORDER BY highest_quantity DESC
    LIMIT 3;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GetTableCount` (`table_name` VARCHAR(100)) RETURNS INT(11)  BEGIN
    DECLARE count_value INT;

    IF table_name = 'store' THEN
        SELECT COUNT(*) INTO count_value FROM store;
    ELSEIF table_name = 'product' THEN
        SELECT COUNT(*) INTO count_value FROM product;
    ELSEIF table_name = 'supplier' THEN
        SELECT COUNT(*) INTO count_value FROM supplier;
    ELSE
        SET count_value = -1;
    END IF;

    RETURN count_value;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `event_type` varchar(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `event_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `event_type`, `order_id`, `store_id`, `event_timestamp`) VALUES
(2, 'Create', 60, 8, '2023-10-30 09:59:18'),
(3, 'Delete', 60, 8, '2023-10-30 10:01:44'),
(4, 'Create', 61, 8, '2023-11-29 08:21:38'),
(5, 'Create', 62, 8, '2023-11-29 08:25:32'),
(6, 'Create', 63, 8, '2023-11-29 08:25:43'),
(7, 'Delete', 61, 8, '2023-12-01 05:20:47'),
(8, 'Update', 62, 8, '2023-12-20 07:24:09'),
(9, 'Delete', 63, 8, '2023-12-20 08:04:07'),
(10, 'Delete', 62, 8, '2023-12-20 08:04:11'),
(11, 'Create', 64, 8, '2023-12-20 08:11:59'),
(12, 'Create', 65, 7, '2023-12-20 08:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `payment_status_id` int(11) NOT NULL DEFAULT 2,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `store_id`, `total_amount`, `payment_method_id`, `payment_status_id`, `order_date`) VALUES
(64, 8, 1020.00, 1, 2, '2023-12-20'),
(65, 7, 2460.00, 2, 2, '2023-12-20');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `order_trigger_delete` BEFORE DELETE ON `orders` FOR EACH ROW BEGIN
    INSERT INTO activity_log (event_type, order_id, store_id, event_timestamp)
    VALUES ('Delete', OLD.order_id, OLD.store_id, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_trigger_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    INSERT INTO activity_log (event_type, order_id, store_id, event_timestamp)
    VALUES ('Create', NEW.order_id, NEW.store_id, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_trigger_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    INSERT INTO activity_log (event_type, order_id, store_id, event_timestamp)
    VALUES ('Update', NEW.order_id, NEW.store_id, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_product`
--

INSERT INTO `order_product` (`order_product_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(9, 64, 28, 1, 180.00),
(10, 64, 29, 4, 840.00),
(11, 65, 26, 10, 1200.00),
(12, 65, 29, 6, 1260.00);

--
-- Triggers `order_product`
--
DELIMITER $$
CREATE TRIGGER `reduce_product_quantity` AFTER INSERT ON `order_product` FOR EACH ROW BEGIN
    -- Reduce the product quantity in product_details
    UPDATE product_details 
    SET product_quantity = product_quantity - NEW.quantity 
    WHERE product_id = NEW.product_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `method_id` int(11) NOT NULL,
  `method` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`method_id`, `method`) VALUES
(1, 'cash'),
(2, 'credit card');

-- --------------------------------------------------------

--
-- Table structure for table `payment_status`
--

CREATE TABLE `payment_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_status`
--

INSERT INTO `payment_status` (`status_id`, `status`) VALUES
(1, 'received'),
(2, 'pending'),
(3, 'cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `supplier_id`) VALUES
(26, 'Posters', 6),
(27, 'Frame', 7),
(28, 'Flower Vase', 7),
(29, 'Painting', 7);

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `product_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `stocked_Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`product_id`, `product_quantity`, `product_price`, `stocked_Date`) VALUES
(26, 15, 120, '2023-12-20'),
(27, 30, 200, '2023-12-20'),
(28, 27, 180, '2023-12-20'),
(29, 14, 210, '2023-12-20');

--
-- Triggers `product_details`
--
DELIMITER $$
CREATE TRIGGER `set_stocked_date` BEFORE INSERT ON `product_details` FOR EACH ROW BEGIN
    SET NEW.stocked_date = CURDATE();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_address` varchar(255) NOT NULL,
  `store_email` varchar(255) NOT NULL,
  `store_phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `user_id`, `store_name`, `store_address`, `store_email`, `store_phone`) VALUES
(7, 15, 'Tindahan ni Kim', 'GenSan', 'Kim@gmail.com', '0909009'),
(8, 19, 'Shop ni Seanne', 'GenSan', 'Seanne@gmail.com', '0909009'),
(10, 22, 'Tindahan ni Niel', 'Davao', 'Niel@gmail.com', '0909009');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_phone` varchar(255) DEFAULT NULL,
  `supplier_address` varchar(255) DEFAULT NULL,
  `supplier_email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `user_id`, `supplier_name`, `supplier_phone`, `supplier_address`, `supplier_email`, `is_active`) VALUES
(6, 17, 'Room Decor', '090909', 'GenSan', 'RDecor@email.com', 1),
(7, 18, 'Lobby Decor', '090909', 'GenSan', 'LDecor@gmail.com', 1),
(8, 21, 'House Furniture', '0909009', 'GenSan', 'HouseFurniture@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(15, 'Kim', '123', 'Store Owner'),
(17, 'Decor', '123', 'Supplier'),
(18, 'Lobby', '123', 'Supplier'),
(19, 'Seanne', '123', 'Store Owner'),
(21, 'HouseFurniture', '123', 'Supplier'),
(22, 'Niel', '123', 'Store Owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_store_store_id` (`store_id`),
  ADD KEY `fk_orders_payment_metho_payment_id` (`payment_method_id`),
  ADD KEY `fk_orders_payment_status_status_id` (`payment_status_id`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `FK_order_product_orders_order_id` (`order_id`),
  ADD KEY `FK_order_product_product_product_id` (`product_id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`method_id`);

--
-- Indexes for table `payment_status`
--
ALTER TABLE `payment_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_supplier_supplier_id` (`supplier_id`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_details_produc_product_id` (`product_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`),
  ADD KEY `fk_store_users_user_id` (`user_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `fk_supplier_users_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_status`
--
ALTER TABLE `payment_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_payment_metho_payment_id` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`method_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_payment_status_status_id` FOREIGN KEY (`payment_status_id`) REFERENCES `payment_status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_store_store_id` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `FK_order_product_orders_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_order_product_product_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_supplier_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `fk_product_details_produc_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `fk_store_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_supplier_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
