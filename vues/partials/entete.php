<?php
$nbArticlesPanier = 0;
if (isset($_SESSION['id_utilisateur'])) {
    require_once __DIR__ . '/../../configuration/connexion_base_donnees.php';
    require_once __DIR__ . '/../../classes/Panier.php';
    $classePanier = new Panier($connexion);
    $idPanier = $classePanier->obtenirOuCreer((int)$_SESSION['id_utilisateur']);
    $contenuPanier = $classePanier->obtenirContenu($idPanier);
    $nbArticlesPanier = array_sum(array_column($contenuPanier, 'quantite'));
}
?>
<header class="entete-principale" role="banner">
    <nav class="barre-navigation" aria-label="Navigation principale">
        <a href="accueil.php" class="logo-principal" aria-label="L'ESSENCE — Accueil">L'ESSENCE</a>

        <ul class="menu-navigation" role="list">
            <li><a href="catalogue.php?categorie=Homme" <?= ($_GET['categorie'] ?? '') === 'Homme' ? 'class="actif"' : '' ?>>Homme</a></li>
            <li><a href="catalogue.php?categorie=Femme" <?= ($_GET['categorie'] ?? '') === 'Femme' ? 'class="actif"' : '' ?>>Femme</a></li>
            <li><a href="catalogue.php?categorie=Unisexe" <?= ($_GET['categorie'] ?? '') === 'Unisexe' ? 'class="actif"' : '' ?>>Unisexe</a></li>
            <li><a href="catalogue.php?tri=recent">Nouveautés</a></li>
        </ul>

        <div class="actions-navigation">
            <form action="catalogue.php" method="GET" role="search" aria-label="Recherche de parfums">
                <input type="search" name="recherche" placeholder="Rechercher…"
                       class="champ-formulaire" style="width:180px;padding:8px 14px;font-size:0.8rem;"
                       value="<?= htmlspecialchars($_GET['recherche'] ?? '') ?>"
                       aria-label="Rechercher un parfum">
            </form>

            <?php if (isset($_SESSION['id_utilisateur'])): ?>
                <a href="profil.php" class="icone-action" aria-label="Mon profil" title="Profil">&#128100;</a>
            <?php else: ?>
                <a href="connexion.php" class="icone-action" aria-label="Se connecter" title="Connexion">&#128100;</a>
            <?php endif; ?>

            <a href="panier.php" class="icone-action badge-panier" aria-label="Mon panier" title="Panier">
                &#128717;
                <?php if ($nbArticlesPanier > 0): ?>
                    <span class="compte" aria-live="polite"><?= $nbArticlesPanier ?></span>
                <?php endif; ?>
            </a>
        </div>
    </nav>
</header>
