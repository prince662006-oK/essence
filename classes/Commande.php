<?php
require_once __DIR__ . '/../configuration/connexion_base_donnees.php';

class Commande {
    private PDO $connexion;

    public function __construct(PDO $connexion) {
        $this->connexion = $connexion;
    }

    public function creer(int $id_utilisateur, float $montant_total): int {
        $requete = $this->connexion->prepare(
            "INSERT INTO commandes (id_utilisateur, montant_total) VALUES (:id, :montant)"
        );
        $requete->execute([':id' => $id_utilisateur, ':montant' => $montant_total]);
        return (int)$this->connexion->lastInsertId();
    }

    public function ajouterDetail(int $id_commande, int $id_produit, int $quantite, float $prix): bool {
        $requete = $this->connexion->prepare(
            "INSERT INTO details_commandes (id_commande, id_produit, quantite, prix_unitaire)
             VALUES (:id_commande, :id_produit, :quantite, :prix)"
        );
        return $requete->execute([
            ':id_commande' => $id_commande,
            ':id_produit'  => $id_produit,
            ':quantite'    => $quantite,
            ':prix'        => $prix,
        ]);
    }

    public function obtenirParUtilisateur(int $id_utilisateur): array {
        $requete = $this->connexion->prepare(
            "SELECT * FROM commandes WHERE id_utilisateur = :id ORDER BY date_commande DESC"
        );
        $requete->execute([':id' => $id_utilisateur]);
        return $requete->fetchAll();
    }

    public function obtenirToutes(): array {
        $requete = $this->connexion->prepare(
            "SELECT c.*, u.nom, u.prenom, u.email
             FROM commandes c JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
             ORDER BY c.date_commande DESC"
        );
        $requete->execute();
        return $requete->fetchAll();
    }

    public function changerStatut(int $id_commande, string $statut): bool {
        $requete = $this->connexion->prepare(
            "UPDATE commandes SET statut_commande = :statut WHERE id_commande = :id"
        );
        return $requete->execute([':statut' => $statut, ':id' => $id_commande]);
    }

    public function obtenirDetailsCommande(int $id_commande): array {
        $requete = $this->connexion->prepare(
            "SELECT dc.*, p.nom_produit, p.image_principale
             FROM details_commandes dc JOIN produits p ON dc.id_produit = p.id_produit
             WHERE dc.id_commande = :id"
        );
        $requete->execute([':id' => $id_commande]);
        return $requete->fetchAll();
    }
}
