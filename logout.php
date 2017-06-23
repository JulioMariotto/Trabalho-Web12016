<?php
  session_start();
  
  setcookie("user", "", time() - 3600);
  setcookie("id", "", time() - 3600);
  setcookie("email", "", time() - 3600);
  setcookie("foto", "", time() - 3600);

  session_unset();

  session_destroy();

  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
?>
