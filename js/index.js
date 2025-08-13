// js/index.js

const loginForm = document.querySelector("form.form");
const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");

function lockForm(seconds) {
    const submitBtn = loginForm.querySelector("button[type='submit']");
    const lockedMsgSpan = document.getElementById("locked-seconds");

    usernameInput.disabled = true;
    passwordInput.disabled = true;
    submitBtn.disabled = true;

    let remaining = seconds;

    const timer = setInterval(() => {
        if (lockedMsgSpan) {
            lockedMsgSpan.textContent = remaining;
        }
        remaining--;
        if (remaining < 0) {
            clearInterval(timer);
            if (lockedMsgSpan) {
                lockedMsgSpan.parentElement.style.display = "none";
            }
            usernameInput.disabled = false;
            passwordInput.disabled = false;
            submitBtn.disabled = false;
            usernameInput.focus();
        }
    }, 1000);
}

// Detecta timestamp real de desbloqueio
const urlParams = new URLSearchParams(window.location.search);
const lockedUntil = parseInt(urlParams.get("locked_until") ?? "0", 10);

if (lockedUntil > 0) {
    const now = Math.floor(Date.now() / 1000);
    let remaining = lockedUntil - now;
    if (remaining > 0) {
        document.getElementById("locked-message").style.display = "block";
        lockForm(remaining);
    }
}

// Submissão normal do formulário
loginForm.addEventListener("submit", function (e) {
    // Backend controla tudo, JS não interfere
});
