let timeoutId = null;

export function showMessage(text, type = "error") {
  const msgBox = document.querySelector(".js-app-msg");
  if (timeoutId) {
    clearTimeout(timeoutId);
  }
  msgBox.showPopover();
  msgBox.textContent = text;

  msgBox.classList.remove("success", "error");

  msgBox.classList.add(type);
  msgBox.classList.add("show");

  setTimeout(() => {
    msgBox.classList.remove("show");
    setTimeout(() => {
      msgBox.hidePopover();
    }, 300);
  }, 3000);
}

export function handleErrors(err) {
  switch (err) {
    case 400:
      showMessage("Невірні дані", "error");
      break;
    case 401:
      showMessage("Ви не авторизовані!", "error");
      break;
    case 403:
      showMessage("У вас немає прав редагувати цей твіт", "error");
      break;
    case 500:
      showMessage("Помилка сервера. Спробуйте пізніше", "error");
      break;
    default:
      showMessage("Сталася невідома помилка", "error");
      break;
  }
}
