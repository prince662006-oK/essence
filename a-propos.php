<?php session_start(); ?>
<!DOCTYPE html><html lang="fr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notre Maison — L'ESSENCE</title>
<link rel="manifest" href="pwa/manifest.json">
<link rel="stylesheet" href="css/style-principal.css">
<style>
.bloc-valeur{background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);padding:36px 28px;border-radius:4px;}
.chiffre-cle{font-family:var(--police-display);font-size:3rem;font-weight:300;color:var(--couleur-or);line-height:1;}
.separateur-or{width:40px;height:1px;background:var(--couleur-or);margin:24px 0;}
.equipe-carte{background:var(--couleur-fond-carte);border:1px solid var(--couleur-bordure);padding:28px;border-radius:4px;text-align:center;}
.avatar-equipe{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--couleur-or),#8b6914);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-family:var(--police-display);font-size:1.5rem;color:var(--couleur-fond);}
</style>
</head><body>
<?php include 'vues/partials/entete.php'; ?>
<main class="conteneur-page">

    <!-- Hero section -->
    <section style="padding:80px 0;border-bottom:1px solid var(--couleur-bordure);">
        <div class="conteneur">
            <span class="etiquette">Notre histoire</span>
            <h1 class="titre-affiche" style="margin:12px 0 24px;max-width:700px;">
                L'art de la parfumerie<br>élevé au rang d'expérience
            </h1>
            <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);max-width:560px;line-height:1.8;">
                Fondée en 2018 à Paris, L'ESSENCE est née d'une conviction simple : chaque fragrance est
                une fenêtre sur une émotion, un voyage, un souvenir. Nous sélectionnons rigoureusement
                les maisons qui partagent cette philosophie.
            </p>
        </div>
    </section>

    <!-- Chiffres clés -->
    <section class="section" style="background:var(--couleur-fond-surface);">
        <div class="conteneur">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:2px;">
                <?php
                $chiffres = [
                    ['valeur' => '6+', 'label' => 'Maisons partenaires'],
                    ['valeur' => '150+', 'label' => 'Fragrances sélectionnées'],
                    ['valeur' => '12 000+', 'label' => 'Clients fidèles'],
                    ['valeur' => '98%', 'label' => 'De satisfaction client'],
                ];
                foreach ($chiffres as $c): ?>
                <div style="background:var(--couleur-fond-carte);padding:40px 32px;border:1px solid var(--couleur-bordure);">
                    <div class="chiffre-cle"><?= $c['valeur'] ?></div>
                    <p class="corps-md" style="color:var(--couleur-ivoire-sombre);margin-top:12px;"><?= $c['label'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Notre philosophie -->
    <section class="section">
        <div class="conteneur">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;">
                <div>
                    <span class="etiquette">Philosophie</span>
                    <h2 class="titre-section" style="margin:12px 0 20px;">La curation comme art</h2>
                    <div class="separateur-or"></div>
                    <p class="corps-md" style="color:var(--couleur-ivoire-sombre);line-height:1.9;margin-bottom:20px;">
                        Nous ne référençons pas des parfums : nous construisons des bibliothèques olfactives.
                        Chaque flacon qui intègre notre catalogue a traversé un processus de sélection rigoureux
                        impliquant nos experts, des tests en conditions réelles et une vérification éthique des
                        pratiques de fabrication.
                    </p>
                    <p class="corps-md" style="color:var(--couleur-ivoire-sombre);line-height:1.9;">
                        Nous travaillons en partenariat direct avec les maisons — jamais via des intermédiaires —
                        afin de garantir l'authenticité absolue de chaque produit et de soutenir les artisans
                        qui perpétuent les savoir-faire de la haute parfumerie.
                    </p>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <?php
                    $valeurs = [
                        ['icone' => '✦', 'titre' => 'Authenticité', 'texte' => 'Chaque produit est sourcé directement auprès des maisons créatrices.'],
                        ['icone' => '◈', 'titre' => 'Expertise', 'texte' => 'Nos conseillers sont formés par des nez professionnels certifiés.'],
                        ['icone' => '⬡', 'titre' => 'Durabilité', 'texte' => 'Emballages recyclables et partenaires engagés dans l\'éco-responsabilité.'],
                        ['icone' => '⟡', 'titre' => 'Discrétion', 'texte' => 'Livraison neutre et données personnelles protégées par chiffrement.'],
                    ];
                    foreach ($valeurs as $v): ?>
                    <div class="bloc-valeur">
                        <div style="font-size:1.4rem;color:var(--couleur-or);margin-bottom:12px;"><?= $v['icone'] ?></div>
                        <h3 style="font-family:var(--police-display);font-size:1rem;color:var(--couleur-ivoire);margin-bottom:8px;"><?= $v['titre'] ?></h3>
                        <p class="corps-md" style="font-size:0.82rem;color:var(--couleur-ivoire-sombre);line-height:1.7;"><?= $v['texte'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Équipe -->
    <section class="section" style="background:var(--couleur-fond-surface);">
        <div class="conteneur">
            <div style="text-align:center;margin-bottom:48px;">
                <span class="etiquette">L'équipe</span>
                <h2 class="titre-section" style="margin:12px auto 0;">Des passionnés à votre écoute</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
                <?php
                $equipe = [
                    ['initiales' => 'SC', 'nom' => 'Sophie Castellan', 'role' => 'Fondatrice & Directrice artistique', 'bio' => '15 ans d\'expérience en parfumerie de niche. Ancienne sélectionneuse pour les Galeries Lafayette.'],
                    ['initiales' => 'AM', 'nom' => 'Antoine Moreau', 'role' => 'Nez conseil', 'bio' => 'Diplômé de l\'ISIPCA. Expert en créations orientales et boisées contemporaines.'],
                    ['initiales' => 'LB', 'nom' => 'Léa Bertrand', 'role' => 'Responsable clientèle', 'bio' => 'Spécialiste du conseil personnalisé. Crée des accords olfactifs sur-mesure pour chaque profil.'],
                ];
                foreach ($equipe as $membre): ?>
                <div class="equipe-carte">
                    <div class="avatar-equipe"><?= $membre['initiales'] ?></div>
                    <h3 style="font-family:var(--police-display);font-size:1.1rem;color:var(--couleur-ivoire);margin-bottom:4px;"><?= $membre['nom'] ?></h3>
                    <p class="etiquette" style="font-size:0.58rem;color:var(--couleur-or);margin-bottom:14px;"><?= $membre['role'] ?></p>
                    <p class="corps-md" style="font-size:0.82rem;color:var(--couleur-ivoire-sombre);line-height:1.7;"><?= $membre['bio'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section style="padding:80px 0;text-align:center;border-top:1px solid var(--couleur-bordure);">
        <div class="conteneur">
            <h2 class="titre-affiche" style="margin-bottom:20px;">Prêt à découvrir votre signature olfactive ?</h2>
            <div style="display:flex;gap:16px;justify-content:center;">
                <a href="catalogue.php" class="bouton-primaire">Explorer le catalogue</a>
                <a href="contact.php" class="bouton-secondaire">Nous contacter</a>
            </div>
        </div>
    </section>

</main>
<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body></html>
