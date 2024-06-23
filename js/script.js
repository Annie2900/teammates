window.addEventListener("DOMContentLoaded", init);

let timeout;
let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})')
let mediumPassword = new RegExp('((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))')

function init() {
    const strengthBadge = document.getElementById('strengthDisp');
    const registerFirstname = document.querySelector('#registerFirstname');
    const registerLastname = document.querySelector('#registerLastname');
    const registerEmail = document.querySelector('#registerEmail');
    const registerPassword = document.querySelector('#registerPassword');
    const registerPasswordConfirm = document.querySelector('#registerPasswordConfirm');
    const resetPassword = document.querySelector('#resetPassword');
    const resetPasswordConfirm = document.querySelector('#resetPasswordConfirm');
    const passwordEye = document.querySelector('#passwordEye');
    const fl = document.querySelector('#fl');
    const registerForm = document.querySelector('#registerForm');
    const loginForm = document.querySelector('#loginForm');
    const forgetForm = document.querySelector('#forgetForm');
    const resetForm = document.querySelector('#resetForm');
    const resetButton = document.querySelector('.resetButton');

    if (registerPassword !== null) {
        registerPassword.addEventListener("input", () => {
            strengthBadge.style.display = 'block'
            clearTimeout(timeout);

            timeout = setTimeout(() => strengthChecker(registerPassword.value, strengthBadge), 300);

            if (registerPassword.value.length !== 0) {
                strengthBadge.style.display != 'block'
            } else {
                strengthBadge.style.display = 'none'
            }
        });
    }

    if (resetPassword !== null) {
        resetPassword.addEventListener("input", () => {
            strengthBadge.style.display = 'block'
            clearTimeout(timeout);

            timeout = setTimeout(() => strengthChecker(resetPassword.value, strengthBadge), 300);

            if (resetPassword.value.length !== 0) {
                strengthBadge.style.display != 'block'
            } else {
                strengthBadge.style.display = 'none'
            }
        });
    }

    if (passwordEye !== null) {
        passwordEye.addEventListener('click', function () {
            let passwordVisibility = document.querySelector('.passwordVisibiliy');
            let type = passwordVisibility.getAttribute("type") === "password" ? "text" : "password";

            if (type === "text") {
                this.classList.remove('bi-eye-slash-fill');
                this.classList.add('bi-eye-fill');
            } else {
                this.classList.remove('bi-eye-fill');
                this.classList.add('bi-eye-slash-fill');
            }
            passwordVisibility.setAttribute("type", type);
        });
    }

    if (fl !== null) {
        fl.addEventListener('click', function (e) {
            let forgetForm = document.querySelector('#forgetForm');

            if (forgetForm.style.display !== "block") {
                forgetForm.style.display = "block";
                this.textContent = 'Űrlap elrejtése';
            } else {
                forgetForm.style.display = "none";
                this.textContent = 'Elfelejtette a jelszavát?';
            }

            e.preventDefault();
        });
    }

    if (registerForm !== null) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (validateForm()) this.submit();
        });
    }

    if (resetButton !== null) {
        resetButton.addEventListener('click', function (e) {
            const smalls = document.querySelectorAll('.field small');
            smalls.forEach((element) => {
                    element.innerText = '';
                    element.classList.remove('error');
                }
            );
            strengthBadge.style.display = 'none'
        });
    }

    if (loginForm !== null) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let isValid = true;

            const loginUsername = document.querySelector('#loginUsername');
            const loginPassword = document.querySelector('#loginPassword');

            if (isEmpty(loginUsername.value.trim())) {
                showErrorMessage(loginUsername, "Az e-mail mező nem lehet üres.");
                isValid = false;
            } else {
                hideErrorMessage(loginUsername);
            }

            if (isEmpty(loginPassword.value.trim())) {
                showErrorMessage(loginPassword, "A jelszó mező nem lehet üres.");
                isValid = false;
            } else {
                hideErrorMessage(loginPassword);
            }

            if (isValid) this.submit();
        });
    }

    if (forgetForm !== null) {
        forgetForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let isValid = true;

            const forgetEmail = document.querySelector('#forgetEmail');

            if (isEmpty(forgetEmail.value.trim())) {
                showErrorMessage(forgetEmail, 'Az e-mail mező nem lehet üres.');
                isValid = false;
            } else if (!isValidEmail(forgetEmail.value.trim())) {
                showErrorMessage(forgetEmail, 'Az e-mail formátuma nem megfelelő!');
                isValid = false;
            } else {
                hideErrorMessage(forgetEmail);
            }

            if (isValid) this.submit();
        });
    }

    if (resetForm !== null) {
        resetForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let isValid = true;

            const resetEmail = document.querySelector('#resetEmail');
            const resetPassword = document.querySelector('#resetPassword');
            const resetPasswordConfirm = document.querySelector('#resetPasswordConfirm');

            if (isEmpty(resetEmail.value.trim())) {
                showErrorMessage(resetEmail, 'Az e-mail mező nem lehet üres.');
                isValid = false;
            } else if (!isValidEmail(resetEmail.value.trim())) {
                showErrorMessage(resetEmail, 'Az e-mail formátuma nem megfelelő!');
                isValid = false;
            } else {
                hideErrorMessage(resetEmail);
            }

            if (isEmpty(resetPassword.value.trim())) {
                showErrorMessage(resetPassword, 'A jelszó mező nem lehet üres.');
                isValid = false;
            } else if (!isPasswordStrong(resetPassword.value.trim())) {
                showErrorMessage(resetPassword, 'A jelszó nem elég erős! (minimum 8 karakter, legalább 1 kisbetű, 1 nagybetű, 1 szám és 1 speciális karakter)');
                isValid = false;
            } else {
                hideErrorMessage(resetPassword);
            }

            if (isEmpty(resetPasswordConfirm.value.trim())) {
                showErrorMessage(resetPasswordConfirm, 'A jelszó nem lehet üres.');
                isValid = false;
            } else if (resetPassword.value.trim() !== resetPasswordConfirm.value.trim()) {
                showErrorMessage(resetPasswordConfirm, 'A jelszavak nem egyenlőek!');
                isValid = false;
            } else {
                hideErrorMessage(resetPasswordConfirm);
            }

            if (isValid) this.submit();
        });
    }

}


let validateForm = () => {


    let isValid = true;

    if (isEmpty(registerFirstname.value.trim())) {
        showErrorMessage(registerFirstname, "A keresztnév mező nem lehet üres.");
        isValid = false;
    } else {
        hideErrorMessage(registerFirstname);
    }

    if (isEmpty(registerLastname.value.trim())) {
        showErrorMessage(registerLastname, "A vezetéknév nem lehet üres.");
        isValid = false;
    } else {
        hideErrorMessage(registerLastname);
    }

    if (isEmpty(registerEmail.value.trim())) {
        showErrorMessage(registerEmail, 'Az e-mail mező nem lehet üres.');
        isValid = false;
    } else if (!isValidEmail(registerEmail.value.trim())) {
        showErrorMessage(registerEmail, 'Az e-mail formátuma nem megfelelő!');
        isValid = false;
        inputError(registerEmail);
    } else {
        hideErrorMessage(registerEmail);
    }

    if (isEmpty(registerPassword.value.trim())) {
        showErrorMessage(registerPassword, 'A jelszó nem lehet üres.');
        isValid = false;
    } else if (!isPasswordStrong(registerPassword.value.trim())) {
        showErrorMessage(registerPassword, 'A jelszó nem elég erős! (minimum 8 karakter, legalább 1 kisbetű, 1 nagybetű, 1 szám és 1 speciális karakter)');
        isValid = false;
    } else {
        hideErrorMessage(registerPassword);
    }

    if (isEmpty(registerPasswordConfirm.value.trim())) {
        showErrorMessage(registerPasswordConfirm, 'A jelszó nem lehet üres.');
        isValid = false;
    } else if (registerPassword.value.trim() !== registerPasswordConfirm.value.trim()) {
        showErrorMessage(registerPasswordConfirm, 'A jelszavak nem egyenlőek!');
        isValid = false;
    } else {
        hideErrorMessage(registerPasswordConfirm);
    }


    return isValid;
};

const isEmpty = value => value === '';

const isValidEmail = (email) => {
    let rex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
    return rex.test(email);
}

const strengthChecker = (enteredPassword, strengthBadge) => {

    if (strongPassword.test(enteredPassword)) {
        strengthBadge.style.backgroundColor = "green";
        strengthBadge.textContent = 'Erős';
    } else if (mediumPassword.test(enteredPassword)) {
        strengthBadge.style.backgroundColor = 'blue';
        strengthBadge.textContent = 'Közepes';
    } else {
        strengthBadge.style.backgroundColor = 'red';
        strengthBadge.textContent = 'Gyenge';
    }
}

const showErrorMessage = (field, message) => {
    const error = field.nextElementSibling;
    error.classList.add('error');
    error.innerText = message;
};

const hideErrorMessage = (field) => {
    const error = field.nextElementSibling;
    error.classList.remove('error');
    error.innerText = '';
}

const isPasswordStrong = () => {
    const strengthBadge = document.getElementById('strengthDisp');
    return strengthBadge.innerText === 'Erős';
}


