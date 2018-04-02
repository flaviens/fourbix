-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mar. 03 avr. 2018 à 00:50
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

--
-- Déchargement des données de la table `binets`
--

INSERT INTO `binets` (`nom`, `image`) VALUES
('Administrateurs', NULL),
('Binet des profs', 'Binet des profs-logo.png'),
('Binet Pokemon', 'Binet Pokemon-logo.png'),
('Binet Reseau', 'Binet Reseau-logo.png');

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
  `item` int(11) NOT NULL,
  `binet` varchar(64) NOT NULL,
  `quantite` int(11) NOT NULL,
  `commentaire` text,
  `debut` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `binet_emprunteur` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `utilisateur`, `item`, `binet`, `quantite`, `commentaire`, `debut`, `fin`, `binet_emprunteur`) VALUES
(8, 'olivier', 2, 'Binet Reseau', 3, NULL, '2018-04-10', '2018-04-01', NULL),
(9, 'olivier', 2, 'Binet Reseau', 3, NULL, '2018-04-17', '2018-04-01', NULL),
(10, 'olivier', 2, 'Binet Reseau', 3, NULL, '2018-04-09', '2018-04-01', NULL),
(11, 'olivier', 2, 'Binet Reseau', 3, NULL, '2018-04-09', '2018-04-01', NULL),
(12, 'olivier', 2, 'Binet Reseau', 2, NULL, '2018-04-03', '2018-04-01', NULL),
(13, 'olivier', 2, 'Binet Reseau', 2, NULL, '2018-04-03', '2018-04-01', NULL),
(14, 'olivier', 2, 'Binet Reseau', 2, NULL, '2018-04-17', '2018-04-01', NULL),
(15, 'olivier', 2, 'Binet Reseau', 2, NULL, '2018-04-10', '2018-04-01', NULL),
(16, 'olivier', 2, 'Binet Reseau', 2, NULL, '2018-04-03', '2018-04-01', NULL),
(17, 'olivier', 2, 'Binet Reseau', 2, NULL, '2018-04-03', '2018-04-01', NULL),
(19, 'olivier', 2, 'Binet Reseau', 2, 'coucou', '2018-04-01', '2018-04-02', NULL),
(20, 'olivier', 2, 'Binet Reseau', 3, 'yguygu', '2018-04-01', '2018-04-03', 'Administrateurs'),
(21, 'olivier', 2, 'Binet Reseau', 2, NULL, NULL, NULL, NULL),
(22, 'olivier', 2, 'Binet Reseau', 3, NULL, NULL, NULL, 'Administrateurs'),
(23, 'gabriel', 1, 'Binet Reseau', 4, NULL, NULL, NULL, NULL),
(24, 'gabriel', 1, 'Binet Reseau', 4, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `nom` varchar(128) NOT NULL,
  `marque` varchar(128) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `binet` varchar(64) NOT NULL,
  `quantite` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `offre` tinyint(1) NOT NULL DEFAULT '1',
  `isstockpublic` tinyint(1) NOT NULL DEFAULT '1',
  `caution` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`id`, `nom`, `marque`, `type`, `binet`, `quantite`, `description`, `image`, `offre`, `isstockpublic`, `caution`) VALUES
(1, 'Câble Ethernet', 'CCAUTP', 'Informatique', 'Binet Reseau', 15, 'Un super cable ethernet de 10m ! Vous n\'en renviendrez pas ! Il n\'y en aura pas pour tout le monde.', 'ethernet.jpeg', 1, 1, 1.5),
(2, 'Câble Ethernet', 'CCAUTP', 'Informatique', 'Binet Pokemon', 50, 'Un cable ethernet pour tous les attraper !', 'ethernet.jpeg', 1, 1, 0.5),
(3, 'Clef USB', 'Origin Info System', 'Informatique', 'Binet Reseau', 50, 'Une clef USB vous permettra de transporter vos données : c\'est l\'objet indispensable de tous les étudiants du platâl !', 'usbKey.jpeg', 1, 1, 0.25),
(7, 'Routeur WiFi', 'TP-Link', 'Informatique', 'Binet Reseau', 1, '515', 'image-item20180401223856.png', 1, 1, 1);

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

--
-- Déchargement des données de la table `membres`
--

INSERT INTO `membres` (`id`, `utilisateur`, `binet`, `role`) VALUES
(1, 'Burrakauchy', 'Administrateurs', 'admin'),
(5, 'Burrakauchy', 'Binet Reseau', 'admin'),
(8, 'Burrakauchy', 'Binet Reseau', 'matosManager'),
(9, 'olivier', 'Binet des profs', 'admin'),
(10, 'olivier', 'Administrateurs', 'admin'),
(11, 'dominique', 'Binet des profs', 'matosManager');

-- --------------------------------------------------------

--
-- Structure de la table `pretoperation`
--

CREATE TABLE `pretoperation` (
  `id` int(11) NOT NULL,
  `utilisateur` varchar(64) NOT NULL,
  `binet_emprunteur` varchar(64) DEFAULT NULL,
  `binet_preteur` varchar(64) NOT NULL,
  `debut` date NOT NULL COMMENT 'debut du pret',
  `date_rendu` date DEFAULT NULL COMMENT 'date de rendu',
  `deadline` date DEFAULT NULL COMMENT 'date limite de rendu',
  `quantite_pret` int(11) NOT NULL DEFAULT '0',
  `caution` int(11) DEFAULT NULL,
  `demande` int(11) DEFAULT NULL COMMENT 'correspond a une demande de pret'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `nom` varchar(64) NOT NULL COMMENT 'Ensemble des noms des roles disponibles.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`nom`) VALUES
('admin'),
('matosManager'),
('membre');

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

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`nom`) VALUES
('autre'),
('Informatique');

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
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`prenom`, `nom`, `formation`, `email`, `login`, `password`, `naissance`) VALUES
('Alexandre', 'Binninger', 'X2016', 'alexandre.binninger@polytechnique.edu', 'Burrakauchy', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '1996-06-15'),
('Dominique', 'Rossin', 'Adjoint DER', 'dominique.rossin@polytechnique.edu', 'dominique', '9cc140dd813383e134e7e365b203780da9376438', '1970-02-11'),
('Martins', 'Oliveira', 'X2016', 'gabriel@mail.com', 'gabriel', '7c222fb2927d828af22f592134e8932480637c0d', '1996-06-10'),
('Joao', 'Silva', 'X2016', 'joao@gmail.com', 'joao', '356a192b7913b04c54574d18c28d46e6395428ab', '2018-04-01'),
('Olivier', 'Serre', 'professeur', 'olivier.serre@polytechnique.edu', 'olivier', '663194f2b9123a38cd9e2e2811f8d2fd387b765e', '1980-03-14');

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
  ADD KEY `utilisateur` (`utilisateur`),
  ADD KEY `stock_id` (`item`),
  ADD KEY `binet` (`binet`),
  ADD KEY `binet_emprunteur` (`binet_emprunteur`);

--
-- Index pour la table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `binet` (`binet`);

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
  ADD KEY `binet_emprunter` (`binet_emprunteur`),
  ADD KEY `binet_preteur` (`binet_preteur`),
  ADD KEY `caution` (`caution`),
  ADD KEY `utilisateur` (`utilisateur`),
  ADD KEY `demande` (`demande`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`nom`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `pretoperation`
--
ALTER TABLE `pretoperation`
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
  ADD CONSTRAINT `demandes_ibfk_2` FOREIGN KEY (`binet_emprunteur`) REFERENCES `binets` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demandes_ibfk_4` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `demandes_ibfk_5` FOREIGN KEY (`item`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`binet`) REFERENCES `binets` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`type`) REFERENCES `types` (`nom`);

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
  ADD CONSTRAINT `pretoperation_ibfk_1` FOREIGN KEY (`binet_emprunteur`) REFERENCES `binets` (`nom`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_2` FOREIGN KEY (`binet_preteur`) REFERENCES `binets` (`nom`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_3` FOREIGN KEY (`caution`) REFERENCES `cautions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_5` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_6` FOREIGN KEY (`demande`) REFERENCES `demandes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
