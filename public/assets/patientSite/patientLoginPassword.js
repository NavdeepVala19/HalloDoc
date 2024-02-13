const passwordField = document.getElementById('exampleInputPassword1');
const togglePassword = document.querySelector('.person-eye');

const confirmpasswordField = document.getElementById('exampleInputPassword2');
const togglePasswordTwo = document.querySelector('.person-eye-two');

togglePassword.addEventListener('click', () => {
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        togglePassword.classList.remove('bi-eye-fill');
        togglePassword.classList.add('bi-eye-slash-fill');
    } else {
        passwordField.type = 'password';
        togglePassword.classList.remove('bi-eye-slash-fill');
        togglePassword.classList.add('bi-eye-fill');
    }
});



togglePasswordTwo.addEventListener('click', () => {
    if (confirmpasswordField.type === 'password') {
        confirmpasswordField.type = 'text';
        togglePasswordTwo.classList.remove('bi-eye-fill');
        togglePasswordTwo.classList.add('bi-eye-slash-fill');
    } else {
        confirmpasswordField.type = 'password';
        togglePasswordTwo.classList.remove('bi-eye-slash-fill');
        togglePasswordTwo.classList.add('bi-eye-fill');
    }
});
