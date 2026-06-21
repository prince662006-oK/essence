<?php
session_start();
if (!isset($_SESSION['id_utilisateur'])) { header('Location: connexion.php'); exit; }
require_once 'configuration/connexion_base_donnees.php';

$idUtilisateur = (int)$_SESSION['id_utilisateur'];

// Ajouter / supprimer favori
if (isset($_GET['toggle'])) {
    $idProduit = (int)$_GET['toggle'];
    $reqVerif = $connexion->prepare("SELECT id_favori FROM favoris WHERE id_utilisateur = :u AND id_produit = :p");
    $reqVerif->execute([':u' => $idUtilisateur, ':p' => $idProduit]);
    if ($reqVerif->fetch()) {
        $connexion->prepare("DELETE FROM favoris WHERE id_utilisateur = :u AND id_produit = :p")
                  ->execute([':u' => $idUtilisateur, ':p' => $idProduit]);
    } else {
        $connexion->prepare("INSERT INTO favoris (id_utilisateur, id_produit) VALUES (:u, :p)")
                  ->execute([':u' => $idUtilisateur, ':p' => $idProduit]);
    }
    header('Location: favoris.php');
    exit;
}

// Récupérer les favoris
$reqFavoris = $connexion->prepare("
    SELECT p.*, m.nom_marque, f.date_ajout
    FROM favoris f
    JOIN produits p ON f.id_produit = p.id_produit
    JOIN marques m ON p.id_marque = m.id_marque
    WHERE f.id_utilisateur = :u
    ORDER BY f.date_ajout DESC
");
$reqFavoris->execute([':u' => $idUtilisateur]);
$favoris = $reqFavoris->fetchAll();
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mes Favoris — L'ESSENCE</title>
<link rel="manifest" href="pwa/manifest.json">
<link rel="stylesheet" href="css/style-principal.css">
</head><body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section">
        <span class="etiquette">Ma sélection</span>
        <h1 class="titre-affiche" style="margin:8px 0 40px;">Mes favoris</h1>

        <?php if (empty($favoris)): ?>
            <div style="text-align:center;padding:80px 0;">
                <p style="font-family:var(--police-display);font-size:1.6rem;color:var(--couleur-ivoire);margin-bottom:12px;">Votre liste est vide</p>
                <p class="corps-md" style="color:var(--couleur-ivoire-sombre);margin-bottom:32px;">Explorez notre catalogue et sauvegardez vos parfums de cœur.</p>
                <a href="catalogue.php" class="bouton-primaire">Découvrir nos parfums</a>
            </div>
        <?php else: ?>
            <p class="corps-md" style="color:var(--couleur-ivoire-sombre);margin-bottom:32px;"><?= count($favoris) ?> parfum<?= count($favoris) > 1 ? 's' : '' ?> sauvegardé<?= count($favoris) > 1 ? 's' : '' ?></p>
            <div class="grille-produits">
                <?php foreach ($favoris as $produit): ?>
                <div class="carte-produit">
                    <a href="produit.php?id=<?= $produit['id_produit'] ?>">
                        <div class="image-produit">
                            <img src="<?= htmlspecialchars($produit['image_principale'] ?: 'images/produits/placeholder.jpg') ?>"
                                 alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                                 onerror="this.src='images/produits/placeholder.jpg'">
                            <div class="superposition-produit">
                                <a href="panier.php?ajouter=<?= $produit['id_produit'] ?>" class="bouton-superposition">Ajouter au panier</a>
                            </div>
                        </div>
                    </a>
                    <div class="info-produit">
                        <span class="marque-produit"><?= htmlspecialchars($produit['nom_marque']) ?></span>
                        <h3 class="nom-produit"><a href="produit.php?id=<?= $produit['id_produit'] ?>"><?= htmlspecialchars($produit['nom_produit']) ?></a></h3>
                        <div class="prix-produit">
                            <span class="prix-actuel"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                            <?php if ($produit['ancien_prix']): ?>
                                <span class="ancien-prix"><?= number_format($produit['ancien_prix'], 2, ',', ' ') ?> €</span>
                            <?php endif; ?>
                        </div>
                        <a href="favoris.php?toggle=<?= $produit['id_produit'] ?>"
                           style="display:inline-block;margin-top:10px;font-size:0.72rem;color:#cf6679;letter-spacing:0.08em;text-transform:uppercase;">
                            ♥ Retirer des favoris
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body></html>
