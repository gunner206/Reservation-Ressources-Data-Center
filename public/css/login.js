/* =========================================
   1. GESTION DU SLIDER (LOGIN REGISTER)
   ========================================= */
const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

// Événement pour glisser vers l'inscription
registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

// Événement pour glisser vers la connexion
loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

/* =========================================
   2. GESTION DE L'OEIL (MOT DE PASSE)
   ========================================= */
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const iconImage = document.getElementById(iconId);
    const pathEyeOpen = "/images/eye.png";
    const pathEyeClosed = "/images/eye-slash.png";

    if (input.type === "password") {
        // AFFICHER LE MOT DE PASSE
        input.type = "text";
        iconImage.src = pathEyeClosed; // On change l'image pour "Oeil barré"
    } else {
        // CACHER LE MOT DE PASSE
        input.type = "password";
        iconImage.src = pathEyeOpen; // On remet l'image "Oeil ouvert"
    }
}

/* =========================================
   3. AUTO-OUVERTURE VIA URL (ex: ?action=signup)
   ========================================= */
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const action = urlParams.get('action');

    // Si l'URL contient ?action=signup, on active le panneau
    if (action === 'signup') {
        container.classList.add("active");
    }
});