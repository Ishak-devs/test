-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 04 nov. 2024 à 11:33
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
-- Base de données : `ecom2425`
--

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `produit_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite_stock` int(11) NOT NULL,
  `nombre_ventes` int(11) NOT NULL,
  `cree_le` date DEFAULT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `fabricant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`produit_id`, `description`, `prix`, `quantite_stock`, `nombre_ventes`, `cree_le`, `nom_produit`, `categorie_id`, `fabricant_id`) VALUES
(1, 'Ordinateur Portable Dell XPS 13', 1200.50, 10, 5, '2024-10-23', 'Ordinateur Portable', 1, 1),
(2, 'Souris Sans Fil Logitech MX Master', 150.00, 15, 20, '2024-10-23', 'Souris Sans Fil', 2, 3),
(3, 'Clavier Mécanique HP', 80.00, 25, 15, '2024-10-23', 'Clavier Mécanique', 2, 2),
(4, 'Ordinateur Portable Dell XPS 13', 1200.50, 10, 5, '2024-10-23', 'Ordinateur Portable', 1, 1),
(5, 'Souris Sans Fil Logitech MX Master', 150.00, 15, 20, '2024-10-23', 'Souris Sans Fil', 2, 3),
(6, 'Clavier Mécanique HP', 80.00, 25, 15, '2024-10-23', 'Clavier Mécanique', 2, 2),
(7, 'Ordinateur Portable Dell XPS 13', 1200.50, 10, 5, '2024-10-23', 'Ordinateur Portable', 1, 1),
(8, 'Souris Sans Fil Logitech MX Master', 150.00, 15, 20, '2024-10-23', 'Souris Sans Fil', 2, 3),
(9, 'Clavier Mécanique HP', 80.00, 25, 15, '2024-10-23', 'Clavier Mécanique', 2, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`produit_id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `fabricant_id` (`fabricant_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`categorie_id`),
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`fabricant_id`) REFERENCES `fabricants` (`fabricant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
