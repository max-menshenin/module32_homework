<?php

    session_start();
    include 'connect.php';

    // проверяем есть ли куки
    if (isset($_COOKIE['remember'])) {
        // поиск пользователя в базе по куки
        $query = "SELECT login FROM users WHERE cookie='" . $_COOKIE['remember'] . "'";
        $result = mysqli_query($link, $query);

        // если пользователь найден, сохраняем логин в сессию
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['login'] = $row['login'];
        }
    }