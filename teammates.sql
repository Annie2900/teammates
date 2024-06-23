-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 23, 2024 at 08:40 PM
-- Server version: 8.0.37-0ubuntu0.20.04.3
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teammates`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `admin_id` int NOT NULL,
  `admin_password` varchar(150) DEFAULT NULL,
  `admin_email` varchar(50) DEFAULT NULL,
  `secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`admin_id`, `admin_password`, `admin_email`, `secret`) VALUES
(2, '$2y$10$NGXDo/roR6iuASNNEixCNu4CBPzyXltdYPwlS68DQw5AjlBc/Qs.u', 'teammates@gmail.com', NULL),
(3, '$2y$10$RmOluO5OeVSoeAtvDDfhD.oz3WXIA0HLG1BGZ0j55Y8dcVXKJ9y9i', 'admin@gmail.com', NULL),
(4, '$2y$10$BEsxGGSaIx7j2B5LOP4lwOqrFpmeDx5pV.ARs8bep2xiAG3YANmQC', 'oravec.anett@gmail.com', NULL),
(7, '$2y$10$wzWLwk4QsXqGg49NgSBcHOy7JRixXnhY1LQZNZc4kXIrDpX/dJHmu', 'basicdavid79@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `DeliveryPerson`
--

CREATE TABLE `DeliveryPerson` (
  `del_id` int NOT NULL,
  `del_fname` varchar(100) DEFAULT NULL,
  `del_lname` varchar(100) DEFAULT NULL,
  `del_password` varchar(150) DEFAULT NULL,
  `del_email` varchar(100) DEFAULT NULL,
  `del_img` varchar(30) DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `del_rating` int DEFAULT '0',
  `del_rating_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `DeliveryPerson`
--

INSERT INTO `DeliveryPerson` (`del_id`, `del_fname`, `del_lname`, `del_password`, `del_email`, `del_img`, `admin_id`, `del_rating`, `del_rating_count`) VALUES
(1, 'John', 'Doe', 'Kutya123/', 'john.doe@gmail.com', NULL, 2, 0, 0),
(4, 'Lorant', 'Oravec', '$2y$10$DB448pIOvYukP4wYbqpPQOamfAVZaLse7Yzs4xKtyDRxW/ycDJc1u', 'oravec.lorant@gmail.com', NULL, 4, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `DeliveryRatingComment`
--

CREATE TABLE `DeliveryRatingComment` (
  `del_id` int DEFAULT NULL,
  `del_rating` int DEFAULT NULL,
  `del_comment` varchar(200) DEFAULT NULL,
  `order_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `DeliveryRatingComment`
--

INSERT INTO `DeliveryRatingComment` (`del_id`, `del_rating`, `del_comment`, `order_id`) VALUES
(1, 5, 'Szuper', 53),
(1, 5, 'Szuper2', 53),
(1, 5, 'Szuper2', 53),
(1, 5, 'Nagyon kedves a kiszállító.', 55),
(1, 5, 'Kedves volt.', 66),
(1, 5, 'Nagyon gyors kiszállítás.', 98),
(1, 5, 'Minden ok.', 88),
(1, 5, 'Kedves.', 89),
(1, 3, 'Lassuka volt', 104),
(1, 2, 'ngeine', 106);

-- --------------------------------------------------------

--
-- Table structure for table `DeliveryRatingTokens`
--

CREATE TABLE `DeliveryRatingTokens` (
  `token_id` int NOT NULL,
  `order_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `token_expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `DeliveryRatingTokens`
--

INSERT INTO `DeliveryRatingTokens` (`token_id`, `order_id`, `token`, `token_expiration`) VALUES
(1, 65, 'f1a460810895f07a50a879f6c3a447387a2ff7023ed22168a9214ab5a4e74720', '2024-06-26 16:03:36'),
(2, 47, '4554d9e03267a23bb362d00e76948618ae85750022ddf113b30ef264d23d5a77', '2024-06-26 16:10:33'),
(3, 66, '90cbe02c9c0b98033fb2e5a46d48474aa1b6b5a65d5a4f1bdc2d18dbe9d2fd54', '2023-06-20 11:42:36'),
(4, 66, 'c4f0ba5e453274d4e9075d8a538f5234fd63ac93ed7f5d540d9cfafede1796e7', '2023-06-20 09:54:27'),
(5, 67, 'e192464063f37c0ff304f2b6d7de1ce68473310903964108209bc3421aeafb09', '2023-06-19 21:11:13'),
(6, 68, '69c3bdb08ca890c5f540c215a1441beaa6da873a7f7274ba3fd1bb72f96942eb', '2023-06-20 06:28:27'),
(7, 53, '37af98e9a43ab7aa4e0b9fd219b52677402e7bba1471f4282d2342bd0e3a74ee', '2023-06-20 11:26:01'),
(8, 60, '100d7dc3bddf62fd1df8ed70b11550c6a8a2d03848fd60761b21cbb814dca4af', '2023-06-20 09:56:47'),
(9, 60, 'de6f6951021cd060fe0013de5cc93a37e023b4c97751aec37f886948b378d53a', '2024-06-27 07:53:12'),
(10, 69, '451b9a4984e6bb6d1f6fcc43677a6653c0fa2a0f9f716e7c6ea46ff1a4f4481f', '2023-06-20 07:53:45'),
(11, 55, 'f0d8ecfd468331dc08ea07f8b45e8e521eb950c04e85e0e18e521269ce43cfc1', '2023-06-20 11:27:18'),
(12, 55, '29016818633c2e7e35dd14d69251d7b33a4a08e2903bfaeea252f27a58f0e1fe', '2024-06-27 10:07:12'),
(13, 55, 'f2d363c0748521cc738c2a43c75ad23d253526df4a678222b1030387dec2eef0', '2024-06-27 10:09:49'),
(14, 55, 'c2710fe5a36e10b6196471d09f7e70ffc2435fa6b3b03c7eb030451fb2b0a69a', '2024-06-27 10:12:14'),
(15, 55, '1dc9b60e29f6985f465a841e21160b8fba9f0daa0cfd5c24994736587c133450', '2024-06-27 10:14:34'),
(16, 55, '471aaa91dde66c70b375d05a82d2f6aebee3529ae60a171c531f90610e78a362', '2024-06-27 10:16:14'),
(17, 55, 'dd1720d0d34b45c650748ba68a4a6fd0d160f015fc46a273adacc89b605aa78e', '2024-06-27 10:18:11'),
(18, 55, '96831dfa9e4682bdbbf8f305e7f39d2a4ce27504c1debd823c079cfdb0dfce65', '2024-06-27 10:21:23'),
(19, 55, '8e88b2e0a7bb361b91c322b4a531af9834e727e0ffd578cd2ced7616818c7df4', '2024-06-27 10:22:41'),
(20, 55, '105104ac700ed3f77e649cae13ffd73e0afa2119fe9110779e1880089f4c6936', '2024-06-27 10:22:54'),
(21, 55, '9f23e5fd9d83f31b328625a4941907198a850774fc3ee052e03dc60e0e3a3249', '2024-06-27 10:26:26'),
(22, 55, '0cd1d011e26e88d65b1c83f52e27909a385d63f14d54c420c7cfc62bdbeac9e3', '2024-06-27 10:26:42'),
(23, 55, '9ed503601302e667e496d3d7a444b54e9b3d759d93fa666d219d37208935311f', '2024-06-27 10:26:52'),
(24, 79, '08c982c5d7b31c4fc49e5f6193c63f6d8b79fa481225b02eb474e95fa4e0ca26', '2024-06-27 13:38:24'),
(25, 80, '2becf8f26b9a2bb5e39cf0af2d054312c93e16cffac88f02b59c0572ac1a81f6', '2024-06-27 14:04:22'),
(26, 80, '0d5c2d9295dbe597441fffedcc2020acad5fe1bbd392da98fc4c6d4c18e04570', '2024-06-27 14:05:04'),
(27, 81, '0c0f24b3b54183d858148025e1a6874167335ca2589804c4e5998d16078292f4', '2024-06-27 14:08:49'),
(28, 83, 'fdc6076c6a01eb653e86937530fc0a3a3cc3a1d5363e6bcf6d629fec5025bba5', '2024-06-27 14:08:58'),
(29, 83, '2f1b363526ca727084a80d7e25bc766e6b002e133fc6e14cb74e940ed5d00a0f', '2024-06-27 14:09:20'),
(30, 82, '4fa819295a8bcc5792d81c30211b2d85374f4b866d44965f455ead669c196e15', '2024-06-27 14:15:16'),
(31, 84, '7b799aceac142ee918e533338ac8bddf03b3509f17d9732341e110195d3f3fe1', '2024-06-27 14:15:28'),
(32, 87, 'a0c6c0d5f0a0014fa0e5351e076690aaec2ca1f273f65b3791ceb2ea4595a09b', '2024-06-27 14:32:42'),
(33, 99, '1ac82b8d76225cf826742430d84b0a45b527c385f32b48f1ac0f2ba59aa6811f', '2024-06-27 15:14:03'),
(34, 98, 'ae991daa4357d481d61a7c46d18034f320b7b4d5d3aad77f383719d5becfcbd6', '2023-06-20 15:16:32'),
(35, 85, '43c1f546410df9fc444b245dc7b62fd5b0ce0be8fce12b0ed12a769a442e3562', '2024-06-27 19:16:28'),
(36, 88, '92dca442d655b6461a0e5d40c111c8e625921ffefd805f2541d9558a3e359e96', '2023-06-20 22:57:14'),
(37, 89, 'eb40713c7cc472dc70269d31437e463d49eb79668f4bbf8fd64f68e2334279f8', '2023-06-20 22:59:57'),
(38, 90, '7285a275f9fd795041aa244c526d6092c8a6404ea82f0e162196ec9c8dfefee9', '2024-06-28 05:33:21'),
(39, 104, '057853b420bb332cd596ca27762f3b14bce56e3debc6e67ba3ca045c0831633a', '2023-06-21 06:29:31'),
(40, 86, '50a6b1a8a350b5a95e70bdce4cae36731fead6cd54803c2069977d817d76c859', '2024-06-28 06:29:54'),
(41, 105, '6b51c8c880a8c53b380e5aa615b228294ca3b11573a60a13a22463dad8920ed7', '2024-06-28 06:38:49'),
(42, 106, '95a22b036104b513c7930e68cb3950b25d8813ab38f4a7a1b9f48a6d725cf350', '2023-06-21 07:21:16'),
(43, 94, '537addf408bf20596c563979c7668d7bbfb14a13c843f1169c9d0a2d9513a0a1', '2024-06-28 07:22:05'),
(44, 91, 'cec17edb8450d9050102395aff9eb4e772f28b00a85e36e770f0e6ae3c811627', '2024-06-30 12:49:11'),
(45, 91, '6c26149d9793bea8cf24a61b89eec6930ca227b8a0fb901015e520368164001f', '2024-06-30 13:02:21'),
(46, 91, '5cf20b6b7ea8bc104309b96156b70e7bba09d71bb2cb7739156c54a14088429a', '2024-06-30 13:05:52'),
(47, 91, '09e301ab6de2d82638f1988852590b5e207a3b7fbb0be85bbdb72d1b25aa56a3', '2024-06-30 13:07:09'),
(48, 103, '87caf5cf367d2182c1ace177dd8c96b4d0a6c93100803ea60ee7323bfab1d303', '2024-06-30 13:07:20'),
(49, 103, 'c1e4058150762611ab8fdaa87dc1bee139e0b614feed3b6b145a1772cb8e171e', '2024-06-30 13:07:25'),
(50, 103, '436d2882fe55a3903bc793fade324eacd4b7e9c38f7b2b0ffdd8902043c66d54', '2024-06-30 13:07:27'),
(51, 103, '8338684791215f0fcc1a30a7fadc6f07f9829a44410eca652221f5c198a95292', '2024-06-30 13:08:13'),
(52, 103, '2b951ef23b29023e537842c02c08fda68dde683bc8359d17e5455aad8cefdec0', '2024-06-30 13:08:17'),
(53, 103, 'a6b1fa699479e41f5dec42291c5e30c34ddf01fd5261448472c1931cbae5ad3e', '2024-06-30 13:08:20'),
(54, 103, '865da2f05415b17e5e0ccc121a2dd3740e5e749afa019e097260a6181c0e1916', '2024-06-30 13:09:14'),
(55, 103, '5c0b52290de1dd4ad38d585e2d4fba70817098be44492ecede0f48fc811dcc0e', '2024-06-30 13:09:17'),
(56, 92, 'b2828bc26bd17914958f9e3ccdbd374af9510573f916ebd2cd228abaf2b16232', '2024-06-30 13:09:27'),
(57, 92, '79f4c15f89be960a1872deef01d4a107b2a1ed4e14eb8564293567082c62cb92', '2024-06-30 13:09:31'),
(58, 92, '02f5155537d974f1ec9a8c2285fd2a202bf1ba10a5539ddcdc52b01d3195dd64', '2024-06-30 13:09:38'),
(59, 92, '3af34870e1eec308160ab82df45c38eb1d204dedfaa521f79c6b65990942cab4', '2024-06-30 13:10:07'),
(60, 92, 'd39c0f2c44188f71da8767cd75b9d0b6802e36dcd02c572884352541299b1bcc', '2024-06-30 13:10:13'),
(61, 92, 'f95bae0e0bcfac269c73d8eb3d839bb631f169b6a96a40c703c63a9d67daed3a', '2024-06-30 13:13:32'),
(62, 92, '4443f67b22225db4b6e8fc4fb4e9ad9fbd3f408267cb96f64c4394043415ab94', '2024-06-30 13:13:35'),
(63, 92, '082d8272f5f86ab80b1cbf20da62b8e157bd6e415b2b405da9c183ddef668161', '2024-06-30 13:13:39'),
(64, 92, '4a47902c0976f19b1e4d7096ea1cb35759b93ac228ff799a02390a8f6ea0a1e4', '2024-06-30 13:13:42'),
(65, 92, '12fa0542d9b90faab5d7be6aff9ed876d06d56de341bc4c52ee47a594b330aa4', '2024-06-30 13:13:45'),
(66, 92, 'c6e6e6106837e5ca10acd863c750cfed4a11f8ce37a3d23819a6e7a673cd1bea', '2024-06-30 13:13:48'),
(67, 92, 'd19cdb19a04796221f8f607facc49d8b73ca7512e13e3b10ca4590321a225507', '2024-06-30 13:13:50'),
(68, 92, '7d0ef2636cfdbb7e84a6b8987fa44bd9e77440a04e21a2ca3c155bddf3893972', '2024-06-30 13:13:53'),
(69, 92, '0ebb9349b02b16866791de4d9d88632f7a51b426cd420b48ed1e1ce5b5d15f15', '2024-06-30 13:14:23'),
(70, 92, 'cf558a489b2a82cd0176691d7a70ab8aac203a1dceca956f42f3320893c69276', '2024-06-30 13:14:26'),
(71, 92, '455add1baca45cf6e3fab54eab0fb51b5c25caf2ec41c3e3da41cb2b011380f9', '2024-06-30 13:14:29'),
(72, 92, 'b9290b746355cbae2677608eb5dffed6f6cf3fa6ba02c3d34b596bbc05f7ddbd', '2024-06-30 13:14:33'),
(73, 92, '4f1c99b61de3ae4b9106219a270e189d7d5dafe6a62ccf4a3ba78121f66afae7', '2024-06-30 13:14:35'),
(74, 92, '0ea6c7e9c3a0b2998e061d62f0c72b4b53d58dac553a6746aabf122091390c0f', '2024-06-30 13:14:38'),
(75, 92, '1f07ff8d85cc41d5b16106a86d477ba3461ae79be5c97d24f3142580f069591f', '2024-06-30 13:14:40'),
(76, 92, '8c0d51a66872f3d47ed8ec9ed20a2869ec324d38c7f3427693d50acacf8eec64', '2024-06-30 13:20:38'),
(77, 92, '1b8c17f8e09f449d9866ec4b2b01d84af16e66ff1541e03af1697c6b5e028d2d', '2024-06-30 13:22:58'),
(78, 92, 'ab4ae79b35c0f6a0f8fb743a4dc17cbb601f3919e03adef7a6a8bc27e9e8fa2d', '2024-06-30 13:23:01'),
(79, 92, '814f2069d9b6e73314cda77fef78ee28ca4aa10cc3c524e5abb8b6a3553ac5f3', '2024-06-30 13:30:01');

-- --------------------------------------------------------

--
-- Table structure for table `Favourite`
--

CREATE TABLE `Favourite` (
  `favorite_id` int NOT NULL,
  `food_id` int DEFAULT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Favourite`
--

INSERT INTO `Favourite` (`favorite_id`, `food_id`, `id_user`) VALUES
(38, 1, 9),
(39, 1, 3),
(42, 2, 3),
(45, 2, 14),
(46, 1, 14),
(47, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `Food`
--

CREATE TABLE `Food` (
  `food_id` int NOT NULL,
  `food_category` varchar(50) DEFAULT NULL,
  `food_name` varchar(100) DEFAULT NULL,
  `food_price` float DEFAULT NULL,
  `food_quantity` varchar(20) DEFAULT NULL,
  `food_img` varchar(100) DEFAULT NULL,
  `food_desc` varchar(200) DEFAULT NULL,
  `food_discount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Food`
--

INSERT INTO `Food` (`food_id`, `food_category`, `food_name`, `food_price`, `food_quantity`, `food_img`, `food_desc`, `food_discount`) VALUES
(1, 'Pizza', 'Da ciso', 500, '500', '20240620221119-kulenkukorica.jpg', 'Kulen, kukorica', NULL),
(2, 'Pizza', 'Da casa', 850, '300', '20240617124108-Pizza2.jpg', 'Sajt,Sonka,Tejföl', 750),
(3, 'Tészta', 'Pasta la More', 900, '250', '20240619091046-sajtosteszta.jpg', 'Pesto,Vékony tészta, szósz', NULL),
(4, 'Csirke', 'La Fenimi', 1200, '325', '20240620222358-LaFenimi.jpg', 'Csirke , Avokádó , Rizs', NULL),
(6, 'Pizza', 'Americano', 750, '250', '20240621070918-paprika.jpg', 'Paprika', NULL),
(7, 'Hamburger', 'Hamburger', 800, '200', '20240621072549-csirkehuhu.jpg', 'gege', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `OrderedFood`
--

CREATE TABLE `OrderedFood` (
  `order_id` int DEFAULT NULL,
  `food_id` int DEFAULT NULL,
  `order_amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `OrderedFood`
--

INSERT INTO `OrderedFood` (`order_id`, `food_id`, `order_amount`) VALUES
(1, 1, 1),
(2, 1, 1),
(2, 2, 1),
(3, 1, 1),
(4, 1, 1),
(4, 2, 1),
(5, 1, 1),
(5, 2, 1),
(6, 1, 7),
(7, 1, 7),
(8, 1, 7),
(9, 1, 7),
(10, 1, 7),
(11, 1, 7),
(12, 1, 7),
(13, 1, 7),
(14, 1, 7),
(15, 1, 1),
(16, 1, 1),
(17, 1, 1),
(18, 1, 1),
(19, 1, 2),
(19, 2, 1),
(20, 1, 1),
(20, 2, 1),
(21, 1, 1),
(21, 2, 1),
(22, 1, 1),
(22, 2, 1),
(23, 1, 1),
(23, 2, 1),
(24, 1, 1),
(24, 2, 1),
(25, 1, 1),
(25, 2, 1),
(26, 1, 1),
(26, 2, 1),
(27, 1, 1),
(27, 2, 1),
(28, 1, 1),
(28, 2, 1),
(29, 1, 1),
(29, 2, 1),
(30, 1, 1),
(30, 2, 1),
(31, 1, 1),
(31, 2, 1),
(32, 1, 1),
(32, 2, 1),
(33, 1, 1),
(34, 1, 10),
(35, 1, 1),
(36, 1, 1),
(36, 2, 1),
(37, 1, 1),
(37, 2, 1),
(38, 1, 1),
(38, 2, 1),
(39, 1, 5),
(39, 2, 1),
(40, 1, 1),
(41, 1, 1),
(42, 1, 1),
(42, 2, 1),
(43, 2, 1),
(44, 3, 1),
(45, 2, 1),
(46, 2, 1),
(47, 2, 1),
(48, 1, 3),
(48, 2, 4),
(48, 3, 4),
(49, 1, 1),
(49, 2, 1),
(50, 2, 1),
(51, 2, 1),
(52, 2, 1),
(52, 3, 1),
(53, 3, 10),
(54, 3, 6),
(55, 2, 4),
(56, 1, 4),
(56, 3, 4),
(57, 2, 3),
(57, 3, 3),
(58, 1, 2),
(58, 2, 1),
(58, 3, 1),
(59, 1, 1),
(59, 3, 1),
(60, 1, 4),
(61, 1, 8),
(62, 2, 4),
(62, 3, 3),
(63, 2, 1),
(63, 3, 1),
(64, 2, 3),
(65, 1, 3),
(66, 1, 4),
(67, 1, 1),
(68, 1, 1),
(68, 3, 1),
(69, 1, 4),
(69, 2, 4),
(79, 1, 1),
(79, 2, 1),
(79, 3, 1),
(80, 1, 1),
(80, 2, 1),
(81, 1, 1),
(81, 3, 1),
(82, 1, 1),
(82, 3, 1),
(83, 1, 1),
(83, 3, 1),
(84, 1, 1),
(84, 3, 1),
(85, 1, 1),
(86, 1, 1),
(87, 1, 1),
(88, 1, 1),
(89, 1, 1),
(89, 2, 1),
(90, 1, 1),
(90, 2, 1),
(91, 1, 1),
(91, 3, 1),
(92, 2, 1),
(93, 1, 1),
(93, 2, 1),
(94, 1, 1),
(95, 1, 1),
(96, 1, 1),
(97, 1, 1),
(98, 1, 1),
(99, 1, 1),
(100, 1, 1),
(101, 1, 1),
(101, 2, 1),
(102, 1, 1),
(103, 1, 1),
(104, 1, 1),
(104, 2, 1),
(105, 1, 1),
(105, 2, 1),
(106, 1, 1),
(106, 2, 3),
(107, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_city` varchar(50) DEFAULT NULL,
  `order_street` varchar(50) DEFAULT NULL,
  `order_status` tinyint(1) DEFAULT NULL,
  `order_price` int DEFAULT NULL,
  `order_comment` varchar(200) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `del_id` int DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `del_comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`order_id`, `id_user`, `order_date`, `order_city`, `order_street`, `order_status`, `order_price`, `order_comment`, `phone`, `del_id`, `admin_id`, `delivery_date`, `del_comment`) VALUES
(1, 3, '2024-06-18 20:30:23', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 4, 4, '2024-06-18 20:30:30', NULL),
(2, 3, '2024-06-18 00:00:00', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 17:57:26', NULL),
(3, 3, '2024-06-18 20:22:06', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 1, 4, '2024-06-18 20:22:11', NULL),
(4, 3, '2024-06-18 20:22:22', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 20:22:23', NULL),
(5, 3, '2024-06-18 20:30:35', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 20:30:38', NULL),
(6, 3, '2024-06-18 21:20:27', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-18 21:20:29', NULL),
(7, 3, '2024-06-18 20:35:23', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-18 20:35:24', NULL),
(8, 3, '2024-06-19 08:59:26', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-19 08:59:27', NULL),
(9, 3, '2024-06-19 08:36:18', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-19 08:59:15', NULL),
(10, 3, '2024-06-19 12:48:35', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, 4, '2024-06-19 14:27:42', NULL),
(11, 3, '2024-06-19 09:00:02', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-19 09:00:03', NULL),
(12, 3, '2024-06-19 09:20:07', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-19 09:20:08', NULL),
(13, 3, '2024-06-19 09:14:49', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-19 09:14:50', NULL),
(14, 3, '2024-06-19 09:24:37', 'Palic', 'vranjska', 2, 3500, NULL, '0600142949', 1, NULL, '2024-06-19 09:24:37', NULL),
(15, 3, '2024-06-18 20:06:39', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 1, NULL, '2024-06-18 20:06:42', NULL),
(16, 3, '2024-06-18 20:07:03', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 1, NULL, '2024-06-18 20:12:06', NULL),
(17, 3, '2024-06-19 09:35:28', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 1, NULL, '2024-06-19 09:35:29', NULL),
(18, 4, '2024-06-19 09:28:42', 'Subotica', 'Tordanska 12', 2, 500, NULL, '+381631744942', 1, NULL, '2024-06-19 09:28:43', NULL),
(19, 4, '2024-06-18 21:21:35', 'Subotica', 'Tordanska 12', 2, 1985, NULL, '+381631744942', 1, NULL, '2024-06-18 21:21:44', NULL),
(20, 3, '2024-06-19 09:32:00', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:32:01', NULL),
(21, 3, '2024-06-19 09:29:19', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:29:20', NULL),
(22, 3, '2024-06-19 09:04:12', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:04:13', NULL),
(23, 3, '2024-06-19 09:42:36', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:42:38', NULL),
(24, 3, '2024-06-19 09:39:08', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:39:09', NULL),
(25, 3, '2024-06-19 09:41:08', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:41:08', NULL),
(26, 3, '2024-06-19 09:03:10', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:03:11', NULL),
(27, 3, '2024-06-19 09:45:25', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:45:26', NULL),
(28, 3, '2024-06-19 09:52:37', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:52:38', NULL),
(29, 3, '2024-06-19 09:57:04', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 10:20:22', NULL),
(30, 3, '2024-06-18 00:00:00', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 17:57:44', NULL),
(31, 3, '2024-06-18 20:30:12', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 20:30:12', NULL),
(32, 3, '2024-06-18 00:00:00', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 17:57:34', NULL),
(33, 3, '2024-06-19 08:36:24', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 1, NULL, '2024-06-19 08:59:16', NULL),
(34, 3, '2024-06-19 09:08:57', 'Palic', 'vranjska', 2, 5000, NULL, '0600142949', 1, NULL, '2024-06-19 09:08:58', NULL),
(35, 3, '2024-06-19 09:53:10', 'Palic', 'vranjska', 2, 500, NULL, '0600142949', 1, NULL, '2024-06-19 09:53:11', NULL),
(36, 3, '2024-06-19 09:54:39', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 09:54:39', NULL),
(37, 3, '2024-06-18 20:12:09', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 20:12:10', NULL),
(38, 3, '2024-06-18 19:46:37', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-18 19:46:38', NULL),
(39, 3, '2024-06-18 19:46:20', 'Palic', 'vranjska', 2, 3485, NULL, '0600142949', 1, NULL, '2024-06-18 19:46:29', NULL),
(40, 4, '2024-06-19 08:36:30', 'Subotica', 'Tordanska 12', 2, 500, NULL, '+381631744942', 1, NULL, '2024-06-19 08:59:17', NULL),
(41, 4, '2024-06-19 10:20:57', 'Subotica', 'Tordanska 12', 2, 500, NULL, '+381631744942', 1, NULL, '2024-06-19 10:21:05', NULL),
(42, 3, '2024-06-19 14:32:18', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 14:32:21', NULL),
(43, 4, '2024-06-19 14:27:59', 'Subotica', 'Tordanska 12', 2, 985, NULL, '+381631744942', 1, NULL, '2024-06-19 14:28:00', NULL),
(44, 4, '2024-06-19 14:27:50', 'Subotica', 'Tordanska 12', 2, 480, NULL, '+381631744942', 1, NULL, '2024-06-19 14:27:53', NULL),
(45, 4, '2024-06-19 14:17:21', 'Subotica', 'Tordanska 12', 2, 985, NULL, '+381631744942', 1, NULL, '2024-06-19 14:17:34', NULL),
(46, 4, '2024-06-19 14:32:24', 'Subotica', 'Tordanska 12', 2, 985, NULL, '+381631744942', 1, NULL, '2024-06-19 14:48:43', NULL),
(47, 4, '2024-06-19 16:10:31', 'Subotica', 'Tordanska 12', 2, 985, NULL, '+381631744942', 1, NULL, '2024-06-19 16:10:33', NULL),
(48, 4, '2024-06-19 13:36:41', 'Subotica', 'Tordanska 12', 2, 7360, NULL, '+381631744942', 1, NULL, '2024-06-19 13:36:44', NULL),
(49, 3, '2024-06-19 14:55:13', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-19 14:55:15', NULL),
(50, 3, '2024-06-19 14:58:09', 'Palic', 'vranjska', 2, 985, NULL, '0600142949', 1, NULL, '2024-06-19 14:58:10', NULL),
(51, 3, '2024-06-19 15:04:26', 'Palic', 'vranjska', 2, 985, NULL, '0600142949', 1, NULL, '2024-06-19 15:04:27', NULL),
(52, 3, '2024-06-19 15:05:31', 'Palic', 'vranjska', 2, 1465, NULL, '0600142949', 1, NULL, '2024-06-19 15:05:33', NULL),
(53, 4, '2024-06-20 07:50:36', 'Subotica', 'Tordanska 12', 2, 4800, NULL, '+381631744942', 1, NULL, '2024-06-20 07:50:46', 'Nagyon sokat kellett várni'),
(54, 3, '2024-06-19 15:08:57', 'Palic', 'vranjska', 2, 2880, NULL, '0600142949', 1, NULL, '2024-06-19 15:17:01', NULL),
(55, 4, '2024-06-20 07:55:22', 'Subotica', 'Tordanska 12', 2, 3940, NULL, '+381631744942', 1, NULL, '2024-06-20 10:26:52', NULL),
(56, 3, '2024-06-19 15:17:03', 'Palic', 'vranjska', 2, 3920, NULL, '0600142949', 1, NULL, '2024-06-19 15:17:05', NULL),
(57, 3, '2024-06-19 15:19:28', 'Palic', 'vranjska', 2, 4395, NULL, '0600142949', 1, NULL, '2024-06-19 15:19:29', NULL),
(58, 3, '2024-06-19 15:22:20', 'Palic', 'vranjska', 2, 2465, NULL, '0600142949', 1, NULL, '2024-06-19 15:22:21', NULL),
(59, 3, '2024-06-19 15:26:14', 'Palic', 'vranjska', 2, 980, NULL, '0600142949', 1, NULL, '2024-06-19 15:26:16', NULL),
(60, 4, '2024-06-20 07:51:26', 'Subotica', 'Tordanska 12', 2, 2000, NULL, '+381631744942', 1, NULL, '2024-06-20 07:53:12', 'Kedves ember volt.'),
(61, 3, '2024-06-19 15:38:16', 'Palic', 'vranjska', 2, 4000, NULL, '0600142949', 1, NULL, '2024-06-19 15:38:18', NULL),
(62, 3, '2024-06-19 15:43:47', 'Palic', 'vranjska', 2, 5380, NULL, '0600142949', 1, NULL, '2024-06-19 15:43:48', NULL),
(63, 3, '2024-06-19 15:46:51', 'Palic', 'vranjska', 2, 1465, NULL, '0600142949', 1, NULL, '2024-06-19 15:58:00', NULL),
(64, 3, '2024-06-19 15:58:38', 'Palic', 'vranjska', 2, 2955, NULL, '0600142949', 1, NULL, '2024-06-19 15:58:40', NULL),
(65, 3, '2024-06-19 16:03:34', 'Palic', 'vranjska', 2, 1500, NULL, '0600142949', 1, NULL, '2024-06-19 16:03:36', NULL),
(66, 3, '2024-06-19 16:10:36', 'Palic', 'vranjska', 2, 2000, NULL, '0600142949', 1, 4, '2024-06-19 16:17:10', NULL),
(67, 9, '2024-06-19 19:22:26', 'Subotica', 'Tordanska 12', 2, 500, NULL, '+381631744942', 1, NULL, '2024-06-19 19:22:35', NULL),
(68, 3, '2024-06-20 06:14:11', 'Palic', 'vranjska', 2, 980, NULL, '0600142949', 1, NULL, '2024-06-20 06:14:20', NULL),
(69, 9, '2024-06-20 07:53:17', 'Subotica', 'Tordanska 12', 2, 5940, NULL, '+381631744942', 1, NULL, '2024-06-20 07:53:19', 'Sok kutya'),
(79, 3, '2024-06-20 13:32:41', 'Palic', 'vranjska', 2, 1965, NULL, '0600142949', 1, 4, '2024-06-20 13:38:24', NULL),
(80, 3, '2024-06-20 14:04:05', 'Palic', 'vranjska', 2, 1485, NULL, '0600142949', 1, NULL, '2024-06-20 14:05:04', NULL),
(81, 9, '2024-06-20 14:08:47', 'Subotica', 'Tordanska 12', 2, 980, NULL, '+381631744942', 1, NULL, '2024-06-20 14:08:49', 'Undok volt az ember.'),
(82, 9, '2024-06-20 14:15:07', 'Subotica', 'Tordanska 12', 2, 980, NULL, '+381631744942', 1, NULL, '2024-06-20 14:15:16', NULL),
(83, 9, '2024-06-20 14:08:50', 'Subotica', 'Tordanska 12', 2, 980, NULL, '+381631744942', 1, NULL, '2024-06-20 14:09:20', NULL),
(84, 9, '2024-06-20 14:15:20', 'Subotica', 'Tordanska 12', 2, -1470, NULL, '+381631744942', 1, NULL, '2024-06-20 14:15:28', 'Sok várakozás'),
(85, 9, '2024-06-20 19:16:25', 'Subotica', 'Tordanska 12', 2, -750, NULL, '+381631744942', 1, NULL, '2024-06-20 19:16:28', NULL),
(86, 9, '2024-06-21 06:29:47', 'Subotica', 'Tordanska 12', 2, -750, NULL, '+381631744942', 1, 4, '2024-06-21 06:29:54', NULL),
(87, 9, '2024-06-20 14:32:35', 'Subotica', 'Tordanska 12', 2, -750, NULL, '+381631744942', 1, NULL, '2024-06-20 14:32:42', NULL),
(88, 9, '2024-06-20 22:55:15', 'Subotica', 'Tordanska 12', 2, 250, NULL, '+381631744942', 1, NULL, '2024-06-20 22:55:41', NULL),
(89, 9, '2024-06-20 22:58:57', 'Subotica', 'Tordanska 12', 2, 1485, NULL, '+381631744942', 1, NULL, '2024-06-20 22:59:35', NULL),
(90, 9, '2024-06-21 05:33:06', 'Subotica', 'Tordanska 12', 2, 1235, NULL, '+381631744942', 1, NULL, '2024-06-21 05:33:21', NULL),
(91, 9, '2024-06-23 12:49:04', 'Subotica', 'Tordanska 12', 2, 500, NULL, '+381631744942', 1, 7, '2024-06-23 13:07:08', NULL),
(92, 9, '2024-06-23 13:09:26', 'Subotica', 'Tordanska 12', 2, 985, NULL, '+381631744942', 1, NULL, '2024-06-23 13:30:01', NULL),
(93, 9, '2024-06-20 14:50:23', 'Subotica', 'Tordanska 12', 0, 1235, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(94, 9, '2024-06-21 07:21:35', 'Subotica', 'Tordanska 12', 2, 250, NULL, '+381631744942', 1, NULL, '2024-06-21 07:22:05', NULL),
(95, 9, '2024-06-20 14:56:58', 'Subotica', 'Tordanska 12', 0, 250, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(96, 9, '2024-06-20 14:58:32', 'Subotica', 'Tordanska 12', 0, 250, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(97, 9, '2024-06-20 15:03:26', 'Subotica', 'Tordanska 12', 0, 250, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(98, 9, '2024-06-20 15:15:10', 'Subotica', 'Tordanska 12', 2, 250, NULL, '+381631744942', 1, NULL, '2024-06-20 15:15:11', NULL),
(99, 9, '2024-06-20 15:13:59', 'Subotica', 'Tordanska 12', 2, 250, NULL, '+381631744942', 1, NULL, '2024-06-20 15:14:03', NULL),
(100, 9, '2024-06-20 15:36:36', 'Subotica', 'Tordanska 12', 0, 250, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(101, 9, '2024-06-20 15:49:52', 'Subotica', 'Tordanska 12', 0, 1235, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(102, 9, '2024-06-20 19:20:42', 'Subotica', 'Tordanska 12', 0, 250, NULL, '+381631744942', NULL, NULL, NULL, NULL),
(103, 9, '2024-06-23 13:07:17', 'Subotica', 'Tordanska 12', 2, 250, NULL, '+381631744942', 1, NULL, '2024-06-23 13:09:17', NULL),
(104, 3, '2024-06-21 06:28:23', 'Palic', 'vranjska', 2, 1250, NULL, '0600142949', 1, NULL, '2024-06-21 06:28:57', 'Nagy kutya az udvarba.'),
(105, 3, '2024-06-21 06:38:48', 'Palic', 'vranjska', 2, 1250, NULL, '0600142949', 1, NULL, '2024-06-21 06:38:49', 'Sok kutya'),
(106, 14, '2024-06-21 07:20:24', 'Palic', 'Matija', 2, 2750, NULL, '0600142949', 1, NULL, '2024-06-21 07:20:42', NULL),
(107, 12, '2024-06-23 13:57:03', 'Subotica', 'Torda 12', 0, 500, NULL, '0631749452', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Ratingcomment`
--

CREATE TABLE `Ratingcomment` (
  `food_rating` int DEFAULT NULL,
  `food_comment` varchar(200) DEFAULT NULL,
  `order_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Ratingcomment`
--

INSERT INTO `Ratingcomment` (`food_rating`, `food_comment`, `order_id`) VALUES
(3, 'i loved it', 67),
(5, 'Nagyon szupika', 67),
(4, 'Szupi', 67),
(5, 'Tetszik', 67),
(3, 'Nagyon finom', 68),
(5, 'I loved it.', 69),
(5, 'Nagyon finom és friss étel volt, többször is rendelni fogok.', 66),
(NULL, '', 60),
(5, 'Szuper', 53),
(5, 'Szuper2', 53),
(5, 'Szuper2', 53),
(5, 'Nagyon megvagyok elégedve az étellel.', 55),
(4, 'Kicsit ki volt hülve.', 66),
(5, 'Meleg és finom étel, mindig innen fogok rendelni.', 98),
(4, 'Meg vagyok elégedve.', 88),
(5, 'Finom és friss.', 89),
(4, 'Fini volt', 104),
(4, 'pkhkh', 106);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `img` varchar(30) DEFAULT NULL,
  `registration_token` char(40) DEFAULT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `active` smallint NOT NULL DEFAULT '0',
  `forgotten_password_token` char(40) DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `is_banned` smallint NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `firstname`, `lastname`, `phone`, `email`, `password`, `img`, `registration_token`, `registration_expires`, `active`, `forgotten_password_token`, `forgotten_password_expires`, `is_banned`, `date_time`) VALUES
(3, 'Ferenc', 'Ferdinand', '0600242949', 'ferencferdinand@gmail.com', '$2y$10$.9zC1Tk0n6T/C3vdsVFZsu0qgfnBgyoaCXDWoojimImTdrQxFsH76', NULL, '', NULL, 1, NULL, NULL, 0, '2024-06-21 06:43:07'),
(4, 'Anettka', 'Oravec', '+381631744942', 'oravec.anettka@gmail.com', '$2y$10$ivYl/CI3GWJuVcAethnKZ.G0AaP.K4.2C8INKqPOnPvgJLYA9xkmO', NULL, NULL, NULL, 1, NULL, NULL, 0, '2024-06-19 16:57:21'),
(7, 'Damijan', 'Oravec', '5458', 'domi@gmail.com', '$2y$10$YAKAnpz5aFUOcWFNiIX9keqh5k6BIyqUPClc/XUOaOIgcAUqXvkb6', NULL, NULL, NULL, 1, NULL, NULL, 0, '2024-06-21 06:27:47'),
(9, 'Anett', 'Fodor', '+381856744942', 'fodoranett@gmail.com', '$2y$10$vcwFXTmq.ULTxhF3e/G3q.FAazQCAhDy2ST0CDvlWaXAB7Cs5kVlu', NULL, '', NULL, 1, NULL, NULL, 0, '2024-06-21 06:27:03'),
(10, 'Petra', 'Lénárd', '060582458', 'lenard75@gmail.com', '$2y$10$uSefRP49VIwBlUcOnIORgO3/SyK.5yavcshCucTCylCpthwY.tCv2', NULL, NULL, NULL, 1, NULL, NULL, 0, '2024-06-20 21:15:16'),
(12, 'Anett', 'Oravec', '0631749452', 'oravec.anett@gmail.com', '$2y$10$/TIAbt.TngTpIfIxj.485.KE5NFgE8PABk7Zy.dxY3Ei5b73C4lLK', NULL, '', NULL, 1, NULL, NULL, 0, '2024-06-21 06:49:27'),
(14, 'David', 'Basic', '0600142949', 'basicdavid79@gmail.com', '$2y$10$dJeKeryyMHhA0fqgJvdRWu0/k82MVRZJb5rKi8wONdlbvKITL7J2e', NULL, '', NULL, 1, NULL, NULL, 0, '2024-06-21 07:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `UsersLocations`
--

CREATE TABLE `UsersLocations` (
  `location_id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `user_city` varchar(50) DEFAULT NULL,
  `user_street` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `UsersLocations`
--

INSERT INTO `UsersLocations` (`location_id`, `id_user`, `user_city`, `user_street`) VALUES
(1, 3, 'Palic', 'vranjska'),
(2, 4, 'Subotica', 'Tordanska 12'),
(3, 9, 'Subotica', 'Tordanska 12'),
(4, 12, 'Subotica', 'Torda 12'),
(5, 14, 'Subotica', 'Matija');

-- --------------------------------------------------------

--
-- Table structure for table `user_email_failures`
--

CREATE TABLE `user_email_failures` (
  `id_user_email_failure` int NOT NULL,
  `id_user` int NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_time_added` datetime NOT NULL,
  `date_time_tried` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `DeliveryPerson`
--
ALTER TABLE `DeliveryPerson`
  ADD PRIMARY KEY (`del_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `DeliveryRatingComment`
--
ALTER TABLE `DeliveryRatingComment`
  ADD KEY `del_id` (`del_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `DeliveryRatingTokens`
--
ALTER TABLE `DeliveryRatingTokens`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `Favourite`
--
ALTER TABLE `Favourite`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `food_id` (`food_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `Food`
--
ALTER TABLE `Food`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `OrderedFood`
--
ALTER TABLE `OrderedFood`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `del_id` (`del_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `Ratingcomment`
--
ALTER TABLE `Ratingcomment`
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `UsersLocations`
--
ALTER TABLE `UsersLocations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD PRIMARY KEY (`id_user_email_failure`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `DeliveryPerson`
--
ALTER TABLE `DeliveryPerson`
  MODIFY `del_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `DeliveryRatingTokens`
--
ALTER TABLE `DeliveryRatingTokens`
  MODIFY `token_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `Favourite`
--
ALTER TABLE `Favourite`
  MODIFY `favorite_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `Food`
--
ALTER TABLE `Food`
  MODIFY `food_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `UsersLocations`
--
ALTER TABLE `UsersLocations`
  MODIFY `location_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  MODIFY `id_user_email_failure` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DeliveryPerson`
--
ALTER TABLE `DeliveryPerson`
  ADD CONSTRAINT `DeliveryPerson_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `Admin` (`admin_id`);

--
-- Constraints for table `DeliveryRatingComment`
--
ALTER TABLE `DeliveryRatingComment`
  ADD CONSTRAINT `DeliveryRatingComment_ibfk_1` FOREIGN KEY (`del_id`) REFERENCES `DeliveryPerson` (`del_id`),
  ADD CONSTRAINT `DeliveryRatingComment_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`);

--
-- Constraints for table `DeliveryRatingTokens`
--
ALTER TABLE `DeliveryRatingTokens`
  ADD CONSTRAINT `DeliveryRatingTokens_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`);

--
-- Constraints for table `Favourite`
--
ALTER TABLE `Favourite`
  ADD CONSTRAINT `Favourite_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `Food` (`food_id`),
  ADD CONSTRAINT `Favourite_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `OrderedFood`
--
ALTER TABLE `OrderedFood`
  ADD CONSTRAINT `OrderedFood_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `OrderedFood_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `Food` (`food_id`);

--
-- Constraints for table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `Orders_ibfk_2` FOREIGN KEY (`del_id`) REFERENCES `DeliveryPerson` (`del_id`),
  ADD CONSTRAINT `Orders_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `Admin` (`admin_id`);

--
-- Constraints for table `Ratingcomment`
--
ALTER TABLE `Ratingcomment`
  ADD CONSTRAINT `Ratingcomment_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`);

--
-- Constraints for table `UsersLocations`
--
ALTER TABLE `UsersLocations`
  ADD CONSTRAINT `UsersLocations_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD CONSTRAINT `user_email_failures_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
