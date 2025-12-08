import { handleBtnDelete, openEditModal } from "./modals.js";
import { toggleLike } from "./tweets.js";

export const container = document.querySelector(".js-tweet-container");

container.addEventListener("click", handleClick);

function handleClick(e) {
  if (e.target.classList.contains("js-like-btn")) {
    e.target.classList.toggle("is-active");
    toggleLike(e.target);
  }
  if (e.target.classList.contains("js-edit-btn")) {
    openEditModal(e.target);
  }
  if (e.target.classList.contains("js-delete-btn")) {
    handleBtnDelete(e.target);
  }
}
