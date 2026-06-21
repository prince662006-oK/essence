<?php
require_once __DIR__ . '/../configuration/connexion_base_donnees.php';

class Administrateur {
    private PDO $connexion;

    public function __construct(PDO $connexion) {
        $this->connexion = $connexion;
    }

    public function connecter(string $email, string $motDePasse): array|false {
        $requete = $this->connexion->prepare(
            "SELECT * FROM administrateurs WHERE email = :email LIMIT 1"
        );
        $requete->execute([':email' => $email]);
        $admin = $requete->fetch();
        if ($admin && password_verify($motDePasse, $admin['mot_de_passe'])) {
            unset($admin['mot_de_passe']);
            return $admin;
        }
        return false;
    }

    public function obtenirStatistiques(): array {
        $stats = [];

        $req = $this->connexion->prepare("SELECT COUNT(*) FROM commandes");
        $req->execute();
        $stats['total_commandes'] = (int)$req->fetchColumn();

        $req = $this->connexion->prepare("SELECT COALESCE(SUM(montant_total), 0) FROM commandes WHERE statut_commande != 'Annulée'");
        $req->execute();
        $stats['chiffre_affaires'] = (float)$req->fetchColumn();

        $req = $this->connexion->prepare("SELECT COUNT(*) FROM utilisateurs");
        $req->execute();
        $stats['total_utilisateurs'] = (int)$req->fetchColumn();

        $req = $this->connexion->prepare(
            "SELECT p.nom_produit, SUM(dc.quantite) AS total_vendu
             FROM details_commandes dc JOIN produits p ON dc.id_produit = p.id_produit
             GROUP BY p.id_produit ORDER BY total_vendu DESC LIMIT 5"
        );
        $req->execute();
        $stats['produits_populaires'] = $req->fetchAll();

        return $stats;
    }
}
