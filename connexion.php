<?php
session_start();
if (isset($_SESSION['id_utilisateur'])) { header('Location: profil.php'); exit; }
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Utilisateur.php';

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $motDePasse = $_POST['mot_de_passe'] ?? '';
    $classeUtilisateur = new Utilisateur($connexion);
    $utilisateur = $classeUtilisateur->connecter($email, $motDePasse);
    if ($utilisateur) {
        $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
        $_SESSION['nom_utilisateur'] = $utilisateur['prenom'] . ' ' . $utilisateur['nom'];
        header('Location: accueil.php');
        exit;
    } else {
        $erreur = 'Identifiants incorrects. Veuillez réessayer.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — L'ESSENCE</title>
    <link rel="manifest" href="pwa/manifest.json">
    <link rel="stylesheet" href="css/style-principal.css">
</head>
<body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page" style="display:flex;align-items:center;justify-content:center;">
    <div style="width:100%;max-width:440px;padding:var(--marge-mobile);">
        <div style="text-align:center;margin-bottom:48px;">
            <span class="etiquette">Bienvenue</span>
            <h1 class="titre-affiche" style="margin-top:8px;">Connexion</h1>
        </div>
        <?php if ($erreur): ?>
            <div class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="POST" action="connexion.php">
            <div class="groupe-formulaire">
                <label for="email" class="etiquette-formulaire">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="champ-formulaire"
                       placeholder="votre@email.fr" required autocomplete="email">
            </div>
            <div class="groupe-formulaire">
                <label for="mot_de_passe" class="etiquette-formulaire">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="champ-formulaire"
                       placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="bouton-primaire" style="width:100%;justify-content:center;margin-top:8px;">
                Se connecter
            </button>
        </form>
        <p class="corps-md" style="text-align:center;margin-top:24px;color:var(--couleur-ivoire-sombre);">
            Pas encore de compte ? <a href="inscription.php" class="bouton-fantome">Créer un compte</a>
        </p>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body>
</html>
