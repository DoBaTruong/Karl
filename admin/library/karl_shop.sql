-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 08, 2021 lúc 10:38 AM
-- Phiên bản máy phục vụ: 10.4.17-MariaDB
-- Phiên bản PHP: 7.3.27

START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `karl_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `bill_qty` int(11) NOT NULL,
  `bill_price` float NOT NULL,
  `bill_total` float NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `bills`
--

INSERT INTO `bills` (`bill_id`, `order_id`, `prd_id`, `bill_qty`, `bill_price`, `bill_total`) VALUES
(1, 1, 1, 2, 1600000, 3200000),
(2, 1, 3, 3, 320000, 960000),
(3, 2, 1, 3, 860000, 2580000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `blog_id` int(11) NOT NULL,
  `blog_topic` varchar(255) NOT NULL,
  `blog_title` varchar(255) NOT NULL,
  `blog_content` text NOT NULL,
  `blog_authors` varchar(255) NOT NULL,
  `blog_post` datetime NOT NULL,
  `blog_view` int(11) NOT NULL,
  `blog_like` int(11) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blog_comments`
--

CREATE TABLE `blog_comments` (
  `comm_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `comm_name` varchar(255) NOT NULL,
  `comm_mail` varchar(255) NOT NULL,
  `comm_date` datetime NOT NULL,
  `comm_callback` int(2) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_callback` int(3) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_callback`) VALUES
(1, 'Woman wear', 0),
(2, 'Man wear', 0),
(3, 'Children', 0),
(4, 'Bags & Purses', 0),
(5, 'Footwear', 0),
(6, 'Eyewear', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_add` varchar(255) NOT NULL,
  `order_phone` varchar(50) NOT NULL,
  `order_paymethod` int(1) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` int(1) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_add`, `order_phone`, `order_paymethod`, `order_date`, `order_status`) VALUES
(1, 1, 'Dan Tao - Tan Minh - Soc Son - Ha Noi', '0988041615', 0, '2021-06-01 15:08:35', 0),
(2, 2, 'Dan Tao - Tan Minh - Soc Son - Ha Noi', '0988041615', 0, '2021-06-02 15:08:35', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prd_comments`
--

CREATE TABLE `prd_comments` (
  `comm_id` int(11) NOT NULL,
  `prd_id` int(11) NOT NULL,
  `comm_name` varchar(255) NOT NULL,
  `comm_mail` varchar(255) NOT NULL,
  `comm_ratt` int(11) NOT NULL,
  `comm_date` datetime NOT NULL,
  `comm_callback` int(2) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `prd_comments`
--

INSERT INTO `prd_comments` (`comm_id`, `prd_id`, `comm_name`, `comm_mail`, `comm_ratt`, `comm_date`, `comm_callback`) VALUES
(1, 1, 'Truongbk48', 'dobatruongbk48@gamil.com', 4, '2021-06-02 15:15:48', 0),
(2, 1, 'admin', 'admin@gmail.com', 3, '2021-06-01 15:15:48', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `prd_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `prd_name` varchar(255) NOT NULL,
  `prd_mage` varchar(255) NOT NULL,
  `prd_color` varchar(255) NOT NULL,
  `prd_size` varchar(255) NOT NULL,
  `prd_brand` varchar(255) NOT NULL,
  `prd_promotion` text NOT NULL,
  `prd_discount` int(2) NOT NULL,
  `prd_update` datetime NOT NULL
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `prm_id` int(11) NOT NULL,
  `prm_gift` varchar(255) NOT NULL,
  `prm_percent` int(11) NOT NULL,
  `prm_dateBegin` date NOT NULL,
  `prm_dateEnd` date NOT NULL
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supports`
--

CREATE TABLE `supports` (
  `supp_id` int(11) NOT NULL,
  `supp_name` varchar(50) NOT NULL,
  `supp_mail` varchar(50) NOT NULL,
  `supp_type` varchar(255) NOT NULL,
  `supp_content` text NOT NULL,
  `supp_reply` text NOT NULL,
  `supp_date` datetime NOT NULL,
  `reply_date` datetime NOT NULL
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_full` varchar(255) NOT NULL,
  `user_birdth` date NOT NULL,
  `user_gender` int(2) NOT NULL,
  `user_tel` varchar(20) NOT NULL,
  `user_mail` varchar(255) NOT NULL,
  `user_add` text NOT NULL,
  `user_level` int(2) NOT NULL,
  `user_pass` varchar(255) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_full`, `user_birdth`, `user_gender`, `user_tel`, `user_mail`, `user_add`, `user_level`, `user_pass`) VALUES
(1, 'Jeckjon_xy_TH89', 'Mickel Jexjon', '1959-09-29', 0, '0988041615', 'dobatruongbk48@gmail.com', 'Dan Tao - Tan Minh - Soc Son - Ha Noi', 0, 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'TruongBk48', 'Do Ba Truong', '1995-05-10', 0, '0988041615', 'dobatruongbk48@gmail.com', 'Dan Tao - Tan Minh - Soc Son - Ha Noi', 1, 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'TruongVodka', 'Do Ba Truong', '1995-04-11', 0, '0988041615', 'dobatruong.menvodka@gmail.com', 'Dan Tao - Tan Minh - Soc Son - Ha Noi', 2, 'e10adc3949ba59abbe56e057f20f883e'),
(4, 'Jeckjon_K67', 'Jeckjon Mywel', '2021-06-08', 0, '0988041615', 'dobatruongbk58@gmail.com', 'Dan Tao - Tan Minh - Soc Son - Ha Noi', 0, 'e10adc3949ba59abbe56e057f20f883e');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`);

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`);

--
-- Chỉ mục cho bảng `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`comm_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Chỉ mục cho bảng `prd_comments`
--
ALTER TABLE `prd_comments`
  ADD PRIMARY KEY (`comm_id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prd_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`prm_id`);

--
-- Chỉ mục cho bảng `supports`
--
ALTER TABLE `supports`
  ADD PRIMARY KEY (`supp_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `prd_comments`
--
ALTER TABLE `prd_comments`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `prd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `prm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `supports`
--
ALTER TABLE `supports`
  MODIFY `supp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
