<?php
session_start();
if (isset($_SESSION['id_admin'])) { header('Location: tableau_bord.php'); exit; }
require_once '../configuration/connexion_base_donnees.php';
require_once '../classes/Administrateur.php';

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classeAdmin = new Administrateur($connexion);
    $admin = $classeAdmin->connecter(
        filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        $_POST['mot_de_passe'] ?? ''
    );
    if ($admin) {
        $_SESSION['id_admin'] = $admin['id_administrateur'];
        $_SESSION['prenom_admin'] = $admin['prenom'];
        $_SESSION['role_admin'] = $admin['role'];
        header('Location: tableau_bord.php');
        exit;
    }
    $erreur = 'Identifiants administrateur incorrects.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — L'ESSENCE</title>
    <link rel="stylesheet" href="../css/style-principal.css">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <div style="width:100%;max-width:400px;padding:40px;">
        <a href="../accueil.php" style="font-family:var(--police-display);font-size:1.5rem;color:var(--couleur-or);font-weight:600;letter-spacing:0.1em;display:block;text-align:center;margin-bottom:8px;text-decoration:none;">L'ESSENCE</a>
        <p style="text-align:center;color:var(--couleur-ivoire-sombre);font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;margin-bottom:40px;">Espace Administration</p>
        <?php if ($erreur): ?><div class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
        <form method="POST">
            <div class="groupe-formulaire">
                <label class="etiquette-formulaire">E-mail administrateur</label>
                <input type="email" name="email" class="champ-formulaire" required>
            </div>
            <div class="groupe-formulaire">
                <label class="etiquette-formulaire">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="champ-formulaire" required>
            </div>
            <button type="submit" class="bouton-primaire" style="width:100%;justify-content:center;">Accéder au tableau de bord</button>
        </form>
    </div>
</body>
</html>
