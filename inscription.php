<?php
session_start();
if (isset($_SESSION['id_utilisateur'])) { header('Location: profil.php'); exit; }
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Utilisateur.php';

$erreur = ''; $succes = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classeUtilisateur = new Utilisateur($connexion);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($classeUtilisateur->emailExiste($email)) {
        $erreur = 'Cette adresse e-mail est déjà utilisée.';
    } elseif ($_POST['mot_de_passe'] !== $_POST['confirmation_mot_de_passe']) {
        $erreur = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($_POST['mot_de_passe']) < 8) {
        $erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        $id = $classeUtilisateur->inscrire([
            'nom' => htmlspecialchars($_POST['nom']),
            'prenom' => htmlspecialchars($_POST['prenom']),
            'email' => $email,
            'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
            'mot_de_passe' => $_POST['mot_de_passe'],
        ]);
        if ($id) {
            $succes = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
        } else {
            $erreur = 'Une erreur est survenue lors de la création du compte.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — L'ESSENCE</title>
    <link rel="manifest" href="pwa/manifest.json">
    <link rel="stylesheet" href="css/style-principal.css">
</head>
<body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page" style="display:flex;align-items:center;justify-content:center;padding:40px 0;">
    <div style="width:100%;max-width:520px;padding:var(--marge-mobile);">
        <div style="text-align:center;margin-bottom:48px;">
            <span class="etiquette">Rejoindre le Cercle</span>
            <h1 class="titre-affiche" style="margin-top:8px;">Créer un compte</h1>
        </div>
        <?php if ($erreur): ?>
            <div class="alerte alerte-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <?php if ($succes): ?>
            <div class="alerte alerte-succes"><?= htmlspecialchars($succes) ?></div>
            <p style="text-align:center;margin-top:16px;"><a href="connexion.php" class="bouton-primaire">Se connecter</a></p>
        <?php else: ?>
        <form method="POST" action="inscription.php" onsubmit="return validerFormulaireInscription(this)">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="groupe-formulaire">
                    <label class="etiquette-formulaire">Prénom</label>
                    <input type="text" name="prenom" class="champ-formulaire" placeholder="Marie" required>
                </div>
                <div class="groupe-formulaire">
                    <label class="etiquette-formulaire">Nom</label>
                    <input type="text" name="nom" class="champ-formulaire" placeholder="Dupont" required>
                </div>
            </div>
            <div class="groupe-formulaire">
                <label class="etiquette-formulaire">Adresse e-mail</label>
                <input type="email" name="email" class="champ-formulaire" placeholder="votre@email.fr" required autocomplete="email">
            </div>
            <div class="groupe-formulaire">
                <label class="etiquette-formulaire">Téléphone (optionnel)</label>
                <input type="tel" name="telephone" class="champ-formulaire" placeholder="+33 6 00 00 00 00">
            </div>
            <div class="groupe-formulaire">
                <label class="etiquette-formulaire">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="champ-formulaire" placeholder="Minimum 8 caractères" required autocomplete="new-password">
            </div>
            <div class="groupe-formulaire">
                <label class="etiquette-formulaire">Confirmer le mot de passe</label>
                <input type="password" name="confirmation_mot_de_passe" class="champ-formulaire" placeholder="••••••••" required autocomplete="new-password">
            </div>
            <button type="submit" class="bouton-primaire" style="width:100%;justify-content:center;">Créer mon compte</button>
        </form>
        <p class="corps-md" style="text-align:center;margin-top:24px;color:var(--couleur-ivoire-sombre);">
            Déjà un compte ? <a href="connexion.php" class="bouton-fantome">Se connecter</a>
        </p>
        <?php endif; ?>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body>
</html>
