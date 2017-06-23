<?php

$cookie_name = "user";
$cookie_id = "id";
$cookie_email = "email";
$cookie_foto = "foto";

if(isset($_COOKIE[$cookie_id]) && isset($_COOKIE[$cookie_name]) && isset($_COOKIE[$cookie_email]) && isset($_COOKIE[$cookie_foto])) 
{
    $_SESSION["user_id"] = $_COOKIE[$cookie_id];
    $_SESSION["user_name"] = $_COOKIE[$cookie_name];
    $_SESSION["user_email"] = $_COOKIE[$cookie_email];
    $_SESSION["user_foto"] = $_COOKIE[$cookie_foto];
    setcookie($cookie_name, $_SESSION["user_name"], time() + (36000), "/");
    setcookie($cookie_id, $_SESSION["user_id"], time() + (36000), "/");
    setcookie($cookie_email, $_SESSION["user_email"], time() + (36000), "/");
    setcookie($cookie_foto, $_SESSION["user_foto"], time() + (36000), "/");
} 
  session_start();

  $user_email = "";
  $prev = false;
  if (isset($_SESSION["user_id"]) && isset($_SESSION["user_name"]) && isset($_SESSION["user_email"])) {
    $login = true;
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
    $user_email = $_SESSION["user_email"];
    $user_foto = $_SESSION["user_foto"];
  }
  else{
    $login = false;
  }
  if(strcmp($user_email, "admin@adm.com") == 0)
  {
    $prev = true;
  }

?>
