// Get elements
const loginBtn = document.getElementById('login-btn');
const registerBtn = document.getElementById('register-btn');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const switchToRegister = document.getElementById('switch-to-register');
const switchToLogin = document.getElementById('switch-to-login');

// Functions to switch forms
function showLogin() {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    loginBtn.classList.add('active-btn');
    registerBtn.classList.remove('active-btn');
}

function showRegister() {
    registerForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
    registerBtn.classList.add('active-btn');
    loginBtn.classList.remove('active-btn');
}

// Event listeners
loginBtn.addEventListener('click', showLogin);
registerBtn.addEventListener('click', showRegister);
switchToRegister.addEventListener('click', showRegister);
switchToLogin.addEventListener('click', showLogin);
