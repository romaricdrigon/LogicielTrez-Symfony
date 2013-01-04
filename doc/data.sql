-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Mar 09 Octobre 2012 à 09:16
-- Version du serveur: 5.5.24
-- Version de PHP: 5.4.6-2~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `logiciel_trez_symfony`
--

-- --------------------------------------------------------

--
-- Contenu de la table `Exercice`
--

INSERT INTO `Exercice` (`id`, `edition`, `annee_1`, `annee_2`) VALUES
(1, '38e', '2011-10-01', '2012-09-30'),
(2, '37', '2010-10-01', '2011-09-30');

-- --------------------------------------------------------

--
-- Contenu de la table `Budget`
--

INSERT INTO `Budget` (`id`, `exercice_id`, `nom`) VALUES
(1, 1, 'Mon exercice à moi'),
(2, 2, 'Exercice pourri');

-- --------------------------------------------------------

--
-- Contenu de la table `Categorie`
--

INSERT INTO `Categorie` (`id`, `budget_id`, `nom`, `commentaire`, `cle`) VALUES
(1, 1, 'Animations', NULL, 1),
(2, 1, 'Courses', NULL, 3);

-- --------------------------------------------------------

--
-- Contenu de la table `SousCategorie`
--

INSERT INTO `SousCategorie` (`id`, `categorie_id`, `nom`, `commentaire`, `cle`) VALUES
(1, 1, 'Sport', NULL, 1),
(2, 1, 'Culture', NULL, 2);

-- --------------------------------------------------------

--
-- Contenu de la table `Ligne`
--

INSERT INTO `Ligne` (`id`, `nom`, `commentaire`, `cle`, `debit`, `credit`, `sousCategorie_id`) VALUES
(1, 'Saut', NULL, 1, 9000, 0, 1),
(2, 'Apéro', NULL, 2, 100, 0, 1),
(3, 'Inscriptions', NULL, 3, 0, 500, 1);

-- --------------------------------------------------------

--
-- Contenu de la table `ClasseTva`
--

INSERT INTO `ClasseTva` (`id`, `nom`, `taux`, `actif`) VALUES
(1, 'Taux normal', 19.60, 1),
(2, 'Taux réduit ftw', 7.00, 1);

-- --------------------------------------------------------

--
-- Contenu de la table `Config`
--

INSERT INTO `Config` (`id`, `cle`, `valeur`) VALUES
(1, 'currency', '€');


-- --------------------------------------------------------

--
-- Contenu de la table `MethodePaiement`
--

INSERT INTO `MethodePaiement` (`id`, `nom`) VALUES
(1, 'chèque'),
(2, 'foutre'),
(3, 'espèce');


-- --------------------------------------------------------

--
-- Contenu de la table `Tiers`
--

INSERT INTO `Tiers` (`id`, `nom`, `telephone`, `mail`, `fax`, `adresse`, `responsable`, `rib`, `ordre_cheque`, `commentaire`) VALUES
(1, 'INSA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


-- --------------------------------------------------------

--
-- Contenu de la table `TypeFacture`
--

INSERT INTO `TypeFacture` (`id`, `abr`, `nom`, `sens`) VALUES
(1, 'FR', 'Facture reçue', 0),
(2, 'OD', 'Opération Diverse', 0),
(3, 'FE', 'Facture émise', 1),
(4, 'NF', 'Note de frais', 0);

-- --------------------------------------------------------

--
-- Contenu de la table `Facture`
--

INSERT INTO `Facture` (`id`, `ligne_id`, `tiers_id`, `numero`, `objet`, `montant`, `date`, `date_paiement`, `commentaire`, `ref_paiement`, `methodePaiement_id`, `typeFacture_id`) VALUES
(1, 1, NULL, 1, '', 9000.00, '2012-10-03', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Contenu de la table `Tva`
--

INSERT INTO `Tva` (`id`, `facture_id`, `montant_ht`, `montant_tva`, `classeTva_id`) VALUES
(1, 1, 9000.00, 1000.00, 1);

-- --------------------------------------------------------


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
