<?php
session_start();


require_once __DIR__ . '/includes/auth_guard.php';



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
  <div class="app-message js-app-msg" popover="manual"></div>

  <header>
    <h1>Міні-Твіттер</h1>
    <div class="svg-wrapper dropdown">
      <p id="welcome-message"><?php echo htmlspecialchars($_SESSION["user_name"]) ?></p>
      <svg class="icon icon-user js-icon-user">
        <use xlink:href="#icon-user"></use>
        <symbol id="icon-user" viewBox="0 0 32 32">
          <path d="M18 22.082v-1.649c2.203-1.241 4-4.337 4-7.432 0-4.971 0-9-6-9s-6 4.029-6 9c0 3.096 1.797 6.191 4 7.432v1.649c-6.784 0.555-12 3.888-12 7.918h28c0-4.030-5.216-7.364-12-7.918z"></path>
        </symbol>
      </svg>

      <ul class="dropdown-content">

        <li> <a class="logout" href="/api/logout.php">Вийти</a>
        </li>
      </ul>

    </div>


  </header>

  <main class="container">



    <button class="add-tweet" id="open-tweet-modal" class="js-button">Додати твіт</button>
    <section class="js-tweet-container">

      <template id="tweet-template">
        <article class="js-tweet-card tweet-card">
          <div class="tweet-meta">
            <span class="js-tweet-name"></span> <span class="js-tweet-date"></span>
          </div>
          <p class="js-tweet-text"></p>
          <div class="tweet-actions">
            <button class="js-delete-btn modal-actions  svg-btn" type="button"><svg class="icon-delete" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
              </svg></button>
            <button class="js-edit-btn modal-actions  svg-btn" type="button">
              <svg class="icon-pencil" viewBox="0 0 32 32">
                <path d="M27 0c2.761 0 5 2.239 5 5 0 1.126-0.372 2.164-1 3l-2 2-7-7 2-2c0.836-0.628 1.874-1 3-1zM2 23l-2 9 9-2 18.5-18.5-7-7-18.5 18.5zM22.362 11.362l-14 14-1.724-1.724 14-14 1.724 1.724z"></path>
              </svg>
            </button>
            <button class=" button-like js-like-btn" data-tweet-id="1">
              <svg class="icon-heart" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg> <span class="like-count js-like-count">0</span>
            </button>
          </div>
        </article>
      </template>
    </section>


    <dialog class="confirm-delete">
      <p tabindex="-1" autofocus>Видалити tweet?</p>


      <div class="actions">
        <button class="cancel-btn js-cancel-btn" type="button">Ні</button>
        <button class="confirm-btn js-confirm-btn is-hidden" type="button"><span>Так</span> <span class="spinner spinner-btn"></span></button>
      </div>
    </dialog>

    <dialog class="tweet-modals js-add-modal" id="tweet-modal">

      <h2 class="modal-title">Новий твіт</h2>
      <form class="js-add-form" novalidate>

        <div class="form-group">
          <label for="tweet-text">Що у вас на думці?</label>
          <textarea id="tweet-text" name="text" rows="4" cols="50" required></textarea>
          <div class="error-message"></div>

        </div>

        <div class="modal-actions ">
          <button type="button" id="close-tweet-modal">Скасувати</button>
          <button type="submit" class="is-hidden submit-btn"><span>Опублікувати</span> <span class="spinner spinner-btn"></span></button>
        </div>
      </form>

    </dialog>
    <dialog class="tweet-modals" id="edit-modal">

      <h2>Редагувати твіт</h2>
      <form id="edit-tweet-form" novalidate>

        <div class="form-group">
          <textarea id="edit-tweet-text" name="text" rows="4" cols="50"></textarea>
          <div class="error-message"></div>

        </div>

        <div class="modal-actions ">
          <button type="button" id="close-edit-modal">Скасувати</button>
          <button type="submit" class="is-hidden submit-btn">Опублікувати <span class="spinner spinner-btn"></span></button>
        </div>
      </form>

    </dialog>

  </main>
  <div class="loader-backdrop js-backdrop">
    <div class="spinner"></div>
  </div>
  <script src="./js/main.js" type="module"></script>

</body>



</html>