-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 07 Août 2013 à 16:23
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `base68`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idCat` char(3) NOT NULL DEFAULT '',
  `nomCat` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idCat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idCat`, `nomCat`) VALUES
('100', 'Téléviseur'),
('200', 'Camescope'),
('300', 'Ordinateur'),
('400', 'Accessoire');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `idClient` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomClient` varchar(50) DEFAULT NULL,
  `prenomClient` varchar(50) DEFAULT NULL,
  `adresseClient` varchar(50) DEFAULT NULL,
  `postalClient` char(5) DEFAULT NULL,
  `villeClient` varchar(50) DEFAULT NULL,
  `regionClient` varchar(50) DEFAULT NULL,
  `telClient` char(15) DEFAULT NULL,
  `faxClient` char(15) DEFAULT NULL,
  `emailClient` varchar(50) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `skin` int(1) DEFAULT '1',
  `admin` tinyint(1) DEFAULT '0',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `derniereConnexion` date NOT NULL DEFAULT '2012-01-01',
  PRIMARY KEY (`idClient`),
  UNIQUE KEY `unique_client` (`idClient`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `client`
--

INSERT INTO `client` (`idClient`, `nomClient`, `prenomClient`, `adresseClient`, `postalClient`, `villeClient`, `regionClient`, `telClient`, `faxClient`, `emailClient`, `login`, `password`, `skin`, `admin`, `avatar`, `derniereConnexion`) VALUES
(1, 'DEMO', 'Démo', '', '', '', '', '', '', '', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 0, '', '2012-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `idCom` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateCom` date DEFAULT NULL,
  `idClient` int(10) unsigned NOT NULL,
  `idPaiement` char(2) DEFAULT NULL,
  `dateEnvoiCom` date DEFAULT NULL,
  `refPaiement` varchar(13) DEFAULT NULL,
  `totalCom` float(7,2) DEFAULT NULL,
  `fin` char(3) DEFAULT NULL,
  PRIMARY KEY (`idCom`),
  UNIQUE KEY `unique_cmd` (`idCom`),
  KEY `fk_commande_client` (`idClient`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`idCom`, `dateCom`, `idClient`, `idPaiement`, `dateEnvoiCom`, `refPaiement`, `totalCom`, `fin`) VALUES
(5, '2011-10-12', 1, 'ch', '2011-10-20', '1265487', 3588.00, 'non'),
(9, '2011-11-11', 1, 'ch', '2011-11-18', '98547', 21528.00, 'non');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE IF NOT EXISTS `commentaires` (
  `idCom` int(11) NOT NULL AUTO_INCREMENT,
  `idArticle` int(11) NOT NULL DEFAULT '0',
  `idUser` int(10) unsigned NOT NULL,
  `heure` datetime NOT NULL,
  `commentaire` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`idCom`),
  KEY `fk_commentaires_login` (`idUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

--
-- Contenu de la table `commentaires`
--

INSERT INTO `commentaires` (`idCom`, `idArticle`, `idUser`, `heure`, `commentaire`) VALUES
(93, 302, 1, '2011-11-11 11:36:03', 'Très bon PC !\n\nFiable,  rapide , et pas chère ! 1700€... voyons c''est rien');

-- --------------------------------------------------------

--
-- Structure de la table `connections`
--

CREATE TABLE IF NOT EXISTS `connections` (
  `idConnec` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  PRIMARY KEY (`idConnec`),
  UNIQUE KEY `unique_connections` (`idConnec`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=184 ;

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

CREATE TABLE IF NOT EXISTS `detail_commande` (
  `idProd` char(5) NOT NULL DEFAULT '',
  `idCom` int(10) unsigned NOT NULL,
  `qteDc` float DEFAULT NULL,
  `prixDc` float(7,2) DEFAULT NULL,
  PRIMARY KEY (`idProd`,`idCom`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `detail_commande`
--

INSERT INTO `detail_commande` (`idProd`, `idCom`, `qteDc`, `prixDc`) VALUES
('115', 1, 2, 2392.00),
('115', 2, 3, 3588.00),
('115', 4, 7, 8372.00),
('115', 5, 2, 2392.00),
('115', 6, 3, 3588.00),
('115', 8, 7, 8372.00),
('115', 14, 4, 4784.00),
('115', 24, 3, 3588.00),
('115', 28, 2, 2392.00),
('115', 33, 2, 2392.00),
('115', 35, 5, 5980.00),
('198', 1, 1, 1196.00),
('198', 2, 15, 17940.00),
('198', 4, 1, 1196.00),
('198', 5, 1, 1196.00),
('198', 6, 15, 17940.00),
('198', 8, 1, 1196.00),
('198', 14, 5, 5980.00),
('198', 24, 4, 4784.00),
('210', 14, 3, 2152.80),
('210', 24, 2, 1291.68),
('231', 2, 11, 5262.40),
('231', 3, 2, 956.80),
('231', 6, 11, 5262.40),
('231', 7, 2, 956.80),
('231', 14, 2, 956.80),
('231', 15, 1, 478.40),
('231', 16, 1, 478.40),
('231', 17, 1, 478.40),
('231', 18, 1, 478.40),
('231', 24, 1, 478.40),
('231', 28, 1, 478.40),
('231', 34, 100, 47840.00),
('231', 36, 3, 1435.20),
('302', 2, 4, 7176.00),
('302', 6, 4, 7176.00),
('302', 9, 12, 21528.00),
('302', 14, 6, 10764.00),
('302', 27, 1, 1794.00),
('302', 28, 3, 5382.00),
('302', 29, 1, 1794.00),
('357', 2, 5, 14651.00),
('357', 3, 10, 29302.00),
('357', 6, 5, 14651.00),
('357', 7, 10, 29302.00),
('410', 27, 1, 23.92),
('410', 30, 10, 239.20),
('410', 31, 1, 23.92),
('410', 32, 1, 23.92),
('410', 34, 5, 119.60),
('410', 35, 1, 23.92),
('410', 36, 1, 23.92),
('410', 37, 2, 47.84),
('411', 29, 1, 65.78),
('411', 34, 10, 657.80),
('411', 35, 1, 65.78),
('411', 37, 5, 328.90),
('412', 36, 2, 1055.00);

-- --------------------------------------------------------

--
-- Structure de la table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `libelle` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`admin`),
  KEY `fk_permission_client` (`admin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `permission`
--

INSERT INTO `permission` (`admin`, `libelle`) VALUES
(0, 'Utilisateur'),
(1, 'Personne de confiance'),
(2, 'Administrateur limité'),
(3, 'Administrateur'),
(4, 'Super administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `idProd` char(5) NOT NULL DEFAULT '',
  `idCat` char(3) DEFAULT NULL,
  `titreProd` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `detailProd` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `imageProd` varchar(100) NOT NULL DEFAULT '',
  `nouvProd` char(3) DEFAULT NULL,
  `promProd` char(3) DEFAULT NULL,
  `selProd` char(3) DEFAULT NULL,
  `poidsProd` float DEFAULT NULL,
  `dispoProd` char(3) DEFAULT NULL,
  `delaiProd` varchar(20) DEFAULT NULL,
  `prixhtProd` float(7,2) DEFAULT NULL,
  `prixhtPromProd` float(7,2) DEFAULT NULL,
  `tauxtvaProd` float(7,2) DEFAULT NULL,
  PRIMARY KEY (`idProd`),
  KEY `fk_produit_categorie` (`idCat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`idProd`, `idCat`, `titreProd`, `detailProd`, `imageProd`, `nouvProd`, `promProd`, `selProd`, `poidsProd`, `dispoProd`, `delaiProd`, `prixhtProd`, `prixhtPromProd`, `tauxtvaProd`) VALUES
('115', '100', 'TV thomson 214i', 'télévision full hd', 'prod115.jpeg', 'oui', 'non', 'oui', 5, 'oui', '7 jours', 1000.00, 1000.00, 19.60),
('198', '100', 'TV samsung highcolor', 'télévision hdi', 'prod198.jpeg', 'non', 'oui', 'non', 5, 'oui', '9 jours', 1200.00, 1000.00, 19.60),
('210', '200', 'Caméscope HD', 'd''la balle !', 'prod210.jpeg', 'oui', 'oui', 'non', 5, 'oui', '8 jours', 600.00, 540.00, 19.60),
('231', '200', 'Caméscope highTrack', 'camescope léger', 'prod231.jpeg', 'non', 'oui', 'oui', 2, 'oui', '3 jours', 500.00, 400.00, 19.60),
('302', '300', 'PC HP 5980', 'PC multimédia', 'prod302.jpeg', 'non', 'oui', 'oui', 6, 'oui', '15 jours', 1800.00, 1500.00, 19.60),
('357', '300', 'PC Server', 'Serveur Professionnel', 'prod357.jpeg', 'non', 'non', 'non', 8, 'non', '12 jours', 2450.00, 2450.00, 19.60),
('410', '400', 'Clavier mou', 'Nouvelle description', 'prod410.jpeg', 'non', 'oui', 'oui', 0.1, 'oui', '3 jours', 25.00, 20.00, 19.60),
('411', '400', 'Souris NoName abcd', 'Souris Gamer Geek spécial CS', 'prod411.jpg', 'oui', 'non', 'oui', 0.5, 'oui', '4 jours', 55.00, 55.00, 19.60);

-- --------------------------------------------------------

--
-- Structure de la table `proposer`
--

CREATE TABLE IF NOT EXISTS `proposer` (
  `idProd1` char(5) NOT NULL DEFAULT '',
  `idProd2` char(5) NOT NULL DEFAULT '',
  `nbFois` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProd1`,`idProd2`),
  KEY `fk_proposer_produit0` (`idProd2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `proposer`
--

INSERT INTO `proposer` (`idProd1`, `idProd2`, `nbFois`) VALUES
('115', '198', 8),
('115', '210', 2),
('115', '231', 5),
('115', '302', 4),
('115', '357', 2),
('115', '410', 1),
('115', '411', 1),
('198', '115', 8),
('198', '210', 2),
('198', '231', 4),
('198', '302', 3),
('198', '357', 2),
('210', '115', 2),
('210', '198', 2),
('210', '231', 2),
('210', '302', 1),
('231', '115', 5),
('231', '198', 4),
('231', '210', 2),
('231', '302', 4),
('231', '357', 4),
('231', '410', 2),
('231', '411', 1),
('302', '115', 4),
('302', '198', 3),
('302', '210', 1),
('302', '231', 4),
('302', '357', 2),
('302', '410', 1),
('302', '411', 1),
('357', '115', 2),
('357', '198', 2),
('357', '231', 4),
('357', '302', 2),
('410', '115', 1),
('410', '231', 2),
('410', '302', 1),
('410', '411', 3),
('411', '115', 1),
('411', '231', 1),
('411', '302', 1),
('411', '410', 3);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_client` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`);

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `fk_commetaires_client` FOREIGN KEY (`idUser`) REFERENCES `client` (`idClient`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`idCat`) REFERENCES `categorie` (`idCat`);

--
-- Contraintes pour la table `proposer`
--
ALTER TABLE `proposer`
  ADD CONSTRAINT `fk_proposer_produit` FOREIGN KEY (`idProd1`) REFERENCES `produit` (`idProd`),
  ADD CONSTRAINT `fk_proposer_produit0` FOREIGN KEY (`idProd2`) REFERENCES `produit` (`idProd`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
