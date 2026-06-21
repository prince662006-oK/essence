<?php
require_once __DIR__ . '/../configuration/connexion_base_donnees.php';

class Produit {
    private PDO $connexion;

    public function __construct(PDO $connexion) {
        $this->connexion = $connexion;
    }

    public function obtenirTous(string $categorie = '', int $limite = 0): array {
        $sql = "SELECT p.*, m.nom_marque, c.nom_categorie
                FROM produits p
                JOIN marques m ON p.id_marque = m.id_marque
                JOIN categories c ON p.id_categorie = c.id_categorie";
        $params = [];
        if ($categorie) {
            $sql .= " WHERE c.nom_categorie = :categorie";
            $params[':categorie'] = $categorie;
        }
        $sql .= " ORDER BY p.date_creation DESC";
        if ($limite > 0) $sql .= " LIMIT " . (int)$limite;

        $requete = $this->connexion->prepare($sql);
        $requete->execute($params);
        return $requete->fetchAll();
    }

    public function obtenirParId(int $id_produit): array|false {
        $requete = $this->connexion->prepare(
            "SELECT p.*, m.nom_marque, c.nom_categorie,
                    n.note_tete, n.note_coeur, n.note_fond
             FROM produits p
             JOIN marques m ON p.id_marque = m.id_marque
             JOIN categories c ON p.id_categorie = c.id_categorie
             LEFT JOIN notes_olfactives n ON n.id_produit = p.id_produit
             WHERE p.id_produit = :id"
        );
        $requete->execute([':id' => $id_produit]);
        return $requete->fetch();
    }

    public function obtenirBestSellers(int $limite = 4): array {
        $requete = $this->connexion->prepare(
            "SELECT p.*, m.nom_marque,
                    COALESCE(SUM(dc.quantite), 0) AS total_vendu
             FROM produits p
             JOIN marques m ON p.id_marque = m.id_marque
             LEFT JOIN details_commandes dc ON dc.id_produit = p.id_produit
             GROUP BY p.id_produit
             ORDER BY total_vendu DESC
             LIMIT :limite"
        );
        $requete->bindValue(':limite', $limite, PDO::PARAM_INT);
        $requete->execute();
        return $requete->fetchAll();
    }

    public function ajouter(array $donnees): int {
        $requete = $this->connexion->prepare(
            "INSERT INTO produits (id_marque, id_categorie, nom_produit, description, prix,
             ancien_prix, quantite_stock, contenance, image_principale)
             VALUES (:id_marque, :id_categorie, :nom_produit, :description, :prix,
             :ancien_prix, :quantite_stock, :contenance, :image_principale)"
        );
        $requete->execute($donnees);
        return (int)$this->connexion->lastInsertId();
    }

    public function modifier(int $id, array $donnees): bool {
        $donnees[':id'] = $id;
        $requete = $this->connexion->prepare(
            "UPDATE produits SET id_marque=:id_marque, id_categorie=:id_categorie,
             nom_produit=:nom_produit, description=:description, prix=:prix,
             ancien_prix=:ancien_prix, quantite_stock=:quantite_stock,
             contenance=:contenance, image_principale=:image_principale
             WHERE id_produit=:id"
        );
        return $requete->execute($donnees);
    }

    public function supprimer(int $id): bool {
        $requete = $this->connexion->prepare("DELETE FROM produits WHERE id_produit = :id");
        return $requete->execute([':id' => $id]);
    }

    public function rechercherParNom(string $terme): array {
        $requete = $this->connexion->prepare(
            "SELECT p.*, m.nom_marque FROM produits p
             JOIN marques m ON p.id_marque = m.id_marque
             WHERE p.nom_produit LIKE :terme1 OR m.nom_marque LIKE :terme2
             ORDER BY p.nom_produit"
        );
        $requete->execute([':terme1' => '%' . $terme . '%', ':terme2' => '%' . $terme . '%']);
        return $requete->fetchAll();
    }
}
