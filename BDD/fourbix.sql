-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  ven. 09 mars 2018 à 13:46
-- Version du serveur :  10.1.26-MariaDB
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `fourbix`
--

-- --------------------------------------------------------

--
-- Structure de la table `binets`
--

CREATE TABLE `binets` (
  `nom` varchar(64) NOT NULL,
  `image` varchar(64) DEFAULT NULL COMMENT 'Adresse de limage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `bugreports`
--

CREATE TABLE `bugreports` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `utilisateur` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cautions`
--

CREATE TABLE `cautions` (
  `id` int(11) NOT NULL,
  `valeur` decimal(10,0) NOT NULL COMMENT 'valeur de la caution',
  `encaisse` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'true si le cheque a ete encaisse',
  `date_encaissement` date DEFAULT NULL COMMENT 'date de l''encaissement'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

CREATE TABLE `demandes` (
  `id` int(11) NOT NULL,
  `utilisateur` varchar(64) NOT NULL,
  `binet` varchar(64) DEFAULT NULL,
  `description` text,
  `debut` date DEFAULT NULL COMMENT 'debut du besoin',
  `fin` date DEFAULT NULL COMMENT 'fin estimee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `nom` varchar(128) NOT NULL,
  `marque` varchar(128) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `itemlent`
--

CREATE TABLE `itemlent` (
  `id` int(11) NOT NULL,
  `idItem` int(11) NOT NULL,
  `quantite` float NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `id` int(11) NOT NULL,
  `utilisateur` varchar(64) NOT NULL,
  `binet` varchar(64) NOT NULL,
  `role` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table relationnelle entre les utilisateurs et les binets.';

-- --------------------------------------------------------

--
-- Structure de la table `pretoperation`
--

CREATE TABLE `pretoperation` (
  `id` int(11) NOT NULL,
  `utilisateur` varchar(64) NOT NULL,
  `binet_emprunter` varchar(64) DEFAULT NULL,
  `binet_preteur` varchar(64) NOT NULL,
  `debut` date NOT NULL COMMENT 'debut du pret',
  `date_rendu` date DEFAULT NULL COMMENT 'date de rendu',
  `deadline` date NOT NULL COMMENT 'date limite de rendu',
  `caution` int(11) DEFAULT NULL,
  `item_lent` int(11) NOT NULL,
  `demande` int(11) DEFAULT NULL COMMENT 'correspond a une demande de pret'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `nom` varchar(64) NOT NULL COMMENT 'Ensemble des noms des roles disponibles.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `binet` varchar(64) NOT NULL,
  `item` int(11) NOT NULL,
  `quantite` float NOT NULL,
  `description` text NOT NULL,
  `image` varchar(128) DEFAULT NULL COMMENT 'lien vers une image pour un eventuel catalogue',
  `offre` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'pour savoir si on affiche dans le catalogue',
  `isstockpublic` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'le stock est il public ?',
  `caution` float DEFAULT NULL COMMENT 'caution eventuelle'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `suggestions`
--

CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL,
  `binet` varchar(64) DEFAULT NULL,
  `description` text NOT NULL,
  `utilisateur` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `nom` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `prenom` varchar(64) NOT NULL,
  `nom` varchar(64) NOT NULL,
  `formation` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `login` varchar(64) NOT NULL,
  `password` varchar(40) NOT NULL,
  `naissance` date NOT NULL COMMENT 'Pour savoir si la personne est majeure.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `binets`
--
ALTER TABLE `binets`
  ADD PRIMARY KEY (`nom`);

--
-- Index pour la table `bugreports`
--
ALTER TABLE `bugreports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur` (`utilisateur`);

--
-- Index pour la table `cautions`
--
ALTER TABLE `cautions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `binet` (`binet`),
  ADD KEY `utilisateur` (`utilisateur`);

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Index pour la table `itemlent`
--
ALTER TABLE `itemlent`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur` (`utilisateur`),
  ADD KEY `binet` (`binet`),
  ADD KEY `role` (`role`);

--
-- Index pour la table `pretoperation`
--
ALTER TABLE `pretoperation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `binet_emprunter` (`binet_emprunter`),
  ADD KEY `binet_preteur` (`binet_preteur`),
  ADD KEY `caution` (`caution`),
  ADD KEY `item_lent` (`item_lent`),
  ADD KEY `utilisateur` (`utilisateur`),
  ADD KEY `demande` (`demande`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`nom`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `binet` (`binet`),
  ADD KEY `item` (`item`);

--
-- Index pour la table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`nom`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bugreports`
--
ALTER TABLE `bugreports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cautions`
--
ALTER TABLE `cautions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demandes`
--
ALTER TABLE `demandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `itemlent`
--
ALTER TABLE `itemlent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pretoperation`
--
ALTER TABLE `pretoperation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bugreports`
--
ALTER TABLE `bugreports`
  ADD CONSTRAINT `bugreports_ibfk_1` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `demandes_ibfk_1` FOREIGN KEY (`binet`) REFERENCES `binets` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demandes_ibfk_2` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`type`) REFERENCES `types` (`nom`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `membres`
--
ALTER TABLE `membres`
  ADD CONSTRAINT `membres_ibfk_1` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `membres_ibfk_2` FOREIGN KEY (`binet`) REFERENCES `binets` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `membres_ibfk_3` FOREIGN KEY (`role`) REFERENCES `role` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pretoperation`
--
ALTER TABLE `pretoperation`
  ADD CONSTRAINT `pretoperation_ibfk_1` FOREIGN KEY (`binet_emprunter`) REFERENCES `binets` (`nom`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_2` FOREIGN KEY (`binet_preteur`) REFERENCES `binets` (`nom`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_3` FOREIGN KEY (`caution`) REFERENCES `cautions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_4` FOREIGN KEY (`item_lent`) REFERENCES `itemlent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_5` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_6` FOREIGN KEY (`demande`) REFERENCES `demandes` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`binet`) REFERENCES `binets` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`item`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
