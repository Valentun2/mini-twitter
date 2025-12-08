import { addTweet, confirmDeleteTweet, handleEditForm, tweetsMap } from "./tweets.js";
import { hideError, validateTextarea } from "./validation.js";

const addModal = document.querySelector(".js-add-modal");
const openButton = document.getElementById("open-tweet-modal");
const closeButton = document.getElementById("close-tweet-modal");

openButton.addEventListener("click", () => {
  addModal.showModal();
});

closeButton.addEventListener("click", () => {
  addModal.close();
});

addModal.addEventListener("click", (e) => {
  if (e.target === addModal) {
    addModal.close();
  }
});

//    EDIT

const editModal = document.getElementById("edit-modal");
const editForm = document.getElementById("edit-tweet-form");
const textArea = document.getElementById("edit-tweet-text");
const closeEditModalBtn = document.getElementById("close-edit-modal");

closeEditModalBtn.addEventListener("click", () => editModal.close());

textArea.addEventListener("blur", () => validateTextarea(textArea));
textArea.addEventListener("input", () => hideError(textArea));
editForm.addEventListener("submit", (e) => handleEditForm(e, editModal));

export function openEditModal(btn) {
  const tweetId = btn.dataset.tweetId;
  editForm.dataset.tweetId = tweetId;
  textArea.value = tweetsMap.get(Number(tweetId)) || "";

  editModal.showModal();
}

//   ADD

const addForm = document.querySelector(".js-add-form");
const addFormField = document.getElementById("tweet-text");
addFormField.addEventListener("blur", () => validateTextarea(addFormField));
addFormField.addEventListener("input", () => hideError(addFormField));

addForm.addEventListener("submit", (e) => addTweet(e, addModal));

//  DELETE

const deleteModal = document.querySelector(".confirm-delete");

const confirmBtn = document.querySelector(".js-confirm-btn");
const cancelConfirmBtn = document.querySelector(".js-cancel-btn");
cancelConfirmBtn.addEventListener("click", () => {
  deleteModal.close();
});
confirmBtn.addEventListener("click", (e) => confirmDeleteTweet(e, deleteModal));

deleteModal.addEventListener("click", (e) => {
  if (e.target === deleteModal) {
    deleteModal.close();
  }
});

export function handleBtnDelete(btn) {
  confirmBtn.dataset.tweetId = btn.dataset.tweetId;
  deleteModal.showModal();
}
