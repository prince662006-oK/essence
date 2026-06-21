<?php
require_once __DIR__ . '/../configuration/connexion_base_donnees.php';

class Panier {
    private PDO $connexion;

    public function __construct(PDO $connexion) {
        $this->connexion = $connexion;
    }

    public function obtenirOuCreer(int $id_utilisateur): int {
        $requete = $this->connexion->prepare(
            "SELECT id_panier FROM paniers WHERE id_utilisateur = :id ORDER BY date_creation DESC LIMIT 1"
        );
        $requete->execute([':id' => $id_utilisateur]);
        $panier = $requete->fetch();
        if ($panier) return $panier['id_panier'];

        $requete = $this->connexion->prepare(
            "INSERT INTO paniers (id_utilisateur) VALUES (:id)"
        );
        $requete->execute([':id' => $id_utilisateur]);
        return (int)$this->connexion->lastInsertId();
    }

    public function ajouterProduit(int $id_panier, int $id_produit, int $quantite = 1): bool {
        $requete = $this->connexion->prepare(
            "INSERT INTO elements_panier (id_panier, id_produit, quantite)
             VALUES (:id_panier, :id_produit, :quantite)
             ON DUPLICATE KEY UPDATE quantite = quantite + :quantite2"
        );
        return $requete->execute([
            ':id_panier'  => $id_panier,
            ':id_produit' => $id_produit,
            ':quantite'   => $quantite,
            ':quantite2'  => $quantite,
        ]);
    }

    public function obtenirContenu(int $id_panier): array {
        $requete = $this->connexion->prepare(
            "SELECT ep.*, p.nom_produit, p.prix, p.image_principale, m.nom_marque
             FROM elements_panier ep
             JOIN produits p ON ep.id_produit = p.id_produit
             JOIN marques m ON p.id_marque = m.id_marque
             WHERE ep.id_panier = :id"
        );
        $requete->execute([':id' => $id_panier]);
        return $requete->fetchAll();
    }

    public function supprimerElement(int $id_element): bool {
        $requete = $this->connexion->prepare(
            "DELETE FROM elements_panier WHERE id_element = :id"
        );
        return $requete->execute([':id' => $id_element]);
    }

    public function vider(int $id_panier): bool {
        $requete = $this->connexion->prepare(
            "DELETE FROM elements_panier WHERE id_panier = :id"
        );
        return $requete->execute([':id' => $id_panier]);
    }

    public function calculerTotal(int $id_panier): float {
        $requete = $this->connexion->prepare(
            "SELECT SUM(ep.quantite * p.prix) AS total
             FROM elements_panier ep JOIN produits p ON ep.id_produit = p.id_produit
             WHERE ep.id_panier = :id"
        );
        $requete->execute([':id' => $id_panier]);
        return (float)($requete->fetchColumn() ?? 0);
    }
}
