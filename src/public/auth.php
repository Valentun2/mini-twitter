<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">

</head>

<body>


    <div class="auth-wrraper-container">
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
                            <button type="submit">Увійти</button>
                        </div>

                    </form>
                </div>
                <div class="form-container register-form">
                    <form class="form " id="register-form" novalidate>
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
                            <button type="submit">Зареєструватися</button>
                        </div>




                    </form>
                </div>
            </div>

            <div class="toggle-wrraper">
                <div class="toggle-container">
                    <div class="text-wrraper toggle-left">
                        <h3>Привіт</h3>
                        <p>Зареєструйтеся, щоб використовувати всі функції сайту</p>
                        <button class="js-login">Створити аккаунт </button>

                    </div>
                    <div class="text-wrraper toggle-right">
                        <h3>Привіт</h3>
                        <p>Увійдіть в аккаунт, щоб використовувати всі функції сайту</p>

                        <button class="js-register">Увійти в аккаунт</button>
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