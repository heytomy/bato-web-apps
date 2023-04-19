import 'bootstrap';

const $ = require('jquery');

const passwordField1 = document.getElementById('registration_form_plainPassword_first');
const passwordField2 = document.getElementById('registration_form_plainPassword_second');

var icon1 = document.querySelector("#show-password-icon-1 i");
var icon2 = document.querySelector("#show-password-icon-2 i");

icon1.addEventListener('click', () => {
    if (passwordField1.type && passwordField2.type === 'password') {
        passwordField1.type = 'text';
        passwordField2.type = 'text';
        icon1.classList.remove("fa-eye");
        icon1.classList.add("fa-eye-slash");
        icon2.classList.remove("fa-eye");
        icon2.classList.add("fa-eye-slash");
    } else {
        passwordField1.type = 'password';
        passwordField2.type = 'password';
        icon1.classList.remove("fa-eye-slash");
        icon1.classList.add("fa-eye");
        icon2.classList.remove("fa-eye-slash");
        icon2.classList.add("fa-eye");
    }
});

icon2.addEventListener('click', () => {
    if (passwordField1.type && passwordField2.type === 'password') {
        passwordField1.type = 'text';
        passwordField2.type = 'text';
        icon1.classList.remove("fa-eye");
        icon1.classList.add("fa-eye-slash");
        icon2.classList.remove("fa-eye");
        icon2.classList.add("fa-eye-slash");
    } else {
        passwordField1.type = 'password';
        passwordField2.type = 'password';
        icon1.classList.remove("fa-eye-slash");
        icon1.classList.add("fa-eye");
        icon2.classList.remove("fa-eye-slash");
        icon2.classList.add("fa-eye");
    }
});