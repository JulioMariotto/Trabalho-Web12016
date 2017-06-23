<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';
require 'lib/autentica.php';

if(!$login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
  exit();
}

$rqst = false;
$info = false;
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) 
{
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
  if($_GET["acao"] == "del")
  {
    if (isset($_GET["id"])) 
    {
      $id = sanitize($_GET['id']);
      $id = mysqli_real_escape_string($conn, $id);
      
      $sql = "DELETE FROM $temlist WHERE idExe=$id";
      if(!mysqli_query($conn,$sql))
      {
        die("Problemas no BD, Nao foi possivel realizar essa operação!<br>".
           mysqli_error($conn));
      }

      $sql = "DELETE FROM $listex WHERE idExe=$id";
      if(!mysqli_query($conn,$sql))
      {
        die("Problemas no BD, Nao foi possivel realizar essa operação!<br>".
           mysqli_error($conn));
      }

      $sql = "DELETE FROM $exe WHERE idExe=$id";
      if(!mysqli_query($conn,$sql))
      {
        die("Problemas no BD, Nao foi possivel realizar essa operação!<br>".
           mysqli_error($conn));
      }
      else
      {
        $info = true;
      }
    }
  }
  elseif($_GET["acao"] == "max")
  {
    if (isset($_GET["id"]) && isset($_GET["nome"]))
    {
      $id = sanitize($_GET["id"]);
      $id = mysqli_real_escape_string($conn, $id);
      $nome = sanitize($_GET["nome"]);
      $nome = mysqli_real_escape_string($conn, $nome);
      $i = 0;

      $sql = "SELECT idExe, titulo, likes FROM $exe ORDER BY likes DESC";
      if(!($votados = mysqli_query($conn,$sql)))
      {
        die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
             mysqli_error($conn));
      }
      else
      {
        $rqst =  true;
      }
    }
  
}

$sql = "SELECT idExe,titulo FROM $exe";
if(!($exercicios = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
       mysqli_error($conn));
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Exercicios</title>
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
            <li><a id="sel">Exercicios</a></li>
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
        <div class="col-md-12">
        <?php if($info): ?>
          <div class="alert alert-danger">
            Exercicio Excluido com Sucesso!
          </div>
        <?php endif; ?>
        <?php if($rqst): ?>
          <div class="alert alert-info">
            Mostrando os Exercicios Mais Votados de <strong><?php echo $nome ?></strong>
          </div>
        <?php endif; ?>
          <h1>Exercicios</h1>
          <div class="panel panel-default">
              <div class="panel-body">
               <ul class="list-group">
                <a href="Novo_Exercicio.php">
                  <button type="button" class="btn btn-success active">Novo Exercicio</button>
                </a>
                <br><br>
                <?php 
                if ($rqst)
                {
                   if(mysqli_num_rows($votados) > 0)
                   {
                      while($vot = mysqli_fetch_assoc($votados))
                      {
                        $conn = mysqli_connect($servername,$username,$password,$dbname);
                        if (!$conn) 
                        {
                          die("Problemas ao conectar com o BD!<br>".
                               mysqli_connect_error());
                        }
                        $sql = "SELECT idExe FROM $listex WHERE idAss = '$id'";
                        if(!($assun = mysqli_query($conn,$sql)))
                        {
                          die("Problemas para carregar o assunto do BD!<br>".
                               mysqli_error($conn));
                        }
                        if(mysqli_num_rows($assun) > 0)
                        {
                          while($ass = mysqli_fetch_assoc($assun))
                          {
                            if (strcmp($vot["idExe"], $ass["idExe"]) == 0) 
                            {
                              if($i == 10)
                              {
                                break 2;
                              }
                              else
                              {
                              echo '<br><a href="Exercicio.php?id=' . $vot["idExe"] . '">
                              <li class="list-group-item">' . $vot["titulo"] . '</li></a>';
                               $i++;
                             }
                            }
                          }
                        }
                        mysqli_close($conn);
                      }

                    }
                    if($i == 0)
                    {
                      echo '<li class="list-group-item">
                        <div class="alert alert-danger">
                          <strong>Nenhum Exericio com esse assunto !</strong>
                        </div>
                      </li>';
                    }
                  }
                }
                  ?> 
                <?php if(!$rqst): ?>
                <?php if(mysqli_num_rows($exercicios) > 0): ?>
                  <?php while($exer = mysqli_fetch_assoc($exercicios)): ?>
                    <br>
                    <a href="Exercicio.php?id=<?php echo $exer["idExe"]; ?>">
                      <li class="list-group-item">
                        <?php echo $exer["titulo"] ?>
                      </li>
                    </a>
                  <?php endwhile; ?>
                <?php else: ?>
                  <li class="list-group-item">
                    <div class="alert alert-danger">
                      <strong>Nenhum Exericio No Sistema !</strong>
                    </div>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
                </ul>
              </div>
          </div>
          <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="back">
          Voltar
        </a>
        </div>
      </div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>