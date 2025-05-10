<?php 

require_once "vendor/autoload.php";

session_start();


$FbObject = new Facebook\Facebook([
    'app_id' => '499171829915623',
    'app_secret' => 'c720b5d93ba5088ceacb1e16f0adf040',
    'default_graph_version' => 'v2.10',
]);


$handler = $FbObject -> getRedirectLoginHelper();
?>