-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- 主機: localhost:3306
-- 產生時間： 2017 年 07 月 25 日 11:43
-- 伺服器版本: 5.6.35
-- PHP 版本： 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `twlifeli_ocmall`
--

-- --------------------------------------------------------

--
-- 資料表結構 `oc_user`
--

CREATE TABLE `oc_user` (
  `user_id` int(11) NOT NULL COMMENT '管理員編號',
  `user_group_id` int(11) NOT NULL COMMENT '管理員種類',
  `username` varchar(20) NOT NULL COMMENT '帳號',
  `password` varchar(40) NOT NULL COMMENT '密碼',
  `salt` varchar(9) NOT NULL,
  `firstname` varchar(32) NOT NULL COMMENT '名字',
  `lastname` varchar(32) NOT NULL COMMENT '名字',
  `email` varchar(96) NOT NULL COMMENT '信箱',
  `image` varchar(255) NOT NULL COMMENT 'LOGO',
  `code` varchar(40) NOT NULL,
  `ip` varchar(40) NOT NULL COMMENT '登入ip',
  `status` tinyint(1) NOT NULL COMMENT '狀態',
  `date_added` datetime NOT NULL COMMENT '加入日期',
  `cat_permission` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '分類授權',
  `store_permission` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '商店授權',
  `vendor_permission` int(11) NOT NULL COMMENT '賣家授權',
  `folder` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '資料夾',
  `user_date_start` date NOT NULL COMMENT '簽約日',
  `user_date_end` date NOT NULL COMMENT '到期日'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `oc_user`
--

INSERT INTO `oc_user` (`user_id`, `user_group_id`, `username`, `password`, `salt`, `firstname`, `lastname`, `email`, `image`, `code`, `ip`, `status`, `date_added`, `cat_permission`, `store_permission`, `vendor_permission`, `folder`, `user_date_start`, `user_date_end`) VALUES
(1, 1, 'twlifeli_ocmall', '3f13ccd672b50980918f1c347ee7cc62f4011c02', 'fca9ed6cd', 'John', 'Doe', 'andy8207133@gmail.com', '', '', '202.39.70.43', 1, '2017-04-17 14:41:53', '', '', 0, '', '0000-00-00', '0000-00-00'),
(7, 1, 'twlifelinkcom', '3d7655d350b8fe3f00c27829add1d205a32624f3', '433d6d371', 'Life', 'Link', '', 'catalog/head.png', '', '202.39.70.43', 1, '2017-04-19 10:22:52', '', '', 0, '', '0000-00-00', '0000-00-00'),
(3, 50, 'LifeLink', 'e12954e9c0304886bb514300f575ad0b781ce2e7', '64d804774', 'Life', 'Link', 'lifelink88@gmail.com', 'catalog/head.png', '', '202.39.70.43', 1, '2017-04-17 15:26:06', '', '', 3, 'LifeLink', '0000-00-00', '0000-00-00'),
(28, 50, '101hotel', 'e7201524743f4feb6579fc402c3f3a9088670bdb', '6e925aed4', '', '101hotel', '101hotel@xx.xx', '', '', '', 1, '2017-05-25 16:40:50', '', '', 25, '', '0000-00-00', '0000-00-00'),
(35, 50, 'paku', '27d12bcba981490ac34019f9c09375c2127fe9a1', 'c5d03bdf7', '', 'paku', 'x8830102@gmail.com', '', '', '202.39.70.43', 1, '2017-05-26 16:11:31', '', '', 32, '', '0000-00-00', '0000-00-00'),
(9, 50, 'isumi', '505e9724d52478cf42b185ca9c717853fd248916', 'da53d426f', '旗成', '許', 'a0925287718@gmail.com', 'catalog/isumi/isuma_logo-01.png', '', '202.39.70.43', 1, '2017-04-26 10:29:06', 'a:2:{i:0;s:3:\"123\";i:1;s:3:\"252\";}', 'a:1:{i:0;s:1:\"0\";}', 6, 'isumi', '0000-00-00', '0000-00-00'),
(13, 50, 'fungo', '68768bfd1f4f418fbbcf0eeeb4d4f3c8', '', '助理', '串', 'service@lifelink.com.tw', 'catalog/logo/fungo_logo＿bk-01.png', '', '111.248.149.178', 1, '2017-05-12 17:48:11', '', '', 10, 'fungo', '0000-00-00', '0000-00-00'),
(14, 50, 'vege', '812361dc0b9109cae8025fe34dd9e2f496c9896b', '72f03f69f', 'vege', 'vege', 'andy820713@gmail.com', '', '', '202.39.70.43', 1, '2017-05-25 14:53:33', '', '', 11, 'vege', '0000-00-00', '0000-00-00'),
(15, 50, 'sunny', '113c50909b2da89e88c888cf87befc561b8176bf', '3c6a4d937', 'sunny', 'sunny', 'Ellainetsai16888@gmail.com', '', '', '61.64.178.246', 1, '2017-05-25 15:06:04', '', '', 12, '', '0000-00-00', '0000-00-00'),
(16, 50, 'terry888', 'fc7529bdeb5e00d7066e6caa1a2848c84dfdf05b', 'eedc15ece', '', 'terry888', 'ok610414@yahoo.com.tw', '', '', '202.39.70.43', 1, '2017-05-25 15:18:28', '', '', 13, '', '0000-00-00', '0000-00-00'),
(25, 50, 'baby131419', '6e0100034addaa3b05bb7537b65e633a075bba69', 'b1c08e7e2', '', 'baby131419', 'baby981461@yahoo.com.tw', '', '', '', 1, '2017-05-25 16:34:41', '', '', 22, '', '0000-00-00', '0000-00-00'),
(17, 50, 'peggy', '40623511aac43b4186c240470f2e5eb0b9eefc6b', '1186f1c16', '', 'peggy', 'peggyc518@gmail.com', '', '', '', 1, '2017-05-25 15:33:44', '', '', 14, '', '0000-00-00', '0000-00-00'),
(18, 50, 'justenjoy', 'a3cd06d6e0ed9a8f37ba2281640ec22a169a2e36', '5bc0ed945', '', 'justenjoy', 'justenjoy@gmail.com', '', '', '', 1, '2017-05-25 15:43:01', '', '', 15, '', '0000-00-00', '0000-00-00'),
(19, 50, 'sonia8', 'd0cc3619071c20c6727992ab0b59adaff228fe54', '21bc97a90', '', 'sonia8', 'chousonia5186@gmail.com', '', '', '', 1, '2017-05-25 15:44:52', '', '', 16, '', '0000-00-00', '0000-00-00'),
(20, 50, 'cash888', 'efa54fe578210d548bf5e97a67b707e809993f6a', '09e876c86', '', 'cash888', 'cash888@xx.xx', '', '', '', 1, '2017-05-25 15:48:13', '', '', 17, '', '0000-00-00', '0000-00-00'),
(21, 50, 'amanda', '128ce75a4511e345647a19bce3466e74ed4d7df9', '8fee6aec9', '', 'amanda', 'amanda2k02@gmail.com', '', '', '', 1, '2017-05-25 16:09:24', '', '', 18, '', '0000-00-00', '0000-00-00'),
(22, 50, '520888', '75c64c8b0ee1227d6b99ae240a6f6f3128d80f3f', '6957482a2', '', '520888', '520888@xx.xx', '', '', '', 1, '2017-05-25 16:10:42', '', '', 19, '', '0000-00-00', '0000-00-00'),
(24, 50, 'peter888', '945fc1715811696b31d0bd922a4cffffe4f1b3d0', '9ff1408ee', '', 'peter888', 'a0926632895@gmail.com', '', '', '', 1, '2017-05-25 16:13:44', '', '', 21, '', '0000-00-00', '0000-00-00'),
(26, 50, 'gen1', 'bd63f6f13a68be9a51b2924c3c302970af72ac50', '224243d9a', '幸福蜜蜜', 'gen1', 'gen1@gmail.com', 'catalog/-01.png', '', '31.187.79.12', 1, '2017-05-25 16:36:45', '', '', 23, '', '0000-00-00', '0000-00-00'),
(27, 50, 'gorich', '92c20c4413ae764366c70d976cca35e5a4fb9476', 'ca61aec1c', '', 'gorich', 'gorich1491@gmail.com', '', '', '', 1, '2017-05-25 16:37:49', '', '', 24, '', '0000-00-00', '0000-00-00'),
(29, 50, 'usmshop', 'f928564dc613c04c7bf259a1335b5726412ca0ef', 'c1608eaa4', '清源', '曹', 'a0971219@gmail.com', 'catalog/homeimg/gos-logo-b.png', '', '202.39.70.43', 1, '2017-05-25 16:42:47', '', '', 26, '', '0000-00-00', '0000-00-00'),
(30, 50, 'songfuu', 'faa28cba268c971e6f8200b352b6af0cd448a99c', '996263ba2', '', 'songfuu', 'song.fuu@msa.hinet.net', '', '', '', 1, '2017-05-25 16:44:59', '', '', 27, '', '0000-00-00', '0000-00-00'),
(31, 50, 'iremei', 'abf59c59259b69bbe12225a3844ea9a84a88fa81', '280d2487a', '', '愛麗美醫美診所', 'iremei@xx.xx', '', '', '', 1, '2017-05-25 17:18:19', '', '', 28, '', '0000-00-00', '0000-00-00'),
(38, 50, 'louis', '0915fbf7acbf20729ba201bf124758d869855119', 'b1f358c6f', '', 'louis', '3r168168@gmail.com', '', '', '', 1, '2017-06-05 23:37:44', '', '', 34, '', '0000-00-00', '0000-00-00'),
(37, 1, 'evo', 'bb4fd06c2bf46dbf6bcf4470011a27a34fecfbfa', '0db30ca2b', 'evo', 'yaya', '', '', '', '202.39.70.43', 1, '2017-06-03 14:06:22', '', '', 0, '', '0000-00-00', '0000-00-00'),
(64, 50, 'tetetette', 'aa55a0400f281a1948a5bff98749b49606577e7d', 'd9288b4ab', '', 'asdfsf', 'tsfse@xx.xx', '', '', '', 1, '2017-06-30 18:06:04', '', '', 60, '', '0000-00-00', '0000-00-00'),
(39, 50, 'ictv', '422519111a9d48effd51235ad38cad3645c67193', '13d9629f9', '', '網紅國際傳播股份有限公司', 'shuning@gmail.com', '', '', '202.39.70.43', 1, '2017-06-06 01:04:20', '', '', 35, '', '0000-00-00', '0000-00-00'),
(40, 50, 'dingyu', '797f3bdea47c8ac175685f3eb0bbe44d406a56f1', '2ddb3d093', '鼎鈺麗緻珠寶', '鼎鈺國際精品有限公司', 'SERVICE@DYJADE.COM', 'catalog/dyjade/dingyu-logow2.png', '44524cc3c4666f233e7450f1204b3f8142557387', '202.39.70.43', 1, '2017-06-06 01:27:58', '', '', 36, '', '0000-00-00', '0000-00-00'),
(57, 50, 'huapei', '185f57113334ce9a9580657770f55c8d9290f036', '3ed0c78f1', '長笛百變天后', '華姵', 'lifelink88@gmail.com', 'catalog/huapei/DSC_8716-1.jpg', '', '202.39.70.43', 1, '2017-06-29 14:07:54', 'a:3:{i:0;s:3:\"138\";i:1;s:3:\"141\";i:2;s:3:\"158\";}', '', 53, '', '0000-00-00', '0000-00-00'),
(58, 50, 'girl888819', 'cf0bbad3b514e8d5d1e06ac998b8ce00a6c87963', 'bb6a29f42', 'girl888819', '一起串門子', 'lifelink88@gmail.com', '', '', '202.39.70.43', 1, '2017-06-30 11:05:59', '', '', 54, '', '0000-00-00', '0000-00-00'),
(59, 50, 'bella', 'df806463d0d39efcb87f3bd53c4d8e8c1416af55', 'd0bb8c4bd', '能量姐姐', 'Bella', 'lifelink88@gmail.com', '', '', '202.39.70.43', 1, '2017-06-30 15:44:37', '', '', 55, '', '0000-00-00', '0000-00-00'),
(60, 50, 'a0952008588', 'daa1ee01bc823d172aaef032602131b58630f79c', 'f456df266', '草本科技生活', 'chiou', '', '', '', '202.39.70.43', 1, '2017-06-30 16:42:43', '', '', 56, '', '0000-00-00', '0000-00-00'),
(61, 50, 'linda', '9a08a8f3f33ed192895b8ccb688751610e1037e1', '890e98065', '豐哥牛排', 'linda', 'lifelink88@gmail.com', '', '', '', 1, '2017-06-30 17:06:51', '', '', 57, '', '0000-00-00', '0000-00-00'),
(66, 50, 'daitianfu', 'c7921f6c2552333effedaf76080ab2e579e12a8b', '81a16db2b', '', '代天府', 'daitianfu@gmail.com', '', '', '', 1, '2017-07-02 16:09:08', '', '', 62, '', '0000-00-00', '0000-00-00'),
(65, 50, 'holo', '2f7c1ffb7e07d7e4abb3f1f7e3e7bf97c7be0daa', 'bb8222798', '', '河洛歌仔戲', 'holo@lifelink.com.tw', '', '', '', 1, '2017-06-30 18:40:56', '', '', 61, '', '0000-00-00', '0000-00-00'),
(55, 50, 'kuntsan', '3f9f69899e3034504073ddfe34c9da1ae46d563d', '158ff886c', '', 'kuntsan', 'lu23664079@gmail.com', '', '', '202.39.70.43', 1, '2017-06-14 13:25:02', '', '', 51, '', '0000-00-00', '0000-00-00'),
(76, 50, 'dt888', 'a89425243a10e541fb05518e137f2489b12f2469', '0aefaf5ce', '', '動趨勢', 'dt888@gmail.com', '', '', '', 1, '2017-07-10 15:26:36', '', '', 72, '', '0000-00-00', '0000-00-00'),
(78, 50, 'dymtea', '4eee486577adb38bab52af9c729c78a8aa4dc456', '825cc676b', '秀寬', '林', 'dymtea@xx.xx', 'catalog/logo/1060721icon.png', '', '202.39.70.43', 1, '2017-07-18 12:23:11', '', '', 74, '', '0000-00-00', '0000-00-00'),
(79, 50, 'usmshop', 'd8d81e9b1d18a8d6f91b2d376b0d54bf66cc4360', '1a8f712e1', '', 'usmshop', 'usmshop@xx.xx', '', '', '', 1, '2017-07-21 12:43:22', '', '', 75, '', '0000-00-00', '0000-00-00'),
(80, 50, 'kuochanlin', '91a41f2bb3c45d687782e2bc8c92e3b7d161e560', '54f25c942', '', 'kuochanlin', 'jadeitemanager1231@gmail.com', '', '', '', 1, '2017-07-21 14:13:20', '', '', 76, '', '0000-00-00', '0000-00-00');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `oc_user`
--
ALTER TABLE `oc_user`
  ADD PRIMARY KEY (`user_id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `oc_user`
--
ALTER TABLE `oc_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理員編號', AUTO_INCREMENT=81;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
