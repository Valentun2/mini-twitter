const loginBtn = document.querySelector(".js-login");
const registerBtn = document.querySelector(".js-register");

const loginBtnMobile = document.querySelector(".js-login-mobile");
const registerBtnMobile = document.querySelector(".js-register-mobile");

const toggleContainer = document.querySelector(".js-toggle-container");
registerBtn.addEventListener("click", toggle);
loginBtn.addEventListener("click", toggle);
loginBtnMobile.addEventListener("click", toggle);
registerBtnMobile.addEventListener("click", toggle);
function toggle(e) {
  toggleContainer.classList.toggle("active");
}
