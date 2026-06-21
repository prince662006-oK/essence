<?php
session_start();
if (!isset($_SESSION['id_admin'])) { header('Location: connexion_admin.php'); exit; }
require_once '../configuration/connexion_base_donnees.php';
require_once '../classes/Commande.php';
$classeCommande = new Commande($connexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'], $_POST['statut'])) {
    $classeCommande->changerStatut((int)$_POST['id_commande'], $_POST['statut']);
    header('Location: commandes.php');
    exit;
}
$commandes = $classeCommande->obtenirToutes();
$statutsCouleurs = ['En attente'=>'#e9c349','Confirmée'=>'var(--couleur-or)','Expédiée'=>'#7fb890','Livrée'=>'#5a9970','Annulée'=>'#cf6679'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes — Administration L'ESSENCE</title>
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
        select.champ-formulaire{appearance:none;background:var(--couleur-fond-carte);padding:6px 10px;font-size:0.75rem;width:auto;}
    </style>
</head>
<body>
<div class="layout-admin">
    <aside class="barre-lat">
        <div class="entete-lat"><p style="font-family:var(--police-display);font-size:1rem;color:var(--couleur-or);font-weight:600;letter-spacing:0.1em;">L'ESSENCE</p></div>
        <ul class="menu-lat">
            <li><a href="tableau_bord.php">Tableau de bord</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="commandes.php" class="actif">Commandes</a></li>
            <li><a href="utilisateurs.php">Utilisateurs</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li style="border-top:1px solid var(--couleur-bordure);margin-top:12px;padding-top:4px;"><a href="deconnexion_admin.php" style="color:#cf6679!important;">Déconnexion</a></li>
        </ul>
    </aside>
    <main class="zone-admin">
        <h1 style="font-family:var(--police-display);font-size:1.8rem;font-weight:400;color:var(--couleur-ivoire);margin-bottom:32px;">Gestion des commandes</h1>
        <table class="tableau-adm">
            <thead><tr><th>#</th><th>Client</th><th>Montant</th><th>Statut</th><th>Date</th><th>Modifier</th></tr></thead>
            <tbody>
                <?php foreach ($commandes as $c): ?>
                <tr>
                    <td style="color:var(--couleur-or);">#<?= str_pad($c['id_commande'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td style="color:var(--couleur-ivoire);"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                    <td><?= number_format($c['montant_total'], 2, ',', ' ') ?> €</td>
                    <td><span style="color:<?= $statutsCouleurs[$c['statut_commande']] ?? '#fff' ?>;font-size:0.75rem;font-weight:600;"><?= htmlspecialchars($c['statut_commande']) ?></span></td>
                    <td style="color:var(--couleur-ivoire-sombre);font-size:0.8rem;"><?= date('d/m/Y', strtotime($c['date_commande'])) ?></td>
                    <td>
                        <form method="POST" style="display:flex;gap:8px;align-items:center;">
                            <input type="hidden" name="id_commande" value="<?= $c['id_commande'] ?>">
                            <select name="statut" class="champ-formulaire">
                                <?php foreach (array_keys($statutsCouleurs) as $s): ?>
                                    <option value="<?= $s ?>" <?= $c['statut_commande'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="bouton-primaire" style="padding:6px 14px;font-size:0.65rem;">OK</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
