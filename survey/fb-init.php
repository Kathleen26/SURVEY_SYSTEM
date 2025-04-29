<?php
   
    session_start();

    require './vendor/autoload.php';


    $fb = new Facebook\Facebook([
        'app_id' => '1111661350643859',
        'app_secret' => '2ba82e7240f5a6557e18a5764d735d17',
        'default_graph_version' => 'v2.10'
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $login_url = $helper->getLoginUrl('http://localhost/sample/home.php');


