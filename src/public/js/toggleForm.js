const loginBtn = document.querySelector(".js-login");
const registerBtn = document.querySelector(".js-register");

const toggleContainer = document.querySelector(".js-toggle-container");
registerBtn.addEventListener("click", toggle);
loginBtn.addEventListener("click", toggle);
function toggle(e) {
  console.log("object");
  toggleContainer.classList.toggle("active");
}
