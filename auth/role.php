<?php

    session_start();

    $vkUser = false;
    $login = null;
    $auth = false;

    if (isset($_SESSION['login'])) {
        $vkUser = false;
        $login = $_SESSION['login'];
        $auth = true;
    }

    if (isset($_SESSION['vklogin'])) {
        $vkUser = true;
        $login = $_SESSION['vklogin'];
        $auth = true;
    }