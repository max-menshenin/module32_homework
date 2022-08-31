<?php

    session_start();
    include 'vklink.php';

    if (isset($_GET['code'])) {
        $params = [
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'code' => $_GET['code'],
            'redirect_uri' => $redirectURI
        ];

        if (!$content = @file_get_contents('https://oauth.vk.com/access_token?' . http_build_query($params))) {
            $error = error_get_last();
            throw new Exception('HTTP error : ' . $error['message']);
        }

        $response = json_decode($content);

        if (isset($response->error)) {
            throw new Exception('access token error: ' . $response->error);
        }

        $token = $response->access_token;
        $userID = $response->user_id;

        $params = [
            'v' => $version,
            'access_token' => $token,
            'user_ids' => $userID,
            'fields' => $scope
        ];

        if (!$content = @file_get_contents('https://api.vk.com/method/users.get?' . http_build_query($params))) {
            $error = error_get_last();
            throw new Exception('HTTP error : ' . $error['message']);
        }

        $response = json_decode($content);

        if (isset($response->error)) {
            throw new Exception('access API error: ' . $response->error);
        }

        $response = $response->response;
        $vkname = null;

        foreach ($response as $value) {
            $vkname = $value->first_name;
        }

        if (!$vkname) return;

        $_SESSION['vklogin'] = $vkname;

        header('Location:/welcome.php');

    } elseif (isset($_GET['error'])) {
        throw new Exception('auth error: ' . $_GET['error']);
    }