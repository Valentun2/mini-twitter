import { handleErrors, showMessage } from "./error.js";
import { hideBtnLoader, showBtnLoader } from "./helpers.js";
import { clearAllErrors, hideError, showError, validateEmail, validatePassword } from "./validation.js";

const loginForm = document.getElementById("login-form");

loginForm.addEventListener("submit", handleLoginForm);
const emailLoginInput = document.getElementById("email-login");
const passwordLogindInput = document.getElementById("password-login");
async function handleLoginForm(e) {
  e.preventDefault();
  clearAllErrors(loginForm);

  if (!validateEmail(emailLoginInput) || !validatePassword(passwordLogindInput)) {
    return;
  }

  const formData = new FormData(loginForm);
  showBtnLoader(e.submitter);
  try {
    const res = await fetch("/api/login_action.php", {
      method: "POST",
      body: formData,
    });
    if (!res.ok) {
      handleErrors(res.status);
      return;
    }
    const data = await res.json();

    console.log(data);
    if (data.status === "success") {
      window.location.href = "/index.php";
      return;
    }

    for (const fieldName in data.errors) {
      if (data.errors[fieldName]) {
        showError(loginForm[fieldName], data.errors[fieldName]);
      }
    }
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  } finally {
    hideBtnLoader(e.submitter, "Увійти");
  }
}

emailLoginInput.addEventListener("blur", () => validateEmail(emailLoginInput));
emailLoginInput.addEventListener("input", () => hideError(emailLoginInput));
passwordLogindInput.addEventListener("blur", () => validatePassword(passwordLogindInput));
passwordLogindInput.addEventListener("input", () => hideError(passwordLogindInput));
