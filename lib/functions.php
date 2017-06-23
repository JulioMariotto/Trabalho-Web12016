<?php

function connect_db()
{
  $conn = mysqli_connect($servername,$username,$password,$dbname);

  if (!$conn) 
  {
      die("A conexão com o banco de dados falhou: " . mysqli_connect_error());
  }

  return($conn);
}

function report_er()
{
	die('<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/sisexe.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body style="height: 100%">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="Home.php">SisExe</a>
        </div>
      </div>
</nav>
<div class="row" id="lgpg1">
  <div class="col-md-4"></div>
  <div class="col-md-4">
    <div class="alert alert-danger">
    <strong>Ocorreu um ERRO, tente novamente mais tarde!</strongo>
    </div>
<div class="col-md-4"></div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>');
}

?>
