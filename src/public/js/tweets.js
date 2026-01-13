import { handleErrors, showMessage } from "./error.js";
import { hideBtnLoader, hideLoader, showBtnLoader, showLoader, timeAgo } from "./helpers.js";
import { showError, validateTextarea } from "./validation.js";

const currentUserId = document.body.dataset.userId;
export const tweetsMap = new Map();

export async function handleEditForm(e, modal) {
  e.preventDefault();
  if (!validateTextarea(e.target[0])) {
    return;
  }

  const tweetId = Number(e.target.dataset.tweetId);
  const newText = e.target[0].value;
  if (tweetsMap.get(tweetId) === newText) {
    showError(e.target[0], "Текст не було змінено");
    return;
  }
  const newData = {
    tweet_id: tweetId,
    new_text: newText,
  };
  try {
    showBtnLoader(e.submitter);
    const res = await fetch("/api/edit_tweet_action.php", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newData),
    });
    if (!res.ok) {
      handleErrors(res.status);
      return;
    }
    const data = await res.json();
    if (data.status === "success") {
      tweetsMap.set(tweetId, data.newText);
      const tweetCard = document.querySelector(`[data-tweet-id="${tweetId}"]`);
      tweetCard.querySelector(".js-tweet-text").textContent = data.newText;
      showMessage("Твіт успішно змінено", "success");
      modal.close();
    }
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  } finally {
    hideBtnLoader(e.submitter, "Опублікувати");
  }
}

//   ADD

export async function addTweet(e, modal) {
  console.dir(e.submitter);
  e.preventDefault();
  if (!validateTextarea(e.target[0])) {
    return;
  }
  const formData = new FormData(e.target);

  try {
    showBtnLoader(e.submitter);
    const res = await fetch("/api/add_tweet_action.php", {
      method: "POST",
      body: formData,
    });
    if (!res.ok) {
      handleErrors(res.status);
      return;
    }
    const data = await res.json();

    tweetsMap.set(data.tweet.id, data.tweet.text);

    renderTweet(data.tweet);
    modal.close();
    e.target.reset();
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  } finally {
    hideBtnLoader(e.submitter, "Опублікувати");
  }
}

//   DELETE

export async function confirmDeleteTweet(e, modal) {
  const tweetId = e.target.dataset.tweetId;

  try {
    showBtnLoader(e.target);
    const res = await fetch("/api/delete_tweet_action.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ tweet_id: tweetId }),
    });

    if (!res.ok) {
      handleErrors(res.status);
      return;
    }
    const data = await res.json();
    if (data.status === "success") {
      const tweetCard = document.querySelector(`[data-tweet-id="${tweetId}"]`);

      tweetCard.remove();
      modal.close();
      return;
    }
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  } finally {
    hideBtnLoader(e.target, "Так");
  }
}

// LIKE

export async function toggleLike(btn) {
  const formData = new FormData();
  formData.append("tweet_id", btn.dataset.tweetId);
  try {
    const res = await fetch("/api/toggle_like_action.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    const countSpan = btn.querySelector(".js-like-count");
    if (countSpan) countSpan.textContent = data.new_count;
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  }
}

async function getAllTweets() {
  showLoader();
  try {
    const res = await fetch("/api/get_all_tweet.php", {
      method: "GET",
    });
    if (!res.ok) {
      handleErrors(res.status);
      return;
    }
    const data = await res.json();
    [...data.tweets].forEach((tweet) => {
      tweetsMap.set(tweet.id, tweet.text);
      renderTweet(tweet);
    });
  } catch (error) {
    showMessage("Помилка під час з’єднання з сервером", "error");
  } finally {
    hideLoader();
  }
}
getAllTweets();

const tweetTemplate = document.getElementById("tweet-template");
export const container = document.querySelector(".js-tweet-container");

export function renderTweet(tweet) {
  const templateClone = tweetTemplate.content.cloneNode(true);

  const newTweetCard = templateClone.querySelector(".js-tweet-card");

  newTweetCard.dataset.tweetId = tweet.id;
  newTweetCard.querySelector(".js-tweet-name").textContent = tweet.name;
  newTweetCard.querySelector(".js-tweet-date").textContent = `Опубліковано: ${timeAgo(tweet.created_at)}`;
  newTweetCard.querySelector(".js-tweet-text").textContent = tweet.text;
  if (tweet.is_liked) {
    newTweetCard.querySelector(".js-like-btn").classList.add("is-active");
  }

  newTweetCard.querySelector(".js-like-count").textContent = ` ${tweet.like_count}`;
  const editBtn = newTweetCard.querySelector(".js-edit-btn");

  const deleteBtn = newTweetCard.querySelector(".js-delete-btn");
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
