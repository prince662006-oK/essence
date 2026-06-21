<?php
session_start();
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Panier.php';

// Gestion ajout rapide
if (isset($_GET['ajouter']) && isset($_SESSION['id_utilisateur'])) {
    $classePanier = new Panier($connexion);
    $idPanier = $classePanier->obtenirOuCreer((int)$_SESSION['id_utilisateur']);
    $classePanier->ajouterProduit($idPanier, (int)$_GET['ajouter'], 1);
    header('Location: panier.php');
    exit;
}

// Suppression
if (isset($_GET['supprimer']) && isset($_SESSION['id_utilisateur'])) {
    $classePanier = new Panier($connexion);
    $idPanier = $classePanier->obtenirOuCreer((int)$_SESSION['id_utilisateur']);
    $classePanier->supprimerElement((int)$_GET['supprimer']);
    header('Location: panier.php');
    exit;
}

$contenuPanier = [];
$totalPanier = 0;
if (isset($_SESSION['id_utilisateur'])) {
    $classePanier = new Panier($connexion);
    $idPanier = $classePanier->obtenirOuCreer((int)$_SESSION['id_utilisateur']);
    $contenuPanier = $classePanier->obtenirContenu($idPanier);
    $totalPanier = $classePanier->calculerTotal($idPanier);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier — L'ESSENCE</title>
    <link rel="manifest" href="pwa/manifest.json">
    <link rel="stylesheet" href="css/style-principal.css">
</head>
<body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section">
        <div style="margin-bottom:48px;">
            <span class="etiquette">Mon espace</span>
            <h1 class="titre-affiche" style="margin-top:8px;">Mon Panier</h1>
        </div>

        <?php if (!isset($_SESSION['id_utilisateur'])): ?>
            <div style="text-align:center;padding:80px 0;">
                <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);margin-bottom:24px;">
                    Connectez-vous pour accéder à votre panier.
                </p>
                <a href="connexion.php" class="bouton-primaire">Se connecter</a>
            </div>

        <?php elseif (empty($contenuPanier)): ?>
            <div style="text-align:center;padding:80px 0;">
                <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);margin-bottom:24px;">Votre panier est vide.</p>
                <a href="catalogue.php" class="bouton-primaire">Découvrir nos parfums</a>
            </div>

        <?php else: ?>
        <div style="display:grid;grid-template-columns:1fr 380px;gap:48px;align-items:start;">
            <!-- Tableau articles -->
            <div>
                <table class="tableau-panier">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contenuPanier as $element): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:16px;">
                                    <img src="<?= htmlspecialchars($element['image_principale'] ?? 'images/placeholder.jpg') ?>"
                                         alt="<?= htmlspecialchars($element['nom_produit']) ?>"
                                         style="width:64px;height:80px;object-fit:cover;opacity:0.88;">
                                    <div>
                                        <p class="etiquette" style="margin-bottom:4px;"><?= htmlspecialchars($element['nom_marque']) ?></p>
                                        <p class="corps-md" style="color:var(--couleur-ivoire);"><?= htmlspecialchars($element['nom_produit']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?= number_format($element['prix'], 2, ',', ' ') ?> €</td>
                            <td><?= $element['quantite'] ?></td>
                            <td><?= number_format($element['prix'] * $element['quantite'], 2, ',', ' ') ?> €</td>
                            <td>
                                <a href="panier.php?supprimer=<?= $element['id_element'] ?>"
                                   style="color:var(--couleur-ivoire-sombre);font-size:1.1rem;transition:0.2s;"
                                   aria-label="Supprimer"
                                   onmouseover="this.style.color='#cf6679'"
                                   onmouseout="this.style.color=''">&times;</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Résumé commande -->
            <div class="resume-commande">
                <h2 class="titre-section" style="margin-bottom:24px;">Résumé</h2>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.9rem;color:var(--couleur-ivoire-sombre);">
                    <span>Sous-total</span><span><?= number_format($totalPanier, 2, ',', ' ') ?> €</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.9rem;color:var(--couleur-ivoire-sombre);">
                    <span>Livraison</span><span style="color:#7fb890;">Offerte</span>
                </div>
                <div style="border-top:1px solid var(--couleur-bordure);margin:20px 0;padding-top:20px;display:flex;justify-content:space-between;">
                    <span style="font-family:var(--police-display);font-size:1.2rem;">Total</span>
                    <span style="font-family:var(--police-display);font-size:1.4rem;color:var(--couleur-or);"><?= number_format($totalPanier, 2, ',', ' ') ?> €</span>
                </div>
                <!-- Code promo -->
                <form action="panier.php" method="POST" style="margin-bottom:20px;">
                    <div style="display:flex;gap:0;">
                        <input type="text" name="code_promo" class="champ-formulaire" placeholder="Code promo" style="border-radius:2px 0 0 2px;flex:1;">
                        <button type="submit" class="bouton-primaire" style="border-radius:0 2px 2px 0;padding:14px 20px;white-space:nowrap;">Appliquer</button>
                    </div>
                </form>
                <a href="paiement.php" class="bouton-primaire" style="width:100%;justify-content:center;">
                    Commander — <?= number_format($totalPanier, 2, ',', ' ') ?> €
                </a>
                <p class="corps-md" style="text-align:center;margin-top:12px;font-size:0.75rem;color:var(--couleur-ivoire-sombre);">
                    &#128274; Paiement 100% sécurisé
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body>
</html>
