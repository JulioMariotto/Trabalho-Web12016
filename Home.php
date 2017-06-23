<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';
require 'lib/autentica.php';

if(!$login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
  exit();
}

$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) 
{
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

$sql = "SELECT idList,nomeList FROM $list";
if(!($listas = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
       mysqli_error($conn));
}

$sql = "SELECT idAss, nomeAss FROM $ass";
if(!($assuntos = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
       mysqli_error($conn));
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sistema de Exercicios</title>
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
      <div class="col-md-12">
        <div class="col-md-8">
          <h1>Lista de Exercicios</h1>
          <div class="panel panel-default">
              <div class="panel-body">
                <ul class="list-group">
                  <?php if(mysqli_num_rows($listas) > 0): ?>
                    <?php while($list = mysqli_fetch_assoc($listas)): ?>
                        <br>
                        <a href="Lista.php?id=<?php echo $list["idList"]; ?>">
                          <li class="list-group-item">
                          <?php echo $list["nomeList"] ?>
                        </li>
                        </a>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <li class="list-group-item">
                      <div class="alert alert-danger">
                        <strong>Nenhuma Lista No Sistema !</strong>
                      </div>
                    </li>
                  <?php endif; ?>  
                </ul>
              </div>
          </div>
        </div>
        <div class="col-md-4">
          <h1>Assuntos</h1>
          <div class="panel panel-default">
              <div class="panel-body">
                <ul class="list-group">
                  <?php if(mysqli_num_rows($assuntos) > 0): ?>
                    <?php while($assun = mysqli_fetch_assoc($assuntos)): ?>
                        <br>
                        <a href="Assunto.php?id=<?php echo $assun["idAss"]; ?>">
                          <li class="list-group-item">
                          <?php echo $assun["nomeAss"] ?>
                        </li>
                        </a>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <li class="list-group-item">
                      <div class="alert alert-danger">
                        <strong>Nenhum Assunto No Sistema !</strong>
                      </div>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>
          </div>
        </div>
      </div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>