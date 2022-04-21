<?php
    require_once('Facebook/autoload.php' );
    $fb = new Facebook\Facebook([
        'app_id' => '500363457864113',
        'app_secret' => '9d418c4201fa65884793c5a6823a9c63',
        'default_graph_version' => 'v2.9',
    ]);
    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://karlfashion.com/admin/logpage/fb-callback.php', $permissions);
?>