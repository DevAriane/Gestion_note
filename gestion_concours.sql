-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 14 oct. 2024 à 09:09
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_concours`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidat`
--

DROP TABLE IF EXISTS `candidat`;
CREATE TABLE IF NOT EXISTS `candidat` (
  `MATRICULE` varchar(20) NOT NULL,
  `NOM_CANDIDAT` varchar(50) NOT NULL,
  `DATE_NAISSANCE` date NOT NULL,
  `LIEU_NAISSANCE` varchar(50) NOT NULL DEFAULT '-',
  `FILIERE` varchar(20) NOT NULL,
  `ANNEE_ACADEMIQUE` varchar(20) NOT NULL,
  PRIMARY KEY (`MATRICULE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `candidat`
--

INSERT INTO `candidat` (`MATRICULE`, `NOM_CANDIDAT`, `DATE_NAISSANCE`, `LIEU_NAISSANCE`, `FILIERE`, `ANNEE_ACADEMIQUE`) VALUES
('M01', 'NDONGO charles', '2000-10-30', 'YAOUNDE', 'MSI', '2023-2024'),
('M02', 'NDOUMBE francois', '2001-10-20', 'DOUALA', 'GSI', '2023-2024'),
('M03', 'TALLA joseph', '2005-08-30', 'BAFOUSSAM', 'GCE', '2023-2024'),
('M04', 'NGOBO valery', '2004-07-10', 'DOUALA', 'GL', '2023-2024'),
('M05', 'NDOGMO nadine', '2003-03-20', 'BAFOUSSAM', 'GL', '2023-2024');

-- --------------------------------------------------------

--
-- Structure de la table `composer`
--

DROP TABLE IF EXISTS `composer`;
CREATE TABLE IF NOT EXISTS `composer` (
  `MATRICULE` varchar(20) NOT NULL,
  `CODE_MATIERE` varchar(20) NOT NULL,
  `NOTE_OBTENUE` float DEFAULT NULL,
  KEY `MATRICULE` (`MATRICULE`),
  KEY `CODE_MATIERE` (`CODE_MATIERE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `composer`
--

INSERT INTO `composer` (`MATRICULE`, `CODE_MATIERE`, `NOTE_OBTENUE`) VALUES
('M01', 'CULTURE', 10),
('M01', 'ANGLAIS', 8),
('M01', 'Francais', 11),
('M02', 'culture', 15),
('M02', 'ANGLAIS', 12),
('M02', 'Francais', 14),
('M03', 'CULTURE', 13),
('M03', 'ANGLAIS', 9),
('M03', 'Francais', 7),
('M04', 'CULTURE', 10),
('M04', 'ANGLAIS', 8),
('M04', 'Francais', 10);

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

DROP TABLE IF EXISTS `matiere`;
CREATE TABLE IF NOT EXISTS `matiere` (
  `CODE_MATIERE` varchar(20) NOT NULL,
  `libelle_matiere` varchar(50) DEFAULT NULL,
  `COEFFICIENT` float DEFAULT NULL,
  PRIMARY KEY (`CODE_MATIERE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`CODE_MATIERE`, `libelle_matiere`, `COEFFICIENT`) VALUES
('ANGLAIS', 'Anglais', 3),
('CULTURE', 'CULTURE GENERALE', 2),
('francais', 'français', 3),
('specialite', 'matiere de specialite', 5);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `composer`
--
ALTER TABLE `composer`
  ADD CONSTRAINT `composer_ibfk_1` FOREIGN KEY (`MATRICULE`) REFERENCES `candidat` (`MATRICULE`),
  ADD CONSTRAINT `composer_ibfk_2` FOREIGN KEY (`CODE_MATIERE`) REFERENCES `matiere` (`CODE_MATIERE`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
