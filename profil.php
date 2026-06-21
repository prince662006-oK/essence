<?php
session_start();
if (!isset($_SESSION['id_utilisateur'])) { header('Location: connexion.php'); exit; }
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Utilisateur.php';
require_once 'classes/Commande.php';
$classeUtilisateur = new Utilisateur($connexion);
$classeCommande = new Commande($connexion);
$utilisateur = $classeUtilisateur->obtenirParId((int)$_SESSION['id_utilisateur']);
$commandes = $classeCommande->obtenirParUtilisateur((int)$_SESSION['id_utilisateur']);
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mon Profil — L'ESSENCE</title>
<link rel="manifest" href="pwa/manifest.json">
<link rel="stylesheet" href="css/style-principal.css">
</head><body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section">
        <span class="etiquette">Mon espace</span>
        <h1 class="titre-affiche" style="margin:8px 0 40px;">
            <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>
        </h1>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:40px;">
            <div style="background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);padding:28px;border-radius:6px;">
                <h2 class="titre-section" style="margin-bottom:20px;font-size:1.1rem;">Mes informations</h2>
                <p class="corps-md" style="margin-bottom:8px;color:var(--couleur-ivoire-sombre);">E-mail</p>
                <p class="corps-md" style="margin-bottom:16px;"><?= htmlspecialchars($utilisateur['email']) ?></p>
                <p class="corps-md" style="margin-bottom:8px;color:var(--couleur-ivoire-sombre);">Téléphone</p>
                <p class="corps-md" style="margin-bottom:16px;"><?= htmlspecialchars($utilisateur['telephone'] ?? '—') ?></p>
                <p class="corps-md" style="margin-bottom:8px;color:var(--couleur-ivoire-sombre);">Membre depuis</p>
                <p class="corps-md"><?= date('d/m/Y', strtotime($utilisateur['date_creation'])) ?></p>
                <hr style="border:none;border-top:1px solid var(--couleur-bordure);margin:20px 0;">
                <a href="deconnexion.php" style="color:#cf6679;font-size:0.8rem;">Se déconnecter</a>
            </div>
            <div>
                <h2 class="titre-section" style="margin-bottom:20px;font-size:1.1rem;">Mes commandes</h2>
                <?php if (empty($commandes)): ?>
                    <p class="corps-md" style="color:var(--couleur-ivoire-sombre);">Vous n'avez pas encore de commande.</p>
                    <a href="catalogue.php" class="bouton-primaire" style="display:inline-flex;margin-top:16px;">Découvrir nos parfums</a>
                <?php else: ?>
                <?php foreach ($commandes as $cmd): ?>
                <div style="background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);padding:20px 24px;border-radius:6px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <p class="etiquette" style="font-size:0.6rem;">Commande #<?= str_pad($cmd['id_commande'], 5, '0', STR_PAD_LEFT) ?></p>
                        <p class="corps-md"><?= date('d/m/Y', strtotime($cmd['date_commande'])) ?></p>
                    </div>
                    <div style="text-align:right;">
                        <p style="color:var(--couleur-or);font-family:var(--police-display);font-size:1.1rem;"><?= number_format($cmd['montant_total'], 2, ',', ' ') ?> €</p>
                        <p class="corps-md" style="font-size:0.8rem;color:var(--couleur-ivoire-sombre);"><?= htmlspecialchars($cmd['statut_commande']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body></html>
