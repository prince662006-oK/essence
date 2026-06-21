<?php
session_start();
$messageEnvoye = false;
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim(htmlspecialchars($_POST['nom'] ?? ''));
    $email  = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $sujet  = trim(htmlspecialchars($_POST['sujet'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    if (!$nom || !$email || !$sujet || !$message) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        // En production : mail($destinataire, $sujet, $message);
        $messageEnvoye = true;
    }
}
?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact — L'ESSENCE</title>
<link rel="manifest" href="pwa/manifest.json">
<link rel="stylesheet" href="css/style-principal.css">
</head><body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">
    <div class="conteneur section" style="max-width:860px;margin:0 auto;">
        <span class="etiquette">À votre service</span>
        <h1 class="titre-affiche" style="margin:8px 0 16px;">Nous contacter</h1>
        <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);margin-bottom:56px;max-width:540px;">
            Notre équipe de conseillers olfactifs est disponible du lundi au vendredi, de 9h à 19h.
        </p>

        <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:60px;">
            <!-- Informations -->
            <div>
                <div style="margin-bottom:36px;">
                    <p class="etiquette" style="font-size:0.6rem;margin-bottom:8px;">Adresse</p>
                    <p class="corps-md" style="color:var(--couleur-ivoire);">12, rue du Faubourg Saint-Honoré<br>75008 Paris — France</p>
                </div>
                <div style="margin-bottom:36px;">
                    <p class="etiquette" style="font-size:0.6rem;margin-bottom:8px;">Téléphone</p>
                    <p class="corps-md" style="color:var(--couleur-ivoire);">+33 1 42 00 00 00</p>
                </div>
                <div style="margin-bottom:36px;">
                    <p class="etiquette" style="font-size:0.6rem;margin-bottom:8px;">E-mail</p>
                    <p class="corps-md" style="color:var(--couleur-or);">contact@lessence.fr</p>
                </div>
                <div>
                    <p class="etiquette" style="font-size:0.6rem;margin-bottom:8px;">Horaires</p>
                    <p class="corps-md" style="color:var(--couleur-ivoire);">Lun – Ven : 9h – 19h<br>Samedi : 10h – 17h<br>Dimanche : fermé</p>
                </div>
            </div>

            <!-- Formulaire -->
            <div>
                <?php if ($messageEnvoye): ?>
                    <div class="alerte alerte-succes" style="margin-bottom:0;">
                        <p style="font-family:var(--police-display);font-size:1.2rem;margin-bottom:6px;">Message envoyé</p>
                        <p class="corps-md">Nous vous répondrons dans les meilleurs délais. Merci de votre confiance.</p>
                    </div>
                <?php else: ?>
                    <?php if ($erreur): ?><div class="alerte alerte-erreur"><?= $erreur ?></div><?php endif; ?>
                    <form method="POST">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div class="groupe-formulaire">
                                <label class="etiquette-formulaire">Nom complet</label>
                                <input type="text" name="nom" class="champ-formulaire"
                                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                            </div>
                            <div class="groupe-formulaire">
                                <label class="etiquette-formulaire">E-mail</label>
                                <input type="email" name="email" class="champ-formulaire"
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="groupe-formulaire">
                            <label class="etiquette-formulaire">Sujet</label>
                            <select name="sujet" class="champ-formulaire" required>
                                <option value="">Sélectionner...</option>
                                <option value="Conseil olfactif">Conseil olfactif</option>
                                <option value="Commande & livraison">Commande &amp; livraison</option>
                                <option value="Retour & remboursement">Retour &amp; remboursement</option>
                                <option value="Partenariat">Partenariat</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="groupe-formulaire">
                            <label class="etiquette-formulaire">Message</label>
                            <textarea name="message" class="champ-formulaire" rows="6"
                                      style="resize:vertical;" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="bouton-primaire">Envoyer le message</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body></html>
