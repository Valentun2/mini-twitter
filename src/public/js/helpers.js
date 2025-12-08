// LOADER

const globalLoader = document.querySelector(".js-backdrop");

export function showLoader() {
  globalLoader.classList.remove("is-hidden");
}

export function hideLoader() {
  globalLoader.classList.add("is-hidden");
}

export function showBtnLoader(btn) {
  btn.firstChild.textContent = "";
  btn.classList.remove("is-hidden");
}

export function hideBtnLoader(btn, text) {
  btn.firstChild.textContent = text;
  btn.classList.add("is-hidden");
}

export function timeAgo(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const seconds = Math.floor((now - date) / 1000);

  let interval = Math.floor(seconds / 31536000);
  if (interval >= 1) return interval + " р. тому";

  interval = Math.floor(seconds / 2592000);
  if (interval >= 1) return interval + " міс. тому";

  interval = Math.floor(seconds / 86400);
  if (interval >= 1) {
    if (interval === 1) return "вчора";
    return interval + " дн. тому";
  }

  interval = Math.floor(seconds / 3600);
  if (interval >= 1) return interval + " год. тому";

  interval = Math.floor(seconds / 60);
  if (interval >= 1) return interval + " хв. тому";

  return "щойно";
}
