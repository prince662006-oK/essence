<?php
session_start();
if (!isset($_SESSION['id_admin'])) { header('Location: connexion_admin.php'); exit; }
require_once '../configuration/connexion_base_donnees.php';
require_once '../classes/Administrateur.php';
$classeAdmin = new Administrateur($connexion);
$stats = $classeAdmin->obtenirStatistiques();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — L'ESSENCE Administration</title>
    <link rel="stylesheet" href="../css/style-principal.css">
    <style>
        .layout-admin{display:grid;grid-template-columns:220px 1fr;min-height:100vh;}
        .barre-lat{background:var(--couleur-fond-surface);border-right:1px solid var(--couleur-bordure);}
        .entete-lat{padding:24px;border-bottom:1px solid var(--couleur-bordure);}
        .menu-lat{list-style:none;padding:12px 0;}
        .menu-lat a{display:block;padding:11px 24px;font-size:0.68rem;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);transition:0.2s;text-decoration:none;border-left:2px solid transparent;}
        .menu-lat a:hover,.menu-lat a.actif{color:var(--couleur-or);border-left-color:var(--couleur-or);background:rgba(201,168,76,0.04);}
        .zone-admin{padding:40px;}
        .grille-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:36px;}
        .stat-carte{background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);border-radius:6px;padding:24px;}
        .stat-label{font-size:0.6rem;font-weight:600;letter-spacing:0.18em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:10px;}
        .stat-val{font-family:var(--police-display);font-size:2rem;color:var(--couleur-or);}
        .tableau-adm{width:100%;border-collapse:collapse;}
        .tableau-adm th,.tableau-adm td{padding:13px 16px;border-bottom:1px solid var(--couleur-bordure);font-size:0.85rem;text-align:left;}
        .tableau-adm th{font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);background:var(--couleur-fond-surface);}
    </style>
</head>
<body>
<div class="layout-admin">
    <aside class="barre-lat">
        <div class="entete-lat">
            <p style="font-family:var(--police-display);font-size:1rem;color:var(--couleur-or);font-weight:600;letter-spacing:0.1em;">L'ESSENCE</p>
            <p style="font-size:0.58rem;color:var(--couleur-ivoire-sombre);margin-top:2px;letter-spacing:0.12em;text-transform:uppercase;">Administration</p>
        </div>
        <ul class="menu-lat">
            <li><a href="tableau_bord.php" class="actif">Tableau de bord</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="commandes.php">Commandes</a></li>
            <li><a href="utilisateurs.php">Utilisateurs</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li style="border-top:1px solid var(--couleur-bordure);margin-top:12px;padding-top:4px;">
                <a href="deconnexion_admin.php" style="color:#cf6679!important;">Déconnexion</a>
            </li>
        </ul>
    </aside>
    <main class="zone-admin">
        <h1 style="font-family:var(--police-display);font-size:1.8rem;font-weight:400;color:var(--couleur-ivoire);margin-bottom:32px;">
            Bonjour, <?= htmlspecialchars($_SESSION['prenom_admin'] ?? 'Admin') ?>
        </h1>
        <div class="grille-stats">
            <div class="stat-carte"><p class="stat-label">Commandes</p><p class="stat-val"><?= $stats['total_commandes'] ?></p></div>
            <div class="stat-carte"><p class="stat-label">Chiffre d'affaires</p><p class="stat-val"><?= number_format($stats['chiffre_affaires'], 0, ',', ' ') ?> €</p></div>
            <div class="stat-carte"><p class="stat-label">Clients</p><p class="stat-val"><?= $stats['total_utilisateurs'] ?></p></div>
            <div class="stat-carte"><p class="stat-label">Produits</p><p class="stat-val">6</p></div>
        </div>
        <?php if (!empty($stats['produits_populaires'])): ?>
        <h2 style="font-family:var(--police-display);font-size:1.2rem;font-weight:400;margin-bottom:16px;">Produits les plus vendus</h2>
        <table class="tableau-adm" style="background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);border-radius:6px;overflow:hidden;margin-bottom:32px;">
            <thead><tr><th>Produit</th><th>Unités vendues</th></tr></thead>
            <tbody>
                <?php foreach ($stats['produits_populaires'] as $p): ?>
                <tr><td style="color:var(--couleur-ivoire);"><?= htmlspecialchars($p['nom_produit']) ?></td><td style="color:var(--couleur-or);"><?= $p['total_vendu'] ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <div style="display:flex;gap:12px;">
            <a href="produits.php" class="bouton-primaire">Gérer les produits</a>
            <a href="commandes.php" class="bouton-secondaire">Voir les commandes</a>
            <a href="../accueil.php" class="bouton-secondaire">Voir le site</a>
        </div>
    </main>
</div>
</body>
</html>
