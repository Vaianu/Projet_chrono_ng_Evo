-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 26 Mars 2019 à 17:12
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `chrono_ng`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idGORIE` int(11) NOT NULL AUTO_INCREMENT,
  `nomGORIE` text NOT NULL,
  `sexeGORIE` varchar(1) NOT NULL,
  `age_mini` tinyint(4) NOT NULL,
  `age_maxi` tinyint(4) NOT NULL,
  PRIMARY KEY (`idGORIE`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idGORIE`, `nomGORIE`, `sexeGORIE`, `age_mini`, `age_maxi`) VALUES
(1, 'Baby Athlé', 'T', 0, 6),
(2, 'École d''Athlétisme ', 'T', 7, 9),
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
