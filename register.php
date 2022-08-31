<?php
    session_start();
    include 'auth/cookie.php';
    include 'auth/vklink.php';
    include 'auth/role.php';

    if ($auth) header('Location:/welcome.php');
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css" />
    <title>security | register</title>
</head>
<body>

    <?php
        if (isset($_SESSION['errors'])) {
            echo '
                <div class="error">
                    ' . $_SESSION['errors'] . '
                </div>            
            ';

            unset($_SESSION['errors']);
        }
    ?>

    <div class="content">

        <header class="header">
            <h2>Регистрация</h2>
            <a href="/">Вход</a>
        </header>

        <main class="main">
            <form action="auth/register.php" method="POST" class="form">
                <input type="text" class="text" name="login" id="login" placeholder="логин" />
                <input type="password" name="password" class="text" id="password" placeholder="пароль" />
                <input type="password" name="confirmPassword" class="text" id="confirmPassword" placeholder="подтвердить пароль" />
                <input type="submit" value="register" class="btn" name="submit" />
                <a href="https://oauth.vk.com/authorize?<?=http_build_query($params)?>" class="auth__link">
                Авторизация через VK.COM
                </a>
            </form>
        </main>

    </div>

    <script src="script.js"></script>
</body>
</html>