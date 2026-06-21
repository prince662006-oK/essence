/**
 * L'ESSENCE Haute Parfumerie — Script principal
 */

'use strict';

/* ── Entête avec effet de défilement ── */
const entete = document.querySelector('.entete-principale');
if (entete) {
  window.addEventListener('scroll', () => {
    entete.classList.toggle('defilée', window.scrollY > 30);
  }, { passive: true });
}

/* ── Menu mobile ── */
const boutonMenu = document.querySelector('.bouton-menu-mobile');
const liensNav   = document.querySelector('.liens-navigation');
if (boutonMenu && liensNav) {
  boutonMenu.addEventListener('click', () => {
    liensNav.classList.toggle('ouverte');
    boutonMenu.querySelector('.material-symbols-outlined').textContent =
      liensNav.classList.contains('ouverte') ? 'close' : 'menu';
  });
}

/* ── Animations au défilement ── */
const observateur = new IntersectionObserver((entrees) => {
  entrees.forEach(entree => {
    if (entree.isIntersecting) {
      entree.target.classList.add('visible');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.animation-apparition').forEach(el => observateur.observe(el));

/* ── Galerie produit (page detail) ── */
function initialiserGalerie() {
  const miniatures = document.querySelectorAll('.miniature');
  const imageP     = document.querySelector('.image-principale-produit img');

  if (!miniatures.length || !imageP) return;

  miniatures.forEach(mini => {
    mini.addEventListener('click', function() {
      miniatures.forEach(m => m.classList.remove('active'));
      this.classList.add('active');
      const src = this.querySelector('img')?.src;
      if (src) {
        imageP.style.opacity = '0';
        setTimeout(() => {
          imageP.src = src;
          imageP.style.opacity = '1';
        }, 200);
        imageP.style.transition = 'opacity 0.2s ease';
      }
    });
  });
}
initialiserGalerie();

/* ── Boutons quantité ── */
document.querySelectorAll('.bouton-quantite').forEach(bouton => {
  bouton.addEventListener('click', function() {
    const action     = this.dataset.action;
    const champQte   = this.closest('.quantite-article')?.querySelector('.valeur-quantite');
    const idElement  = this.dataset.idElement;
    if (!champQte) return;

    let quantite = parseInt(champQte.textContent);
    if (action === 'augmenter') quantite++;
    if (action === 'diminuer' && quantite > 1) quantite--;

    champQte.textContent = quantite;

    // Appel AJAX pour mettre à jour le panier
    fetch('ajax/panier.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'modifier_quantite', id_element: idElement, quantite })
    })
    .then(r => r.json())
    .then(data => {
      if (data.succes) {
        mettreAJourResumePanier(data);
      }
    })
    .catch(console.error);
  });
});

/* ── Ajouter au panier (AJAX) ── */
document.querySelectorAll('[data-action="ajouter-panier"]').forEach(bouton => {
  bouton.addEventListener('click', function() {
    const idProduit = this.dataset.idProduit;
    if (!idProduit) return;

    const textOriginal = this.textContent;
    this.textContent = 'AJOUTÉ ✓';
    this.style.background = '#a5d6a7';

    fetch('ajax/panier.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'ajouter', id_produit: idProduit, quantite: 1 })
    })
    .then(r => r.json())
    .then(data => {
      if (data.succes) {
        const badge = document.querySelector('.badge-panier');
        if (badge) badge.textContent = data.nombre_articles;
        afficherNotification('Article ajouté au panier', 'succes');
      } else if (data.redirection) {
        window.location.href = data.redirection;
      }
    })
    .catch(console.error)
    .finally(() => {
      setTimeout(() => {
        this.textContent = textOriginal;
        this.style.background = '';
      }, 2000);
    });
  });
});

/* ── Supprimer du panier ── */
document.querySelectorAll('[data-action="supprimer-article"]').forEach(bouton => {
  bouton.addEventListener('click', function() {
    const idElement = this.dataset.idElement;
    const article   = this.closest('.article-panier');

    fetch('ajax/panier.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'supprimer', id_element: idElement })
    })
    .then(r => r.json())
    .then(data => {
      if (data.succes) {
        article?.remove();
        mettreAJourResumePanier(data);
      }
    })
    .catch(console.error);
  });
});

/* ── Code promo ── */
const formulairePromo = document.querySelector('#formulaire-promo');
if (formulairePromo) {
  formulairePromo.addEventListener('submit', function(e) {
    e.preventDefault();
    const codePromo = this.querySelector('input').value.trim();
    if (!codePromo) return;

    fetch('ajax/panier.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'appliquer_promo', code_promo: codePromo })
    })
    .then(r => r.json())
    .then(data => {
      const msg = document.querySelector('#message-promo');
      if (msg) {
        msg.textContent = data.message;
        msg.className = data.valide ? 'message-succes' : 'message-erreur';
      }
      if (data.valide) mettreAJourResumePanier(data);
    })
    .catch(console.error);
  });
}

/* ── Favori ── */
document.querySelectorAll('[data-action="favori"]').forEach(bouton => {
  bouton.addEventListener('click', function() {
    const idProduit = this.dataset.idProduit;
    fetch('ajax/utilisateur.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'basculer_favori', id_produit: idProduit })
    })
    .then(r => r.json())
    .then(data => {
      if (data.succes) {
        const icone = this.querySelector('.material-symbols-outlined');
        if (icone) {
          icone.style.fontVariationSettings = data.etat === 'ajouté'
            ? "'FILL' 1" : "'FILL' 0";
        }
        afficherNotification(
          data.etat === 'ajouté' ? 'Ajouté aux favoris' : 'Retiré des favoris',
          'succes'
        );
      } else if (data.redirection) {
        window.location.href = data.redirection;
      }
    })
    .catch(console.error);
  });
});

/* ── Mettre à jour le résumé du panier ── */
function mettreAJourResumePanier(data) {
  const elTotal   = document.querySelector('#total-panier');
  const elSousTotal = document.querySelector('#sous-total');
  const badge     = document.querySelector('.badge-panier');

  if (elTotal && data.total !== undefined) elTotal.textContent = data.total.toFixed(2) + ' €';
  if (elSousTotal && data.sous_total !== undefined) elSousTotal.textContent = data.sous_total.toFixed(2) + ' €';
  if (badge && data.nombre_articles !== undefined) badge.textContent = data.nombre_articles;
}

/* ── Notification toast ── */
function afficherNotification(message, type = 'succes') {
  const toast = document.createElement('div');
  toast.className = `alerte alerte-${type}`;
  toast.textContent = message;
  toast.style.cssText = `
    position: fixed; bottom: 24px; right: 24px;
    z-index: 9999; animation: glisserEntree 0.3s ease;
    min-width: 280px; box-shadow: 0 8px 30px rgba(0,0,0,0.4);
  `;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

/* ── Recherche en temps réel ── */
let minuteurRecherche;
const champRecherche = document.querySelector('#champ-recherche');
if (champRecherche) {
  champRecherche.addEventListener('input', function() {
    clearTimeout(minuteurRecherche);
    const terme = this.value.trim();
    minuteurRecherche = setTimeout(() => {
      if (terme.length >= 2) {
        window.location.href = `catalogue.php?recherche=${encodeURIComponent(terme)}`;
      }
    }, 500);
  });
}

/* ── PWA : Installation ── */
let demandeInstallation;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  demandeInstallation = e;

  const banniere = document.querySelector('.banniere-installation');
  if (banniere) banniere.style.display = 'flex';

  const boutonInstaller = document.querySelector('#bouton-installer');
  if (boutonInstaller) {
    boutonInstaller.addEventListener('click', async () => {
      if (!demandeInstallation) return;
      demandeInstallation.prompt();
      const { outcome } = await demandeInstallation.userChoice;
      if (outcome === 'accepted') {
        banniere.style.display = 'none';
        demandeInstallation = null;
      }
    });
  }
});

const boutonFermerBanniere = document.querySelector('#fermer-banniere');
if (boutonFermerBanniere) {
  boutonFermerBanniere.addEventListener('click', () => {
    const banniere = document.querySelector('.banniere-installation');
    if (banniere) banniere.style.display = 'none';
  });
}

/* ── Enregistrement du Service Worker ── */
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/pwa/service-worker.js')
      .then(enregistrement => {
        console.log('Service Worker enregistré :', enregistrement.scope);
      })
      .catch(erreur => {
        console.error('Erreur Service Worker :', erreur);
      });
  });
}
