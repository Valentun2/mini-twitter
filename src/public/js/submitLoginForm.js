import { clearAllErrors, hideError, showError, validateEmail, validatePassword } from "./validation.js";

const loginForm = document.getElementById("login-form");

loginForm.addEventListener("submit", handleLoginForm);
const emailLoginInput = document.getElementById("email-login");
const passwordLogindInput = document.getElementById("password-login");
async function handleLoginForm(e) {
  e.preventDefault();
  clearAllErrors(loginForm);
  const formData = new FormData(loginForm);
  const plainObject = Object.fromEntries(formData.entries());
  console.log(plainObject);

  try {
    const res = await fetch("../api/login_action.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();

    console.log(data);
    if (data.success) {
      window.location.href = "../index.php";
      return;
    }

    for (const fieldName in data.errors) {
      if (data.errors[fieldName]) {
        showError(loginForm[fieldName], data.errors[fieldName]);
      }
    }
  } catch (error) {}
  //   if (validateEmail(emailLoginInput) && validatePassword(passwordLogindInput)) {
  //   }
}
// console.log(emailLoginInput);

emailLoginInput.addEventListener("blur", () => validateEmail(emailLoginInput));
emailLoginInput.addEventListener("input", () => hideError(emailLoginInput));
passwordLogindInput.addEventListener("blur", () => validatePassword(passwordLogindInput));
passwordLogindInput.addEventListener("input", () => hideError(passwordLogindInput));
