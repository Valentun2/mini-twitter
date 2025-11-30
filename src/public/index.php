<?php
// 1. ПОЧАТОК PHP-БЛОКУ
session_start();


require_once __DIR__ . '/includes/auth_guard.php';

// require_once __DIR__ . '/api/get_all_tweet.php';
require_once __DIR__ . '/includes/helpers.php'

// 4. ІМПОРТ КЛАСІВ

?>

<!DOCTYPE html>
<html lang="uk">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Головна - Міні-Твіттер</title>
  <link rel="stylesheet" href="/css/style.css">

</head>

<body class="" data-user-id="<?= $_SESSION['user_id'] ?? '' ?>">
  <header>
    <h1>Міні-Твіттер</h1>
    <div class="svg-wrraper dropdown">
      <svg class="icon icon-user js-icon-user">
        <use xlink:href="#icon-user"></use>
        <symbol id="icon-user" viewBox="0 0 32 32">
          <path d="M18 22.082v-1.649c2.203-1.241 4-4.337 4-7.432 0-4.971 0-9-6-9s-6 4.029-6 9c0 3.096 1.797 6.191 4 7.432v1.649c-6.784 0.555-12 3.888-12 7.918h28c0-4.030-5.216-7.364-12-7.918z"></path>
        </symbol>
      </svg>

      <ul class="dropdown-content">
        <li> <span id="welcome-message"><?php echo htmlspecialchars($_SESSION["user_name"]) ?></span>
        </li>
        <li> <a class="logout" href="/api/logout.php" style="margin-left: 15px">Вийти</a>
        </li>
      </ul>
    </div>

    <!-- <dialog class="js-user-modal">

    </dialog> -->
  </header>

  <main class="container">



    <button class="add" id="open-tweet-modal" class="js-button">Додати твіт</button>
    <section class="js-tweet-container">

      <template id="tweet-template">
        <article class="js-tweet-card tweet-card">
          <div class="tweet-meta">
            <span class="js-tweet-name"></span> <span class="js-tweet-date"></span>
          </div>
          <p class="js-tweet-text"></p>
          <div class="tweet-actions">
            <button class="js-delete-btn modal-actions button" type="button">🗑️</button>
            <button class="js-edit-btn modal-actions button" type="button">edit</button>
            <button class="js-like-btn" data-tweet-id="1">
              ❤️ <span class="like-count js-like-count">0</span>
            </button>
          </div>
        </article>
      </template>
    </section>


    <dialog class="confirm-delete">
      <p tabindex="-1" autofocus>Видалити tweet?</p>


      <div class="actions">
        <button class="cancel-btn js-cancel-btn" type="button">Ні</button>
        <button class="confirm-btn js-confirm-btn" type="button">Так</button>
      </div>
    </dialog>

    <dialog class="tweet-modals js-add-modal" id="tweet-modal">

      <h2 class="modal-title">Новий твіт</h2>
      <form class="js-add-form">

        <div>
          <label for="tweet-text">Що у вас на думці?</label>
          <textarea id="tweet-text" name="text" rows="4" cols="50" required></textarea>
        </div>

        <div class="modal-actions">
          <button type="button" id="close-tweet-modal">Скасувати</button>
          <button type="submit">Опублікувати</button>
        </div>
      </form>

    </dialog>
    <dialog class="tweet-modals" id="edit-modal">

      <h2>Редагувати твіт</h2>
      <form id="edit-tweet-form">

        <div>
          <textarea id="edit-tweet-text" name="text" rows="4" cols="50" required></textarea>
        </div>

        <div class="modal-actions">
          <button type="button" id="close-edit-modal">Скасувати</button>
          <button type="submit">Опублікувати</button>
        </div>
      </form>

    </dialog>

  </main>

  <script src="./js/main.js"></script>

</body>



</html>