// this code is used to switch between sign in and sign up forms

const signUpButton=document.getElementById('signUpButton');
const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpForm=document.getElementById('signup');
signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})
signInButton.addEventListener('click', function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
})

// for Password hide
// Toggle Password Visibility
// const togglePassword = document.querySelector('#togglePassword');
// togglePassword.textContent = '🔒'; // Default lock icon

// const password = document.querySelector('#password');

// togglePassword.addEventListener('click', () => {
//     // Toggle the type attribute
//     const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
//     password.setAttribute('type', type);

//     // Change the icon
//     togglePassword.textContent = type === 'password' ? '🔒' : '👁️';
// });


// const togglePassword = document.querySelector('#togglePassword');
// console.log(togglePassword);
// document.addEventListener('DOMContentLoaded', () => {
//     const togglePassword = document.querySelector('#togglePassword');
//     const password = document.querySelector('#password');

//     togglePassword.textContent = '🔒'; // Default icon

//     togglePassword.addEventListener('click', () => {
//         const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
//         password.setAttribute('type', type);
//         togglePassword.textContent = type === 'password' ? '🔒' : '👁️';
//     });
// });
// document.addEventListener('DOMContentLoaded', () => {
//     // Select the toggle icon and the password input field
//     const togglePassword = document.querySelector('#togglePassword');
//     const passwordField = document.querySelector('#password');

//     // Add a click event listener to the icon
//     togglePassword.addEventListener('click', () => {
//         // Toggle the input field's type between "password" and "text"
//         const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
//         passwordField.setAttribute('type', type);

//         // Change the icon based on the visibility state
//         togglePassword.textContent = type === 'password' ? '👁️' : '🔒';
//     });
// });



