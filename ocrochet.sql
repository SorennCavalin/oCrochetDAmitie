-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 13 mars 2023 à 11:09
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ocrochet`
--

-- --------------------------------------------------------

--
-- Structure de la table `concours`
--

CREATE TABLE `concours` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `date_fin` date NOT NULL,
  `date_debut` date NOT NULL,
  `projet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `concours`
--

INSERT INTO `concours` (`id`, `nom`, `date_fin`, `date_debut`, `projet_id`) VALUES
(2, 'Test', '2022-10-30', '2022-10-13', 7),
(3, 'concours test 1', '2022-11-25', '2022-11-06', 9),
(4, 'concours test 2', '2022-11-25', '2022-11-06', 9),
(5, 'concours test 3', '2022-12-04', '2022-11-04', 10),
(6, 'concours test 11', '2023-02-14', '2023-02-14', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `don`
--

CREATE TABLE `don` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL COMMENT 'envoi/reception',
  `date` date NOT NULL,
  `donataire` varchar(50) NOT NULL,
  `organisme` varchar(50) NOT NULL,
  `reception` tinyint(1) NOT NULL,
  `description` text DEFAULT NULL,
  `concours_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `don`
--

INSERT INTO `don` (`id`, `type`, `date`, `donataire`, `organisme`, `reception`, `description`, `concours_id`) VALUES
(27, 'envoi', '2023-02-14', '', 'truc muche', 1, NULL, NULL),
(28, 'envoi', '2022-11-12', '', 'truc muche 2', 1, NULL, NULL),
(29, 'reception', '2022-11-18', 'mitra', '', 1, NULL, NULL),
(31, 'envoi', '2023-02-14', '', 'croix rouge', 0, NULL, NULL),
(32, 'reception', '2022-11-18', '15', '', 1, NULL, NULL),
(33, 'envoi', '2023-02-14', '', 'truc muche', 1, NULL, NULL),
(34, 'envoi', '2022-11-12', '', 'truc muche 2', 1, NULL, NULL),
(35, 'reception', '2022-11-18', 'mitra', '', 1, NULL, NULL),
(37, 'reception', '2022-11-18', '15', '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `don_details`
--

CREATE TABLE `don_details` (
  `id` int(11) NOT NULL,
  `don_id` int(11) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `qte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `don_details`
--

INSERT INTO `don_details` (`id`, `don_id`, `nom`, `qte`) VALUES
(27, 27, 'Testos spé', 25),
(28, 27, 'Testos max', 1),
(29, 27, 'Testos sm', 50),
(30, 27, 'Testos ult', 10),
(31, 28, 'Testos spé', 25),
(32, 29, 'Emoji', 1),
(37, 31, 'objet 1', 20),
(38, 31, 'objet 2', 10),
(39, 27, 'Testos spé', 25),
(40, 27, 'Testos max', 1),
(41, 27, 'Testos sm', 50),
(42, 27, 'Testos ult', 10),
(43, 28, 'Testos spé', 25),
(44, 29, 'Emoji', 1),
(45, 31, 'objet 1', 20),
(46, 31, 'objet 2', 10),
(47, 27, 'Testos spé', 25),
(48, 27, 'Testos max', 1),
(49, 27, 'Testos sm', 50),
(50, 27, 'Testos ult', 10),
(51, 28, 'Testos spé', 25),
(52, 29, 'Emoji', 1),
(53, 31, 'objet 1', 20),
(54, 31, 'objet 2', 10),
(55, 27, 'Testos spé', 25),
(56, 27, 'Testos max', 1),
(57, 27, 'Testos sm', 50),
(58, 27, 'Testos ult', 10),
(59, 28, 'Testos spé', 25),
(60, 29, 'Emoji', 1),
(61, 31, 'objet 1', 20),
(62, 31, 'objet 2', 10);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `concours_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id`, `concours_id`, `user_id`) VALUES
(1, 2, 11);

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `page` text NOT NULL COMMENT 'false',
  `date_debut` date NOT NULL DEFAULT current_timestamp(),
  `date_fin` date DEFAULT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id`, `nom`, `page`, `date_debut`, `date_fin`, `slug`) VALUES
(7, 'projet test 35', '<ul>\n    <li>test test</li>\n    <li>test test</li>\n    <li>test test</li>\n    <li>test test</li>\n    <li>test test</li>\n</ul>', '2022-10-13', '2022-10-30', 'projet-test-35'),
(9, 'projet test 1', 'test test', '2022-12-06', '2022-11-25', 'projet-test-1'),
(10, 'projet test 2', 'test test', '2022-11-04', '2022-12-04', 'projet-test-2'),
(11, 'projet test 5', '<p>un projet </p>', '2022-12-16', '2022-12-17', 'projet-test-5'),
(12, 'projet test 4', '', '2023-01-12', '2023-01-25', 'projet-test-4'),
(13, 'projet test 1.1', 'test test', '2023-02-01', '2023-05-28', 'projet-test-1-1'),
(14, 'projet test 6', 'test test', '2022-09-15', '2022-10-23', 'projet-test-6'),
(15, 'projet x', 'une page', '2023-02-20', '2023-03-20', 'projet-test-x'),
(16, 'Projet test véritable', '<h1>Mon repas de Noël</h1>\r\n    <P>J\'ai eu la chance d\'avoir un repas de Noël excellent, puisqu\'il était composé des plats suivant:\r\n        <ul> \r\n    <li>- Du foie gras sur des toats grillés (ça j\'adore) </li> \r\n    <li>- De la pintade avec une puré </li>\r\n    <li>- Une bûche glacé </li>\r\n        </ul></P>\r\n   <p><br>Et le tout arrosé de Champagne !\r\n    <br>Après, j\'ai pu déballer tous mes cadeaux :\r\n    <ol>\r\n        <li>Mamie Lulu : des DVD</li>\r\n        <li>Mamie Elisabeth : des CD</li>\r\n        <li>Tonton hugues : des livres</li>\r\n    </ol>\r\n    </P>', '2023-02-06', '2023-03-03', 'projet-test-veritable'),
(17, 'concours test ultime', 'code code', '2023-02-22', '2023-02-26', 'concours-test-ultime'),
(31, 'projet test 35', '<ul>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n</ul>', '2022-10-13', '2022-10-30', 'projet-test-35'),
(32, 'projet test 1', 'test test', '2022-12-06', '2022-11-25', 'projet-test-1'),
(33, 'projet test 2', 'test test', '2022-11-04', '2022-12-04', 'projet-test-2'),
(46, 'projet test 1.1', 'test test', '2023-02-01', '2023-05-28', 'projet-test-1-1'),
(47, 'projet test 6', 'test test', '2022-09-15', '2022-10-23', 'projet-test-6'),
(48, 'projet x', 'une page', '2023-02-20', '2023-03-20', 'projet-test-x'),
(49, 'Projet test véritable', '<h1>Mon repas de Noël</h1>\r\n    <P>J\'ai eu la chance d\'avoir un repas de Noël excellent, puisqu\'il était composé des plats suivant:\r\n        <ul> \r\n    <li>- Du foie gras sur des toats grillés (ça j\'adore) </li> \r\n    <li>- De la pintade avec une puré </li>\r\n    <li>- Une bûche glacé </li>\r\n        </ul></P>\r\n   <p><br>Et le tout arrosé de Champagne !\r\n    <br>Après, j\'ai pu déballer tous mes cadeaux :\r\n    <ol>\r\n        <li>Mamie Lulu : des DVD</li>\r\n        <li>Mamie Elisabeth : des CD</li>\r\n        <li>Tonton hugues : des livres</li>\r\n    </ol>\r\n    </P>', '2023-02-06', '2023-03-03', 'projet-test-veritable'),
(50, 'concours test ultime', 'code code', '2023-02-22', '2023-02-26', 'concours-test-ultime'),
(51, 'projet test 35', '<ul>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n</ul>', '2022-10-13', '2022-10-30', 'projet-test-35'),
(52, 'projet test 1', 'test test', '2022-12-06', '2022-11-25', 'projet-test-1'),
(58, 'projet x', 'une page', '2023-02-20', '2023-03-20', 'projet-test-x'),
(59, 'Projet test véritable', '<h1>Mon repas de Noël</h1>\r\n    <P>J\'ai eu la chance d\'avoir un repas de Noël excellent, puisqu\'il était composé des plats suivant:\r\n        <ul> \r\n    <li>- Du foie gras sur des toats grillés (ça j\'adore) </li> \r\n    <li>- De la pintade avec une puré </li>\r\n    <li>- Une bûche glacé </li>\r\n        </ul></P>\r\n   <p><br>Et le tout arrosé de Champagne !\r\n    <br>Après, j\'ai pu déballer tous mes cadeaux :\r\n    <ol>\r\n        <li>Mamie Lulu : des DVD</li>\r\n        <li>Mamie Elisabeth : des CD</li>\r\n        <li>Tonton hugues : des livres</li>\r\n    </ol>\r\n    </P>', '2023-02-06', '2023-03-03', 'projet-test-veritable'),
(60, 'concours test ultime', 'code code', '2023-02-22', '2023-02-26', 'concours-test-ultime'),
(61, 'projet test 35', '<ul>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n</ul>', '2022-10-13', '2022-10-30', 'projet-test-35'),
(62, 'projet test 1', 'test test', '2022-12-06', '2022-11-25', 'projet-test-1'),
(63, 'projet test 2', 'test test', '2022-11-04', '2022-12-04', 'projet-test-2'),
(64, 'projet test 5', '<p>un projet </p>', '2022-12-16', '2022-12-17', 'projet-test-5'),
(65, 'projet test 4', '', '2023-01-12', '2023-01-25', 'projet-test-4'),
(66, 'projet test 1.1', 'test test', '2023-02-01', '2023-05-28', 'projet-test-1-1'),
(67, 'projet test 6', 'test test', '2022-09-15', '2022-10-23', 'projet-test-6'),
(68, 'projet x', 'une page', '2023-02-20', '2023-03-20', 'projet-test-x'),
(69, 'Projet test véritable', '<h1>Mon repas de Noël</h1>\r\n    <P>J\'ai eu la chance d\'avoir un repas de Noël excellent, puisqu\'il était composé des plats suivant:\r\n        <ul> \r\n    <li>- Du foie gras sur des toats grillés (ça j\'adore) </li> \r\n    <li>- De la pintade avec une puré </li>\r\n    <li>- Une bûche glacé </li>\r\n        </ul></P>\r\n   <p><br>Et le tout arrosé de Champagne !\r\n    <br>Après, j\'ai pu déballer tous mes cadeaux :\r\n    <ol>\r\n        <li>Mamie Lulu : des DVD</li>\r\n        <li>Mamie Elisabeth : des CD</li>\r\n        <li>Tonton hugues : des livres</li>\r\n    </ol>\r\n    </P>', '2023-02-06', '2023-03-03', 'projet-test-veritable'),
(70, 'concours test ultime', 'code code', '2023-02-22', '2023-02-26', 'concours-test-ultime'),
(71, 'projet test 35', '<ul>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n</ul>', '2022-10-13', '2022-10-30', 'projet-test-35'),
(72, 'projet test 1', 'test test', '2022-12-06', '2022-11-25', 'projet-test-1'),
(73, 'projet test 2', 'test test', '2022-11-04', '2022-12-04', 'projet-test-2'),
(74, 'projet test 1.1', 'test test', '2023-02-01', '2023-05-28', 'projet-test-1-1'),
(75, 'projet test 6', 'test test', '2022-09-15', '2022-10-23', 'projet-test-6'),
(76, 'projet x', 'une page', '2023-02-20', '2023-03-20', 'projet-test-x'),
(77, 'Projet test véritable', '<h1>Mon repas de Noël</h1>\r\n    <P>J\'ai eu la chance d\'avoir un repas de Noël excellent, puisqu\'il était composé des plats suivant:\r\n        <ul> \r\n    <li>- Du foie gras sur des toats grillés (ça j\'adore) </li> \r\n    <li>- De la pintade avec une puré </li>\r\n    <li>- Une bûche glacé </li>\r\n        </ul></P>\r\n   <p><br>Et le tout arrosé de Champagne !\r\n    <br>Après, j\'ai pu déballer tous mes cadeaux :\r\n    <ol>\r\n        <li>Mamie Lulu : des DVD</li>\r\n        <li>Mamie Elisabeth : des CD</li>\r\n        <li>Tonton hugues : des livres</li>\r\n    </ol>\r\n    </P>', '2023-02-06', '2023-03-03', 'projet-test-veritable'),
(78, 'concours test ultime', 'code code', '2023-02-22', '2023-02-26', 'concours-test-ultime'),
(79, 'projet test 35', '<ul>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n    <li>test test</li>\r\n</ul>', '2022-10-13', '2022-10-30', 'projet-test-35'),
(80, 'projet test 1', 'test test', '2022-12-06', '2022-11-25', 'projet-test-1'),
(81, 'projet x', 'une page', '2023-02-20', '2023-03-20', 'projet-test-x'),
(82, 'Projet test véritable', '<h1>Mon repas de Noël</h1>\r\n    <P>J\'ai eu la chance d\'avoir un repas de Noël excellent, puisqu\'il était composé des plats suivant:\r\n        <ul> \r\n    <li>- Du foie gras sur des toats grillés (ça j\'adore) </li> \r\n    <li>- De la pintade avec une puré </li>\r\n    <li>- Une bûche glacé </li>\r\n        </ul></P>\r\n   <p><br>Et le tout arrosé de Champagne !\r\n    <br>Après, j\'ai pu déballer tous mes cadeaux :\r\n    <ol>\r\n        <li>Mamie Lulu : des DVD</li>\r\n        <li>Mamie Elisabeth : des CD</li>\r\n        <li>Tonton hugues : des livres</li>\r\n    </ol>\r\n    </P>', '2023-02-06', '2023-03-03', 'projet-test-veritable'),
(83, 'concours test ultime', 'code code', '2023-02-22', '2023-02-26', 'concours-test-ultime');

-- --------------------------------------------------------

--
-- Structure de la table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL COMMENT 'false',
  `region` varchar(70) NOT NULL,
  `departement` int(5) NOT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(100) NOT NULL COMMENT 'admin/benevol',
  `confirmation_token` varchar(255) DEFAULT NULL COMMENT 'false',
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `email`, `mdp`, `telephone`, `region`, `departement`, `adresse`, `date_inscription`, `role`, `confirmation_token`, `active`) VALUES
(11, 'Sorenn', 'Cavalin', 'email@email.com', '$2y$10$kwFFv5ld5MpmuWocdn8nFexgIhYR4t0/f06eUVu/z6vPmS7coxNI2', '0615151515', 'Ile de France', 94200, '8 Avenue Spinoza', '2023-03-08 09:01:01', 'ROLE_ADMIN', NULL, 1),
(15, 'Prenom', 'Nom', 'email1@email.com', '$2y$09$Msk4gN.zxmJMG.xUYq1tU.lYXLpNA/m6e/SYXw.IWYErXBIUO8h5S', '0615151515', 'Region-sur-Region', 15151, '15 Rue Du Quinze', '2023-02-09 09:28:28', 'ROLE_BENEVOLE', NULL, 1),
(16, 'Nom', 'Prenom', 'email2@email.com', '$2y$09$DOT0L2wjNbg4S6bz0OBoNOD.t7bvyChKZsaGpsmkBZnB9qFwP28BC', '0615151515', 'Quinze-de-Quinze', 15151, '15 Rue Du Quinze', '2023-02-09 09:28:30', 'ROLE_BENEVOLE', NULL, 1),
(18, 'Prenom', 'Nom', 'email3@email.com', '$2y$09$0QRY9u7Ntw0gMDODqkqgOOVWIJo4mLdB4UTaYHKccLaX9X4xWo3GC', '0615151515', 'Prenom', 15151, '15 Rue Du Quinze', '2023-02-09 09:28:31', 'ROLE_BENEVOLE', NULL, 1),
(22, 'Truc', 'Nom', 'test@email.com', '$2y$10$W2/tgu3N4zmoznwy2RYZ3uQtrl/Vh3ae/H4Xpso.k1rDjzRisrNZC', '0615151515', 'Prenom', 75015, '5 Rue Lecourbe 75015 Paris', '2023-02-09 09:28:33', 'ROLE_BENEVOLE', NULL, 1),
(23, 'Machin', 'Nom', 'test2@email.com', '$2y$09$7Yn0iUUu4gPECPCwgwA41unvaUtzk7BDENk89Anvdk0/ot6Q6iRnm', '0615151515', 'Nouvelle-Aquitaine', 33800, '5 Rue Pelleport 33800 Bordeaux', '2023-02-09 09:28:35', 'ROLE_BENEVOLE', NULL, 1),
(24, 'Jean', 'Nom', 'test4@email.com', '$2y$09$O5gjXe8DuQeW99KCpMHHlObGFQPSZ9irTID4K7hYQ8EeTkrZkRZ1G', '0615151515', 'Nouvelle-Aquitaine', 33800, '5 Rue Pelleport 33800 Bordeaux', '2023-03-08 08:45:29', 'ROLE_ADMIN', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `nom` varchar(80) NOT NULL,
  `lien` text NOT NULL,
  `plateforme` varchar(30) NOT NULL COMMENT 'youtube/tiktok/instagram/facebook',
  `type` varchar(20) NOT NULL,
  `slug` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `nom`, `lien`, `plateforme`, `type`, `slug`) VALUES
(2, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', 'video-test'),
(4, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(5, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube', '', ''),
(6, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(7, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube', '', ''),
(8, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(9, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube', '', ''),
(10, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(12, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(13, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube', '', ''),
(14, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(15, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube', '', ''),
(16, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', '', ''),
(17, 'vidéo de test slug àèé12$*', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', 'live', 'video-de-test-slug-aee12'),
(18, 'video test 2', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook', 'live', 'video-test-2');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `concours`
--
ALTER TABLE `concours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projet_id` (`projet_id`);

--
-- Index pour la table `don`
--
ALTER TABLE `don`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `don_details`
--
ALTER TABLE `don_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `don_id` (`don_id`);

--
-- Index pour la table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concours_id` (`concours_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `concours`
--
ALTER TABLE `concours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `don`
--
ALTER TABLE `don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `don_details`
--
ALTER TABLE `don_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT pour la table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `concours`
--
ALTER TABLE `concours`
  ADD CONSTRAINT `concours_ibfk_1` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`id`);

--
-- Contraintes pour la table `don_details`
--
ALTER TABLE `don_details`
  ADD CONSTRAINT `don_details_ibfk_1` FOREIGN KEY (`don_id`) REFERENCES `don` (`id`);

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `participant_ibfk_1` FOREIGN KEY (`concours_id`) REFERENCES `concours` (`id`),
  ADD CONSTRAINT `participant_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
