<?php

    require_once '../vendor/autoload.php';
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    session_start();

    // подключение к базе данных
    include 'connect.php';

    // система логирования
    $log = new Logger('auth');
    $log->pushHandler(new StreamHandler('../log.txt', Logger::ERROR));

    // если нажата клавиша submit
    if (isset($_POST['submit'])) {
        // CSRF защита
        if ($_POST['CSRF_token'] !== $_SESSION['CSRF_token']) {
            header('Location:/');
            return;
        }

        // обнуляем ошибки
        $errors = null;

        // данные с формы
        $login = htmlspecialchars(trim($_POST['login']));
        $password = htmlspecialchars(trim($_POST['password']));

        // если поля пустые, записываем ошибку
        if (empty($login) || empty($password)) {
            $errors = 'Нет каких-либо данных';
        } else {
            // ищем пользователя в базе
            $query = "SELECT * FROM users WHERE login='" . $login . "'";
            $result = mysqli_query($link, $query);

            // если пользователь не найден, записываем ошибку
            if (!mysqli_num_rows($result)) $errors = 'Пользователь не найден';
            // иначе сравниваем хэш пароли
            else {
                $row = mysqli_fetch_assoc($result);

                if (!password_verify($password, $row['password'])) $errors = 'username or password entered incorrectly';
            }
        }

        // если ошибки есть, выводим через сессию и останавливаем авторизацию
        if ($errors) {
            $_SESSION['errors'] = $errors;
            // запись ошибки в log файл
            $log->error($errors);
            header('Location:/');
            return;
        }

        // удаляем ошибки из сессии
        unset($_SESSION['errors']);
        // записываем пользователя в сессию
        $_SESSION['login'] = $row['login'];

        // remember me
        if (isset($_POST['remember'])) {
            // шифруем пароль и создаем куки на 1 день
            $passwordCookie = md5($password . time());
            setcookie('remember', $passwordCookie, time() + (60 * 60 * 24), '/');
            // записываем куки в базу
            $query = "UPDATE users SET cookie='" . $passwordCookie . "' WHERE login='" . $_SESSION['login'] . "'";
            mysqli_query($link, $query);
        }
        else {
            // удаляем куки из базы
            $query = "UPDATE users SET cookie='' WHERE login='" . $_SESSION['login'] . "'";
            mysqli_query($link, $query);
            //удаляем сам куки
            setcookie('remember', '', time() - 3600, '/');
        }

        // перенаправляем на страницу приветствия
        header('Location:/welcome.php');
    }