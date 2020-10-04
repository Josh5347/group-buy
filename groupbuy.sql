-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2020-10-01 05:21:07
-- 伺服器版本： 10.4.10-MariaDB
-- PHP 版本： 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `3598353_groupbuy`
--


-- --------------------------------------------------------

--
-- 資料表結構 `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `user_sn` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '編號',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `login_password` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `user_group` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '群組',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'email',
  `nickname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暱稱',
  `register_date` datetime NOT NULL COMMENT '註冊日期',
  `update_date` datetime DEFAULT NULL COMMENT '修改日期',
  `login_enable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '登入狀態',
  PRIMARY KEY (`user_sn`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `account`
--

INSERT INTO `account` (`user_sn`, `username`, `login_password`, `user_group`, `email`, `nickname`, `register_date`, `update_date`, `login_enable`) VALUES
(1, 'john', 'johnjohn', NULL, NULL, NULL, '2020-08-25 11:56:45', NULL, 0),
(2, 'tom', '$2y$10$RShPPMhYn9iix4PIcTXyF.Ed/srZrka2v30NFk0vL.ZXx0l1xHEyW', NULL, NULL, NULL, '2020-08-26 03:05:57', NULL, 0),
(3, 'alex', '$2y$10$k.IcnZ5kJOSVpbJDx7Op0eWTd3IBL0PKkrCo8nfmT3RC4fFij2VY6', NULL, NULL, NULL, '2020-08-26 03:08:34', NULL, 0),
(4, 'dinger', '$2y$10$1lA/1eKWUI8pWAmMDj2Vluey3WP3LcS6nE0Wrj/HopTJDrPniozpC', NULL, NULL, NULL, '2020-08-26 03:15:24', NULL, 0),
(5, 'jerry1', '$2y$10$1.dwsE54E/CnDzvFuml4ze.HGJqztGNwwVYcJNaX.SScCqMtEARaK', NULL, NULL, NULL, '2020-08-26 03:18:35', NULL, 0),
(6, 'jack12', '$2y$10$fo1MT8grYvzuNzjsckKHZuM4tH7EvtXLJ.VhHfSuyQAix4DPjCoRS', NULL, NULL, NULL, '2020-08-26 03:20:05', NULL, 0),
(7, 'aaaaaa', '$2y$10$PEjqzGRSoxilgnAaJPOXpe2Vc3tp4kxvQZPOZLy7qhYv3MC8cTtTO', NULL, NULL, NULL, '2020-08-26 03:24:02', NULL, 0),
(8, 'bbbbbb', '$2y$10$xk2EKqefMs8VMtrWaR0JlOvoHtg/KxnWCqiS6s.FIDTxLwzshqXzy', NULL, NULL, NULL, '2020-08-26 03:27:47', NULL, 0),
(9, 'cccccc', '$2y$10$ohyrkFgbMp2ckd0PeomGSO1PKbMlwlWgJV2ccHuu5Q8MqqWyJrnbS', NULL, NULL, NULL, '2020-08-26 03:32:32', NULL, 0),
(10, 'dddddd', '$2y$10$yODUZOP9NdGCLyMWcmPsgO8DGgSwSyNzquKFW9WZvQV5p5DbGDV/C', NULL, NULL, NULL, '2020-08-26 03:33:41', NULL, 0),
(11, 'eeeeee', '$2y$10$c15Xs4.ZGIqS9/iuCB8srOmKGaLtVA86O03NobJYCE72D5cMZIbXm', NULL, NULL, NULL, '2020-08-26 03:41:36', NULL, 0),
(12, 'ffffff', '$2y$10$qmybkv/0TEXD8dSvcRTIguukiBCCirN3pzodtPAHZUFwR0krnWMQK', NULL, NULL, NULL, '2020-08-26 03:45:51', NULL, 0),
(13, 'gggggg', '$2y$10$OrH5jloM2moQveUeATJl7O2mKtmk2VkBPQqhTjD4twLNnb3cXgmfi', NULL, NULL, NULL, '2020-08-26 03:47:30', NULL, 0),
(14, 'hhhhhh', '$2y$10$rihscYTGFB609PnfjdNDw.1IyLVxZqb11l4.NsowG3OMDzTiidB.K', NULL, NULL, NULL, '2020-08-26 04:06:18', NULL, 0),
(15, 'iiiiii', '$2y$10$vdJbw.UzysiRUKyITw3cTOmKbuoH3Q6EqKzC.fPVQye/U/x0vKUTO', NULL, NULL, NULL, '2020-08-26 04:15:14', NULL, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `buy_info`
--

DROP TABLE IF EXISTS `buy_info`;
CREATE TABLE IF NOT EXISTS `buy_info` (
  `buy_id` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT '團購編號',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `store_no` mediumint(9) NOT NULL COMMENT '商店編號',
  `enable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '進行中',
  `in_charge_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '負責人',
  `expired_time` time DEFAULT NULL COMMENT '截止時間',
  `announce` text COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '公告事項',
  `memo` text COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '小筆記',
  `info_1` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '訂購人資訊1',
  `info_2` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '訂購人資訊2',
  `total_paid` int(10) DEFAULT 0 COMMENT '已付總金額',
  `amount` mediumint(9) NOT NULL DEFAULT 0 COMMENT '數量(人)',
  `sum` int(10) NOT NULL DEFAULT 0 COMMENT '總金額',
  `create_date` datetime NOT NULL COMMENT '建立日期',
  PRIMARY KEY (`buy_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `buy_info`
--

INSERT INTO `buy_info` (`buy_id`, `username`, `store_no`, `enable`, `in_charge_name`, `expired_time`, `announce`, `memo`, `info_1`, `info_2`, `total_paid`, `amount`, `sum`, `create_date`) VALUES
(1, 'cccccc', 18, 1, '小張', NULL, NULL, NULL, NULL, NULL, 0, 1, 120, '2020-09-08 07:53:17'),
(2, 'cccccc', 16, 1, '阿豪', NULL, '注意注意注意注意注意', NULL, NULL, NULL, 0, 0, 0, '2020-09-09 07:28:45'),
(3, 'cccccc', 1, 1, '老張', '18:00:00', NULL, NULL, NULL, NULL, 100, 5, 820, '2020-09-10 03:21:16');

-- --------------------------------------------------------

--
-- 資料表結構 `order_info`
--

DROP TABLE IF EXISTS `order_info`;
CREATE TABLE IF NOT EXISTS `order_info` (
  `buy_id` mediumint(9) NOT NULL COMMENT '團購編號',
  `order_id` mediumint(9) NOT NULL COMMENT '訂單編號',
  `order_sn` mediumint(9) NOT NULL COMMENT '序號',
  `store_no` mediumint(9) NOT NULL COMMENT '商店編號',
  `orderer` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購人',
  `product_no` varchar(9) COLLATE utf8_unicode_ci NOT NULL COMMENT '產品編號',
  `product` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '產品',
  `price` int(6) NOT NULL COMMENT '單價',
  `explanation` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '說明',
  `paid` tinyint(1) NOT NULL DEFAULT 0 COMMENT '已付款',
  `shipping_date` date DEFAULT NULL COMMENT '出貨日期',
  `order_time` datetime NOT NULL COMMENT '訂購時間',
  PRIMARY KEY (`buy_id`,`order_id`,`order_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `order_info`
--

INSERT INTO `order_info` (`buy_id`, `order_id`, `order_sn`, `store_no`, `orderer`, `product_no`, `product`, `price`, `explanation`, `paid`, `shipping_date`, `order_time`) VALUES
(3, 2, 2, 1, 'aaa', '1', '魯肉飯', 40, NULL, 1, '2020-09-28', '2020-09-14 09:57:54'),
(3, 2, 1, 1, 'aaa', '2', '叉燒飯', 60, NULL, 1, '2020-09-28', '2020-09-14 09:57:54'),
(3, 3, 1, 1, 'bbb', '4-1', '陽春麵:普通', 40, NULL, 0, NULL, '2020-09-14 09:58:33'),
(3, 3, 2, 1, 'bbb', '4-1', '陽春麵:普通', 40, NULL, 0, NULL, '2020-09-14 09:58:33'),
(3, 3, 3, 1, 'bbb', '3-1', '牛肉麵:意麵', 80, NULL, 0, NULL, '2020-09-14 09:58:33'),
(1, 1, 1, 18, 'aaa', '3-1', '甜甜圈:大', 40, NULL, 0, NULL, '2020-09-14 12:01:33'),
(1, 1, 2, 18, 'aaa', '2-1', '冰咖啡:中杯', 40, NULL, 0, NULL, '2020-09-14 12:01:33'),
(1, 1, 3, 18, 'aaa', '1-3', '奶茶:L', 40, NULL, 0, NULL, '2020-09-14 12:01:33'),
(3, 4, 1, 1, 'ccc', '1', '魯肉飯', 40, '加肉加飯', 0, NULL, '2020-09-17 07:30:36'),
(3, 4, 2, 1, 'ccc', '1', '魯肉飯', 40, '加肉加飯', 0, NULL, '2020-09-17 07:30:36'),
(3, 5, 2, 1, 'ddd', '4-1', '陽春麵:普通', 40, '多一點d', 0, NULL, '2020-09-21 09:25:34'),
(3, 5, 3, 1, 'ddd', '3-1', '牛肉麵:意麵', 80, '多一點c', 0, NULL, '2020-09-21 09:25:34'),
(3, 5, 4, 1, 'ddd', '3-1', '牛肉麵:意麵', 80, '多一點c', 0, NULL, '2020-09-21 09:25:34'),
(3, 5, 5, 1, 'ddd', '4-1', '陽春麵:普通', 40, NULL, 0, NULL, '2020-09-21 09:25:34'),
(3, 5, 6, 1, 'ddd', '2', '叉燒飯', 60, '多一點b', 0, NULL, '2020-09-21 09:25:34'),
(3, 5, 7, 1, 'ddd', '1', '魯肉飯', 40, '多一點a', 0, NULL, '2020-09-21 09:25:34'),
(3, 5, 8, 1, 'ddd', '1', '魯肉飯', 40, '多一點a', 0, NULL, '2020-09-21 09:25:34');

-- --------------------------------------------------------

--
-- 資料表結構 `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE IF NOT EXISTS `store` (
  `store_no` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '商店編號',
  `public_flag` tinyint(1) NOT NULL COMMENT '公開或群組店家',
  `store_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
  `introduction` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '簡介',
  `store_tel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '電話',
  `store_address` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `detail` text COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '訂購說明',
  `store_fax` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '傳真',
  `store_url` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '店家網址',
  `store_type` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '服務類型',
  `create_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '建立人員',
  `create_date` datetime NOT NULL COMMENT '建立日期',
  `update_date` datetime NOT NULL COMMENT '修改日期',
  PRIMARY KEY (`store_no`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `store`
--

INSERT INTO `store` (`store_no`, `public_flag`, `store_name`, `introduction`, `store_tel`, `store_address`, `detail`, `store_fax`, `store_url`, `store_type`, `create_username`, `create_date`, `update_date`) VALUES
(1, 0, '新店家1', '簡介1', '06-2345678', '台南市東區榮譽街1號', '訂購說明1', '06-2345677', 'http://group-buy.idv', NULL, 'cccccc', '2020-09-05 22:50:27', '2020-09-05 22:50:27'),
(17, 0, '新店家3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cccccc', '2020-09-07 02:09:42', '2020-09-07 02:09:42'),
(18, 0, '新店家4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cccccc', '2020-09-07 04:05:49', '2020-09-07 04:05:49'),
(15, 0, '新店家2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cccccc', '2020-09-06 07:06:27', '2020-09-06 07:06:27'),
(16, 0, '美德中藥行(養生茶/蜜餞/果乾/中藥飲)', '中藥玩家-美德中藥行(養生茶/蜜餞/果乾/中藥飲)', NULL, NULL, NULL, NULL, NULL, NULL, 'cccccc', '2020-09-06 07:44:29', '2020-09-06 07:44:29');

-- --------------------------------------------------------

--
-- 資料表結構 `store_product`
--

DROP TABLE IF EXISTS `store_product`;
CREATE TABLE IF NOT EXISTS `store_product` (
  `store_no` mediumint(8) NOT NULL COMMENT '商店編號',
  `edit_version` tinyint(4) DEFAULT NULL COMMENT '版本編號',
  `product_list` text COLLATE utf8_unicode_ci NOT NULL COMMENT '產品清單',
  PRIMARY KEY (`store_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `store_product`
--

INSERT INTO `store_product` (`store_no`, `edit_version`, `product_list`) VALUES
(1, NULL, '魯肉飯, 40\r\n叉燒飯, 60\r\n{麵食}\r\n牛肉麵, 意麵 80, 手工麵 90\r\n陽春麵, 普通 40, 加蛋 50\r\n{飲料冰品}\r\n奶茶, S 20, M 30, L 40\r\n冰咖啡, 中杯 40, 大杯 50\r\n{下午茶系列}\r\n甜甜圈, 大 40, 小 30'),
(15, NULL, '魯肉飯, 40\r\n叉燒飯, 60'),
(16, NULL, '{茶飲-10包/袋}\r\n桂花黑糖桂圓紅棗茶, 250\r\n桂花桂圓紅棗茶, 250\r\n玫瑰黑糖四物茶, 250\r\n玫瑰棗杞茶, 250\r\n黑糖薑母棗杞茶, 250\r\n黑糖桂圓紅棗茶, 250\r\n黑糖紅棗枸杞茶, 250\r\n薑母黑糖桂棗茶, 250\r\n洛橙茶, 250\r\n洛神話梅茶, 250\r\n菊花橙柚茶, 250\r\n菊花紅棗枸杞茶, 250\r\n元氣茶, 250\r\n粉光蔘棗杞茶, 400\r\n{茶包-10包/袋/內容物請見茶飲圖示}\r\n輕盈茶包, 180\r\n玫瑰山楂茶包, 180\r\n洛神話梅茶包, 180\r\n黑豆牛蒡茶包, 180\r\n黑豆茶包, 180\r\n精彩世界茶包(12包), 180\r\n好乾元氣茶包(12包), 180\r\n酸梅湯茶包, 180\r\n安神茶包, 180\r\n桂花茶包, 180\r\n袪濕茶包, 180\r\n菊花決明子茶包, 180\r\n{藥膳}\r\n十全大補帖, 140\r\n八珍帖, 100\r\n加味四物帖, 80\r\n四神帖, 100\r\n肉骨茶帖, 120\r\n{果乾-5贈1}\r\n芭樂乾, 100香橙片, 100無花果乾, 100柚子乾, 100情人果乾, 100青提子, 100{蜜餞-5贈1}\r\n紫蘇梅, 100白話梅, 100甘草橄欖, 100洛神花乾, 100\r\n化核李, 100\r\n{其他}\r\n八仙菓, 100\r\n漢方驅蚊包, 60\r\n精油紫雲膏, 150\r\n愛玉籽(37.5公克), 100'),
(17, NULL, '{飲料冰品}\r\n奶茶, S 20, M 30, L 40\r\n冰咖啡, 中杯 40, 大杯 50'),
(18, NULL, '{飲料冰品}\r\n奶茶, S 20, M 30, L 40\r\n冰咖啡, 中杯 40, 大杯 50\r\n{下午茶系列}\r\n甜甜圈, 大 40, 小 30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
