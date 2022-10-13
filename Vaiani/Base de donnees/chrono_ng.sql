-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 10 oct. 2022 à 21:56
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `chrono_ng`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `idGORIE` int(11) NOT NULL AUTO_INCREMENT,
  `nomGORIE` text NOT NULL,
  `sexeGORIE` varchar(1) NOT NULL,
  `age_mini` tinyint(4) NOT NULL,
  `age_maxi` tinyint(4) NOT NULL,
  PRIMARY KEY (`idGORIE`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idGORIE`, `nomGORIE`, `sexeGORIE`, `age_mini`, `age_maxi`) VALUES
(1, 'Baby Athlé', 'T', 0, 6),
(2, 'École d\'Athlétisme ', 'T', 7, 9),
(3, 'Poussin ', 'T', 10, 11),
(4, 'Benjamin', 'T', 12, 13),
(5, 'Minime', 'T', 14, 15),
(6, 'Cadet', 'T', 16, 17),
(7, 'Junior', 'M', 18, 19),
(8, 'Junior', 'F', 18, 19),
(9, 'Espoir', 'M', 20, 22),
(10, 'Espoir', 'F', 20, 22),
(11, 'Senior', 'M', 23, 39),
(12, 'Senior', 'F', 23, 39),
(13, 'Master 1 ', 'M', 40, 49),
(14, 'Master 1', 'F', 40, 49),
(15, 'Master 2', 'M', 50, 59),
(16, 'Master 2', 'F', 50, 59),
(17, 'Master 3', 'T', 60, 69),
(18, 'Master 4', 'T', 70, 79),
(19, 'Master 5', 'T', 80, 120);

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `idCHAT` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `heure_sms` time NOT NULL,
  `date_sms` date NOT NULL,
  PRIMARY KEY (`idCHAT`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `chat`
--

INSERT INTO `chat` (`idCHAT`, `pseudo`, `message`, `heure_sms`, `date_sms`) VALUES
(1, 'a', 'a', '16:15:13', '2022-09-16'),
(2, 'a', 'test202', '16:15:16', '2022-09-16'),
(3, 'a', 'a', '18:11:20', '2022-10-03');

-- --------------------------------------------------------

--
-- Structure de la table `chrono`
--

DROP TABLE IF EXISTS `chrono`;
CREATE TABLE IF NOT EXISTS `chrono` (
  `idRONO` int(11) NOT NULL AUTO_INCREMENT,
  `idURSE` int(11) NOT NULL,
  `idREUR` int(11) NOT NULL,
  `t_depart` time NOT NULL,
  `t_inter` time NOT NULL DEFAULT '00:00:00',
  `t_arrivee` time NOT NULL DEFAULT '00:00:00',
  `tempsInter` time NOT NULL DEFAULT '00:00:00',
  `tempsFinal` time NOT NULL DEFAULT '00:00:00',
  `difference` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`idRONO`),
  UNIQUE KEY `idREUR` (`idREUR`),
  KEY `idURSE` (`idURSE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `chrono`
--

INSERT INTO `chrono` (`idRONO`, `idURSE`, `idREUR`, `t_depart`, `t_inter`, `t_arrivee`, `tempsInter`, `tempsFinal`, `difference`) VALUES
(1, 2, 1, '23:46:20', '23:46:47', '23:47:31', '00:00:27', '00:01:11', '00:00:00'),
(2, 2, 2, '23:46:27', '23:46:54', '23:47:57', '00:00:27', '00:01:30', '00:00:19'),
(3, 2, 3, '23:46:41', '23:47:01', '23:48:08', '00:00:20', '00:01:27', '00:00:16');

-- --------------------------------------------------------

--
-- Structure de la table `coureur`
--

DROP TABLE IF EXISTS `coureur`;
CREATE TABLE IF NOT EXISTS `coureur` (
  `idREUR` int(11) NOT NULL AUTO_INCREMENT,
  `nomREUR` text NOT NULL,
  `prenomREUR` text NOT NULL,
  `date_naissance` date NOT NULL,
  `sexeREUR` varchar(1) NOT NULL,
  `idURSE` int(11) NOT NULL,
  `idGORIE` int(11) NOT NULL,
  PRIMARY KEY (`idREUR`),
  KEY `idURSE` (`idURSE`),
  KEY `idGORIE` (`idGORIE`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `coureur`
--

INSERT INTO `coureur` (`idREUR`, `nomREUR`, `prenomREUR`, `date_naissance`, `sexeREUR`, `idURSE`, `idGORIE`) VALUES
(1, 'Nanuee', 'Eia', '1998-02-05', 'M', 2, 9),
(2, 'Totara', 'Totara', '1998-04-25', 'M', 2, 8),
(3, 'Paihere', 'Paihere', '1998-02-15', 'M', 2, 9),
(4, 'Manini', 'Manini', '1998-05-12', 'F', 2, 8),
(5, 'Ume', 'Ume', '1998-01-01', 'M', 2, 9);

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `idURSE` int(11) NOT NULL AUTO_INCREMENT,
  `nomURSE` text NOT NULL,
  `lieuURSE` text NOT NULL,
  `distanceURSE` varchar(5) NOT NULL,
  `dateURSE` date NOT NULL,
  `h_depart` time NOT NULL,
  PRIMARY KEY (`idURSE`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`idURSE`, `nomURSE`, `lieuURSE`, `distanceURSE`, `dateURSE`, `h_depart`) VALUES
(1, 'Course raromatai', 'Raiatea', '1km', '2021-08-11', '03:00:00'),
(2, 'Course Paiheree', 'Rimatara', '12km', '2022-10-10', '22:55:00');

-- --------------------------------------------------------

--
-- Structure de la table `dossard`
--

DROP TABLE IF EXISTS `dossard`;
CREATE TABLE IF NOT EXISTS `dossard` (
  `idSARD` int(11) NOT NULL AUTO_INCREMENT,
  `idURSE` int(11) NOT NULL,
  `idREUR` int(11) NOT NULL,
  `numSARD` int(11) NOT NULL,
  `numRFID` text NOT NULL,
  PRIMARY KEY (`idSARD`),
  UNIQUE KEY `idREUR` (`idREUR`),
  KEY `idURSE` (`idURSE`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dossard`
--

INSERT INTO `dossard` (`idSARD`, `idURSE`, `idREUR`, `numSARD`, `numRFID`) VALUES
(1, 2, 1, 1001, '1'),
(2, 2, 2, 1002, '2'),
(3, 2, 3, 1003, '3'),
(4, 2, 4, 1004, '4'),
(5, 2, 5, 1005, '5');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chrono`
--
ALTER TABLE `chrono`
  ADD CONSTRAINT `chrono_ibfk_1` FOREIGN KEY (`idURSE`) REFERENCES `course` (`idURSE`),
  ADD CONSTRAINT `chrono_ibfk_2` FOREIGN KEY (`idREUR`) REFERENCES `coureur` (`idREUR`);

--
-- Contraintes pour la table `coureur`
--
ALTER TABLE `coureur`
  ADD CONSTRAINT `coureur_ibfk_1` FOREIGN KEY (`idURSE`) REFERENCES `course` (`idURSE`),
  ADD CONSTRAINT `coureur_ibfk_2` FOREIGN KEY (`idGORIE`) REFERENCES `categorie` (`idGORIE`);

--
-- Contraintes pour la table `dossard`
--
ALTER TABLE `dossard`
  ADD CONSTRAINT `dossard_ibfk_1` FOREIGN KEY (`idURSE`) REFERENCES `course` (`idURSE`),
  ADD CONSTRAINT `dossard_ibfk_2` FOREIGN KEY (`idREUR`) REFERENCES `coureur` (`idREUR`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
