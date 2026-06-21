<?php
session_start();
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Produit.php';

$classeProduit = new Produit($connexion);
$categorie = $_GET['categorie'] ?? '';
$recherche = $_GET['recherche'] ?? '';

if ($recherche) {
    $produits = $classeProduit->rechercherParNom($recherche);
} else {
    $produits = $classeProduit->obtenirTous($categorie);
}

$titrePage = "Catalogue — L'ESSENCE Haute Parfumerie";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titrePage) ?></title>
    <link rel="manifest" href="pwa/manifest.json">
    <link rel="stylesheet" href="css/style-principal.css">
</head>
<body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section">
        <!-- En-tête catalogue -->
        <div class="entete-section" style="margin-bottom:48px;">
            <div class="groupe-titre-section">
                <span class="etiquette">Parfumerie de Luxe</span>
                <h1 class="titre-affiche">
                    <?php
                    if ($recherche) echo 'Résultats pour "' . htmlspecialchars($recherche) . '"';
                    elseif ($categorie) echo htmlspecialchars($categorie);
                    else echo 'Toute la Collection';
                    ?>
                </h1>
                <p class="corps-md" style="color:var(--couleur-ivoire-sombre);"><?= count($produits) ?> parfum<?= count($produits) > 1 ? 's' : '' ?></p>
            </div>
            <!-- Filtres catégorie -->
            <nav class="filtres-categorie" aria-label="Filtrer par catégorie">
                <style>
                    .filtres-categorie { display:flex; gap:12px; flex-wrap:wrap; }
                    .filtre-btn { padding:8px 20px; border:1px solid var(--couleur-bordure); font-size:0.7rem; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--couleur-ivoire-sombre); transition:0.3s; border-radius:2px; }
                    .filtre-btn:hover, .filtre-btn.actif { border-color:var(--couleur-or); color:var(--couleur-or); background:rgba(201,168,76,0.05); }
                </style>
                <a href="catalogue.php" class="filtre-btn<?= !$categorie ? ' actif' : '' ?>">Tous</a>
                <a href="catalogue.php?categorie=Homme" class="filtre-btn<?= $categorie === 'Homme' ? ' actif' : '' ?>">Homme</a>
                <a href="catalogue.php?categorie=Femme" class="filtre-btn<?= $categorie === 'Femme' ? ' actif' : '' ?>">Femme</a>
                <a href="catalogue.php?categorie=Unisexe" class="filtre-btn<?= $categorie === 'Unisexe' ? ' actif' : '' ?>">Unisexe</a>
            </nav>
        </div>

        <?php if (empty($produits)): ?>
            <div style="text-align:center;padding:80px 0;">
                <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);">Aucun parfum trouvé pour cette recherche.</p>
                <a href="catalogue.php" class="bouton-secondaire" style="display:inline-flex;margin-top:24px;">Voir tout le catalogue</a>
            </div>
        <?php else: ?>
        <div class="grille-produits">
            <?php foreach ($produits as $produit): ?>
            <div class="carte-produit reveler">
                <div class="carte-produit__visuel">
                    <img class="carte-produit__image"
                         src="<?= htmlspecialchars($produit['image_principale'] ?? 'images/placeholder.jpg') ?>"
                         alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                         loading="lazy">
                    <div class="carte-produit__superposition"></div>
                    <?php if (!empty($produit['ancien_prix'])): ?>
                        <span class="carte-produit__badge">Promo</span>
                    <?php endif; ?>
                    <a href="panier.php?ajouter=<?= $produit['id_produit'] ?>"
                       class="bouton-ajouter-panier">Ajouter au panier</a>
                </div>
                <p class="carte-produit__marque"><?= htmlspecialchars($produit['nom_marque'] ?? '') ?></p>
                <h2 class="carte-produit__nom">
                    <a href="produit.php?id=<?= $produit['id_produit'] ?>"><?= htmlspecialchars($produit['nom_produit']) ?></a>
                </h2>
                <div class="carte-produit__prix-groupe">
                    <span class="carte-produit__prix"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                    <?php if (!empty($produit['ancien_prix'])): ?>
                        <span class="carte-produit__ancien-prix"><?= number_format($produit['ancien_prix'], 2, ',', ' ') ?> €</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body>
</html>
