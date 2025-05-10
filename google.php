<?php

require __DIR__. "/vendor/autoload.php";

$client = new Google\Client;

$client->setClientId("489210130241-pk14q3ai30begvhd0qm0l7h5k19rhq9d.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/sample/home.php");

if( !isset($_GET["code"]) ){
    exit("Login failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

$client->setAccessToken($token["access_token"]);

$oauth = new Google\Service\Oauth2($client);

$userinfo = $oauth->userinfo->get();

var_dump(
    $userinfo->email,
    $userinfo->familyname,
    $userinfo->givenname,
    $userinfo->name
);

?>
