<?php
session_start();
if (!isset($_SESSION['id_admin'])) { header('Location: connexion_admin.php'); exit; }
require_once '../configuration/connexion_base_donnees.php';
require_once '../classes/Produit.php';

$classeProduit = new Produit($connexion);
$action = $_GET['action'] ?? 'liste';
$message = '';

// Suppression
if ($action === 'supprimer' && isset($_GET['id'])) {
    $classeProduit->supprimer((int)$_GET['id']);
    header('Location: produits.php?message=supprime');
    exit;
}

// Ajout / Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donnees = [
        ':id_marque'        => (int)$_POST['id_marque'],
        ':id_categorie'     => (int)$_POST['id_categorie'],
        ':nom_produit'      => htmlspecialchars($_POST['nom_produit']),
        ':description'      => htmlspecialchars($_POST['description']),
        ':prix'             => (float)$_POST['prix'],
        ':ancien_prix'      => !empty($_POST['ancien_prix']) ? (float)$_POST['ancien_prix'] : null,
        ':quantite_stock'   => (int)$_POST['quantite_stock'],
        ':contenance'       => htmlspecialchars($_POST['contenance']),
        ':image_principale' => htmlspecialchars($_POST['image_principale_existante'] ?? ''),
    ];

    if (!empty($_FILES['image_principale']['name']) && $_FILES['image_principale']['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($_FILES['image_principale']['name'], PATHINFO_EXTENSION));
        $nomFichier = uniqid('produit_', true) . '.' . $extension;
        $dossierDestination = __DIR__ . '/../images/produits/';
        if (move_uploaded_file($_FILES['image_principale']['tmp_name'], $dossierDestination . $nomFichier)) {
            $donnees[':image_principale'] = 'images/produits/' . $nomFichier;
        }
    }

    if (!empty($_POST['id_produit'])) {
        $classeProduit->modifier((int)$_POST['id_produit'], $donnees);
        $message = 'Produit modifié avec succès.';
    } else {
        $classeProduit->ajouter($donnees);
        $message = 'Produit ajouté avec succès.';
    }
    header('Location: produits.php?message=' . urlencode($message));
    exit;
}

$produitAModifier = null;
if ($action === 'modifier' && isset($_GET['id'])) {
    $produitAModifier = $classeProduit->obtenirParId((int)$_GET['id']);
}

$produits = $classeProduit->obtenirTous();
$message = $_GET['message'] ?? '';

// Marques et catégories pour le formulaire
$reqMarques = $connexion->prepare("SELECT * FROM marques ORDER BY nom_marque");
$reqMarques->execute(); $marques = $reqMarques->fetchAll();
$reqCategories = $connexion->prepare("SELECT * FROM categories");
$reqCategories->execute(); $categories = $reqCategories->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits — Administration L'ESSENCE</title>
    <link rel="stylesheet" href="../css/style-principal.css">
    <style>
        .layout-admin{display:grid;grid-template-columns:220px 1fr;min-height:100vh;}
        .barre-lat{background:var(--couleur-fond-surface);border-right:1px solid var(--couleur-bordure);}
        .entete-lat{padding:24px;border-bottom:1px solid var(--couleur-bordure);}
        .menu-lat{list-style:none;padding:12px 0;}
        .menu-lat a{display:block;padding:11px 24px;font-size:0.68rem;font-weight:600;letter-spacing:0.15em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);transition:0.2s;text-decoration:none;border-left:2px solid transparent;}
        .menu-lat a:hover,.menu-lat a.actif{color:var(--couleur-or);border-left-color:var(--couleur-or);background:rgba(201,168,76,0.04);}
        .zone-admin{padding:40px;}
        .tableau-adm{width:100%;border-collapse:collapse;background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);border-radius:6px;}
        .tableau-adm th,.tableau-adm td{padding:13px 16px;border-bottom:1px solid var(--couleur-bordure);font-size:0.85rem;text-align:left;}
        .tableau-adm th{font-size:0.62rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--couleur-ivoire-sombre);background:var(--couleur-fond-surface);}
        select.champ-formulaire{appearance:none;background:var(--couleur-fond-carte);}
    </style>
</head>
<body>
<div class="layout-admin">
    <aside class="barre-lat">
        <div class="entete-lat"><p style="font-family:var(--police-display);font-size:1rem;color:var(--couleur-or);font-weight:600;letter-spacing:0.1em;">L'ESSENCE</p></div>
        <ul class="menu-lat">
            <li><a href="tableau_bord.php">Tableau de bord</a></li>
            <li><a href="produits.php" class="actif">Produits</a></li>
            <li><a href="commandes.php">Commandes</a></li>
            <li><a href="utilisateurs.php">Utilisateurs</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li style="border-top:1px solid var(--couleur-bordure);margin-top:12px;padding-top:4px;"><a href="deconnexion_admin.php" style="color:#cf6679!important;">Déconnexion</a></li>
        </ul>
    </aside>
    <main class="zone-admin">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
            <h1 style="font-family:var(--police-display);font-size:1.8rem;font-weight:400;color:var(--couleur-ivoire);">Gestion des produits</h1>
            <a href="produits.php?action=ajouter" class="bouton-primaire">+ Ajouter un produit</a>
        </div>

        <?php if ($message): ?><div class="alerte alerte-succes"><?= htmlspecialchars($message) ?></div><?php endif; ?>

        <!-- Formulaire d'ajout/modification (masqué par défaut) -->
        <div id="formulaire-produit" style="display:<?= ($action === 'ajouter' || $produitAModifier) ? 'block' : 'none' ?>;background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure-forte);border-radius:6px;padding:32px;margin-bottom:32px;">
            <h2 style="font-family:var(--police-display);font-size:1.3rem;margin-bottom:24px;color:var(--couleur-ivoire);"><?= $produitAModifier ? 'Modifier le produit' : 'Nouveau produit' ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_produit" value="<?= $produitAModifier['id_produit'] ?? '' ?>">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Nom du parfum</label>
                        <input type="text" name="nom_produit" class="champ-formulaire" value="<?= htmlspecialchars($produitAModifier['nom_produit'] ?? '') ?>" required>
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Marque</label>
                        <select name="id_marque" class="champ-formulaire" required>
                            <?php foreach ($marques as $m): ?><option value="<?= $m['id_marque'] ?>" <?= ($produitAModifier && (int)$produitAModifier['id_marque'] === (int)$m['id_marque']) ? 'selected' : '' ?>><?= htmlspecialchars($m['nom_marque']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Catégorie</label>
                        <select name="id_categorie" class="champ-formulaire" required>
                            <?php foreach ($categories as $c): ?><option value="<?= $c['id_categorie'] ?>" <?= ($produitAModifier && (int)$produitAModifier['id_categorie'] === (int)$c['id_categorie']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nom_categorie']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Contenance</label>
                        <input type="text" name="contenance" class="champ-formulaire" placeholder="100 ml" value="<?= htmlspecialchars($produitAModifier['contenance'] ?? '') ?>">
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Prix (€)</label>
                        <input type="number" name="prix" step="0.01" class="champ-formulaire" value="<?= htmlspecialchars($produitAModifier['prix'] ?? '') ?>" required>
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Ancien prix (€)</label>
                        <input type="number" name="ancien_prix" step="0.01" class="champ-formulaire" value="<?= htmlspecialchars($produitAModifier['ancien_prix'] ?? '') ?>">
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Stock</label>
                        <input type="number" name="quantite_stock" class="champ-formulaire" value="<?= htmlspecialchars($produitAModifier['quantite_stock'] ?? '0') ?>" required>
                    </div>
                    <div class="groupe-formulaire">
                        <label class="etiquette-formulaire">Image principale</label>
                        <?php if (!empty($produitAModifier['image_principale'])): ?>
                            <img src="../<?= htmlspecialchars($produitAModifier['image_principale']) ?>" alt="" style="height:40px;display:block;margin-bottom:6px;border-radius:4px;">
                        <?php endif; ?>
                        <input type="file" name="image_principale" class="champ-formulaire" accept="image/*">
                        <input type="hidden" name="image_principale_existante" value="<?= htmlspecialchars($produitAModifier['image_principale'] ?? '') ?>">
                    </div>
                </div>
                <div class="groupe-formulaire">
                    <label class="etiquette-formulaire">Description</label>
                    <textarea name="description" class="champ-formulaire" rows="4" style="resize:vertical;"><?= htmlspecialchars($produitAModifier['description'] ?? '') ?></textarea>
                </div>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="bouton-primaire">Enregistrer le produit</button>
                    <button type="button" onclick="document.getElementById('formulaire-produit').style.display='none'" class="bouton-secondaire">Annuler</button>
                </div>
            </form>
        </div>

        <!-- Tableau produits -->
        <table class="tableau-adm">
            <thead><tr><th>Produit</th><th>Marque</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($produits as $p): ?>
                <tr>
                    <td style="color:var(--couleur-ivoire);"><?= htmlspecialchars($p['nom_produit']) ?></td>
                    <td style="color:var(--couleur-or);font-size:0.78rem;"><?= htmlspecialchars($p['nom_marque'] ?? '') ?></td>
                    <td style="font-size:0.78rem;"><?= htmlspecialchars($p['nom_categorie'] ?? '') ?></td>
                    <td><?= number_format($p['prix'], 2, ',', ' ') ?> €</td>
                    <td style="color:<?= $p['quantite_stock'] > 10 ? '#7fb890' : ($p['quantite_stock'] > 0 ? '#e9c349' : '#cf6679') ?>;">
                        <?= $p['quantite_stock'] ?>
                    </td>
                    <td style="display:flex;gap:12px;">
                        <a href="../produit.php?id=<?= $p['id_produit'] ?>" style="color:var(--couleur-or);font-size:0.78rem;" target="_blank">Voir</a>
                        <a href="produits.php?action=modifier&id=<?= $p['id_produit'] ?>" style="color:var(--couleur-ivoire);font-size:0.78rem;">Modifier</a>
                        <a href="produits.php?action=supprimer&id=<?= $p['id_produit'] ?>"
                           onclick="return confirm('Supprimer ce produit ?')"
                           style="color:#cf6679;font-size:0.78rem;">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
