export function showError(el, errorText) {
  const formGroup = el.closest(".form-group");

  formGroup.lastElementChild.textContent = errorText;
}

export function hideError(el) {
  const formGroup = el.closest(".form-group");

  formGroup.lastElementChild.textContent = "";
}
export function clearAllErrors(form) {
  const errorMessages = form.querySelectorAll(".error");
  errorMessages.forEach((el) => {
    el.textContent = "";
  });
}

export function validateName(el) {
  const name = el.value.trim();
  if (name.length < 3) {
    showError(el, "Ім'я користувача має бути не менше 3 символів.");
    return false;
  }

  if (name.length > 20) {
    showError(el, "Ім'я користувача має бути не більше 20 символів.");
    return false;
  }
  hideError(el);
  return true;
}

export function validateEmail(el) {
  const email = el.value.trim();
  if (email === "") {
    showError(el, "Поле email не може бути порожнім.");
    return false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    showError(el, "Введіть правильний email!");
    return false;
  }
  hideError(el);
  return true;
}

export function validatePassword(el) {
  const password = el.value;
  if (password.length < 6) {
    showError(el, "Пароль користувача має бути не менше 6 символів.");
    return false;
  }
  hideError(el);

  return true;
}
