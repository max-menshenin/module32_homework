const login = document.querySelector('#login');
const password = document.querySelector('#password');
const confirmPassword = document.querySelector('#confirmPassword');
const error = document.querySelector('.error');

// фокусы на поля ввода
if (confirmPassword) { if (confirmPassword.value.trim() === '') confirmPassword.focus(); }
if (password) { if (password.value.trim() === '') password.focus(); }
if (login) { if (login.value.trim() === '') login.focus(); }

// удаляем сообщение об ошибке через 5 сек
if (error) {
    error.style.opacity = 1;
    setTimeout(() => {
        setInterval(() => error.style.opacity -= 0.01, 10);
    }, 4000);
}