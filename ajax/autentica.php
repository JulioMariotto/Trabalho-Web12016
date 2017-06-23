<?php
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
