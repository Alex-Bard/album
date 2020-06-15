-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 28 2020 г., 15:08
-- Версия сервера: 5.7.26
-- Версия PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `album`
--

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `idFile` int(50) NOT NULL AUTO_INCREMENT,
  `nameFile` varchar(255) NOT NULL,
  `orig` varchar(255) NOT NULL,
  `miniature` varchar(255) NOT NULL,
  `dateFile` datetime DEFAULT NULL,
  `model` varchar(60) DEFAULT NULL,
  `favorites` tinyint(1) DEFAULT NULL,
  `lat` varchar(60) DEFAULT NULL,
  `Clong` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`idFile`),
  UNIQUE KEY `orig` (`orig`),
  UNIQUE KEY `miniature` (`miniature`)
) ENGINE=MyISAM AUTO_INCREMENT=917 DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`idFile`, `nameFile`, `orig`, `miniature`, `dateFile`, `model`, `favorites`, `lat`, `Clong`) VALUES
(906, 'DSC_0010.JPG', 'files/full/DSC_0010.JPG', 'files/thumb/m_DSC_0010.JPG', '2013-09-21 09:59:11', 'Sony C6903', NULL, 'NULL', 'NULL'),
(905, 'DSC_0009_1.JPG', 'files/full/DSC_0009_1.JPG', 'files/thumb/m_DSC_0009_1.JPG', '2014-10-17 12:53:26', 'Sony C6903', NULL, 'NULL', 'NULL'),
(899, 'DSC_0005.JPG', 'files/full/DSC_0005.JPG', 'files/thumb/m_DSC_0005.JPG', '2013-09-21 09:56:49', 'Sony C6903', NULL, 'NULL', 'NULL'),
(900, 'DSC_0006.JPG', 'files/full/DSC_0006.JPG', 'files/thumb/m_DSC_0006.JPG', '2013-09-21 09:57:06', 'Sony C6903', NULL, 'NULL', 'NULL'),
(901, 'DSC_0007.JPG', 'files/full/DSC_0007.JPG', 'files/thumb/m_DSC_0007.JPG', '2013-09-21 09:57:42', 'Sony C6903', NULL, 'NULL', 'NULL'),
(902, 'DSC_0008.JPG', 'files/full/DSC_0008.JPG', 'files/thumb/m_DSC_0008.JPG', '2013-09-21 09:57:59', 'Sony C6903', NULL, 'NULL', 'NULL'),
(903, 'DSC_0008_1.JPG', 'files/full/DSC_0008_1.JPG', 'files/thumb/m_DSC_0008_1.JPG', '2014-10-17 10:14:02', 'Sony C6903', NULL, 'NULL', 'NULL'),
(904, 'DSC_0009.JPG', 'files/full/DSC_0009.JPG', 'files/thumb/m_DSC_0009.JPG', '2013-09-21 09:58:14', 'Sony C6903', NULL, 'NULL', 'NULL'),
(898, 'DSC_0004_1.JPG', 'files/full/DSC_0004_1.JPG', 'files/thumb/m_DSC_0004_1.JPG', '2013-09-20 19:04:58', 'Sony C6903', NULL, 'NULL', 'NULL'),
(897, 'DSC_0004.JPG', 'files/full/DSC_0004.JPG', 'files/thumb/m_DSC_0004.JPG', '2013-09-21 08:14:17', 'Sony C6903', NULL, 'NULL', 'NULL'),
(896, 'DSC_0003_1.JPG', 'files/full/DSC_0003_1.JPG', 'files/thumb/m_DSC_0003_1.JPG', '2013-09-20 19:04:41', 'Sony C6903', NULL, 'NULL', 'NULL'),
(907, 'DSC_0010_1.JPG', 'files/full/DSC_0010_1.JPG', 'files/thumb/m_DSC_0010_1.JPG', '2014-10-17 17:01:17', 'Sony C6903', NULL, 'NULL', 'NULL'),
(908, 'DSC_0011.JPG', 'files/full/DSC_0011.JPG', 'files/thumb/m_DSC_0011.JPG', '2013-09-21 09:59:54', 'Sony C6903', NULL, 'NULL', 'NULL'),
(909, 'DSC_0011_1.JPG', 'files/full/DSC_0011_1.JPG', 'files/thumb/m_DSC_0011_1.JPG', '2014-10-17 17:01:40', 'Sony C6903', NULL, 'NULL', 'NULL'),
(910, 'DSC_0012.JPG', 'files/full/DSC_0012.JPG', 'files/thumb/m_DSC_0012.JPG', '2013-09-21 10:04:47', 'Sony C6903', NULL, 'NULL', 'NULL'),
(911, 'DSC_0012_1.JPG', 'files/full/DSC_0012_1.JPG', 'files/thumb/m_DSC_0012_1.JPG', '2014-10-17 17:01:44', 'Sony C6903', NULL, 'NULL', 'NULL'),
(912, 'DSC_0013.JPG', 'files/full/DSC_0013.JPG', 'files/thumb/m_DSC_0013.JPG', '2013-09-21 10:05:25', 'Sony C6903', NULL, 'NULL', 'NULL'),
(913, 'DSC_0014.JPG', 'files/full/DSC_0014.JPG', 'files/thumb/m_DSC_0014.JPG', '2014-10-27 20:30:11', 'Sony C6903', NULL, 'NULL', 'NULL'),
(914, 'DSC_0015.JPG', 'files/full/DSC_0015.JPG', 'files/thumb/m_DSC_0015.JPG', '2014-09-21 11:03:59', 'Sony C6903', NULL, 'NULL', 'NULL'),
(915, 'DSC_0015_1.JPG', 'files/full/DSC_0015_1.JPG', 'files/thumb/m_DSC_0015_1.JPG', '2014-10-27 20:47:47', 'Sony C6903', NULL, 'NULL', 'NULL'),
(916, 'DSC_0016.JPG', 'files/full/DSC_0016.JPG', 'files/thumb/m_DSC_0016.JPG', '2014-09-21 11:04:04', 'Sony C6903', NULL, 'NULL', 'NULL');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(255) DEFAULT NULL,
  `tokBot` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `role`, `token`, `tokBot`) VALUES
(39, 'Vasia', '$2y$10$Beu9GZJEhFsOFdgb9lW2R.09nIjSUTD3HJ/n/m2sr7qU8Utq5MXyC', 1, '02d774e985c7a3376dcf7f926e4f3d4a', NULL),
(40, 'Petia', '$2y$10$jxosQ4rrdQtwGdcBMUa9SuJs4rahDyA8hiAFPkIRvXz/37.WvZINy', 1, 'e08a4c4af6e681f0be0c8840656f270e', NULL),
(34, 'Alex', '$2y$10$GcUvqq/N5VvDXTrVPpj.8OK280sJKhmL8X8tvN.kJIrk52iYpFiqC', 2, 'da48a16fe84bf693e7d8cc26c2b60f6e', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
