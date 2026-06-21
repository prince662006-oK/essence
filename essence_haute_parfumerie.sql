-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 20 juin 2026 à 17:39
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
-- Base de données : `essence_haute_parfumerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id_administrateur` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','moderateur') NOT NULL DEFAULT 'admin',
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id_administrateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'Dupont', 'Jean', 'admin@lessence.fr', '$2y$12$LzH5q4DxOJeF8.CiLnmv3.wFaTj3sKzQBGzEd7Ai5KyvW4cMHvJUO', 'super_admin', '2026-06-20 15:55:39'),
(2, 'coulibaly', 'abdoul', 'coulibalyganime@icloud.com', '$2y$10$OK/QHmbZJGiqpRe/Ia585OhkDbY0DgC/FZPnGbmFNRx/kbPdzfIFm', 'admin', '2026-06-20 16:07:48');

-- --------------------------------------------------------

--
-- Structure de la table `avis_clients`
--

CREATE TABLE `avis_clients` (
  `id_avis` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `id_produit` int(10) UNSIGNED NOT NULL,
  `note` tinyint(3) UNSIGNED NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_avis` datetime NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int(10) UNSIGNED NOT NULL,
  `nom_categorie` enum('Homme','Femme','Unisexe') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Homme'),
(2, 'Femme'),
(3, 'Unisexe');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `statut_commande` enum('En attente','Confirmée','Expédiée','Livrée','Annulée') NOT NULL DEFAULT 'En attente',
  `date_commande` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `details_commandes`
--

CREATE TABLE `details_commandes` (
  `id_detail` int(10) UNSIGNED NOT NULL,
  `id_commande` int(10) UNSIGNED NOT NULL,
  `id_produit` int(10) UNSIGNED NOT NULL,
  `quantite` int(10) UNSIGNED NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `elements_panier`
--

CREATE TABLE `elements_panier` (
  `id_element` int(10) UNSIGNED NOT NULL,
  `id_panier` int(10) UNSIGNED NOT NULL,
  `id_produit` int(10) UNSIGNED NOT NULL,
  `quantite` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `elements_panier`
--

INSERT INTO `elements_panier` (`id_element`, `id_panier`, `id_produit`, `quantite`) VALUES
(1, 1, 8, 1);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id_favori` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `id_produit` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `images_produits`
--

CREATE TABLE `images_produits` (
  `id_image` int(10) UNSIGNED NOT NULL,
  `id_produit` int(10) UNSIGNED NOT NULL,
  `chemin_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `marques`
--

CREATE TABLE `marques` (
  `id_marque` int(10) UNSIGNED NOT NULL,
  `nom_marque` varchar(150) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `marques`
--

INSERT INTO `marques` (`id_marque`, `nom_marque`, `logo`, `description`) VALUES
(1, 'Tom Ford', NULL, 'Maison américaine de luxe fondée en 2006, reconnue pour ses fragrances audacieuses et sensuelles.'),
(2, 'Dior', NULL, 'Maison de couture parisienne dont les parfums incarnent élégance et féminité depuis 1947.'),
(3, 'Chanel', NULL, 'Icône de la mode et de la parfumerie, Chanel crée des fragrances intemporelles depuis 1921.'),
(4, 'Armani', NULL, 'Giorgio Armani Privé propose des parfums exception inspirés des voyages et de la nature.'),
(5, 'Byredo', NULL, 'Maison suédoise fondée à Stockholm, connue pour ses compositions minimalistes et poétiques.'),
(6, 'Le Labo', NULL, 'Laboratoire olfactif new-yorkais qui fabrique chaque flacon à la demande.');

-- --------------------------------------------------------

--
-- Structure de la table `notes_olfactives`
--

CREATE TABLE `notes_olfactives` (
  `id_note` int(10) UNSIGNED NOT NULL,
  `id_produit` int(10) UNSIGNED NOT NULL,
  `note_tete` varchar(200) DEFAULT NULL,
  `note_coeur` varchar(200) DEFAULT NULL,
  `note_fond` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notes_olfactives`
--

INSERT INTO `notes_olfactives` (`id_note`, `id_produit`, `note_tete`, `note_coeur`, `note_fond`) VALUES
(1, 1, 'Cardamome, Poivre de Sichuan', 'Bois de Oud, Santal', 'Ambre, Vétiver'),
(3, 3, 'Citron, Bergamote', 'Gingembre, Noix de muscade', 'Santal, Vétiver, Cèdre'),
(4, 4, 'Grenade, Bergamote', 'Rose turque, Patchouli', 'Ambre, Bois de santal'),
(5, 5, 'Bergamote, Marigold africain', 'Néroli, Violette', 'Cèdre, Vétiver, Musc');

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

CREATE TABLE `paniers` (
  `id_panier` int(10) UNSIGNED NOT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paniers`
--

INSERT INTO `paniers` (`id_panier`, `id_utilisateur`, `date_creation`) VALUES
(1, 1, '2026-06-20 16:46:50');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(10) UNSIGNED NOT NULL,
  `id_marque` int(10) UNSIGNED NOT NULL,
  `id_categorie` int(10) UNSIGNED NOT NULL,
  `nom_produit` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `ancien_prix` decimal(10,2) DEFAULT NULL,
  `quantite_stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `contenance` varchar(50) DEFAULT NULL,
  `image_principale` varchar(255) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `id_marque`, `id_categorie`, `nom_produit`, `description`, `prix`, `ancien_prix`, `quantite_stock`, `contenance`, `image_principale`, `date_creation`) VALUES
(1, 1, 3, 'Oud Wood', 'Une fusion envoûtante de bois de oud, de santal et épices rares. Une signature olfactive inoubliable.', 245.00, NULL, 50, '100 ml', 'images/produits/produit_6a36aa88c541e1.63649676.webp', '2026-06-20 15:55:39'),
(3, 3, 1, 'Bleu de Chanel', 'Une fragrance boisée aromatique qui incarne la liberté virile et l&#039;élégance parisienne.', 112.00, NULL, 120, '100 ml', 'images/produits/produit_6a36aa5232c7c1.64872920.jpg', '2026-06-20 15:55:39'),
(4, 4, 2, 'Rouge Malachite', 'Un sillage précieux de grenade, rose turque et bois d&#039;ambre. L&#039;audace sublimée.', 295.00, 330.00, 30, '100 ml', 'images/produits/produit_6a36aa99bbe9f3.78267458.webp', '2026-06-20 15:55:39'),
(5, 5, 3, 'Bal d&#039;Afrique', 'Un mélange vibrant de marigold africain, cèdre et vétiver. Culture et poésie réunies.', 198.00, NULL, 60, '100 ml', 'images/produits/produit_6a36ab0ebca963.57741615.webp', '2026-06-20 15:55:39'),
(8, 4, 1, 'dior', '', 75000.00, 90000.00, 120, '1OOml', 'images/produits/produit_6a36a3b01077b6.15246176.jpg', '2026-06-20 16:29:04');

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id_promotion` int(10) UNSIGNED NOT NULL,
  `code_promo` varchar(50) NOT NULL,
  `type_reduction` enum('pourcentage','montant_fixe') NOT NULL,
  `valeur_reduction` decimal(10,2) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`id_promotion`, `code_promo`, `type_reduction`, `valeur_reduction`, `date_debut`, `date_fin`) VALUES
(1, 'BIENVENUE20', 'pourcentage', 20.00, '2024-01-01', '2025-12-31'),
(2, 'ETE10', 'montant_fixe', 10.00, '2024-06-01', '2025-08-31');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `telephone`, `mot_de_passe`, `date_creation`) VALUES
(1, 'Ganime', 'Coulibaly', 'leiiyabdoulaye68@gmail.com', '+2250759464493', '$2y$10$fqkhlJDlsTFL0Ak/T8HHQuPlgS0lzUApHpgDSGIJ.qZn5h6LXLaYW', '2026-06-20 16:46:01');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id_administrateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `avis_clients`
--
ALTER TABLE `avis_clients`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `elements_panier`
--
ALTER TABLE `elements_panier`
  ADD PRIMARY KEY (`id_element`),
  ADD KEY `id_panier` (`id_panier`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id_favori`),
  ADD UNIQUE KEY `unique_favori` (`id_utilisateur`,`id_produit`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `images_produits`
--
ALTER TABLE `images_produits`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `marques`
--
ALTER TABLE `marques`
  ADD PRIMARY KEY (`id_marque`);

--
-- Index pour la table `notes_olfactives`
--
ALTER TABLE `notes_olfactives`
  ADD PRIMARY KEY (`id_note`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD PRIMARY KEY (`id_panier`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_marque` (`id_marque`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id_promotion`),
  ADD UNIQUE KEY `code_promo` (`code_promo`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id_administrateur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `avis_clients`
--
ALTER TABLE `avis_clients`
  MODIFY `id_avis` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  MODIFY `id_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `elements_panier`
--
ALTER TABLE `elements_panier`
  MODIFY `id_element` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id_favori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `images_produits`
--
ALTER TABLE `images_produits`
  MODIFY `id_image` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `marques`
--
ALTER TABLE `marques`
  MODIFY `id_marque` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `notes_olfactives`
--
ALTER TABLE `notes_olfactives`
  MODIFY `id_note` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `paniers`
--
ALTER TABLE `paniers`
  MODIFY `id_panier` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id_promotion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis_clients`
--
ALTER TABLE `avis_clients`
  ADD CONSTRAINT `avis_clients_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_clients_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `details_commandes`
--
ALTER TABLE `details_commandes`
  ADD CONSTRAINT `details_commandes_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `details_commandes_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `elements_panier`
--
ALTER TABLE `elements_panier`
  ADD CONSTRAINT `elements_panier_ibfk_1` FOREIGN KEY (`id_panier`) REFERENCES `paniers` (`id_panier`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `elements_panier_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `images_produits`
--
ALTER TABLE `images_produits`
  ADD CONSTRAINT `images_produits_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `notes_olfactives`
--
ALTER TABLE `notes_olfactives`
  ADD CONSTRAINT `notes_olfactives_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD CONSTRAINT `paniers_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`id_marque`) REFERENCES `marques` (`id_marque`) ON UPDATE CASCADE,
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
