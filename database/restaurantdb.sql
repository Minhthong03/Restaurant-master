-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 09:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurantdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`) VALUES
(1, 'Món khai vị', 'Các món ăn nhẹ trước bữa chính'),
(2, 'Món chính', 'Món ăn chính trong bữa ăn'),
(3, 'Món tráng miệng', 'Món ngọt sau bữa ăn'),
(4, 'Đồ uống', 'Các loại nước giải khát'),
(5, 'Đặc sản', 'Món đặc sản của nhà hàng'),
(6, 'è', '1');

-- --------------------------------------------------------

--
-- Table structure for table `cauhoi_chatbox`
--

CREATE TABLE `cauhoi_chatbox` (
  `id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `keywords` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cauhoi_chatbox`
--

INSERT INTO `cauhoi_chatbox` (`id`, `question`, `answer`, `keywords`, `created_at`, `updated_at`) VALUES
(1, 'Nhà hàng mở cửa lúc mấy giờ?', 'Nhà hàng mở cửa từ 9h sáng đến 10h tối mỗi ngày.', 'mở cửa, giờ mở cửa, thời gian', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(2, 'Có phục vụ món chay không?', 'Vâng, chúng tôi có nhiều món chay ngon phục vụ khách hàng.', 'món chay, chay, ăn chay', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(3, 'Cách đặt bàn trước như thế nào?', 'Bạn có thể đặt bàn trước qua điện thoại hoặc trên website của chúng tôi.', 'đặt bàn, đặt chỗ, book bàn', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(4, 'Có nhận giao hàng tận nhà không?', 'Nhà hàng có dịch vụ giao hàng tận nhà trong khu vực nội thành.', 'giao hàng, delivery, ship', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(5, 'Thanh toán bằng thẻ có được không?', 'Chúng tôi chấp nhận thanh toán bằng tiền mặt, thẻ tín dụng và ví điện tử.', 'thanh toán, thẻ, trả tiền', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(6, 'Nhà hàng có chỗ đậu xe không?', 'Có, chúng tôi có bãi đậu xe rộng rãi cho khách hàng.', 'đậu xe, parking', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(7, 'Có thực đơn cho trẻ em không?', 'Nhà hàng có thực đơn riêng dành cho trẻ em với nhiều món ăn hấp dẫn và dinh dưỡng.', 'thực đơn trẻ em, món trẻ em', '2025-05-22 05:23:20', '2025-05-22 05:23:20'),
(8, 'Có chỗ tổ chức tiệc sinh nhật không?', 'Có, nhà hàng có phòng riêng dành cho tiệc sinh nhật và sự kiện.', 'tiệc sinh nhật, sự kiện', '2025-05-22 05:23:20', '2025-05-22 05:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `id` int(11) NOT NULL,
  `dish_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `dishes`
--

INSERT INTO `dishes` (`id`, `dish_name`, `price`, `category_id`, `image`, `description`, `status`) VALUES
(1, 'Gỏi cuốn tôm thịt bằm', 36000.00, 2, 'food_icon07.jpg', 'Các món ăn nhẹ trước bữa chính vui vẻ', 'active'),
(2, 'Gỏi cuốn tôm thịt bằm nhỏ', 35000.00, 3, 'h2.jpg', 'Món ăn chính trong bữa ăn thú', 'active'),
(3, 'Gỏi cuốn tôm thịt bằm nhỏ', 35000.00, 3, 'h3.jpg', 'Món ngọt sau bữa ăn', 'active'),
(4, 'Trà đào cam sả', 30000.00, 4, 'h4.jpg', 'Trà đào thơm mát với cam và sả', 'active'),
(5, 'Cá kho tộ', 70000.00, 5, 'h6.jpg', 'Cá kho tộ đậm đà hương vị miền Nam', 'active'),
(7, 'tri', 70000.00, 2, 'h8.jpg', 'tri', 'active'),
(8, 'thu', 13.00, 4, 'h5.jpg', 'thu nguyen', 'active'),
(9, 'Gỏi cuốn tôm thịt bằm nắm', 23.00, 3, 'h1.jpg', 'tr', 'active'),
(10, 'thông thông ', 23.00, 1, 'h2.jpg', 'đẹp trai', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `description`, `supplier_id`, `stock_quantity`) VALUES
(1, 'Thịt bò', 'Thịt bò tươi nhập khẩu', 1, 50),
(2, 'Tôm', 'Tôm sú loại 1', 2, 30),
(3, 'Đường', 'Đường cát trắng', 3, 100),
(4, 'Trà', 'Trà đen hảo hạng', 4, 40),
(5, 'Cá basa', 'Cá tươi sống', 5, 20);

-- --------------------------------------------------------

--
-- Table structure for table `inventorytransactions`
--

CREATE TABLE `inventorytransactions` (
  `id` int(11) NOT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `transaction_type` enum('Import','Export') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `inventorytransactions`
--

INSERT INTO `inventorytransactions` (`id`, `ingredient_id`, `transaction_type`, `quantity`, `transaction_date`, `price`) VALUES
(1, 1, 'Import', 20, '2025-05-06 15:11:27', 500000.00),
(2, 2, 'Export', 10, '2025-05-06 15:11:27', 200000.00),
(3, 3, 'Import', 50, '2025-05-06 15:11:27', 150000.00),
(4, 4, 'Export', 5, '2025-05-06 15:11:27', 100000.00),
(5, 5, 'Import', 10, '2025-05-06 15:11:27', 300000.00);

-- --------------------------------------------------------

--
-- Table structure for table `monan_chatbox`
--

CREATE TABLE `monan_chatbox` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `available_today` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monan_chatbox`
--

INSERT INTO `monan_chatbox` (`id`, `name`, `category`, `description`, `price`, `available_today`, `created_at`, `updated_at`) VALUES
(1, 'Phở bò tái', 'Món chính', 'Phở bò tái ngon, nước dùng trong, đậm đà hương vị truyền thống', 60000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(2, 'Gỏi cuốn', 'Món khai vị', 'Gỏi cuốn tươi ngon với tôm, thịt và rau sống', 40000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(3, 'Cơm sườn nướng', 'Món chính', 'Cơm trắng kèm sườn nướng mềm, thơm', 70000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(4, 'Chè đậu xanh', 'Tráng miệng', 'Chè đậu xanh ngọt dịu, thanh mát', 30000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(5, 'Bún chả', 'Món chính', 'Bún chả Hà Nội với thịt nướng, nước chấm đậm đà', 65000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(6, 'Nước mía', 'Đồ uống', 'Nước mía tươi nguyên chất, ngọt tự nhiên', 15000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(7, 'Cá chiên giòn', 'Món phụ', 'Cá chiên giòn rụm, ăn kèm nước mắm chua ngọt', 80000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01'),
(8, 'Rau muống xào tỏi', 'Món rau', 'Rau muống xào tỏi thơm ngon, giòn giòn', 35000.00, 1, '2025-05-22 05:26:01', '2025-05-22 05:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `unit_price` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`order_id`, `dish_id`, `quantity`, `unit_price`, `note`) VALUES
(1, 1, 2, 35000.00, 'Không hành'),
(1, 3, 1, 25000.00, 'Ăn sau'),
(1, 4, 1, 30000.00, 'Thêm đá'),
(2, 2, 1, 55000.00, ''),
(3, 3, 1, 25000.00, ''),
(4, 1, 2, 35000.00, 'Cuốn kỹ'),
(4, 2, 1, 55000.00, 'Không rau'),
(4, 5, 1, 60000.00, 'Ít mặn'),
(5, 3, 1, 25000.00, 'Ít ngọt'),
(5, 4, 2, 30000.00, ''),
(9, 8, 1, 13.00, 'sdsfdfs'),
(9, 10, 2, 23.00, 'dsdf'),
(10, 2, 2, 35000.00, 'sfdsdf'),
(10, 7, 1, 70000.00, 'dđffd'),
(11, 1, 1, 36000.00, 'cdfsdgdfg'),
(11, 7, 2, 70000.00, 'cdfsdgdfg'),
(12, 3, 1, 35000.00, 'd'),
(12, 9, 1, 23.00, 'd'),
(13, 10, 1, 23.00, 'fffg'),
(14, 10, 2, 23.00, 'wdưd'),
(15, 10, 2, 23.00, 'wdưd'),
(20, 10, 1, 23.00, ''),
(21, 1, 1, 36000.00, 'dsfdsdsdfsdfs'),
(21, 7, 1, 70000.00, 'sấdas'),
(21, 10, 2, 23.00, 'dfđdsfsdfsdfsdf'),
(22, 10, 2, 23.00, ''),
(24, 10, 1, 23.00, ''),
(25, 10, 1, 23.00, ''),
(26, 10, 1, 23.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `status` enum('Chờ xác nhận','Đang xử lý','Đã giao','Đã hủy') DEFAULT 'Chờ xác nhận',
  `table_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_date`, `total_amount`, `status`, `table_id`, `customer_id`, `description`) VALUES
(1, '2025-05-01 10:00:00', 110000.00, 'Đã giao', 1, NULL, 'thông'),
(2, '2025-05-02 11:15:00', 55000.00, 'Đang xử lý', 2, NULL, NULL),
(3, '2025-05-03 09:45:00', 25000.00, 'Đã giao', NULL, NULL, NULL),
(4, '2025-05-04 13:30:00', 130000.00, 'Chờ xác nhận', NULL, NULL, NULL),
(5, '2025-05-05 14:00:00', 85000.00, 'Đã hủy', NULL, NULL, NULL),
(9, '2025-05-19 18:03:17', 59.00, 'Chờ xác nhận', 1, 13, ''),
(10, '2025-05-19 18:04:52', 140000.00, 'Chờ xác nhận', 3, 13, ''),
(11, '2025-05-19 18:19:49', 176000.00, 'Chờ xác nhận', 3, 13, ''),
(12, '2025-05-19 18:44:54', 35023.00, 'Chờ xác nhận', 3, 13, ''),
(13, '2025-05-19 18:48:02', 23.00, 'Chờ xác nhận', 2, 13, ''),
(14, '2025-05-19 21:15:56', 46.00, 'Chờ xác nhận', 2, 13, ''),
(15, '2025-05-19 21:25:34', 46.00, 'Chờ xác nhận', 2, 13, 'Tên: thuan\r\nSĐT: 0901000003\r\nĐịa chỉ: 789 Đường C, Quận 3, TP.HCM đsff'),
(20, '2025-05-19 21:47:56', 23.00, 'Chờ xác nhận', 4, NULL, NULL),
(21, '2025-05-19 21:49:06', 106046.00, 'Chờ xác nhận', 2, 13, 'Tên: thuan\r\nSĐT: 0901000003\r\nĐịa chỉ: 789 Đường C, Quận 3, TP.HCM dssaadsda'),
(22, '2025-05-19 21:49:21', 46.00, 'Chờ xác nhận', 3, NULL, NULL),
(23, '2025-05-19 23:24:39', 23.00, 'Chờ xác nhận', 3, NULL, NULL),
(24, '2025-05-19 23:37:48', 23.00, 'Chờ xác nhận', 2, NULL, NULL),
(25, '2025-05-19 23:38:03', 23.00, 'Đang xử lý', 2, NULL, NULL),
(26, '2025-05-19 23:39:42', 23.00, 'Đang xử lý', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `reservation_time` datetime DEFAULT NULL,
  `number_of_people` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `customer_id`, `table_id`, `reservation_time`, `number_of_people`) VALUES
(1, 1, 2, '2025-05-06 15:11:27', 2),
(2, 2, 3, '2025-05-06 15:11:27', 4),
(3, 3, 1, '2025-05-06 15:11:27', 3),
(4, 4, 4, '2025-05-06 15:11:27', 2),
(5, 5, 5, '2025-05-06 15:11:27', 5);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(4, 'Khách hàng'),
(2, 'Nhân viên kho'),
(3, 'Nhân viên tiếp tân'),
(1, 'Quản lý');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`) VALUES
(1, 'Công ty A', '0123456789'),
(2, 'Công ty B', '0987654321'),
(3, 'Công ty C', '0111222333'),
(4, 'Công ty D', '0222333444'),
(5, 'Công ty E', '0333444555');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(11) DEFAULT NULL,
  `status` enum('Available','Reserved','Occupied') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `status`) VALUES
(1, '1', 'Available'),
(2, '2', 'Reserved'),
(3, '3', 'Occupied'),
(4, '4', 'Available'),
(5, '5', 'Reserved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `address`, `role_id`, `status`) VALUES
(11, 'thong', 'c4ca4238a0b923820dcc509a6f75849b', 'admin@example.com', '0901000002', '123 Đường A, Quận 1, TP.HCM', 1, 'active'),
(12, 'trí th', 'c4ca4238a0b923820dcc509a6f75849b', 'kho12@example.com', '0901000003', '456 Đường B, Quận 2, TP.HCM', 3, 'inactive'),
(13, 'thuan', '74be16979710d4c4e7c6647856088456', 'tt1@example.com', '0901000003', '789 Đường C, Quận 3, TP.HCM', 4, 'inactive'),
(14, 'thu', '6ad14ba9986e3615423dfca256d04e3f', 'kh1@example.com', '0901000004', '101 Đường D, Quận 4, TP.HCM', 4, 'active'),
(15, 'kha', 'efd398f9c21a334f1c3940de1862d5e8', 'kh2@example.com', '0901000005', '202 Đường E, Quận 5, TP.HCM', 4, 'active'),
(16, 'minhthong', '28c8edde3d61a0411511d3b1866f0636', 'wd@gmail.com', '0901000001', '303 Đường F, Quận 6, TP.HCM', 2, 'active'),
(17, 'minh', '74be16979710d4c4e7c6647856088456', 'd@gmail.com', '0901000002', '404 Đường G, Quận 7, TP.HCM', 2, 'active'),
(18, 'thy', '28c8edde3d61a0411511d3b1866f0636', 'qsdf@gmail.com', '0901000004', '505 Đường H, Quận 8, TP.HCM', 2, 'active'),
(19, 'f', 'c4ca4238a0b923820dcc509a6f75849b', 'fw@gmail.com', '0901000005', '606 Đường I, Quận 9, TP.HCM', 3, 'active'),
(20, '', 'd41d8cd98f00b204e9800998ecf8427e', 'tl@gmail.com', '1234567890', 'trtr', 4, 'active'),
(21, '', '74be16979710d4c4e7c6647856088456', '', '1221211222', 'rgf', 4, 'active'),
(22, '', '28c8edde3d61a0411511d3b1866f0636', 'ed@gmail.com', '1234565432', 'rf', 4, 'active'),
(23, '', '28c8edde3d61a0411511d3b1866f0636', 'rew@gmail.com', '1234554321', '3e', 4, 'active'),
(24, 'mitr', '28c8edde3d61a0411511d3b1866f0636', 'etyu@gmail.com', '1111122222', '1wd', 2, 'active'),
(25, '', 'c4ca4238a0b923820dcc509a6f75849b', 'wfdt@gmail.com', '1232123123', 'ef', 4, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cauhoi_chatbox`
--
ALTER TABLE `cauhoi_chatbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `inventorytransactions`
--
ALTER TABLE `inventorytransactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `monan_chatbox`
--
ALTER TABLE `monan_chatbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_id`,`dish_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_table` (`table_id`),
  ADD KEY `fk_orders_customer` (`customer_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cauhoi_chatbox`
--
ALTER TABLE `cauhoi_chatbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventorytransactions`
--
ALTER TABLE `inventorytransactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `monan_chatbox`
--
ALTER TABLE `monan_chatbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `dishes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `inventorytransactions`
--
ALTER TABLE `inventorytransactions`
  ADD CONSTRAINT `inventorytransactions_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
