import { clearAllErrors, hideError, validateEmail, validateName, validatePassword } from "./validation.js";

const registerForm = document.getElementById("register-form");

const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email-register");
const passwordInput = document.getElementById("password-register");
// function validateRegistrationForm() {}
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

  // console.log("object");
  if (validateName(nameInput) && validatePassword(passwordInput) && validateEmail(emailInput)) {
    const formData = new FormData(registerForm);
    // const plainObject = Object.fromEntries(formData.entries());
    // console.log(plainObject);
    const res = await fetch("../api/register_action.php", {
      method: "POST",

      body: formData,
    });

    const data = await res.json();
    if (data.success) {
      window.location.href = "../index.php";
      return;
    }
    console.log(data);
    for (const fieldName in data.errors) {
      // console.log(fieldName);
      if (!data.errors[fieldName]) {
        // hideError(registerForm[fieldName]);
        showError(registerForm[fieldName], data.errors[fieldName]);
      }
    }
  }
}
