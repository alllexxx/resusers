-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2018 at 08:04 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grt_resources`
--

-- --------------------------------------------------------

--
-- Table structure for table `bases`
--

CREATE TABLE `bases` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fields` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bases`
--

INSERT INTO `bases` (`id`, `name`, `fields`, `active`) VALUES
(1, 'ГАИ', 'ip,login,term,annotation,docFileName', 1),
(2, 'Паспорт', 'ip,login,term,annotation,docFileName', 1),
(3, 'НАСЭД', 'login,annotation,docFileName', 1),
(4, 'ЭЦП', 'login,term,flag,annotation,docFileName', 1),
(5, 'АИПСИН', 'login,annotation,docFileName', 1),
(6, 'ИР \"Пассажиропоток\"', 'login,annotation,docFileName', 1),
(7, 'ПС ЕГБДП', 'ip,login,term,annotation,docFileName', 1),
(8, 'АС ЕГБДП', 'ip,login,term,annotation,docFileName', 1),
(9, 'ИПС Запрос', 'login,annotation,docFileName', 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `active`) VALUES
(1, 'OIBiATO', 1),
(2, 'OBKiATP', 1),
(3, 'AHO', 1),
(4, 'OAiKTP', 1),
(5, 'OAiUR', 1),
(6, 'OBUiK', 1),
(8, 'OTOiK1', 1),
(9, 'OTOiK2', 1),
(10, 'OTOiK3', 1),
(11, 'OTOiK4', 1),
(12, 'OTSTKS', 1),
(14, 'ORIKI', 1),
(15, 'PFO', 1),
(16, 'YRO', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fos_user`
--

INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(1, 'admin', 'admin', 'admin@grt.customs.gov.by', 'admin@grt.customs.gov.by', 1, NULL, '$2y$13$mYd2bEbaaDzmr0T45IY/F.u3sHx4YDi3VodDcxTJxKj3Gz/fvbRU6', '2018-07-12 20:01:05', NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}'),
(2, 'chmakav', 'chmakav', 'chmakav@grt.customs.gov.by', 'chmakav@grt.customs.gov.by', 1, NULL, '$2y$13$mYd2bEbaaDzmr0T45IY/F.u3sHx4YDi3VodDcxTJxKj3Gz/fvbRU6', '2018-08-05 08:28:42', NULL, NULL, 'a:0:{}');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `kod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `active`, `kod`) VALUES
(1, 'Карского, 53', 1, 0),
(2, 'Брузги-2', 1, 417),
(3, 'Берестовица', 1, 419),
(4, 'Привалка', 1, 412),
(5, 'Бенякони', 1, 407),
(6, 'Красносельск', 1, 458),
(7, 'Лида-Авто', 1, 421),
(8, 'Котловка', 1, 401),
(9, 'Каменный Лог', 1, 408);

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `term` date NOT NULL,
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  `admin_id` int(11) DEFAULT NULL,
  `doc_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `user_id`, `ip`, `login`, `annotation`, `created`, `updated`, `base_id`, `term`, `flag`, `admin_id`, `doc_file_name`) VALUES
(11, 1, '10.16.20.111', 'GRT_02', '', '2018-01-03 15:23:10', '2018-01-03 15:24:25', 1, '2017-12-01', 0, 1, ''),
(18, 1, '', '16-dmm', '', '2018-01-22 13:35:24', '2018-01-22 13:35:24', 3, '0000-00-00', 0, 1, ''),
(19, 1, '', 'AVP0101010100101', '', '2018-01-22 13:39:55', '2018-03-16 08:05:48', 4, '2018-04-30', 0, 2, ''),
(22, 1, '10.16.20.111', 'GRT_09', '', '2018-02-07 12:08:19', '2018-02-08 09:29:46', 2, '2018-06-12', 0, 1, ''),
(23, 118, '10.16.14.11', 'gr111', 'Приказ №1234', '2018-07-12 19:04:17', '2018-07-12 20:06:12', 1, '2018-08-10', 0, 1, ''),
(24, 72, '10.16.14.12', 'gr111', '1', '2018-07-28 11:02:43', '2018-07-28 11:02:43', 1, '2018-07-28', 0, 2, 'Penguins.jpg'),
(25, 41, '10.16.14.12', 'gr111', '1', '2018-07-28 11:09:11', '2018-07-28 11:09:11', 1, '2018-07-28', 0, 2, 'Penguins.jpg'),
(26, 62, '10.16.14.12', 'gr111', '1', '2018-07-28 11:16:45', '2018-07-28 11:16:45', 1, '2018-07-28', 0, 2, 'Desert.jpg'),
(27, 146, '10.16.14.12', 'gr111', '1', '2018-07-28 11:59:30', '2018-07-28 11:59:30', 1, '2018-07-28', 0, 2, 'Tulips.jpg'),
(28, 60, '10.16.14.12', 'gr111', '1', '2018-07-28 12:16:34', '2018-08-05 11:04:33', 1, '2018-07-28', 0, 2, '5b66bda1e54d27.85420638.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `firstname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `middlename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `domainname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `department_id`, `location_id`, `firstname`, `lastname`, `middlename`, `domainname`, `active`) VALUES
(1, 1, 1, 'Дмитрий', 'Деретчик', 'Михайлович', 'DeretchikDM', 1),
(7, 3, 1, 'Ирина', 'Никито', 'Алейзыевна', 'NikitoIA', 1),
(8, 3, 1, 'Андрей', 'Телеш', 'Иосифович', 'TeleshAI', 1),
(10, 3, 1, 'Наталия', 'Шимчик', 'Николаевна', 'ShimchikNN', 1),
(11, 3, 1, 'Марина', 'Сергеева', 'Владиславовна', 'SergeevaMV', 1),
(12, 3, 1, 'Ольга', 'Микевич', 'Геннадьевна', 'MikevichOG', 1),
(14, 3, 1, 'Валентина', 'Костюшко', 'Михайловна', 'KostiushkoVM', 1),
(15, 3, 1, 'Олег', 'Штурма', 'Эдуардович', 'ShturmaOE', 1),
(16, 3, 1, 'Сергей', 'Витко', 'Геннадьевич', 'VitkoSG', 1),
(17, 3, 1, 'Елена', 'Ковалькова', 'Александровна', 'KovalkovaEA', 1),
(18, 5, 1, 'Евгений', 'Дубатовка', 'Александрович', 'DubatovkaEA', 1),
(19, 5, 1, 'Анна', 'Босак', 'Николаевна', 'BosakAN', 1),
(20, 5, 1, 'Сергей', 'Диковицкий', 'Анатольевич', 'DzikaviskiSA', 1),
(21, 5, 1, 'Денис', 'Литвинчик', 'Юрьевич', 'LitvinchikDY', 1),
(22, 5, 1, 'Дмитрий', 'Прудниченко', 'Сергеевич', 'PrudnichenkoDS', 1),
(23, 5, 1, 'Олег', 'Герман', 'Юрьевич', 'GermanOU', 1),
(24, 4, 1, 'Мария', 'Роник', 'Геннадьевна', 'RonikMG', 1),
(25, 4, 1, 'Андрей', 'Климович', 'Ежевич', 'KlimovichAJ', 1),
(26, 4, 1, 'Владимир', 'Матвеев', 'Николаевич', 'MatveevVN', 1),
(27, 4, 1, 'Ирина', 'Ленец', 'Николаевна', 'LenecIN', 1),
(28, 4, 1, '', 'Карпач', 'Михайлович', 'KarpachSM', 1),
(29, 4, 1, 'Юлия', 'Замировская', 'Евгеньевна', 'ZamirovskayaYE', 1),
(30, 4, 1, 'Юрий', 'Марковский', 'Францевич', 'MarkovskiyYF', 1),
(31, 4, 1, 'Анджей', 'Можджер', 'Альфонсович', 'MozhjarAA', 1),
(32, 4, 1, 'Наталья', 'Коптюк', 'Михайловна', 'KapstiukNM', 1),
(33, 4, 1, 'Екатерина', 'Карпович', 'Чеславовна', 'KarpovichECh', 1),
(34, 4, 1, 'Елена', 'Дукшта', 'Ивановна', 'DukshtaAI', 1),
(35, 4, 1, 'Наталья', 'Оганесян', 'Николаевна', 'OganesyanNN', 1),
(36, 4, 1, 'Николай', 'Лапко', 'Николаевич', 'LapkoNN', 1),
(37, 4, 1, 'Ирина', 'Ксенжик', 'Ивановна', 'KsenzhykII', 1),
(38, 4, 1, 'Анжелика', 'Беленкова', 'Валентиновна', 'BelenkovaAV', 1),
(39, 4, 1, 'Светлана', 'Хонская', 'Георгиевна', 'HonskajaSG', 1),
(40, 4, 1, 'Марина', 'Войтукевич', 'Евгеньевна', 'VaitukevichME', 1),
(41, 4, 1, 'Инна', 'Астапчик', 'Александровна', 'AstapchikIA', 1),
(42, 8, 2, 'Анна', 'Литвинюк', 'Ивановна', 'LitviniukAI', 1),
(43, 8, 2, 'Анастасия', 'Витут', 'Алейзовна', 'VitutAA', 1),
(44, 8, 2, 'Вячеслав', 'Дударевич', 'Вячеславович', 'DudarevichVV', 1),
(45, 8, 2, 'Виолетта', 'Сечко', 'Олеговна', 'SechkoVO', 1),
(46, 8, 2, 'Андрей', 'Левкевич', 'Игоревич', 'LevkevichAI', 1),
(47, 8, 2, 'Светлана', 'Жук', 'Игоревна', 'ZhukSI', 1),
(48, 8, 2, 'Александр', 'Смоляков', 'Сергеевич', 'SmoliakovAS', 1),
(49, 8, 2, 'Екатерина', 'Грицук', 'Анатольевна', 'GricukEA', 1),
(50, 8, 2, 'Дарья', 'Маковец', 'Андреевна', 'MakovecDA', 1),
(51, 8, 2, 'Виктор', 'Кукла', 'Геннадьевич', 'KuklaVG', 1),
(52, 8, 2, 'Николай', 'Стасевич', 'Юрьевич', 'StasevichNY', 1),
(53, 8, 2, '', 'Сидорина', 'Владимировна', 'SidorinaAV', 1),
(54, 8, 2, 'Виктория', 'Мороз', 'Ивановна', 'MorozVI', 1),
(55, 8, 2, 'Евгения', 'Тарасюк', 'Эдуардовна', 'TarasiukEE', 1),
(56, 8, 2, 'Ирина', 'Курман', 'Владимировна', 'KurmanIV', 1),
(57, 8, 2, 'Павел', 'Шахов', 'Юрьевич', 'ShahovPY', 1),
(58, 8, 2, 'Виталий', 'Шиманович', 'Леонидович', 'ShimanovichVL', 1),
(59, 8, 2, 'Олег', 'Живулько', 'Александрович', 'ZhivulkoOA', 1),
(60, 8, 2, 'Иван', 'Барташевич', 'Рафалович', 'BartashevichIR', 1),
(61, 8, 2, 'Дмитрий', 'Кузнецов', 'Григорьевич', 'KuznecovDG', 1),
(62, 8, 2, 'Валерий', 'Бабко', 'Александрович', 'BabkoVA', 1),
(63, 8, 2, 'Владимир', 'Крицкий', 'Анатольевич', 'KrickiyVA', 1),
(64, 8, 2, 'Наталья', 'Салей', 'Дмитриевна', 'SaleyND', 1),
(65, 8, 2, 'Юлия', 'Радевич', 'Станиславовна', 'RadevichYS', 1),
(66, 8, 2, 'Тамара', 'Агеенко', 'Анатольевна', 'AgeenkoTA', 0),
(67, 8, 2, 'Юлия', 'Парфинович', 'Владимировна', 'ParfinovichYV', 1),
(68, 8, 2, 'Олег', 'Турчук', 'Викторович', 'TurchukOV', 1),
(69, 8, 2, 'Александр', 'Наркевич', 'Леонович', 'NarkevichAL', 1),
(70, 8, 2, 'Виталий', 'Невертович', 'Анатольевич', 'NevertovichVA', 1),
(71, 8, 2, 'Артем', 'Шуляк', 'Геннадьевич', 'ShuliakAG', 1),
(72, 8, 2, 'Анастасия', 'Анастасия', 'Владимировна', 'MisiukevichAV', 1),
(73, 8, 2, 'Антон', 'Романовский', 'Францевич', 'RomanovskiyAF', 1),
(74, 1, 1, 'Виталий', 'Смирнов', 'Вячеславович', 'SmirnoffVV', 1),
(75, 1, 1, 'Дмитрий', 'Микевич', 'Львович', 'MikevichDL', 1),
(76, 1, 1, 'Александр', 'Чмак', 'Владимирович', 'ChmakAV', 1),
(77, 1, 1, 'Денис', 'Болдуев', 'Александрович', 'BolduevDA', 1),
(78, 9, 2, '', 'Зотов', 'Вадимович', 'ZotovFV', 1),
(79, 9, 2, 'Татьяна', 'Дереченик', 'Анатольевна', 'DerechenikTA', 1),
(80, 9, 2, 'Вероника', 'Войтехович', 'Мечиславовна', 'VoitehovichVM', 1),
(81, 9, 2, 'Андрей', 'Бородавко', 'Александрович', 'BorodavkoAA', 1),
(82, 9, 2, 'Димитрий', 'Волчкевич', 'Вячеславович', 'VolchkevichDV', 1),
(83, 9, 2, 'Ольга', 'Ленец', 'Александровна', 'LenecOA', 1),
(84, 9, 2, 'Андрей', 'Сухоцкий', 'Васильевич', 'SuhockiyAV', 1),
(85, 9, 2, 'Майя', 'Пильтицкая', 'Ивановна', 'PilcickaiaMI', 1),
(86, 9, 2, 'Наталья', 'Сабанцева', 'Михайловна', 'SabancevaNM', 1),
(87, 9, 2, 'Надежда', 'Сергей', 'Александровна', 'SergeyNA', 1),
(88, 9, 2, 'Дмитрий', 'Латушко', 'Иванович', 'LatushkoDI', 1),
(89, 9, 2, 'Александр', 'Царенков', 'Аркадьевич', 'CarenkovAA', 1),
(90, 9, 2, 'Артем', 'Шикунов', 'Александрович', 'ShikunovAA', 1),
(91, 9, 2, 'Андрей', 'Бородич', 'Владимирович', 'BorodichAV', 1),
(92, 9, 2, 'Анна', 'Полуйчик', 'Геннадьевна', 'PoluychikAG', 1),
(93, 9, 2, 'Татьяна', 'Рудомина', 'Александровна', 'RudominaTA', 1),
(94, 9, 2, 'Андрей', 'Пузырев', 'Андреевич', 'PuzyrevAA', 1),
(95, 9, 2, 'Ольга', 'Бомбель', 'Валерьевна', 'BombelOV', 1),
(96, 9, 2, 'Дмитрий', 'Ненартович', 'Иосифович', 'NenartovichDI', 1),
(97, 9, 2, 'Алексей', 'Чуйков', 'Евгеньевич', 'ChuykovAE', 1),
(98, 9, 2, 'Анастасия', 'Мялик', 'Владимировна', 'MialikAV', 1),
(99, 9, 2, 'Вероника', 'Статкевич', 'Викторовна', 'StatkevichVV', 1),
(100, 9, 2, 'Ирина', 'Евдокимова', 'Александровна', 'EvdokimovaIA', 1),
(101, 9, 2, 'Дмитрий', 'Ярошевич', 'Константинович', 'YaroshevichDK', 1),
(102, 9, 2, 'Александра', 'Садовская', 'Сергеевна', 'SadovskaiaAS', 1),
(106, 8, 4, '', 'Денисевич', 'Александр', 'DenisevichAA', 1),
(107, 8, 4, 'Глебик-Дайлида', 'Глебик-Дайлида', 'Анна', 'GlebikDailidaAA', 1),
(108, 8, 4, 'Липский', 'Липский', 'Денис', 'LipskyDA', 1),
(109, 8, 4, 'Бондарук', 'Бондарук', 'Ольга', 'BondarukOV', 1),
(110, 8, 4, 'Астапчик', 'Астапчик', 'Ольга', 'AstapchikOV', 1),
(111, 8, 4, 'Мойса', 'Мойса', 'Валерий', 'MoysaVN', 1),
(112, 8, 4, 'Барейша', 'Барейша', 'Станислав', 'BoreyshaSF', 1),
(113, 8, 4, 'Богатко', 'Богатко', 'Анастасия', 'BogatkoAV', 1),
(114, 8, 4, 'Гуменный', 'Гуменный', 'Евгений', 'GumennyyEV', 1),
(115, 8, 4, 'Синякова', 'Синякова', 'Анна', 'SinyakovaAS', 1),
(116, 8, 4, 'Пашкевич', 'Пашкевич', 'Александр', 'PashkevichAV', 1),
(117, 8, 4, 'Ладыгин', 'Ладыгин', 'Алексей', 'LadyginAY', 1),
(118, 8, 4, 'Азука', 'Азука', 'Владимир', 'AzukaVS', 1),
(119, 8, 4, 'Матушевский', 'Матушевский', 'Артём', 'MatushevskiAV', 1),
(120, 8, 4, 'Алейзы', 'Витут', 'Славомирович', 'VitutAS', 1),
(121, 8, 4, 'НИКОЛАЙ', 'Лаба', 'СТЕПАНОВИЧ', 'LabaNS', 1),
(122, 8, 4, 'Алексей', 'Романовский', 'Николаевич', 'RomanovskyAN', 1),
(123, 8, 4, 'Валерий', 'Чугунов', 'Олегович', 'ChugunovVA', 1),
(124, 9, 4, 'Александр', 'Санукевич', 'Николаевич', 'SanukevichAN', 1),
(125, 9, 4, 'Виктор', 'Дзядович', 'Янович', 'DzyadovichVY', 1),
(126, 9, 4, 'Светлана', 'Прохор', 'Анатольевна', 'ProhorSA', 1),
(127, 9, 4, 'Екатерина', 'Миронова', 'Александровна', 'MironovaEA', 1),
(128, 9, 4, 'Евгений', 'Шомин', 'Александрович', 'ShominEA', 1),
(129, 9, 4, 'Александр', 'Козел', 'Владимирович', 'KozelAV', 1),
(130, 9, 4, 'Оскар', 'Грундсбергс', 'Янович', 'GrundsbergsOY', 1),
(131, 9, 4, '', 'Терешко', 'Викторович', 'TereshkoMV', 1),
(132, 9, 4, 'Вадим', 'Билида', 'Евгеньевич', 'BilidaVE', 1),
(133, 9, 4, 'Екатерина', 'Салей', 'Станиславовна', 'SaleyES', 1),
(134, 9, 4, 'Юлия', 'Нехайчик', 'Владимировна', 'NehaychikYV', 1),
(135, 9, 4, 'Виталий', 'Кишкун', 'Владимирович', 'KishkunVV', 1),
(136, 9, 4, 'Дмитрий', 'Листопадов', 'Леонидович', 'ListopadovDL', 1),
(137, 10, 4, 'Юрий', 'Кисель', 'Михайлович', 'KiselYM', 1),
(138, 10, 4, 'Виктор', 'Обуховский', 'Иванович', 'ObuhovskyVI', 1),
(139, 10, 4, 'Сергей', 'Филиппов', 'Иванович', 'FilippovSI', 1),
(140, 10, 4, 'Евгений', 'Казакевич', 'Генрихович', 'KazakevichEG', 1),
(141, 10, 4, 'Наталья', 'Волкович', 'Александровна', 'VolkovichNA', 1),
(142, 10, 4, 'Ольга', 'Жук', 'Юрьевна', 'ZhukOY', 1),
(143, 10, 4, 'Елена', 'Ровба', 'Валентиновна', 'RovbaEV', 1),
(144, 10, 4, 'Роман', 'Саакян', 'Борисович', 'SaakyanRB', 1),
(145, 10, 4, 'Евгений', 'Касперец', 'Чеславович', 'KasperetsECh', 1),
(146, 10, 4, 'Татьяна', 'Баран', 'Евгеньевна', 'BaranTE', 1),
(147, 10, 4, 'Павел', 'Новик', 'Юрьевич', 'NovikPY', 1),
(148, 10, 4, 'Ольга', 'Казак', 'Юрьевна', 'KazakOY', 1),
(149, 10, 4, 'Вита', 'Ковкель', 'Иосифовна', 'KovkelVIo', 1),
(150, 10, 4, 'Андрей', 'Сидорович', 'Юрьевич', 'SidorovichAY', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bases`
--
ALTER TABLE `bases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_217B2A3B5E237E06` (`name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_16AEB8D45E237E06` (`name`);

--
-- Indexes for table `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_17E64ABA5E237E06` (`name`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EF66EBAEA76ED395` (`user_id`),
  ADD KEY `IDX_EF66EBAE6967DF41` (`base_id`),
  ADD KEY `IDX_EF66EBAE642B8210` (`admin_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649A8ED4696` (`domainname`),
  ADD KEY `IDX_8D93D649AE80F5DF` (`department_id`),
  ADD KEY `IDX_8D93D64964D218E` (`location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bases`
--
ALTER TABLE `bases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `FK_EF66EBAE642B8210` FOREIGN KEY (`admin_id`) REFERENCES `fos_user` (`id`),
  ADD CONSTRAINT `FK_EF66EBAE6967DF41` FOREIGN KEY (`base_id`) REFERENCES `bases` (`id`),
  ADD CONSTRAINT `FK_EF66EBAEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64964D218E` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `FK_8D93D649AE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
