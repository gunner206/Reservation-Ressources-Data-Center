
        document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        // 1. LOGIQUE D'OUVERTURE AUTOMATIQUE (Nouveau)
        // On regarde si l'URL contient "?action=signup"
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'signup') {
            // On ajoute la classe "active" immédiatement au chargement
            container.classList.add("active");
        }

        // 2. TON CODE EXISTANT (Pour les clics)
        // Quand on clique sur "S'inscrire"
        if(registerBtn) {
            registerBtn.addEventListener('click', () => {
                container.classList.add("active");
            });
        }

        // Quand on clique sur "Se connecter"
        if(loginBtn) {
            loginBtn.addEventListener('click', () => {
                container.classList.remove("active");
            });
        }
    });