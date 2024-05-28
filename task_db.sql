-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 28 mai 2024 à 01:12
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `task_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `ID_category` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`ID_category`, `category_name`) VALUES
(2, 'Persllll'),
(3, 'Urgent'),
(7, 'nnn');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `ID_role` int(11) NOT NULL,
  `role_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`ID_role`, `role_type`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_title` varchar(30) NOT NULL,
  `task_description` text DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL,
  `due_date` date NOT NULL,
  `is_completed` tinyint(1) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `ID_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_title`, `task_description`, `priority`, `due_date`, `is_completed`, `ID_category`, `ID_user`) VALUES
(62, 'hhdhd', 'dhhdhhd', 'high', '2024-05-14', 1, 2, 6),
(63, 'obadil', 'heloo', 'medium', '2024-05-30', 1, 3, 6),
(64, 'Title of Task 1', 'Description of Task 1', 'high', '2024-05-30', 0, 1, NULL),
(65, 'Title of Task 2', 'Description of Task 2', 'medium', '2024-06-01', 0, 2, NULL),
(107, 'helo', 'nom', 'low', '2024-05-15', 1, 2, NULL),
(108, 'mm', 'pp', 'medium', '2024-05-10', 1, 3, NULL),
(109, 'nn', 'bb', 'medium', '2024-05-15', 1, 2, 1),
(110, 'nn', 'j', 'medium', '2024-05-18', 1, 3, 1),
(111, 'Title of Task 1', 'Description of Task 1', 'high', '2024-05-30', 0, 1, NULL),
(112, 'Title of Task 2', 'Description of Task 2', 'medium', '2024-06-01', 0, 2, NULL),
(113, 'lllll', 'ppp', 'medium', '2024-05-15', 1, 2, NULL),
(115, 'Title of Task 1', 'Description of Task 1', 'high', '2024-05-30', 0, 1, NULL),
(120, 'Title of Task 1', 'Description of Task 1', 'high', '2024-05-30', 0, 1, NULL),
(121, 'Title of Task 2', 'Description of Task 2', 'medium', '2024-06-01', 0, 2, NULL),
(122, 'Title of Task 1', 'Description of Task 1', 'high', '2024-05-30', 0, 1, NULL),
(123, 'Title of Task 2', 'Description of Task 2', 'medium', '2024-06-01', 0, 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID_user` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ID_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID_user`, `username`, `email`, `password`, `ID_role`) VALUES
(1, 'mouadddd', 'mouad@gmail.com', 'mouad@gmail.com', 2),
(2, 'marwa', 'marwa@gmail.com', 'marwa@gmail.com', 1),
(6, 'hanane', 'hanane@gmail.com', 'hanane@gmail.com', 2),
(7, 'mouad@gmail.com', 'mouad@gmail.com', 'khadija', 2),
(8, 'mouaaad@gmail.com', 'mouad@gmail.com', 'khadija', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID_category`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID_role`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `fk_user_id` (`ID_user`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_user`),
  ADD KEY `ID_role` (`ID_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `ID_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`ID_role`) REFERENCES `roles` (`ID_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
