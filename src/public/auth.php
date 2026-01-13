<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">

</head>

<body>
    <div class="app-message js-app-msg" popover="manual"></div>


    <div class="auth-wrapper-container">
        <div class="auth-container js-toggle-container">
            <div class="forms-wraper">
                <div class="form-container login-form">
                    <form class="form" id="login-form" novalidate>
                        <h2>Вхід у Міні-Твіттер</h2>


                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email-login" name="email" required />
                            <div class="error-message"></div>

                        </div>

                        <div class="form-group">
                            <label for="password">Пароль:</label>
                            <input type="password" id="password-login" name="password" required />
                            <div class="error-message"></div>
                        </div>

                        <div>
                            <button class="auth-button auth-button-blue is-hidden" type="submit"><span>Увійти</span> <span class="spinner spinner-btn"></span></button>
                        </div>
                        <div class="auth-mobile-btn-wrapper">
                            <p>Немає аккаунту?</p>
                            <button class="js-login-mobile">Зареєструватися </button>
                        </div>

                    </form>
                </div>
                <div class="form-container register-form">
                    <form class="form" id="register-form" novalidate>
                        <h2>Створити акаунт</h2>

                        <div class="form-group">
                            <label for="name">Ім'я користувача:</label>
                            <input type="text" id="name" name="name" required />
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email-register" name="email" required />
                            <div class="error-message"></div>

                        </div>

                        <div class="form-group ">
                            <label for="password">Пароль:</label>
                            <input type="password" id="password-register" name="password" required />
                            <div class="error-message"></div>
                        </div>

                        <div>
                            <button class="auth-button auth-button-blue is-hidden" type="submit"><span>Зареєструватися</span> <span class="spinner spinner-btn"></span></button>
                        </div>


                        <div class="auth-mobile-btn-wrapper">
                            <p>Вже маєш аккаунт?</p>
                            <button class="js-register-mobile">Увійти </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="toggle-wrapper">
                <div class="toggle-container">
                    <div class="text-wrapper toggle-left">
                        <h3>Привіт</h3>
                        <p>Зареєструйтеся, щоб використовувати всі функції сайту</p>
                        <button class="js-login auth-button auth-button-transparent ">Створити аккаунт </button>

                    </div>
                    <div class="text-wrapper toggle-right">
                        <h3>Привіт</h3>
                        <p>Увійдіть в аккаунт, щоб використовувати всі функції сайту</p>

                        <button class="js-register auth-button auth-button-transparent">Увійти в аккаунт</button>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <script type="module" src="/js/toggleForm.js"></script>
    <script src="/js/submitLoginForm.js" type="module"></script>
    <script src="/js/submitRegisterForm.js" type="module"></script>
</body>

</html>