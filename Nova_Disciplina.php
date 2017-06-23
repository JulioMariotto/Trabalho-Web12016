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

$sql = "SELECT idAss, nomeAss FROM $ass";
if(!($assuntos = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
       mysqli_error($conn));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST["disc"])) 
  {
    $disc = sanitize($_POST['disc']);
    $disc = mysqli_real_escape_string($conn, $disc);

    $sql = "INSERT INTO $dis (nomeDis, idUser) VALUES ('$disc', '$user_id')";
    if(!mysqli_query($conn,$sql))
    {
      die("Problemas para inserir nova tarefa no BD!<br>".
           mysqli_error($conn));
    }
    else
    {
      $info = true;
      $last_id = mysqli_insert_id($conn);
    }

    $c = count($_POST['assun']);
    if($c > 0)
    {
      for($x = 0; $x < $c; $x++) 
      {
        $assun = $_POST['assun'];
        $assun = $assun[$x];
        $sql = "INSERT INTO $listass (idAss, idDis) VALUES ('$assun', '$last_id')";
        if(!mysqli_query($conn,$sql))
        {
          die("Problemas para inserir os exercicios no BD!<br>".
               mysqli_error($conn));
        }
      }
    }
  }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Criar Disciplina</title>
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
            Disciplina Criada com Sucesso!
          </div>
        <?php endif; ?>
        <h1>Nova Disciplina</h1><br>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST"> 
          <div class="form-group">
            <label for="name">Nome da Disciplina:</label>
            <input type="text" class="form-control" name="disc">
          </div>
          <div class="form-group">
            <br>
            <h3>
                  <span class="glyphicon glyphicon-list"></span>
                    Assuntos
            </h3>
            <div class="panel panel-default">
              <div class="new-panel">
               <?php if(mysqli_num_rows($assuntos) > 0): ?>
                  <?php while($assun = mysqli_fetch_assoc($assuntos)): ?>
                   <div class="panel-body">
                     <input type="checkbox" name="assun[]" value="<?php echo $assun["idAss"]?>">  <?php echo $assun["nomeAss"]?>
                     <br>
                    </div>
               <?php endwhile; ?>
                <?php else: ?>
                  <div class="panel-body">
                    <div class="alert alert-danger">
                      Nenhum Assunto no sistema !!!
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
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