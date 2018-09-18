-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  jeu. 05 avr. 2018 à 05:39
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
-- Base de données :  `matos`
--

-- --------------------------------------------------------

--
-- Structure de la table `binets`
--

CREATE TABLE `binets` (
  `nom` varchar(64) NOT NULL,
  `image` varchar(64) NOT NULL DEFAULT 'default-binetlogo.png' COMMENT 'Adresse de limage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `binets`
--

INSERT INTO `binets` (`nom`, `image`) VALUES
('Administrateurs', 'default-binetlogo.png'),
('ADO', 'ADO-logo.png'),
('BDA', 'BDA-logo.png'),
('Binet des profs', 'Binet des profs-logo.png'),
('Binet Philosophie', 'default-binetlogo.png'),
('Binet Photo', 'Binet Photo-logo.png'),
('Binet Pokemon', 'Binet Pokemon-logo.png'),
('Binet Ratatouille', 'Binet Ratatouille-logo.png'),
('Binet Reseau', 'Binet Reseau-logo.png'),
('Binet Œnologie', 'Binet Œnologie-logo.png'),
('BôBar', 'BôBar-logo.png'),
('CCX', 'CCX-logo.png'),
('Coffee X-Shop', 'Coffee X-Shop-logo.png'),
('Faërix', 'Faërix-logo.png'),
('JTX', 'JTX-logo.png'),
('Kès', 'Kès-logo.png'),
('Khômiss', 'Khômiss-logo.png'),
('Styx', 'Styx-logo.png'),
('Vibes', 'Vibes-logo.png'),
('X-Broadway', 'X-Broadway-logo.png'),
('X-Chine', 'X-Chine-logo.png'),
('X-Ride', 'X-Ride-logo.png'),
('X-Robot', 'X-Robot-logo.png');

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

--
-- Déchargement des données de la table `cautions`
--

INSERT INTO `cautions` (`id`, `valeur`, `encaisse`, `date_encaissement`) VALUES
(2, '1', 0, NULL),
(3, '1', 0, NULL),
(4, '1', 0, NULL),
(5, '1', 0, NULL),
(6, '2', 0, NULL),
(7, '2', 0, NULL),
(8, '2', 0, NULL),
(9, '1', 1, '2018-04-04'),
(10, '1', 1, '2018-04-04'),
(11, '0', 0, NULL),
(12, '1', 0, NULL);

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
  `binet_emprunteur` varchar(64) DEFAULT NULL,
  `isAccepted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Vaut true si la demande a ete acceptee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `utilisateur`, `item`, `binet`, `quantite`, `commentaire`, `debut`, `fin`, `binet_emprunteur`, `isAccepted`) VALUES
(4, 'dominique', 28, 'Binet Reseau', 1, 'J\'en ai besoin pour mes élèves !', '2018-04-07', '2018-04-13', 'Binet des profs', 0);

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
  `image` varchar(128) NOT NULL DEFAULT 'default-itemlogo.png',
  `offre` tinyint(1) NOT NULL DEFAULT '1',
  `isstockpublic` tinyint(1) NOT NULL DEFAULT '1',
  `caution` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`id`, `nom`, `marque`, `type`, `binet`, `quantite`, `description`, `image`, `offre`, `isstockpublic`, `caution`) VALUES
(11, 'L\'être et le Néant', 'Gallimard', 'autre', 'Binet Philosophie', 2, 'Un incontournable pour découvrir la pensée de Sartres.', 'image-L\'être et le Néant20180405025412.png', 1, 1, 0.5),
(12, 'L\'étonnement Philosophique', 'Folio Essai', 'Livre', 'Binet Philosophie', 3, 'Une anthologie qui reprend les principaux courants philosophiques. Un must-have.', 'image-L\'étonnement Philosophique20180405025835.png', 1, 1, 1.5),
(13, 'Appareil Photo Nikon D850', 'Nikon', 'Électronique', 'Binet Photo', 1, 'Vous rêvez de pouvoir enfin prendre des photos mémorables ? Vous en avez marre du bas de gamme proposé par les téléphones portables ? N\'hésitez pas à emprunter ce magnifique appareil !', 'image-Appareil Photo Nikon D85020180405030106.png', 1, 1, 1500),
(14, 'Pokeball', 'The Pokemon Company', 'Jeu', 'Binet Pokemon', 151, 'Vous souhaitez devenir maître de la ligue ? N\'attendez-plus et attrapez les tous !', 'image-Pokeball20180405030211.png', 1, 1, 0.1),
(15, 'Poêle', 'Teflon UHD', 'Culinaire', 'Binet Ratatouille', 2, 'Une poêle pour vos steaks, n\'hésitez plus !', 'image-Poêle20180405030328.png', 1, 1, 15),
(16, 'Assiette', '', 'Culinaire', 'Binet Ratatouille', 15, 'Ces assiettes vous permettront de changer de celles bas de gamme en plastique. Passez enfin à la qualité supérieure.', 'image-Assiette20180405030458.png', 1, 1, 2),
(17, 'Cocotte Minute', 'C Discount', 'Culinaire', 'Binet Ratatouille', 2, 'Pour des gros plats, soyez enfin bien équipé ! Cette cocotte minute vous permettra d\'améliorer la qualité de vos plats de manière sensible.', 'image-Cocotte Minute20180405030630.png', 1, 1, 60),
(18, 'Verre à vin', '', 'Culinaire', 'Binet Œnologie', 24, 'On ne saurait se délecter correctement d\'un bon vin dans un gobelet en plastique.', 'image-Verre à vin20180405030808.png', 1, 1, 1.25),
(19, 'Flûte à champagne', '', 'Culinaire', 'Binet Œnologie', 48, 'Pour vos soirées mousseuses, osez la grande classe.', 'image-Flûte à champagne20180405030859.png', 1, 1, 1.75),
(20, 'Tasse de café ', '', 'Culinaire', 'Coffee X-Shop', 6, '', 'image-Tasse de café 20180405031011.png', 1, 1, 0),
(21, 'Salle Jean Girette', '', 'Mobilier', 'CCX', 1, 'La salle Jean Girette vous ouvre ses portes pour faire de la cuisine à grande échelle !', 'default-itemlogo.png', 1, 1, 75),
(22, 'La Bible', 'Dieu', 'Livre', 'CCX', 10, 'Les Saintes Écritures vous guident vers une connaissance plus grande et plus pure de l\'amour de Dieu.', 'image-La Bible20180405031230.png', 1, 1, 0),
(23, 'Jeu Fantasy', 'Fantasy Asmodée', 'Jeu', 'Faërix', 1, 'Un jeu de Fantasy qui vaut le détour. Se joue bien entre amis.', 'image-Jeu Fantasy20180405031421.png', 1, 1, 8),
(24, 'Monopoly', 'Monopoly', 'Jeu', 'Faërix', 1, 'Un grand classique...', 'image-Monopoly20180405031531.png', 1, 1, 8),
(25, 'Gamecube', 'Nintendo', 'autre', 'Faërix', 1, 'Enfin de retour sur Smash Bros Melee !', 'default-itemlogo.png', 1, 1, 70),
(26, 'AU-EVA1', 'Panasonic', 'Cinéma', 'JTX', 1, '/!\\ Extremement précieux. Permet une très bonne qualité d\'image.', 'image-AU-EVA120180405032227.png', 1, 1, 2000),
(27, 'Stabilisateur de caméra', 'Panasonic', 'autre', 'JTX', 2, 'Nécessaire pour un tournage.', 'image-Stabilisateur de caméra20180405032311.png', 1, 1, 100),
(28, 'Serveur', '', 'Électronique', 'Binet Reseau', 8, 'Nous vous offrons des places sur nos serveurs (8 max).', 'image-Serveur20180405032435.png', 1, 1, 0),
(29, 'Boule Disco', '', 'Musique', 'Styx', 1, 'Une boule disco rétro pour les nostalgiques.', 'image-Boule Disco20180405032548.png', 1, 1, 20),
(30, 'Matériel d\'éclairage', '', 'Électronique', 'Styx', 3, 'De quoi mettre une ambiance de folie !', 'image-Matériel d\'éclairage20180405032654.png', 1, 1, 40),
(31, 'Deguisement Sultan Aladin', 'Fait maison', 'Vêtement', 'X-Broadway', 1, 'Une soirée déguisée à l\'approche ? Pas le temps de trouver un déguisement ? N\'attendez plus !', 'image-Deguisement Sultan Aladin20180405032758.png', 1, 1, 3),
(32, 'Baguettes chinoises', 'Table et Prestige', 'Culinaire', 'X-Chine', 5, 'Une paire de baguette chinoise pour une soirée chinois entre amis.', 'image-Baguettes chinoises20180405032919.png', 1, 1, 1),
(33, 'L\'art de la guerre', '', 'Livre', 'X-Chine', 3, 'Découvrez ou redécouvrez ce classique de la culture chinoise !', 'image-L\'art de la guerre20180405033031.png', 1, 1, 0),
(34, 'Paire de skis', 'Rossignol', 'Sport', 'X-Ride', 3, 'Personne ne devrait se retenir d\'aller à la montagne à cause d\'un manque de matériel, c\'est pourquoi X-Ride vous propose ce qu\'il lui reste en stock.', 'image-Paire de skis20180405033205.png', 1, 1, 43),
(35, 'Snowboard', 'SnowDaze', 'Sport', 'X-Ride', 4, 'Envie d\'essayer le snow ? C\'est maintenant !', 'image-Snowboard20180405033258.png', 1, 1, 58),
(36, 'Raspberry PI', '', 'Électronique', 'X-Robot', 5, 'Un indispensable de l\'electronique.', 'image-Raspberry PI20180405033401.png', 1, 1, 2),
(37, 'Arduino', '', 'Électronique', 'X-Robot', 10, 'Un indispensable de l\'electronique.', 'image-Arduino20180405033442.png', 1, 1, 3),
(38, 'Robot Neo', '', 'autre', 'X-Robot', 1, 'Le meilleur compagnon du geek.', 'image-Robot Neo20180405033546.png', 1, 1, 300),
(39, 'Table de Mixage', '', 'Musique', 'Vibes', 0, 'De quoi animer les soirées ennuyeuses.', 'image-Table de Mixage20180405033644.png', 1, 1, 200),
(40, 'Microphone v85', 'Galland', 'Électronique', 'ADO', 2, 'Un micro de qualité pour les plus belles prises de son.', 'image-Microphone v8520180405033821.png', 1, 1, 30),
(41, 'Casque', 'Bose', 'Musique', 'ADO', 2, 'Le meilleur casque du marché en ce moment.', 'image-Casque20180405033919.png', 1, 1, 120),
(42, 'Pinceau', '', 'Outil', 'BDA', 4, 'Du matériel vachement utile pour la peinture !', 'image-Pinceau20180405034033.png', 1, 1, 0),
(43, 'Agrafeuse', 'Gaerstaecker', 'Outil', 'Kès', 2, '', 'image-Agrafeuse20180405034155.png', 1, 1, 0),
(44, 'Chope', 'Chope classique', 'Culinaire', 'BôBar', 20, 'Pour une bonne bouffe entre amis, rien de tel qu\'une bonne chope de bière !', 'image-Chope20180405034258.png', 1, 1, 2),
(45, 'Masque de déchouffe', 'Missaine Factory', 'Vêtement', 'Khômiss', 536, 'La déchouffe...', 'image-Masque de déchouffe20180405034437.png', 1, 1, 0),
(46, 'Routeur Wi-Fi', 'TP-Link', 'Informatique', 'Binet Reseau', 3, 'Pour vous connecter en wi-fi en toute circonstance !', 'image-Routeur Wi-Fi20180405034955.png', 1, 1, 5),
(47, 'Clef USB', 'Origin Info System', 'Informatique', 'Binet Reseau', 50, 'Une clef USB vous permettra de transporter vos données : c\'est l\'objet indispensable de tous les étudiants du platâl !', 'image-Clef USB20180405035037.png', 1, 1, 0.25),
(48, 'Câble Ethernet', 'CCAUTP', 'Informatique', 'Binet Reseau', 15, 'Un super cable ethernet de 10m ! Vous n\'en renviendrez pas ! Il n\'y en aura pas pour tout le monde.', 'image-Câble Ethernet20180405035124.png', 1, 1, 1.5);

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
(13, 'Burrakauchy', 'Administrateurs', 'admin'),
(14, 'olivier', 'Administrateurs', 'admin'),
(16, 'Burrakauchy', 'Binet Reseau', 'matosManager'),
(17, 'Burrakauchy', 'Binet Reseau', 'membre'),
(20, 'Burrakauchy', 'Binet Reseau', 'admin'),
(25, 'dominique', 'Binet Reseau', 'matosManager'),
(29, 'Burrakauchy', 'Binet Philosophie', 'admin'),
(30, 'Burrakauchy', 'Binet Philosophie', 'matosManager'),
(31, 'Burrakauchy', 'Binet Philosophie', 'membre'),
(32, 'zom', 'BDA', 'admin'),
(33, 'zom', 'Binet Philosophie', 'membre'),
(34, 'zom', 'Binet Photo', 'membre'),
(35, 'zom', 'Binet Ratatouille', 'matosManager'),
(36, 'zom', 'Coffee X-Shop', 'membre'),
(37, 'zom', 'Vibes', 'membre'),
(38, 'cheikdav', 'ADO', 'matosManager'),
(39, 'cheikdav', 'Binet Pokemon', 'admin'),
(40, 'cheikdav', 'Binet Reseau', 'membre'),
(41, 'cheikdav', 'JTX', 'membre'),
(42, 'cheikdav', 'Kès', 'membre'),
(43, 'cheikdav', 'Khômiss', 'matosManager'),
(44, 'lolo', 'Faërix', 'matosManager'),
(45, 'lolo', 'ADO', 'admin'),
(46, 'lolo', 'BDA', 'matosManager'),
(47, 'lolo', 'BôBar', 'membre'),
(48, 'lolo', 'Coffee X-Shop', 'matosManager'),
(49, 'lolo', 'JTX', 'membre'),
(50, 'lolo', 'Kès', 'membre'),
(51, 'lolo', 'Vibes', 'admin'),
(52, 'lolo', 'X-Broadway', 'matosManager'),
(53, 'lolo', 'Faërix', 'membre'),
(54, 'Sacha', 'BDA', 'matosManager'),
(55, 'Sacha', 'Binet Pokemon', 'admin'),
(56, 'Sacha', 'Binet Ratatouille', 'membre'),
(57, 'Sacha', 'Binet Reseau', 'membre'),
(58, 'Sacha', 'CCX', 'membre'),
(59, 'Sacha', 'Khômiss', 'membre'),
(60, 'Sacha', 'Styx', 'matosManager'),
(61, 'Maxou', 'ADO', 'matosManager'),
(62, 'Maxou', 'JTX', 'admin'),
(63, 'Maxou', 'X-Broadway', 'membre'),
(64, 'gabriel', 'Administrateurs', 'admin'),
(65, 'dominique', 'Binet des profs', 'admin'),
(67, 'olivier', 'Binet des profs', 'matosManager');

-- --------------------------------------------------------

--
-- Structure de la table `pretoperation`
--

CREATE TABLE `pretoperation` (
  `id` int(11) NOT NULL,
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
('Cinéma'),
('Culinaire'),
('Électronique'),
('Informatique'),
('Jeu'),
('Livre'),
('Mobilier'),
('Musique'),
('Outil'),
('Sport'),
('Vêtement');

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
('Alexandre', 'Binninger', 'X2016', 'alexandre.binninger@polytechnique.edu', 'Burrakauchy', '2bac2aaed823620d75679dd4ff939e475979c511', '1996-06-15'),
('David', 'Cheikhi', 'X2016', 'cheikdav@gmail.com', 'cheikdav', 'ce0274f210660d05ac21a64855c19ec8a6f9a7ce', '1996-09-04'),
('Dominique', 'Rossin', 'Adjoint DER', 'dominique.rossin@polytechnique.edu', 'dominique', 'acb8637c4e1b6ee40817974f2a47bcafc14ca9d3', '1975-12-25'),
('Gabriel', 'Oliveira Martins', 'X2016', 'gabriel.oliveiragom@gmail.com', 'gabriel', '0486ee776c027c980709e94906390a4fb7728b82', '1996-10-06'),
('Faisant', 'Lois', 'X2016', 'lolo@gmail.com', 'Lolo', '14c9de094142ca0cab8829387a61c288485651b5', '1995-05-08'),
('Fabre', 'Maxime', 'X2016', 'max@gmail.com', 'Maxou', '977acf9ffc1ee60efd6cf2174169b23901445b0c', '1996-12-28'),
('Olivier', 'Serre', 'Professeur', 'olivier.serre@polytechnique.edu', 'olivier', 'd428549ca0899ee3edc266c44eca8db740a2e602', '1988-04-07'),
('Ketchum', 'Ash', 'Maître Ligue', 'sacha@gmail.com', 'Sacha', 'c3fc1897dc84eb3178c860cfd3ce4f14653e8a3f', '2000-05-07'),
('Omar', 'Mouchtaki', 'X2016', 'zom@gmail.com', 'zom', 'c94c7bd6997b5efccf2a9b04c35dd3bee981904a', '1996-02-08');

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
  ADD KEY `caution` (`caution`),
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `binet` (`binet`),
  ADD KEY `utilisateur` (`utilisateur`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `demandes`
--
ALTER TABLE `demandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT pour la table `pretoperation`
--
ALTER TABLE `pretoperation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `pretoperation_ibfk_3` FOREIGN KEY (`caution`) REFERENCES `cautions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pretoperation_ibfk_6` FOREIGN KEY (`demande`) REFERENCES `demandes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`binet`) REFERENCES `binets` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `suggestions_ibfk_2` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`login`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
