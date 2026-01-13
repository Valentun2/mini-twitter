import { handleErrors, showMessage } from "./error.js";
import { hideBtnLoader, showBtnLoader } from "./helpers.js";
import { clearAllErrors, hideError, showError, validateEmail, validateName, validatePassword } from "./validation.js";

const registerForm = document.getElementById("register-form");

const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email-register");
const passwordInput = document.getElementById("password-register");
nameInput.addEventListener("blur", () => validateName(nameInput));
nameInput.addEventListener("input", () => hideError(nameInput));
emailInput.addEventListener("blur", () => validateEmail(emailInput));
emailInput.addEventListener("input", () => hideError(emailInput));
passwordInput.addEventListener("blur", () => validatePassword(passwordInput));
passwordInput.addEventListener("input", () => hideError(passwordInput));

registerForm.addEventListener("submit", handleRegisterForm);

async function handleRegisterForm(e) {
  e.preventDefault();
  clearAllErrors(registerForm);

  if (!validateName(nameInput) || !validatePassword(passwordInput) || !validateEmail(emailInput)) {
    return;
  }
  const formData = new FormData(registerForm);
  showBtnLoader(e.submitter);
  try {
    const res = await fetch("../api/register_action.php", {
      method: "POST",

      body: formData,
    });
    if (!res.ok) {
      handleErrors(res.status);
      return;
    }
    const data = await res.json();
    if (data.status === "success") {
      window.location.href = "../index.php";
      return;
    }
    for (const fieldName in data.errors) {
      if (data.errors[fieldName]) {
        showError(registerForm[fieldName], data.errors[fieldName]);
      }
    }
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  } finally {
    hideBtnLoader(e.submitter, "Зареєструватися");
  }
}
