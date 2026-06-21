<footer class="pied-de-page" role="contentinfo">
    <div class="conteneur">
        <div class="grille-pied-de-page">
            <div>
                <a href="accueil.php" class="logo-pied-de-page">L'ESSENCE</a>
                <p class="description-pied-de-page">L'adresse de référence pour la haute parfumerie mondiale. Une curation d'exception pour des nez avertis.</p>
                <button id="bouton-installer-pwa" class="bouton-secondaire" style="display:none;font-size:0.7rem;">
                    &#8595; Installer l'application
                </button>
            </div>
            <nav aria-label="E-Boutique">
                <h3 class="titre-colonne-pied">E-Boutique</h3>
                <ul class="liste-liens-pied">
                    <li><a href="catalogue.php?categorie=Homme">Homme</a></li>
                    <li><a href="catalogue.php?categorie=Femme">Femme</a></li>
                    <li><a href="catalogue.php?categorie=Unisexe">Unisexe</a></li>
                    <li><a href="catalogue.php?tri=recent">Nouveautés</a></li>
                    <li><a href="catalogue.php?promo=1">Promotions</a></li>
                </ul>
            </nav>
            <nav aria-label="Assistance">
                <h3 class="titre-colonne-pied">Assistance</h3>
                <ul class="liste-liens-pied">
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="#">Livraisons &amp; Retours</a></li>
                    <li><a href="#">Localisateur de boutiques</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </nav>
            <nav aria-label="La Maison">
                <h3 class="titre-colonne-pied">La Maison</h3>
                <ul class="liste-liens-pied">
                    <li><a href="a-propos.php">À Propos</a></li>
                    <li><a href="#">Développement durable</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Mentions légales</a></li>
                </ul>
            </nav>
        </div>
        <div class="barre-copyright">
            <p class="texte-copyright">&copy; <?= date('Y') ?> L'ESSENCE HAUTE PARFUMERIE. TOUS DROITS RÉSERVÉS.</p>
            <div class="icones-reseaux" role="list" aria-label="Réseaux sociaux">
                <a href="#" class="icone-reseau" aria-label="Instagram" role="listitem">&#9670;</a>
                <a href="#" class="icone-reseau" aria-label="Facebook" role="listitem">&#9671;</a>
                <a href="#" class="icone-reseau" aria-label="Pinterest" role="listitem">&#9656;</a>
            </div>
        </div>
    </div>
</footer>
