-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 16, 2024 at 07:49 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restod`
--

-- --------------------------------------------------------

--
-- Table structure for table `hinnangud`
--
CREATE TABLE `hinnangud` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nimi` VARCHAR(18) NOT NULL,
  `kommentaar` VARCHAR(204) NOT NULL,
  `hinnang` TINYINT UNSIGNED NOT NULL,
  `resto_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hinnangud`
--

INSERT INTO `hinnangud` (`id`, `nimi`, `kommentaar`, `hinnang`, `resto_id`) VALUES
(1, 'Roderick Bilbey', 'Integer tincidunt ante vel ipsum. Praesent blandit lacinia erat. Vestibulum sed magna at nunc commodo placerat.', '8.59', 1),
(2, 'Demetre Rossant', 'Phasellus in felis. Donec semper sapien a libero. Nam dui.', '2.15', 5),
(3, 'Lem Flippen', 'Sed ante. Vivamus tortor. Duis mattis egestas metus.', '5.21', 20),
(4, 'Horatio Huygens', 'In sagittis dui vel nisl. Duis ac nibh. Fusce lacus purus, aliquet at, feugiat non, pretium quis, lectus.', '6.94', 2),
(5, 'Riane Bartod', 'Aenean lectus. Pellentesque eget nunc. Donec quis orci eget orci vehicula condimentum.', '9.44', 14),
(6, 'Kimbell Hedderly', 'Phasellus sit amet erat. Nulla tempus. Vivamus in felis eu sapien cursus vestibulum.', '7.06', 6),
(7, 'Shermy Rockey', 'Proin interdum mauris non ligula pellentesque ultrices. Phasellus id sapien in sapien iaculis congue. Vivamus metus arcu, adipiscing molestie, hendrerit at, vulputate vitae, nisl.', '8.54', 6),
(8, 'Ellis Court', 'Vestibulum quam sapien, varius ut, blandit non, interdum in, ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis faucibus accumsan odio. Curabitur convallis.', '5.07', 3),
(9, 'Rosanne Milier', 'Nullam porttitor lacus at turpis. Donec posuere metus vitae ipsum. Aliquam non mauris.', '4.08', 10),
(10, 'Nefen Schustl', 'Aenean lectus. Pellentesque eget nunc. Donec quis orci eget orci vehicula condimentum.', '7.68', 10),
(11, 'Lani Swindall', 'Morbi porttitor lorem id ligula. Suspendisse ornare consequat lectus. In est risus, auctor sed, tristique in, tempus sit amet, sem.', '6.28', 2),
(12, 'Adel Mees', 'In quis justo. Maecenas rhoncus aliquam lacus. Morbi quis tortor id nulla ultrices aliquet.', '5.65', 19),
(13, 'Jobye Niave', 'Morbi non lectus. Aliquam sit amet diam in magna bibendum imperdiet. Nullam orci pede, venenatis non, sodales sed, tincidunt eu, felis.', '2.69', 4),
(14, 'Samantha Benedicto', 'Proin interdum mauris non ligula pellentesque ultrices. Phasellus id sapien in sapien iaculis congue. Vivamus metus arcu, adipiscing molestie, hendrerit at, vulputate vitae, nisl.', '5.40', 6),
(15, 'Fina Dessaur', 'In quis justo. Maecenas rhoncus aliquam lacus. Morbi quis tortor id nulla ultrices aliquet.', '6.64', 5),
(16, 'Niven Cuncarr', 'Duis bibendum. Morbi non quam nec dui luctus rutrum. Nulla tellus.', '5.14', 10),
(17, 'Susann Ikin', 'Etiam vel augue. Vestibulum rutrum rutrum neque. Aenean auctor gravida sem.', '9.41', 10),
(18, 'Lance Spriggen', 'Curabitur gravida nisi at nibh. In hac habitasse platea dictumst. Aliquam augue quam, sollicitudin vitae, consectetuer eget, rutrum at, lorem.', '9.15', 18),
(19, 'Taddeusz Oxtoby', 'Aliquam quis turpis eget elit sodales scelerisque. Mauris sit amet eros. Suspendisse accumsan tortor quis turpis.', '5.47', 3),
(20, 'Vale Hendrix', 'Vestibulum quam sapien, varius ut, blandit non, interdum in, ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis faucibus accumsan odio. Curabitur convallis.', '3.09', 3);

-- --------------------------------------------------------

--
-- Table structure for table `restod`
--

CREATE TABLE `restod` (
  `id` int(2) DEFAULT NULL,
  `resto` varchar(9) DEFAULT NULL,
  `asukoht` varchar(13) DEFAULT NULL,
  `keskmine` decimal(3,2) DEFAULT NULL,
  `hinnatud` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restod`
--

INSERT INTO `restod` (`id`, `resto`, `asukoht`, `keskmine`, `hinnatud`) VALUES
(1, 'Matsoft', 'Sweden', '6.99', 137),
(2, 'Regrant', 'China', '7.64', 118),
(3, 'Lotlux', 'Ukraine', '5.49', 65),
(4, 'Sonsing', 'Portugal', '4.82', 39),
(5, 'Lotstring', 'China', '4.14', 36),
(6, 'Namfix', 'Sweden', '4.40', 99),
(7, 'Rank', 'Ukraine', '5.32', 171),
(8, 'Sonsing', 'Portugal', '3.16', 87),
(9, 'Redhold', 'Netherlands', '6.16', 159),
(10, 'Fix San', 'Russia', '8.74', 123),
(11, 'Transcof', 'Brazil', '4.64', 8),
(12, 'Duobam', 'Indonesia', '7.96', 20),
(13, 'Domainer', 'Brazil', '5.25', 56),
(14, 'Overhold', 'Moldova', '9.34', 173),
(15, 'Bigtax', 'Peru', '5.76', 144),
(16, 'Latlux', 'United States', '9.79', 27),
(17, 'Zathin', 'Sweden', '8.55', 100),
(18, 'Sub-Ex', 'China', '7.74', 196),
(19, 'Keylex', 'Portugal', '2.68', 150),
(20, 'Zoolab', 'Indonesia', '7.06', 122);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
