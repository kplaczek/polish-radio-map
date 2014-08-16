
--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `region` varchar(2) COLLATE utf8_bin NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `city_id` (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `city_radio`
--

CREATE TABLE IF NOT EXISTS `city_radio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `frequency` decimal(4,1) NOT NULL,
  `city_id` int(11) NOT NULL,
  `url` text COLLATE utf8_bin NOT NULL,
  `radio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id`, `code`, `name`) VALUES
(1, 'LD', 'łódzkie'),
(2, 'LB', 'lubuskie'),
(3, 'WM', 'warmińsko-mazurskie'),
(4, 'SL', 'śląskie'),
(5, 'SW', 'świętokrzyskie'),
(6, 'LU', 'lubelskie'),
(7, 'KP', 'kujawsko-pomorskie'),
(8, 'MP', 'małopolskie'),
(9, 'PD', 'podlaskie'),
(10, 'PK', 'podkarpackie'),
(11, 'WP', 'wielkopolskie'),
(12, 'MA', 'mazowieckie'),
(13, 'PM', 'pomorskie'),
(14, 'ZP', 'zachodniopomorskie'),
(15, 'DS', 'dolnośląskie'),
(16, 'OP', 'opolskie');

-- --------------------------------------------------------

--
-- Table structure for table `transmitter`
--

CREATE TABLE IF NOT EXISTS `transmitter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `region` varchar(2) COLLATE utf8_bin NOT NULL,
  `transmitter_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transmitter_id` (`transmitter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `transmitter_radio`
--

CREATE TABLE IF NOT EXISTS `transmitter_radio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `frequency` decimal(4,1) NOT NULL,
  `transmitter_id` int(11) NOT NULL,
  `url` text COLLATE utf8_bin NOT NULL,
  `radio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
