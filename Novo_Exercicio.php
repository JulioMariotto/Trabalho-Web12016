<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';
require 'lib/autentica.php';

if(!$login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
  exit();
}

$info = false;
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) 
{
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST["titulo"]) && isset($_POST["enum"]) && isset($_POST["resposta"]) && isset($_POST["alt1"]) && isset($_POST["alt2"]) && isset($_POST["alt3"]) && isset($_POST["alt4"]))
  {

    $titulo = sanitize($_POST["titulo"]);
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $enum = sanitize($_POST["enum"]);
    $enum = mysqli_real_escape_string($conn, $enum);
    $resp = sanitize($_POST["resposta"]);
    $resp = mysqli_real_escape_string($conn, $resp);
    $alt1 = sanitize($_POST["alt1"]);
    $alt1 = mysqli_real_escape_string($conn, $alt1);
    $alt2 = sanitize($_POST["alt2"]);
    $alt2 = mysqli_real_escape_string($conn, $alt2);
    $alt3 = sanitize($_POST["alt3"]);
    $alt3 = mysqli_real_escape_string($conn, $alt3);
    $alt4 = sanitize($_POST["alt4"]);
    $alt4 = mysqli_real_escape_string($conn, $alt4);

    

    $sql = "INSERT INTO $exe (titulo,enunciado,resposta,alt1,alt2,alt3,alt4,idUser)
            VALUES ('$titulo', '$enum', '$resp', '$alt1', '$alt2', '$alt3', '$alt4', '$user_id')";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas para inserir nova tarefa no BD!<br>".
           mysqli_error($conn));
    }
    else
    {
      $info = true;
    }
  }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Criar Exercicio</title>
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
            Exercicio Criado com Sucesso!
          </div>
        <?php endif; ?>
        <h1>Novo Exercicio</h1><br>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
          <div class="form-group">
            <label for="name">Titulo do Exercicio:</label>
            <input type="text" class="form-control" name="titulo" required>
          </div>
          <div class="form-group">
            <label for="curso">Enunciado:</label>
            <textarea class="form-control" rows="5" maxlength="350" name="enum" required></textarea>
          </div>
          <div class="form-group">
            <label for="curso">Resposta Correta:</label>
            <textarea class="form-control" rows="5" maxlength="350" name="resposta" required></textarea>
          </div>
          <div class="form-group">
            <label for="curso">Alternativa 1:</label>
            <textarea class="form-control" rows="5" maxlength="350" name="alt1" required></textarea>
          </div>
          <div class="form-group">
            <label for="curso">Alternativa 2:</label>
            <textarea class="form-control" rows="5" maxlength="350" name="alt2"></textarea>
          </div>
          <div class="form-group">
            <label for="curso">Alternativa 3:</label>
            <textarea class="form-control" rows="5" maxlength="350" name="alt3"></textarea>
          </div>
          <div class="form-group">
            <label for="curso">Alternativa 4:</label>
            <textarea class="form-control" rows="5" maxlength="350" name="alt4"></textarea>
          </div>
          <button type="submit" class="btn btn-success">Criar</button>
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