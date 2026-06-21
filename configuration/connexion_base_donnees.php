<?php
/**
 * Connexion à la base de données — L'ESSENCE Haute Parfumerie
 */
try {
    $connexion = new PDO(
        "mysql:host=localhost;dbname=essence_haute_parfumerie;charset=utf8mb4",
        "root",
        ""
    );
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $erreur) {
    http_response_code(500);
    die('Connexion impossible : ' . $erreur->getMessage());
}
