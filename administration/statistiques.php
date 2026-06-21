<?php
session_start();
if (!isset($_SESSION['id_admin'])) { header('Location: connexion_admin.php'); exit; }
require_once '../configuration/connexion_base_donnees.php';

// CA par mois (12 derniers mois)
$reqCA = $connexion->prepare("
    SELECT DATE_FORMAT(date_commande, '%Y-%m') as mois,
           COUNT(*) as nb_commandes,
           SUM(montant_total) as chiffre_affaires
    FROM commandes
    WHERE date_commande >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    AND statut_commande != 'Annulée'
    GROUP BY mois
    ORDER BY mois ASC
");
$reqCA->execute();
$donneesMensuelles = $reqCA->fetchAll();

// Top catégories
$reqCats = $connexion->prepare("
    SELECT cat.nom_categorie, COUNT(dc.id_detail) as nb_ventes, SUM(dc.prix_unitaire * dc.quantite) as ca
    FROM details_commandes dc
    JOIN produits p ON dc.id_produit = p.id_produit
    JOIN categories cat ON p.id_categorie = cat.id_categorie
    GROUP BY cat.id_categorie
    ORDER BY ca DESC
");
$reqCats->execute();
$statsCategories = $reqCats->fetchAll();

// Top marques
$reqMarques = $connexion->prepare("
    SELECT m.nom_marque, SUM(dc.quantite) as unites, SUM(dc.prix_unitaire * dc.quantite) as ca
    FROM details_commandes dc
    JOIN produits p ON dc.id_produit = p.id_produit
    JOIN marques m ON p.id_marque = m.id_marque
    GROUP BY m.id_marque
    ORDER BY ca DESC
    LIMIT 6
");
$reqMarques->execute();
$statsMarques = $reqMarques->fetchAll();

// Récap global
$reqGlobal = $connexion->prepare("
    SELECT
        COUNT(*) as total_commandes,
        SUM(CASE WHEN statut_commande != 'Annulée' THEN montant_total ELSE 0 END) as ca_total,
        AVG(CASE WHEN statut_commande != 'Annulée' THEN montant_total ELSE NULL END) as panier_moyen
    FROM commandes
");
$reqGlobal->execute();
$global = $reqGlobal->fetch();

$maxCA = max(array_column($donneesMensuelles, 'chiffre_affaires') ?: [1]);
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Statistiques — Administration L'ESSENCE</title>
<link rel="stylesheet" href="../css/style-principal.css">
<style>
    .layout-admin{display:grid;grid-template-columns:220px 1fr;min-height:100vh;}
    .barre-lat{background:var(--couleur-fond-surface);border-right:1px solid var(--couleur-bordure);}
    .entete-lat{padding:24px;border-bottom:1px solid var(--couleur-bordure);}
    .menu-lat{list-style:none;padding:12px 0;}
    .menu-lat a{display:block;padding:11px 24px;font-size:0.68rem;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);transition:0.2s;text-decoration:none;border-left:2px solid transparent;}
    .menu-lat a:hover,.menu-lat a.actif{color:var(--couleur-or);border-left-color:var(--couleur-or);background:rgba(201,168,76,0.04);}
    .zone-admin{padding:40px;}
    .stat-card{background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);padding:28px;border-radius:6px;}
    .graphe-barre{display:flex;align-items:flex-end;gap:8px;height:180px;padding-top:16px;}
    .barre{flex:1;background:linear-gradient(to top,var(--couleur-or),rgba(201,168,76,0.4));border-radius:3px 3px 0 0;position:relative;cursor:default;transition:opacity 0.2s;}
    .barre:hover{opacity:0.8;}
    .barre-label{font-size:0.55rem;color:var(--couleur-ivoire-sombre);text-align:center;margin-top:6px;letter-spacing:0.04em;}
    .barre-tooltip{position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--couleur-fond-surface);border:1px solid var(--couleur-bordure-forte);padding:4px 8px;font-size:0.65rem;white-space:nowrap;border-radius:3px;color:var(--couleur-ivoire);opacity:0;transition:0.2s;pointer-events:none;}
    .barre:hover .barre-tooltip{opacity:1;}
    .jauge-barre{height:8px;background:linear-gradient(to right,var(--couleur-or),rgba(201,168,76,0.3));border-radius:4px;}
</style>
</head><body>
<div class="layout-admin">
    <aside class="barre-lat">
        <div class="entete-lat"><p style="font-family:var(--police-display);font-size:1rem;color:var(--couleur-or);font-weight:600;letter-spacing:0.1em;">L'ESSENCE</p></div>
        <ul class="menu-lat">
            <li><a href="tableau_bord.php">Tableau de bord</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="commandes.php">Commandes</a></li>
            <li><a href="utilisateurs.php">Utilisateurs</a></li>
            <li><a href="statistiques.php" class="actif">Statistiques</a></li>
            <li style="border-top:1px solid var(--couleur-bordure);margin-top:12px;padding-top:4px;"><a href="deconnexion_admin.php" style="color:#cf6679!important;">Déconnexion</a></li>
        </ul>
    </aside>
    <main class="zone-admin">
        <h1 style="font-family:var(--police-display);font-size:1.8rem;font-weight:400;color:var(--couleur-ivoire);margin-bottom:32px;">Statistiques</h1>

        <!-- KPI globaux -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;">
            <div class="stat-card">
                <p style="font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:10px;">Chiffre d'affaires total</p>
                <p style="font-family:var(--police-display);font-size:2rem;color:var(--couleur-or);"><?= number_format($global['ca_total'] ?? 0, 0, ',', ' ') ?> €</p>
            </div>
            <div class="stat-card">
                <p style="font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:10px;">Commandes passées</p>
                <p style="font-family:var(--police-display);font-size:2rem;color:var(--couleur-ivoire);"><?= $global['total_commandes'] ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <p style="font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:10px;">Panier moyen</p>
                <p style="font-family:var(--police-display);font-size:2rem;color:var(--couleur-ivoire);"><?= number_format($global['panier_moyen'] ?? 0, 2, ',', ' ') ?> €</p>
            </div>
        </div>

        <!-- Graphe CA mensuel -->
        <div class="stat-card" style="margin-bottom:32px;">
            <h2 style="font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:4px;">CA mensuel</h2>
            <p style="font-size:0.78rem;color:var(--couleur-ivoire-sombre);margin-bottom:16px;">12 derniers mois — commandes non annulées</p>
            <?php if (empty($donneesMensuelles)): ?>
                <p style="color:var(--couleur-ivoire-sombre);font-size:0.85rem;">Aucune donnée disponible.</p>
            <?php else: ?>
            <div class="graphe-barre">
                <?php foreach ($donneesMensuelles as $mois):
                    $hauteur = $maxCA > 0 ? round(($mois['chiffre_affaires'] / $maxCA) * 100) : 0;
                    $libelle = date('M', mktime(0,0,0,(int)substr($mois['mois'],5,2),1));
                ?>
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                    <div class="barre" style="height:<?= $hauteur ?>%;width:100%;">
                        <span class="barre-tooltip"><?= number_format($mois['chiffre_affaires'], 0, ',', ' ') ?> €<br><?= $mois['nb_commandes'] ?> cmd</span>
                    </div>
                    <p class="barre-label"><?= $libelle ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <!-- Top catégories -->
            <div class="stat-card">
                <h2 style="font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:20px;">CA par catégorie</h2>
                <?php
                $totalCat = array_sum(array_column($statsCategories, 'ca')) ?: 1;
                foreach ($statsCategories as $cat):
                    $pct = round(($cat['ca'] / $totalCat) * 100);
                ?>
                <div style="margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="font-size:0.82rem;color:var(--couleur-ivoire);"><?= htmlspecialchars($cat['nom_categorie']) ?></span>
                        <span style="font-size:0.8rem;color:var(--couleur-or);"><?= $pct ?>%</span>
                    </div>
                    <div style="background:var(--couleur-bordure);border-radius:4px;overflow:hidden;">
                        <div class="jauge-barre" style="width:<?= $pct ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($statsCategories)): ?><p style="color:var(--couleur-ivoire-sombre);font-size:0.82rem;">Aucune donnée.</p><?php endif; ?>
            </div>

            <!-- Top marques -->
            <div class="stat-card">
                <h2 style="font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);margin-bottom:20px;">Performance par marque</h2>
                <?php if (empty($statsMarques)): ?>
                    <p style="color:var(--couleur-ivoire-sombre);font-size:0.82rem;">Aucune donnée.</p>
                <?php else:
                    $maxMarque = max(array_column($statsMarques, 'ca')) ?: 1;
                    foreach ($statsMarques as $marque):
                        $pct = round(($marque['ca'] / $maxMarque) * 100);
                ?>
                <div style="margin-bottom:14px;display:flex;align-items:center;gap:14px;">
                    <span style="font-size:0.78rem;color:var(--couleur-ivoire);min-width:80px;"><?= htmlspecialchars($marque['nom_marque']) ?></span>
                    <div style="flex:1;background:var(--couleur-bordure);border-radius:4px;overflow:hidden;">
                        <div class="jauge-barre" style="width:<?= $pct ?>%;"></div>
                    </div>
                    <span style="font-size:0.72rem;color:var(--couleur-ivoire-sombre);min-width:55px;text-align:right;"><?= number_format($marque['ca'], 0, ',', ' ') ?> €</span>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </main>
</div>
</body></html>
