-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mar. 01 mars 2022 à 19:47
-- Version du serveur : 10.6.5-MariaDB
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test_atelier_bts`
--

-- --------------------------------------------------------

--
-- Structure de la table `commune`
--

DROP TABLE IF EXISTS `commune`;
CREATE TABLE IF NOT EXISTS `commune` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` char(64) NOT NULL,
  `id_departement` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commune_departement_id` (`id_departement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

DROP TABLE IF EXISTS `departement`;
CREATE TABLE IF NOT EXISTS `departement` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `especes`
--

DROP TABLE IF EXISTS `especes`;
CREATE TABLE IF NOT EXISTS `especes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `etudes`
--

DROP TABLE IF EXISTS `etudes`;
CREATE TABLE IF NOT EXISTS `etudes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

DROP TABLE IF EXISTS `groupes`;
CREATE TABLE IF NOT EXISTS `groupes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_etude` int(10) UNSIGNED NOT NULL,
  `id_plage` int(10) UNSIGNED NOT NULL,
  `nbr_personnes` tinyint(3) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `coordonnee_depart_1` char(32) NOT NULL,
  `coordonnee_depart_2` char(32) NOT NULL,
  `coordonnee_arrivee_1` char(32) NOT NULL,
  `coordonnee_arrivee_2` char(32) NOT NULL,
  `nom` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_groupes_etude_id` (`id_etude`),
  KEY `fk_groupes_plage_id` (`id_plage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `groupe_utilisateur`
--

DROP TABLE IF EXISTS `groupe_utilisateur`;
CREATE TABLE IF NOT EXISTS `groupe_utilisateur` (
  `groupe_id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  KEY `fk_groupe-utilisateur_utilisateur_id` (`utilisateur_id`),
  KEY `fk_groupe-utilisateur_groupe_id` (`groupe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `plages`
--

DROP TABLE IF EXISTS `plages`;
CREATE TABLE IF NOT EXISTS `plages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` char(32) NOT NULL,
  `id_commune` int(10) UNSIGNED NOT NULL,
  `superficie_etude` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_plages_id_commune` (`id_commune`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `plages_etude`
--

DROP TABLE IF EXISTS `plages_etude`;
CREATE TABLE IF NOT EXISTS `plages_etude` (
  `id_plage` int(10) UNSIGNED NOT NULL,
  `id_etude` int(10) UNSIGNED NOT NULL,
  KEY `fk_plage-etude_plage_id` (`id_plage`),
  KEY `fk_plage-etude_etude_id` (`id_etude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `prelevements`
--

DROP TABLE IF EXISTS `prelevements`;
CREATE TABLE IF NOT EXISTS `prelevements` (
  `groupe_id` int(10) UNSIGNED NOT NULL,
  `espece_id` int(10) UNSIGNED NOT NULL,
  `nbr_specimenes` int(10) UNSIGNED NOT NULL,
  KEY `fk_prelevements_especes_id` (`espece_id`),
  KEY `fk_prelevements_groupe_id` (`groupe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` char(64) NOT NULL,
  `mdp` char(128) NOT NULL,
  `type_utilisateur` char(16) NOT NULL,
  `nom` char(32) NOT NULL,
  `prenom` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commune`
--
ALTER TABLE `commune`
  ADD CONSTRAINT `fk_commune_departement_id` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id`);

--
-- Contraintes pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD CONSTRAINT `fk_groupes_prelevement_etude_id` FOREIGN KEY (`id_etude`) REFERENCES `etudes` (`id`),
  ADD CONSTRAINT `fk_groupes_prelevement_plage_id` FOREIGN KEY (`id_plage`) REFERENCES `plages` (`id`);

--
-- Contraintes pour la table `groupe_utilisateur`
--
ALTER TABLE `groupe_utilisateur`
  ADD CONSTRAINT `fk_groupe-utilisateur_groupe_id` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`),
  ADD CONSTRAINT `fk_groupe-utilisateur_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `plages`
--
ALTER TABLE `plages`
  ADD CONSTRAINT `fk_plages_id_commune` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id`);

--
-- Contraintes pour la table `plages_etude`
--
ALTER TABLE `plages_etude`
  ADD CONSTRAINT `fk_plage-etude_etude_id` FOREIGN KEY (`id_etude`) REFERENCES `etudes` (`id`),
  ADD CONSTRAINT `fk_plage-etude_plage_id` FOREIGN KEY (`id_plage`) REFERENCES `plages` (`id`);

--
-- Contraintes pour la table `prelevements`
--
ALTER TABLE `prelevements`
  ADD CONSTRAINT `fk_prelevements_especes_id` FOREIGN KEY (`espece_id`) REFERENCES `especes` (`id`),
  ADD CONSTRAINT `fk_prelevements_groupe_id` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
