<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';
require 'lib/autentica.php';

if(!$login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
  exit();
}

$error = "";
$erro = false;
$info = false;
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) {
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
  if (isset($_GET["id"])) 
  {

    $id = sanitize($_GET["id"]);
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT senha FROM $user WHERE idUser = '$id'";
    if(!(mysqli_query($conn, $sql)))
    {
      $erro = true;
      $error = "Problema na conexão com o BD, tente novamente mais tarde.";
    }
  }
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST["id"]) && isset ($_POST["newsenha"]) && isset($_POST["confirm"]) && isset ($_POST["senha"]))
  {
    if(strcmp($_POST["newsenha"], $_POST["confirm"]) == 0)
    {
      $id = sanitize($_POST["id"]);
      $id = mysqli_real_escape_string($conn, $id);
      $senha = sanitize($_POST["senha"]);
      $senha = mysqli_real_escape_string($conn, $senha);
      $senha = md5($senha);
      $nsenha = sanitize($_POST["newsenha"]);
      $nsenha = mysqli_real_escape_string($conn, $nsenha);
      $nsenha = md5($nsenha);

      $sql = "SELECT senha FROM $user WHERE idUser = '$id'";
      if(!($use = mysqli_query($conn, $sql)))
      {
        $erro = true;
        $error = "Problema no BD, tente novamente mais tarde!";
      }
      else
      {
        $use = mysqli_fetch_assoc($use);
        if(strcmp($senha, $use["senha"]) == 0)
        {
          $sql = "UPDATE $user SET senha = '$nsenha' WHERE idUser = '$id'";
          if(!(mysqli_query($conn, $sql)))
          {
            $erro = true;
            $error = "Problema no BD, tente novamente mais tarde!";
          }
          else
          {
            $info = true;
            $error = "Senha Alterada com Sucesso!";
          }
        }
        else
        {
          $erro = true;
          $error = "Senha Incorreta !!!"; 
        }
      }
    }
    else
    {
      $erro = true;
      $error = "As Senhas não estão iguais !!!";
    }
  }
  else
  {
    $erro = true;
        $error = "Problema com as Senhas, tente novamente mais tarde!";
  } 
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Mudar Senha</title>
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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="Home.php">SisExe</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="Cursos.php?acao=debug">Cursos</a></li>
            <li><a href="Disciplinas.php?acao=debug">Disciplinas</a></li>
            <li><a href="Exercicios.php?acao=debug">Exercicios</a></li>
            <li><a href="Listas.php?acao=debug">Listas</a></li>
            <li><a href="Assuntos.php?acao=debug">Assuntos</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Novo<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="Novo_Curso.php">Curso</a></li>
                <li><a href="Nova_Disciplina.php">Disciplina</a></li>
                <li><a href="Novo_Exercicio.php">Exercicio</a></li>
                <li><a href="Nova_Lista.php">Lista</a></li>
                <li><a href="Novo_Assunto.php">Assunto</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right" action="Pesquisa.php" method="GET">
            <div class="input-group">
              <input type="text" class="form-control" name="busca" placeholder="Search">
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default">
                  <i class="glyphicon glyphicon-search"></i>
                </button>
              </div>
            </div>
          </form>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a <?php if($prev): ?> href="Usuarios.php?acao=msja" <?php endif; ?>>Acesso Restrito</a>
            </li>
            <li class="dropdown" id="user">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Usuario<span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="Usuario.php?id=<?php echo $user_id ?>"><img id="image2" src="<?php echo $user_foto ?>" style="width: 15%">
                <div class="pull-right">Perfil</div></a></li>
                <li><a href="logout.php"> ... <div class="pull-right">Sair</div></a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
      <?php if($info): ?>
          <div class="alert alert-success">
            <?php echo $error ?>
          </div>
        <?php endif; ?>
        <?php if($erro): ?>
          <div class="alert alert-danger">
            <?php echo $error ?>
          </div>
            <br>
            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="back">
              Voltar
            </a>
          <?php die(); ?>
        <?php endif; ?>
        <h1>Novo Usuario</h1><br>
        <form action="Nova_Senha.php" method="POST">
          <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <label for="name">Senha Antiga:</label>
            <input type="password" class="form-control" name="senha" required>
          </div>
            <div class="form-group">
              <label>Nova Senha:</label>
              <input type="password" class="form-control" name="newsenha" id="pssw" minlength="6" required>
            </div>
            <div class="form-group" id="dng">
                <label>Digite a Nova Senha Novamente:</label>
                <input type="password" class="form-control" name="confirm" minlength="6" onkeyup="verf(this.value)" required>
            </div>
            <br>
            <label id="lbl">As senhas não são iguais !!</label>
          <br>
          <br>
          <button type="submit" class="btn btn-success">Alterar</button>
        </form>
        <br>
        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="back">
          Voltar
        </a>
      </div>
      <div class="col-md-3">
      </div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>