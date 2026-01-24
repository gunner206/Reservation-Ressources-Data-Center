/* =========================================
   GESTION DU SLIDER (ANIMATION)
   ========================================= */
const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

if (registerBtn && loginBtn && container) {
    // Quand on clique sur "S'inscrire" (dans le panneau de droite)
    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    // Quand on clique sur "Se connecter" (dans le panneau de gauche)
    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
}

/* =========================================
   GESTION DE L'OEIL (MOT DE PASSE)
   ========================================= */
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    
    // Si le type est password, on le passe en text (visible)
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash"); // Change l'icône
    } else {
        // Sinon, on remet en password (caché)
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye"); // Remet l'icône originale
    }
}
    const urlParams = new URLSearchParams(window.location.search);
    const action = urlParams.get('action');


    // 3. Si l'action est 'signup', on active le panneau d'inscription direct !
    if (action === 'signup') {
        container.classList.add("active");
    }