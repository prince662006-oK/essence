/**
 * L'ESSENCE Haute Parfumerie — JavaScript principal
 */

document.addEventListener('DOMContentLoaded', () => {

    // ---------- En-tête au défilement ----------
    const entete = document.querySelector('.entete-principale');
    if (entete) {
        window.addEventListener('scroll', () => {
            entete.classList.toggle('defilée', window.scrollY > 60);
        }, { passive: true });
    }

    // ---------- Parallaxe hero ----------
    const fondHero = document.querySelector('.fond-hero');
    if (fondHero) {
        window.addEventListener('scroll', () => {
            const decalage = window.scrollY;
            fondHero.style.transform = `scale(1.05) translateY(${decalage * 0.08}px)`;
        }, { passive: true });
    }

    // ---------- Défilement des images du hero ----------
    const diapositivesHero = document.querySelectorAll('.diapo-hero');
    if (diapositivesHero.length > 1) {
        let indexDiapoActuelle = 0;
        setInterval(() => {
            diapositivesHero[indexDiapoActuelle].classList.remove('diapo-hero--active');
            indexDiapoActuelle = (indexDiapoActuelle + 1) % diapositivesHero.length;
            diapositivesHero[indexDiapoActuelle].classList.add('diapo-hero--active');
        }, 5000);
    }

    // ---------- Révélation au défilement ----------
    const observateurReveler = new IntersectionObserver((entrees) => {
        entrees.forEach((entree, index) => {
            if (entree.isIntersecting) {
                setTimeout(() => {
                    entree.target.classList.add('visible');
                }, index * 80);
                observateurReveler.unobserve(entree.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveler').forEach(el => observateurReveler.observe(el));

    // ---------- Carrousel ----------
    const pisteCarrousel = document.querySelector('.piste-carrousel');
    const btnGauche = document.querySelector('.bouton-carrousel--gauche');
    const btnDroite = document.querySelector('.bouton-carrousel--droite');

    if (pisteCarrousel && btnGauche && btnDroite) {
        const largeurDefilement = 324;
        btnDroite.addEventListener('click', () => {
            pisteCarrousel.scrollBy({ left: largeurDefilement, behavior: 'smooth' });
        });
        btnGauche.addEventListener('click', () => {
            pisteCarrousel.scrollBy({ left: -largeurDefilement, behavior: 'smooth' });
        });
    }

    // ---------- Ajouter au panier (micro-interaction) ----------
    document.querySelectorAll('.bouton-ajouter-panier').forEach(bouton => {
        bouton.addEventListener('click', function(e) {
            e.stopPropagation();
            const texteOriginal = this.textContent;
            this.textContent = '✓ Ajouté';
            this.style.background = '#7fb890';
            setTimeout(() => {
                this.textContent = texteOriginal;
                this.style.background = '';
            }, 2000);
            // Mise à jour badge panier
            const badge = document.querySelector('.badge-panier .compte');
            if (badge) {
                const compte = parseInt(badge.textContent) || 0;
                badge.textContent = compte + 1;
            }
        });
    });

    // ---------- Notification PWA d'installation ----------
    let invitationInstallation;
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        invitationInstallation = e;
        const boutonInstaller = document.querySelector('#bouton-installer-pwa');
        if (boutonInstaller) boutonInstaller.style.display = 'flex';
    });

    const boutonInstaller = document.querySelector('#bouton-installer-pwa');
    if (boutonInstaller) {
        boutonInstaller.addEventListener('click', async () => {
            if (invitationInstallation) {
                invitationInstallation.prompt();
                const { outcome } = await invitationInstallation.userChoice;
                if (outcome === 'accepted') boutonInstaller.style.display = 'none';
                invitationInstallation = null;
            }
        });
    }

    // ---------- Service Worker PWA ----------
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/pwa/service-worker.js')
            .catch(err => console.warn('SW non enregistré :', err));
    }

});

// ---------- Validation formulaire inscription ----------
function validerFormulaireInscription(formulaire) {
    const email = formulaire.querySelector('[name="email"]');
    const motDePasse = formulaire.querySelector('[name="mot_de_passe"]');
    const confirmation = formulaire.querySelector('[name="confirmation_mot_de_passe"]');
    let valide = true;

    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        afficherErreurChamp(email, 'Adresse e-mail invalide');
        valide = false;
    }
    if (motDePasse && motDePasse.value.length < 8) {
        afficherErreurChamp(motDePasse, 'Le mot de passe doit contenir au moins 8 caractères');
        valide = false;
    }
    if (confirmation && motDePasse && confirmation.value !== motDePasse.value) {
        afficherErreurChamp(confirmation, 'Les mots de passe ne correspondent pas');
        valide = false;
    }
    return valide;
}

function afficherErreurChamp(champ, message) {
    champ.style.borderColor = '#cf6679';
    let erreur = champ.parentNode.querySelector('.message-erreur');
    if (!erreur) {
        erreur = document.createElement('p');
        erreur.className = 'message-erreur';
        champ.parentNode.appendChild(erreur);
    }
    erreur.textContent = message;
    champ.addEventListener('input', () => {
        champ.style.borderColor = '';
        if (erreur) erreur.remove();
    }, { once: true });
}
