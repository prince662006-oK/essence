<?php
session_start();
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Produit.php';

$idProduit = (int)($_GET['id'] ?? 0);
if (!$idProduit) { header('Location: catalogue.php'); exit; }

$classeProduit = new Produit($connexion);
$produit = $classeProduit->obtenirParId($idProduit);
if (!$produit) { header('Location: catalogue.php'); exit; }

$produitsAssocies = $classeProduit->obtenirBestSellers(4);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['nom_produit']) ?> — L'ESSENCE</title>
    <link rel="manifest" href="pwa/manifest.json">
    <link rel="stylesheet" href="css/style-principal.css">
</head>
<body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section">
        <!-- Fil d'Ariane -->
        <nav style="margin-bottom:40px;font-size:0.75rem;color:var(--couleur-ivoire-sombre);" aria-label="Fil d'Ariane">
            <a href="accueil.php" style="color:var(--couleur-ivoire-sombre);transition:0.2s;">Accueil</a>
            <span style="margin:0 8px;opacity:0.4;">›</span>
            <a href="catalogue.php" style="color:var(--couleur-ivoire-sombre);">Catalogue</a>
            <span style="margin:0 8px;opacity:0.4;">›</span>
            <span style="color:var(--couleur-or);"><?= htmlspecialchars($produit['nom_produit']) ?></span>
        </nav>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start;">
            <!-- Galerie produit -->
            <div>
                <div style="aspect-ratio:4/5;overflow:hidden;background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);">
                    <img src="<?= htmlspecialchars($produit['image_principale'] ?? 'images/placeholder.jpg') ?>"
                         alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                         style="width:100%;height:100%;object-fit:cover;opacity:0.92;">
                </div>
            </div>

            <!-- Infos produit -->
            <div style="position:sticky;top:120px;">
                <p class="etiquette"><?= htmlspecialchars($produit['nom_marque']) ?></p>
                <h1 class="titre-affiche" style="margin:8px 0 16px;"><?= htmlspecialchars($produit['nom_produit']) ?></h1>

                <div style="display:flex;align-items:baseline;gap:16px;margin-bottom:28px;">
                    <span style="font-family:var(--police-display);font-size:1.8rem;color:var(--couleur-or);">
                        <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                    </span>
                    <span style="font-size:0.85rem;color:var(--couleur-ivoire-sombre);">/ <?= htmlspecialchars($produit['contenance'] ?? '100 ml') ?></span>
                    <?php if ($produit['ancien_prix']): ?>
                        <span style="font-size:0.9rem;color:var(--couleur-ivoire-sombre);text-decoration:line-through;">
                            <?= number_format($produit['ancien_prix'], 2, ',', ' ') ?> €
                        </span>
                    <?php endif; ?>
                </div>

                <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);margin-bottom:36px;">
                    <?= htmlspecialchars($produit['description'] ?? '') ?>
                </p>

                <!-- Notes olfactives -->
                <?php if ($produit['note_tete'] || $produit['note_coeur'] || $produit['note_fond']): ?>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);border:1px solid var(--couleur-bordure);margin-bottom:36px;">
                    <div style="padding:20px;border-right:1px solid var(--couleur-bordure);">
                        <p class="etiquette" style="margin-bottom:8px;font-size:0.6rem;">Note de tête</p>
                        <p class="corps-md" style="color:var(--couleur-ivoire);"><?= htmlspecialchars($produit['note_tete'] ?? '—') ?></p>
                    </div>
                    <div style="padding:20px;border-right:1px solid var(--couleur-bordure);">
                        <p class="etiquette" style="margin-bottom:8px;font-size:0.6rem;">Note de cœur</p>
                        <p class="corps-md" style="color:var(--couleur-ivoire);"><?= htmlspecialchars($produit['note_coeur'] ?? '—') ?></p>
                    </div>
                    <div style="padding:20px;">
                        <p class="etiquette" style="margin-bottom:8px;font-size:0.6rem;">Note de fond</p>
                        <p class="corps-md" style="color:var(--couleur-ivoire);"><?= htmlspecialchars($produit['note_fond'] ?? '—') ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Catégorie -->
                <p class="corps-md" style="color:var(--couleur-ivoire-sombre);margin-bottom:32px;">
                    Catégorie : <span style="color:var(--couleur-or);"><?= htmlspecialchars($produit['nom_categorie']) ?></span>
                    &nbsp;·&nbsp; Stock :
                    <?php if ($produit['quantite_stock'] > 10): ?>
                        <span style="color:#7fb890;">Disponible</span>
                    <?php elseif ($produit['quantite_stock'] > 0): ?>
                        <span style="color:#e9c349;">Plus que <?= $produit['quantite_stock'] ?> en stock</span>
                    <?php else: ?>
                        <span style="color:#cf6679;">Rupture de stock</span>
                    <?php endif; ?>
                </p>

                <!-- CTA -->
                <?php if ($produit['quantite_stock'] > 0): ?>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <a href="panier.php?ajouter=<?= $produit['id_produit'] ?>"
                       class="bouton-primaire" style="justify-content:center;">Ajouter au panier</a>
                    <a href="favoris.php?toggle=<?= $produit['id_produit'] ?>"
                       class="bouton-secondaire" style="justify-content:center;">Ajouter aux favoris &#9825;</a>
                </div>
                <?php else: ?>
                <p class="alerte alerte-erreur">Ce parfum est actuellement épuisé.</p>
                <?php endif; ?>

                <!-- Expédition -->
                <div style="display:flex;gap:24px;margin-top:28px;">
                    <p class="corps-md" style="color:var(--couleur-ivoire-sombre);font-size:0.78rem;">&#128666; Livraison express offerte</p>
                    <p class="corps-md" style="color:var(--couleur-ivoire-sombre);font-size:0.78rem;">&#127873; Échantillons offerts</p>
                </div>
            </div>
        </div>

        <!-- Produits associés -->
        <div style="margin-top:80px;padding-top:64px;border-top:1px solid var(--couleur-bordure);">
            <h2 class="titre-section reveler" style="margin-bottom:40px;">Vous aimerez aussi</h2>
            <div class="grille-produits">
                <?php foreach ($produitsAssocies as $associe): ?>
                    <?php if ($associe['id_produit'] == $idProduit) continue; ?>
                    <div class="carte-produit reveler">
                        <div class="carte-produit__visuel">
                            <img class="carte-produit__image"
                                 src="<?= htmlspecialchars($associe['image_principale'] ?? 'images/placeholder.jpg') ?>"
                                 alt="<?= htmlspecialchars($associe['nom_produit']) ?>" loading="lazy">
                            <div class="carte-produit__superposition"></div>
                            <a href="panier.php?ajouter=<?= $associe['id_produit'] ?>" class="bouton-ajouter-panier">Ajouter au panier</a>
                        </div>
                        <p class="carte-produit__marque"><?= htmlspecialchars($associe['nom_marque'] ?? '') ?></p>
                        <h3 class="carte-produit__nom"><a href="produit.php?id=<?= $associe['id_produit'] ?>"><?= htmlspecialchars($associe['nom_produit']) ?></a></h3>
                        <span class="carte-produit__prix"><?= number_format($associe['prix'], 2, ',', ' ') ?> €</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body>
</html>
