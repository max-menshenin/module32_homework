<?php

    require_once '../vendor/autoload.php';
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    session_start();
    // подключение к базе данных
    include 'connect.php';

    // система логирования
    $log = new Logger('register');
    $log->pushHandler(new StreamHandler('../log.txt', Logger::ERROR));

    // если нажата клавиша submit
    if (isset($_POST['submit'])) {
        // обнуляем ошибки
        $errors = null;

        // данные с формы
        $login = htmlspecialchars(trim($_POST['login']));
        $password = htmlspecialchars(trim($_POST['password']));
        $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));

        // если поля пустые, записываем ошибку
        if (empty($login) || empty($password) || empty($confirmPassword)) {
            $errors = 'Нет каких-либо данных';
        // если логин менее 3 символов
        } elseif (strlen($login) < 3) {
            $errors = 'Ведено мнее 3х символов';
        // если пароль менее 5 символов
        } elseif (strlen($password) < 5) {
            $errors = 'Пароль менее 5 симоволов';
        // если пароли не совпадают, записываем ошибку
        } elseif ($password !== $confirmPassword) {
            $errors = 'Пароли не совпадают';
        } else {
            // если такой пользователь уже зарегистрирован, записываем ошибку
            $query = "SELECT login FROM users WHERE login='" . $login . "'";
            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0) $errors = 'such user is already registered';
        }

        // если ошибки есть, выводим через сессию и останавливаем регистрацию
        if ($errors) {
            $_SESSION['errors'] = $errors;
            // запись ошибки в log файл
            $log->error($errors);
            header('Location:/register.php');
            return;
        }

        // шифруем пароль
        $password = password_hash($password, PASSWORD_DEFAULT);

        // добавляем нового пользователя
        $query = "INSERT INTO users VALUES (null, '" . $login . "', '" . $password . "', '')";
        mysqli_query($link, $query);

        // удаляем ошибки из сессии
        unset($_SESSION['errors']);
        // перенаправляем на страницу авторизации
        header('Location:/');
    }