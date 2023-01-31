-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 13 nov. 2022 à 23:19
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
(2, 'Test', '2022-10-30', '2022-10-13', 7);

-- --------------------------------------------------------

--
-- Structure de la table `don`
--

CREATE TABLE `don` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `donataire` varchar(50) NOT NULL,
  `organisme` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `don`
--

INSERT INTO `don` (`id`, `type`, `date`, `donataire`, `organisme`) VALUES
(27, 'envoi', '2022-11-11', '', 'Testos bastos'),
(28, 'envoi', '2022-11-12', '', 'Testos bastos');

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
(30, 27, 'Testos ult', 1),
(31, 28, 'Testos spé', 25);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `concours_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `page` text NOT NULL,
  `date_debut` date NOT NULL DEFAULT current_timestamp(),
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id`, `nom`, `page`, `date_debut`, `date_fin`) VALUES
(7, 'projet test 25', '<ul>\n    <li>test test</li>\n    <li>test test</li>\n    <li>test test</li>\n    <li>test test</li>\n    <li>test test</li>\n</ul>', '2022-10-13', '2022-10-30');

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
  `telephone` varchar(20) DEFAULT NULL,
  `region` varchar(70) NOT NULL,
  `departement` int(5) NOT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `email`, `mdp`, `telephone`, `region`, `departement`, `adresse`, `date_inscription`, `role`) VALUES
(11, 'Sorenn', 'Cavalin', 'sosolekilr@gmail.com', '$2y$10$lYpd810lgdwy4JCvTUBJAuaHqZRItJelF1E2xtB63mNHJPZJv0Prq', NULL, 'Ile de France', 94200, '8 Avenue Spinoza', '2022-10-27 08:09:15', 'a:2:{i:0;s:13:\"ROLE_BENEVOLE\";i:1;s:10:\"ROLE_ADMIN\";}');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `nom` varchar(80) NOT NULL,
  `lien` text NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `nom`, `lien`, `type`) VALUES
(2, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(4, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(5, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube'),
(6, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(7, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube'),
(8, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(9, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube'),
(10, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(12, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(13, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube'),
(14, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook'),
(15, 'nom', 'https://www.facebook.com/plugins/video.php?height=288&href=https%3A%2F%2Fwww.facebook.com%2Focrochetdamitie%2Fvideos%2F287152576688505%2F&show_text=false&width=560&t=0', 'youtube'),
(16, 'video test', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2FUn.soleil.pour.chacun%2Fvideos%2F595524425690340%2F&show_text=false&width=267&t=0', 'facebook');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `don`
--
ALTER TABLE `don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `don_details`
--
ALTER TABLE `don_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
