<?php
session_start();
if (!isset($_SESSION['id_admin'])) { header('Location: connexion_admin.php'); exit; }
require_once '../configuration/connexion_base_donnees.php';
require_once '../classes/Utilisateur.php';

$classeUtilisateur = new Utilisateur($connexion);
$utilisateurs = $classeUtilisateur->obtenirTous();

// Recherche
$recherche = trim($_GET['q'] ?? '');
if ($recherche) {
    $req = $connexion->prepare("
        SELECT u.*, COUNT(c.id_commande) as nb_commandes, COALESCE(SUM(c.montant_total),0) as total_depense
        FROM utilisateurs u
        LEFT JOIN commandes c ON u.id_utilisateur = c.id_utilisateur
        WHERE u.nom LIKE :q OR u.prenom LIKE :q OR u.email LIKE :q
        GROUP BY u.id_utilisateur
        ORDER BY u.date_creation DESC
    ");
    $req->execute([':q' => "%$recherche%"]);
} else {
    $req = $connexion->prepare("
        SELECT u.*, COUNT(c.id_commande) as nb_commandes, COALESCE(SUM(c.montant_total),0) as total_depense
        FROM utilisateurs u
        LEFT JOIN commandes c ON u.id_utilisateur = c.id_utilisateur
        GROUP BY u.id_utilisateur
        ORDER BY u.date_creation DESC
    ");
    $req->execute();
}
$utilisateurs = $req->fetchAll();
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Utilisateurs — Administration L'ESSENCE</title>
<link rel="stylesheet" href="../css/style-principal.css">
<style>
    .layout-admin{display:grid;grid-template-columns:220px 1fr;min-height:100vh;}
    .barre-lat{background:var(--couleur-fond-surface);border-right:1px solid var(--couleur-bordure);}
    .entete-lat{padding:24px;border-bottom:1px solid var(--couleur-bordure);}
    .menu-lat{list-style:none;padding:12px 0;}
    .menu-lat a{display:block;padding:11px 24px;font-size:0.68rem;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);transition:0.2s;text-decoration:none;border-left:2px solid transparent;}
    .menu-lat a:hover,.menu-lat a.actif{color:var(--couleur-or);border-left-color:var(--couleur-or);background:rgba(201,168,76,0.04);}
    .zone-admin{padding:40px;}
    .tableau-adm{width:100%;border-collapse:collapse;background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);border-radius:6px;overflow:hidden;}
    .tableau-adm th,.tableau-adm td{padding:13px 16px;border-bottom:1px solid var(--couleur-bordure);font-size:0.85rem;text-align:left;}
    .tableau-adm th{font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);background:var(--couleur-fond-surface);}
    .badge-commandes{display:inline-block;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;background:rgba(201,168,76,0.12);color:var(--couleur-or);}
</style>
</head><body>
<div class="layout-admin">
    <aside class="barre-lat">
        <div class="entete-lat"><p style="font-family:var(--police-display);font-size:1rem;color:var(--couleur-or);font-weight:600;letter-spacing:0.1em;">L'ESSENCE</p></div>
        <ul class="menu-lat">
            <li><a href="tableau_bord.php">Tableau de bord</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="commandes.php">Commandes</a></li>
            <li><a href="utilisateurs.php" class="actif">Utilisateurs</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li style="border-top:1px solid var(--couleur-bordure);margin-top:12px;padding-top:4px;"><a href="deconnexion_admin.php" style="color:#cf6679!important;">Déconnexion</a></li>
        </ul>
    </aside>
    <main class="zone-admin">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
            <h1 style="font-family:var(--police-display);font-size:1.8rem;font-weight:400;color:var(--couleur-ivoire);">
                Utilisateurs <span style="font-size:1rem;color:var(--couleur-ivoire-sombre);margin-left:8px;">(<?= count($utilisateurs) ?>)</span>
            </h1>
            <form method="GET" style="display:flex;gap:10px;">
                <input type="text" name="q" value="<?= htmlspecialchars($recherche) ?>"
                       class="champ-formulaire" placeholder="Rechercher..." style="padding:8px 14px;font-size:0.82rem;width:220px;">
                <button type="submit" class="bouton-primaire" style="padding:8px 18px;font-size:0.72rem;">Chercher</button>
                <?php if ($recherche): ?><a href="utilisateurs.php" class="bouton-secondaire" style="padding:8px 14px;font-size:0.72rem;">✕</a><?php endif; ?>
            </form>
        </div>

        <table class="tableau-adm">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>E-mail</th>
                    <th>Téléphone</th>
                    <th>Commandes</th>
                    <th>Total dépensé</th>
                    <th>Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($utilisateurs)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--couleur-ivoire-sombre);padding:40px;">Aucun résultat.</td></tr>
                <?php else: ?>
                <?php foreach ($utilisateurs as $u): ?>
                <tr>
                    <td style="color:var(--couleur-ivoire);"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                    <td style="color:var(--couleur-or);font-size:0.8rem;"><?= htmlspecialchars($u['email']) ?></td>
                    <td style="color:var(--couleur-ivoire-sombre);font-size:0.8rem;"><?= htmlspecialchars($u['telephone'] ?? '—') ?></td>
                    <td><span class="badge-commandes"><?= $u['nb_commandes'] ?> commande<?= $u['nb_commandes'] > 1 ? 's' : '' ?></span></td>
                    <td style="color:var(--couleur-ivoire);"><?= number_format($u['total_depense'], 2, ',', ' ') ?> €</td>
                    <td style="color:var(--couleur-ivoire-sombre);font-size:0.8rem;"><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body></html>
