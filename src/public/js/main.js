const currentUserId = document.body.dataset.userId;

const tweetsMap = new Map();

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

// EDIT MODAL
const editModal = document.getElementById("edit-modal");
const editForm = document.getElementById("edit-tweet-form");
const textArea = document.getElementById("edit-tweet-text");
const closeEditModalBtn = document.getElementById("close-edit-modal");

closeEditModalBtn.addEventListener("click", () => editModal.close());

function openEditModal(btn) {
  const tweetId = btn.dataset.tweetId;
  editForm.dataset.tweetId = tweetId;
  textArea.value = tweetsMap.get(Number(tweetId)) || "";

  editModal.showModal();
}

editForm.addEventListener("submit", handleEditForm);
async function handleEditForm(e) {
  e.preventDefault();
  const tweetId = Number(editForm.dataset.tweetId);

  if (tweetsMap.get(tweetId) === newText) {
    return;
  }
  const newData = {
    tweet_id: tweetId,
    new_text: newText,
  };
  try {
    const res = await fetch("../api/edit_tweet_action.php", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newData),
    });
    const data = await res.json();
    console.log(data);
    if (data.status === "success") {
      tweetsMap.set(tweetId, data.newText);
      const tweetCard = document.querySelector(`[data-tweet-id="${tweetId}"]`);
      tweetCard.querySelector(".js-tweet-text").textContent = data.newText;
      editModal.close();
    }
  } catch (error) {
    console.error(error);
  }
}

//   LIKE

async function toggleLike(btn) {
  const formData = new FormData();
  formData.append("tweet_id", btn.dataset.tweetId);
  try {
    const res = await fetch("../api/toggle_like_action.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();

    const countSpan = btn.querySelector(".js-like-count");
    if (countSpan) countSpan.textContent = data.new_count;
  } catch (error) {
    console.log(error);
  }
}

// DELETE
const deleteModal = document.querySelector(".confirm-delete");

const deleteBtn = document.querySelectorAll(".js-delete-btn");
const confirmBtn = document.querySelector(".js-confirm-btn");
const cancelConfirmBtn = document.querySelector(".js-cancel-btn");
cancelConfirmBtn.addEventListener("click", () => {
  deleteModal.close();
});

deleteModal.addEventListener("click", (e) => {
  if (e.target === deleteModal) {
    deleteModal.close();
  }
});

function handleBtnDelete(btn) {
  confirmBtn.dataset.tweetId = btn.dataset.tweetId;
  deleteModal.showModal();
}

confirmBtn.addEventListener("click", confirmDeleteTweet);

async function confirmDeleteTweet(e) {
  const tweetId = e.target.dataset.tweetId;
  console.dir(e.target);

  try {
    const res = await fetch("../api/delete_tweet_action.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ tweet_id: tweetId }),
    });
    const data = await res.json();
    console.log(data);
    if (data.status === "success") {
      const tweetCard = document.querySelector(`[data-tweet-id="${tweetId}"]`);

      tweetCard.remove();
      deleteModal.close();
      return;
    } else {
      alert(data.error);
    }
  } catch (error) {
    console.log(error);
  }
}

const addForm = document.querySelector(".js-add-form");

addForm.addEventListener("submit", addTweet);

async function addTweet(e) {
  e.preventDefault();

  const formData = new FormData(addForm);

  try {
    const res = await fetch("/api/add_tweet_action.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    tweetsMap.set(data.newTweet.id, data.newTweet.text);

    renderTweet(data.newTweet);
    addModal.close();
  } catch (error) {
    console.error(error);
  }
}

const tweetTemplate = document.getElementById("tweet-template");
const container = document.querySelector(".js-tweet-container");

container.addEventListener("click", handleClick);

function handleClick(e) {
  if (e.target.classList.contains("js-like-btn")) {
    toggleLike(e.target);
  }
  if (e.target.classList.contains("js-edit-btn")) {
    openEditModal(e.target);
  }
  if (e.target.classList.contains("js-delete-btn")) {
    handleBtnDelete(e.target);
  }
}

function renderTweet(tweet) {
  const templateClone = tweetTemplate.content.cloneNode(true);

  const newTweetCard = templateClone.querySelector(".js-tweet-card");

  newTweetCard.dataset.tweetId = tweet.id;
  newTweetCard.querySelector(".js-tweet-name").textContent = tweet.name;
  newTweetCard.querySelector(".js-tweet-date").textContent = `Опубліковано: ${tweet.created_at}`;
  newTweetCard.querySelector(".js-tweet-text").textContent = tweet.text;
  newTweetCard.querySelector(".js-like-count").textContent = ` ${tweet.like_count}`;
  const editBtn = newTweetCard.querySelector(".js-edit-btn");

  const deleteBtn = newTweetCard.querySelector(".js-delete-btn");
  console.dir(editBtn);
  if (tweet.user_id !== Number(currentUserId)) {
    deleteBtn.remove();
    editBtn.remove();
  } else {
    editBtn.dataset.tweetId = tweet.id;
    editBtn.dataset.currentText = tweet.text;
    deleteBtn.dataset.tweetId = tweet.id;
  }
  newTweetCard.querySelector(".js-like-btn").dataset.tweetId = tweet.id;

  container.prepend(newTweetCard);
}

async function getAllTweets() {
  try {
    const res = await fetch("/api/get_all_tweet.php", {
      method: "GET",
    });
    const data = await res.json();
    const tweets = [...data.tweets].sort((a, b) => {
      return new Date(a.created_at) - new Date(b.created_at);
    });

    tweets.forEach((tweet) => {
      tweetsMap.set(tweet.id, tweet.text);
      renderTweet(tweet);
    });
  } catch (error) {
    console.log(error);
  }
}
getAllTweets();

// const svg = document.querySelector(".js-icon-user");
// const userModal = document.querySelector(".js-user-modal");

// svg.addEventListener("click", handleClickSvg);

// function handleClickSvg(e) {
//   console.log(e);

//   if (userModal.open) {
//     userModal.close();
//   } else {
//     userModal.showModal();
//   }
// }
