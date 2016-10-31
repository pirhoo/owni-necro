-- phpMyAdmin SQL Dump
-- version 3.3.7deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 06 Octobre 2010 à 18:45
-- Version du serveur: 5.1.49
-- Version de PHP: 5.3.2-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `NECRAPP`
--

-- --------------------------------------------------------

--
-- Structure de la table `mand_article`
--

CREATE TABLE IF NOT EXISTS `mand_article` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `article_name` varchar(255) NOT NULL,
  `article_langue` varchar(10) NOT NULL COMMENT ':= FR_fr | EN_uk',
  `article_category` bigint(20) NOT NULL,
  `article_source` varchar(512) NOT NULL COMMENT ':= URL',
  `article_content` text NOT NULL,
  `article_mediatype` varchar(10) NOT NULL COMMENT ':= video | image | vf24',
  `article_media` varchar(1024) NOT NULL COMMENT ':= URL | YoutubeID | <param>',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Structure de la table `mand_option`
--

CREATE TABLE IF NOT EXISTS `mand_option` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Structure de la table `mand_testify`
--

CREATE TABLE IF NOT EXISTS `mand_testify` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `author` bigint(20) NOT NULL,
  `time` bigint(20) NOT NULL,
  `content` text NOT NULL,
  `lang` varchar(10) NOT NULL COMMENT ':= FR_fr | EN_uk',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Structure de la table `mand_testify_author`
--

CREATE TABLE IF NOT EXISTS `mand_testify_author` (
  `ID` mediumint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT ':= FACEBOOK | TWITTER',
  `network_ID` bigint(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `mand_user`
--

CREATE TABLE IF NOT EXISTS `mand_user` (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_pseudo` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
