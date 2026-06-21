<?php
session_start();
if (!isset($_SESSION['id_utilisateur'])) { header('Location: connexion.php'); exit; }
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Panier.php';
require_once 'classes/Commande.php';
$classePanier = new Panier($connexion);
$idPanier = $classePanier->obtenirOuCreer((int)$_SESSION['id_utilisateur']);
$contenuPanier = $classePanier->obtenirContenu($idPanier);
$totalPanier = $classePanier->calculerTotal($idPanier);
if (empty($contenuPanier)) { header('Location: panier.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classeCommande = new Commande($connexion);
    $idCommande = $classeCommande->creer((int)$_SESSION['id_utilisateur'], $totalPanier);
    foreach ($contenuPanier as $element) {
        $classeCommande->ajouterDetail($idCommande, $element['id_produit'], $element['quantite'], $element['prix']);
    }
    $classePanier->vider($idPanier);
    header('Location: profil.php?commande=' . $idCommande);
    exit;
}
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paiement — L'ESSENCE</title>
<link rel="manifest" href="pwa/manifest.json">
<link rel="stylesheet" href="css/style-principal.css">
</head><body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section" style="max-width:800px;margin:0 auto;">
        <span class="etiquette">Dernière étape</span>
        <h1 class="titre-affiche" style="margin:8px 0 40px;">Paiement sécurisé</h1>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;">
            <div>
                <h2 style="font-family:var(--police-display);font-size:1.1rem;margin-bottom:20px;">Informations de livraison</h2>
                <form method="POST" id="formulaire-paiement">
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Adresse</label>
                        <input type="text" name="adresse" class="champ-formulaire" placeholder="123 rue de la Paix" required>
                    </div>
                    <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;">
                        <div class="groupe-formulaire">
                            <label class="etiquette-formulaire">Ville</label>
                            <input type="text" name="ville" class="champ-formulaire" placeholder="Paris" required>
                        </div>
                        <div class="groupe-formulaire">
                            <label class="etiquette-formulaire">Code postal</label>
                            <input type="text" name="code_postal" class="champ-formulaire" placeholder="75001" required>
                        </div>
                    </div>
                    <h2 style="font-family:var(--police-display);font-size:1.1rem;margin:24px 0 16px;">Informations de carte</h2>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Numéro de carte</label>
                        <input type="text" class="champ-formulaire" placeholder="•••• •••• •••• ••••" maxlength="19">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="groupe-formulaire">
                            <label class="etiquette-formulaire">Expiration</label>
                            <input type="text" class="champ-formulaire" placeholder="MM/AA" maxlength="5">
                        </div>
                        <div class="groupe-formulaire">
                            <label class="etiquette-formulaire">CVV</label>
                            <input type="text" class="champ-formulaire" placeholder="•••" maxlength="3">
                        </div>
                    </div>
                    <button type="submit" class="bouton-primaire" style="width:100%;justify-content:center;margin-top:8px;">
                        &#128274; Confirmer la commande — <?= number_format($totalPanier, 2, ',', ' ') ?> €
                    </button>
                    <p class="corps-md" style="text-align:center;margin-top:12px;font-size:0.72rem;color:var(--couleur-ivoire-sombre);">Paiement simulé — aucune donnée réelle transmise</p>
                </form>
            </div>
            <div style="background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);padding:24px;border-radius:6px;height:fit-content;">
                <h3 style="font-family:var(--police-display);font-size:1rem;margin-bottom:16px;">Récapitulatif</h3>
                <?php foreach ($contenuPanier as $el): ?>
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:0.85rem;">
                    <span style="color:var(--couleur-ivoire-sombre);"><?= htmlspecialchars($el['nom_produit']) ?> ×<?= $el['quantite'] ?></span>
                    <span><?= number_format($el['prix'] * $el['quantite'], 2, ',', ' ') ?> €</span>
                </div>
                <?php endforeach; ?>
                <div style="border-top:1px solid var(--couleur-bordure);margin-top:16px;padding-top:16px;display:flex;justify-content:space-between;">
                    <span style="font-family:var(--police-display);">Total</span>
                    <span style="font-family:var(--police-display);color:var(--couleur-or);"><?= number_format($totalPanier, 2, ',', ' ') ?> €</span>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body></html>
