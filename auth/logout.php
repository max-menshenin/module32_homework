<?php

    session_start();
    include 'connect.php';

    // удаляем куки
    $query = "UPDATE users SET cookie='' WHERE login='" . $_SESSION['login'] . "'";
    mysqli_query($link, $query);
    setcookie('remember', '', time() - 3600, '/');

    // удаляем сессии
    session_destroy();
    // перенаправляем на страницу авторизации
    header('Location:/');