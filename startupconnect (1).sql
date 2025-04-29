-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 17 avr. 2025 à 17:58
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `startupconnect`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `name`) VALUES
(1, 'Technologie'),
(2, 'Santé'),
(3, 'Éducation'),
(4, 'Finance'),
(5, 'E-commerce');

-- --------------------------------------------------------

--
-- Structure de la table `startup`
--

CREATE TABLE `startup` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `startup`
--

INSERT INTO `startup` (`id`, `name`, `description`, `category_id`, `image_path`) VALUES
(26, 'hygiene', 'Startup spécialisée dans le suivi des données de santé en temps réel via des capteurs portables.', 2, '/startupConnect-website/uploads/68010a7e2c80a.jpg'),
(27, 'takiacademy', 'TakiAcademy est une plateforme éducative innovante qui propose des cours en ligne interactifs dans divers domaines, allant de la programmation au développement personnel. Grâce à une approche axée sur la pratique, elle permet aux apprenants de tous niveaux de développer des compétences concrètes, à leur rythme, avec l’accompagnement de formateurs qualifiés.', 3, '/startupConnect-website/uploads/68010bca21415.jpg'),
(28, 'E-comerce', 'Solution de paiement sécurisée pour les petites entreprises avec intégration facile aux e-commerces.', 5, '/startupConnect-website/uploads/68010c2b34c4a.png'),
(29, 'figma', 'Plateforme de design collaboratif en ligne utilisée pour la création d\'interfaces et de prototypes.', 1, '/startupConnect-website/uploads/68010d2cb43d8.jpg'),
(30, 'Qonto', 'Qonto esst une néobanque française dédiée aux professionnels, freelances, et PME. Elle propose un compte bancaire 100% en ligne, avec des outils de gestion de dépenses, de facturation, et de comptabilité intégrés. L’objectif est de simplifier la gestion financière des entreprises au quotidien.', 4, '/startupConnect-website/uploads/68010e40199e2.png');

-- --------------------------------------------------------

--
-- Structure de la table `startup_ratings`
--

CREATE TABLE `startup_ratings` (
  `id` int(11) NOT NULL,
  `startup_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `startup_ratings`
--

INSERT INTO `startup_ratings` (`id`, `startup_id`, `rating`, `comment`, `created_at`) VALUES
(1, 29, 2, 'Cool startup', '2025-04-17 14:36:59'),
(2, 30, 5, 'Beau startup', '2025-04-17 14:46:20'),
(3, 27, 1, '', '2025-04-17 15:23:42'),
(4, 26, 4, 'good but expencive', '2025-04-17 15:27:49'),
(5, 27, 2, '', '2025-04-17 15:52:22');

-- --------------------------------------------------------

--
-- Structure de la table `startup_views`
--

CREATE TABLE `startup_views` (
  `id` int(11) NOT NULL,
  `startup_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `view_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `startup_views`
--

INSERT INTO `startup_views` (`id`, `startup_id`, `user_id`, `view_date`) VALUES
(2, 30, NULL, '2025-04-17 16:31:20'),
(3, 30, NULL, '2025-04-17 16:31:20'),
(4, 30, NULL, '2025-04-17 16:31:20'),
(5, 29, NULL, '2025-04-17 16:31:20'),
(6, 29, NULL, '2025-04-17 16:31:20'),
(7, 28, NULL, '2025-04-17 16:31:20');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `startup`
--
ALTER TABLE `startup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `startup_ratings`
--
ALTER TABLE `startup_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `startup_id` (`startup_id`);

--
-- Index pour la table `startup_views`
--
ALTER TABLE `startup_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `startup_id` (`startup_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `startup`
--
ALTER TABLE `startup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `startup_ratings`
--
ALTER TABLE `startup_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `startup_views`
--
ALTER TABLE `startup_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `startup`
--
ALTER TABLE `startup`
  ADD CONSTRAINT `startup_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categorie` (`id`);

--
-- Contraintes pour la table `startup_ratings`
--
ALTER TABLE `startup_ratings`
  ADD CONSTRAINT `startup_ratings_ibfk_1` FOREIGN KEY (`startup_id`) REFERENCES `startup` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `startup_views`
--
ALTER TABLE `startup_views`
  ADD CONSTRAINT `startup_views_ibfk_1` FOREIGN KEY (`startup_id`) REFERENCES `startup` (`id`),
  ADD CONSTRAINT `startup_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
