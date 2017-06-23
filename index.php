<?php
require 'lib/sanitize.php';
require 'lib/autentica.php';
require 'lib/credenciais.php';


$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) 
{
  die("A conexão com o banco de dados falhou: " . mysqli_connect_error());
}

$info = false;

if (!$login && $_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST["email"]) && isset($_POST["senha"])) 
  {
    
    $email = sanitize($_POST["email"]);
    $email = mysqli_real_escape_string($conn, $email);
    $email = strtolower($email);
    $senha = sanitize($_POST["senha"]);
    $senha = mysqli_real_escape_string($conn, $senha);
    $senha = md5($senha);

    $sql = "SELECT idUser, nome, email, senha, foto FROM $user WHERE email = '$email'";
    if($result = mysqli_query($conn, $sql))
    {
      if(mysqli_num_rows($result) == 1) 
      {
        $use = mysqli_fetch_assoc($result);
        $check = $use["senha"];
        if(strcmp($senha, $check) == 0) 
        {

          $_SESSION["user_id"] = $use["idUser"];
          $_SESSION["user_name"] = $use["nome"];
          $_SESSION["user_email"] = $use["email"];
          $_SESSION["user_foto"] = $use["foto"];

          $cookie_name = "user";
          $cookie_id = "id";
          $cookie_email = "email";
          $cookie_foto = "foto";
          setcookie($cookie_name, $use["nome"], time() + (36000), "/");
          setcookie($cookie_id, $use["idUser"], time() + (36000), "/");
          setcookie($cookie_email, $use["email"], time() + (36000), "/");
          setcookie($cookie_foto, $use["foto"], time() + (36000), "/");

          header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/Home.php");
          exit();
        }
        else 
        {
          $error = "Senha incorreta!";
          $info = true;
        }
      }
      else{
        $error = "Usuário não encontrado!";
        $info = true;
      }
    }
    else 
    {
      $error = "Houve um problema no Banco de Dados, Tente Novamente Mais Tarde";
      $info = true;
    }
  }
  else 
  {
    $error = "Preencha todos os dados.";
    $info = true;
  }
}
elseif($login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/Home.php");
  exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
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
  <?php if($info): ?>
    <div class="alert alert-danger">
      <?php echo $error ?>
    </div>
  <?php endif; ?>
    <form action="index.php" method="POST">
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input type="email" class="form-control" name="email" placeholder="Email">
      </div>
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
        <input type="password" class="form-control" name="senha" placeholder="Password">
      </div>
      <button type="submit" class="btn btn-default">Entrar</button>
    </form>
  </div>
    <div class="col-md-4"></div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>