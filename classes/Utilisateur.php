<?php
require_once __DIR__ . '/../configuration/connexion_base_donnees.php';

class Utilisateur {
    private PDO $connexion;

    public function __construct(PDO $connexion) {
        $this->connexion = $connexion;
    }

    public function inscrire(array $donnees): int|false {
        $motDePasseHache = password_hash($donnees['mot_de_passe'], PASSWORD_BCRYPT);
        $requete = $this->connexion->prepare(
            "INSERT INTO utilisateurs (nom, prenom, email, telephone, mot_de_passe)
             VALUES (:nom, :prenom, :email, :telephone, :mot_de_passe)"
        );
        $resultat = $requete->execute([
            ':nom'          => $donnees['nom'],
            ':prenom'       => $donnees['prenom'],
            ':email'        => $donnees['email'],
            ':telephone'    => $donnees['telephone'] ?? null,
            ':mot_de_passe' => $motDePasseHache,
        ]);
        return $resultat ? (int)$this->connexion->lastInsertId() : false;
    }

    public function connecter(string $email, string $motDePasse): array|false {
        $requete = $this->connexion->prepare(
            "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1"
        );
        $requete->execute([':email' => $email]);
        $utilisateur = $requete->fetch();
        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            unset($utilisateur['mot_de_passe']);
            return $utilisateur;
        }
        return false;
    }

    public function obtenirParId(int $id): array|false {
        $requete = $this->connexion->prepare(
            "SELECT id_utilisateur, nom, prenom, email, telephone, date_creation
             FROM utilisateurs WHERE id_utilisateur = :id"
        );
        $requete->execute([':id' => $id]);
        return $requete->fetch();
    }

    public function obtenirTous(): array {
        $requete = $this->connexion->prepare(
            "SELECT id_utilisateur, nom, prenom, email, telephone, date_creation
             FROM utilisateurs ORDER BY date_creation DESC"
        );
        $requete->execute();
        return $requete->fetchAll();
    }

    public function emailExiste(string $email): bool {
        $requete = $this->connexion->prepare(
            "SELECT COUNT(*) FROM utilisateurs WHERE email = :email"
        );
        $requete->execute([':email' => $email]);
        return (bool)$requete->fetchColumn();
    }
}
