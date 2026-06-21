<?php
session_start();
require_once 'configuration/connexion_base_donnees.php';
require_once 'classes/Produit.php';

$classeProduit = new Produit($connexion);
$meilleuresVentes = $classeProduit->obtenirBestSellers(4);
$nouveautes = $classeProduit->obtenirTous('', 6);

$titrePageCourante = "L'ESSENCE Haute Parfumerie | L'Art du Parfum d'Exception";
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="L'adresse de référence pour la haute parfumerie mondiale. Dior, Chanel, Tom Ford, Hermès.">
    <meta name="theme-color" content="#C9A84C">
    <title><?= htmlspecialchars($titrePageCourante) ?></title>
    <link rel="manifest" href="pwa/manifest.json">
    <link rel="stylesheet" href="css/style-principal.css">
    <link rel="icon" type="image/x-icon" href="images/icones/favicon.ico">
</head>
<body>

<!-- Navigation -->
<?php include 'vues/partials/entete.php'; ?>

<main>
    <!-- Bandeau olfactif (Signature) -->
    <div class="bandeau-olfactif" style="margin-top:74px;">
        <div class="bandeau-olfactif-piste" id="piste-bandeau">
            <?php
            $notesDefilantes = ['Saffran', '✦', 'Oud', '✦', 'Rose de Damas', '✦', 'Bergamote', '✦', 'Ambre', '✦', 'Vétiver', '✦', 'Iris', '✦', 'Musc blanc', '✦', 'Patchouli', '✦', 'Santal', '✦', 'Jasmin Sambac', '✦', 'Cèdre', '✦'];
            for ($i = 0; $i < 3; $i++) {
                foreach ($notesDefilantes as $note) { ?>
                    <span><?= htmlspecialchars($note) ?></span>
                <?php }
            } ?>
        </div>
    </div>

    <!-- Section Hero -->
    <section class="section-hero">
        <div class="fond-hero">
            <div class="diapo-hero diapo-hero--active" style="background-image:url('https://images.unsplash.com/photo-1598634222670-87c5f558119c?auto=format&fit=crop&w=1920&q=80')"></div>
            <div class="diapo-hero" style="background-image:url('https://images.unsplash.com/photo-1592842312573-dca0b185d2e0?auto=format&fit=crop&w=1920&q=80')"></div>
            <div class="diapo-hero" style="background-image:url('https://images.unsplash.com/photo-1749264361617-dbe17a223f54?auto=format&fit=crop&w=1920&q=80')"></div>
        </div>
        <div class="contenu-hero">
            <p class="sous-titre-hero">Paris &bull; Haute Parfumerie</p>
            <h1 class="titre-hero">L'Art du Parfum<br><em>d'Exception</em></h1>
            <p class="description-hero corps-lg">
                Une curation rigoureuse des fragrances les plus précieuses au monde,
                pour les nez qui refusent l'ordinaire.
            </p>
            <div class="actions-hero">
                <a href="catalogue.php" class="bouton-primaire">Découvrir la collection</a>
                <a href="catalogue.php?categorie=marques" class="bouton-secondaire">Explorer les Maisons</a>
            </div>
        </div>
    </section>

    <!-- Bande marques -->
    <section class="bande-marques">
        <div class="liste-marques">
            <span class="nom-marque">DIOR</span>
            <span class="nom-marque">CHANEL</span>
            <span class="nom-marque">ARMANI</span>
            <span class="nom-marque">TOM FORD</span>
            <span class="nom-marque">HERMÈS</span>
            <span class="nom-marque">BYREDO</span>
        </div>
    </section>

    <!-- Best-Sellers -->
    <section class="section conteneur">
        <div class="entete-section reveler">
            <div class="groupe-titre-section">
                <span class="etiquette">Les Iconiques</span>
                <h2 class="titre-affiche">Nos Best-Sellers</h2>
            </div>
            <a href="catalogue.php" class="bouton-fantome">Voir tout</a>
        </div>
        <div class="grille-produits">
            <?php foreach ($meilleuresVentes as $index => $produit): ?>
            <div class="carte-produit reveler" style="transition-delay: <?= $index * 80 ?>ms;">
                <div class="carte-produit__visuel">
                    <img class="carte-produit__image"
                         src="<?= htmlspecialchars($produit['image_principale'] ?? 'images/placeholder.jpg') ?>"
                         alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                         loading="lazy">
                    <div class="carte-produit__superposition"></div>
                    <?php if ($produit['ancien_prix']): ?>
                        <span class="carte-produit__badge">Promo</span>
                    <?php endif; ?>
                    <a href="produit.php?id=<?= $produit['id_produit'] ?>"
                       class="bouton-ajouter-panier">Ajouter au panier</a>
                </div>
                <p class="carte-produit__marque"><?= htmlspecialchars($produit['nom_marque'] ?? '') ?></p>
                <h3 class="carte-produit__nom">
                    <a href="produit.php?id=<?= $produit['id_produit'] ?>"><?= htmlspecialchars($produit['nom_produit']) ?></a>
                </h3>
                <div class="carte-produit__prix-groupe">
                    <span class="carte-produit__prix"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                    <?php if ($produit['ancien_prix']): ?>
                        <span class="carte-produit__ancien-prix"><?= number_format($produit['ancien_prix'], 2, ',', ' ') ?> €</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Nouveautés (Carrousel) -->
    <section class="section section-nouveautes">
        <div class="conteneur">
            <div class="entete-section reveler">
                <h2 class="titre-affiche">Nouveautés</h2>
                <div class="controles-carrousel">
                    <button class="bouton-carrousel bouton-carrousel--gauche" aria-label="Précédent">&#8592;</button>
                    <button class="bouton-carrousel bouton-carrousel--droite" aria-label="Suivant">&#8594;</button>
                </div>
            </div>
            <div class="wrapper-carrousel">
                <div class="piste-carrousel">
                    <?php foreach ($nouveautes as $produit): ?>
                    <div class="carte-nouveaute">
                        <img class="carte-nouveaute__image"
                             src="<?= htmlspecialchars($produit['image_principale'] ?? 'images/placeholder.jpg') ?>"
                             alt="<?= htmlspecialchars($produit['nom_produit']) ?>"
                             loading="lazy">
                        <span class="etiquette"><?= htmlspecialchars($produit['nom_categorie'] ?? '') ?></span>
                        <h4 class="titre-section" style="margin:12px 0 8px;"><?= htmlspecialchars($produit['nom_produit']) ?></h4>
                        <p class="corps-md" style="color:var(--couleur-ivoire-sombre);margin-bottom:16px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= htmlspecialchars($produit['description'] ?? '') ?>
                        </p>
                        <a href="produit.php?id=<?= $produit['id_produit'] ?>" class="bouton-fantome">En savoir plus</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Bannière Ventes Privées -->
    <section class="section conteneur">
        <div class="banniere-promotion reveler">
            <img class="banniere-promotion__fond"
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDm-Nef-k9rt8IQmNVRamMl0RG5NZxOh9m8kXf6UGO-dRBG8crkwlA-UGbViA1CNzZs-X9TLn0_U8hRejTexdDM7lMlxZ_pmXQp3AcuVguxFlOdnhyuHVf64WHS91jxyl79iA3C7q9FmNZl8SxWqWBoSjiR6Lp9AyQBlDz6zurkFzswmmcB7JrX_OApYsRSVcWpXIY5NNhh5df4w7WaBFzKNrYOfx_m7aYUI--vux7R5GHU9hwt1SfECptkhqDXv2_R8qvDzRxfXUA"
                 alt="">
            <div class="banniere-promotion__voile"></div>
            <div class="banniere-promotion__contenu">
                <span class="etiquette">Ventes Privées</span>
                <p class="banniere-promotion__remise"><strong>-20%</strong> sur<br>la Collection<br>d'Exception</p>
                <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);margin:16px 0 28px;">
                    Offre exclusive sur nos parfums les plus rares, pour une durée limitée.
                </p>
                <a href="catalogue.php?promo=1" class="bouton-primaire">Accéder à la vente</a>
            </div>
        </div>
    </section>

    <!-- Avantages -->
    <section class="section conteneur" style="border-top:1px solid var(--couleur-bordure);">
        <div class="grille-avantages">
            <?php
            $avantages = [
                ['&#128666;', 'Livraison Express', 'Expédié sous 24h avec un soin exceptionnel.'],
                ['&#10003;', 'Produits Authentiques', 'Directement approvisionnés auprès des maisons.'],
                ['&#128274;', 'Paiement Sécurisé', 'Chiffrement SSL 256 bits sur chaque transaction.'],
                ['&#9993;', 'Service Client', 'Nos experts olfactifs à votre écoute, 7j/7.'],
            ];
            foreach ($avantages as $index => $avantage): ?>
            <div class="carte-avantage reveler" style="transition-delay:<?= $index * 60 ?>ms;">
                <div class="carte-avantage__icone"><?= $avantage[0] ?></div>
                <h4 class="carte-avantage__titre"><?= $avantage[1] ?></h4>
                <p class="carte-avantage__texte"><?= $avantage[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="section section-temoignages">
        <div class="conteneur">
            <div class="entete-section reveler">
                <div class="groupe-titre-section">
                    <span class="etiquette">Expériences</span>
                    <h2 class="titre-affiche">Leur voyage olfactif</h2>
                </div>
            </div>
            <div class="grille-temoignages">
                <?php
                $temoignages = [
                    ['note'=>5,'texte'=>'Une sélection rare et précieuse. J\'ai enfin trouvé mon parfum signature grâce aux conseils avisés de l\'équipe L\'Essence.','nom'=>'Marie L.','titre'=>'Collectionneuse','avatar'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuC5_5PLa3eQPqPb_3LsDEP2mKmd230DN4eAQkkqT5Tsl6vaakT29rCcAtAFcGsrqZZ6SJa_p41McxamB8CTV34QfCyHW2c0sm-S0sb-SayShgfw6zEo79MvjVPJ5egMTYjtZEuhv4r4gplZc03iXyW1iEScc3fxuaBTvX23QylnB8fODLYZJX1U3MmCPU400AkJNDBD__e_QTsGH75ouaNfQ298R1_Pn6n6FFCB-xVcVjwrPHJzVmCyfaH7wceD-V_732JbZbr1pNY','vedette'=>false],
                    ['note'=>5,'texte'=>'Le service est à la hauteur des produits : exceptionnel. La livraison était rapide et le packaging absolument sublime.','nom'=>'Julien R.','titre'=>'Client Privilège','avatar'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuA4YX3k6cnEMeCH96qdNeDki-hpECM-KW0rB-A1qs2TRRCgMzYlDl-29PYSoEUhRRsAYpRYyJULxH5JBwgtrc7JB5-1I40A6EN_ykh-YLGNtWUcx0MPUYyPqGWI8l6zMIGJoyXNMKkkgERlqB4SWc7Eb4WtD77JybgnlAcvOuEzsNu4z_cCrdqaAvlA7sbDSgUT958bmg1pvUcZyiqEfEMFh3MHTPfuadtAwbHp3ZAifGegnTlJEdOUeLZuH8Dq55ogAGn849VCG3k','vedette'=>true],
                    ['note'=>5,'texte'=>'Chaque commande est une véritable expérience sensorielle. L\'Essence redéfinit la parfumerie de luxe en ligne.','nom'=>'Sophie V.','titre'=>'Passionnée','avatar'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuAlVuKDkdToKkYVsnto1GE2coD_TlJdvh4aR5xq-yQtLpluwACwM01pcJ_iyloog9Yxli-WSIUJN55xfU_nGpsWIOLS5z_3U5dHWf3vSf3HAj7K6H0pn_i4-UmyiejnTCtnpuEznAbVdkiKUmyVRy7zMtKLo2vbUA1C9b6053f2GBq1GcsOiFifUPW2QZuzIlQB4FlE873y7mo9jdgWgTARX9vFIHSbM1dcE_QGwF0rNSy9seMqj1HXizNWU-jTp7j3TyR6Z4RuvPM','vedette'=>false],
                ];
                foreach ($temoignages as $index => $t): ?>
                <div class="carte-temoignage<?= $t['vedette'] ? ' vedette' : '' ?> reveler" style="transition-delay:<?= $index * 100 ?>ms;">
                    <div class="etoiles-avis"><?= str_repeat('★', $t['note']) ?></div>
                    <p class="texte-temoignage">"<?= htmlspecialchars($t['texte']) ?>"</p>
                    <div class="auteur-temoignage">
                        <div class="avatar-auteur">
                            <img src="<?= $t['avatar'] ?>" alt="<?= htmlspecialchars($t['nom']) ?>" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div>
                            <p class="nom-auteur"><?= htmlspecialchars($t['nom']) ?></p>
                            <p class="titre-auteur"><?= htmlspecialchars($t['titre']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="section section-newsletter">
        <div class="conteneur">
            <div class="contenu-newsletter reveler">
                <span class="etiquette">Le Cercle L'Essence</span>
                <h2 class="titre-affiche" style="margin:12px 0 16px;">Rejoignez le Cercle</h2>
                <p class="corps-lg" style="color:var(--couleur-ivoire-sombre);">
                    Recevez en avant-première nos nouvelles collections et invitations aux ventes privées exclusives.
                </p>
                <form class="formulaire-newsletter" action="#" method="POST">
                    <input type="email" name="email_newsletter" class="champ-email-newsletter"
                           placeholder="Votre adresse e-mail" required>
                    <button type="submit" class="bouton-newsletter">S'inscrire</button>
                </form>
                <p class="corps-md" style="color:var(--couleur-ivoire-sombre);margin-top:12px;font-size:0.75rem;">
                    En vous inscrivant, vous acceptez notre politique de confidentialité.
                </p>
            </div>
        </div>
    </section>
</main>

<?php include 'vues/partials/pied_de_page.php'; ?>
<script src="js/principal.js"></script>
</body>
</html>
