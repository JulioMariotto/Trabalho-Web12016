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

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
  $info = false;
  if (isset($_GET["id"])) 
  {
    $id = sanitize($_GET['id']);
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT idCur, nomeCur, idUser FROM $curso WHERE idCur = '$id'";
    if(!($curs = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($curs) != 1) 
    {
      die("Id de Curso incorreto.");
    }
    
    $sql = "SELECT idDis FROM $listdis WHERE idCur = '$id'";
    if(!($disc = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
      
  }
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
  if (isset($_POST["id"]) && isset($_POST["curso"])) 
  {

    $id = sanitize($_POST["id"]);
    $id = mysqli_real_escape_string($conn, $id);
    $curs = sanitize($_POST["curso"]);
    $curs = mysqli_real_escape_string($conn, $curs);
    
    $sql = "UPDATE $curso SET nomeCur = '$curs' WHERE idCur = '$id'";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas no BD!<br>".
           mysqli_error($conn));
    }
    else
    {
      $info = true;
    }

    $sql = "DELETE FROM $listdis WHERE idCur = '$id'";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas no BD!<br>".
           mysqli_error($conn));
    }

    $c = count($_POST['disc']);
    if($c > 0)
    {
      for($x = 0; $x < $c; $x++) 
      {
        $disc = $_POST['disc'];
        $disc = $disc[$x];
        $sql = "INSERT INTO $listdis (idDis, idCur) VALUES ('$disc', '$id')";
        if(!mysqli_query($conn,$sql))
        {
          die("Problemas para inserir os exercicios no BD!<br>".
               mysqli_error($conn));
        }
      }
    }
    $sql = "SELECT idCur, nomeCur, idUser FROM $curso WHERE idCur = '$id'";
    if(!($curs = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($curs) != 1) 
    {
      die("Id de curso incorreto.");
    }

    $sql = "SELECT idDis FROM $listdis WHERE idCur = '$id'";
    if(!($disc = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    } 
  }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $curs = mysqli_fetch_assoc($curs); ?>
    <title><?php echo $curs["nomeCur"] ?></title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/sisexe.js"></script>
    <script >
      $(function(){
      $(".btn-delete").on("click",function(){
        return confirm("Você tem certeza que deseja remover <?php echo $curs["nomeCur"] ?>?");
      });
    })
    </script>
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
        <div class="col-md-12">
          <?php if($info): ?>
            <div class="alert alert-success">
              Curso Editado com Sucesso!
            </div>
          <?php endif; ?>
          <h1><?php echo $curs["nomeCur"] ?></h1>
          <div class="panel panel-default">
              <div class="panel-body">
               <ul class="list-group">
                <?php if(strcmp($curs["idUser"], $user_id) == 0 || $prev): ?>
                <a href="<?php echo "Edita_Curso.php?id=" . $curs["idCur"]?>">
                  <button type="button" class="btn btn-warning active">
                    Editar Curso
                  </button>
                </a>
                <a class="btn-delete" href="<?php echo "Cursos.php?id=" . $curs["idCur"] . "&" . "acao=del"?>">
                  <button type="button" class="btn btn-danger active">
                    Excluir Curso
                  </button>
                </a>
                <?php endif; ?>
                <h2>Disciplinas</h2>
                <?php if (mysqli_num_rows($disc) > 0): ?>
                  <?php while($row = mysqli_fetch_assoc($disc)): ?>
                    <?php 
                      $conn = mysqli_connect($servername,$username,$password,$dbname);
                      if (!$conn) 
                      {
                        die("Problemas ao conectar com o BD!<br>".
                             mysqli_connect_error());
                      }
                      $sql = "SELECT nomeDis FROM $dis WHERE idDis =" . $row["idDis"];
                      if(!($disci = mysqli_query($conn,$sql)))
                      {
                        die("Problemas para carregar o assunto do BD!<br>".
                             mysqli_error($conn));
                      }
                      $disci = mysqli_fetch_assoc($disci);
                      mysqli_close($conn);
                    ?>                            
                    
                    <a href="Disciplina.php?id=<?php echo $row["idDis"]; ?>">
                      <li class="list-group-item"><?php echo $disci["nomeDis"] ?></li>
                    </a>
                    
                  <?php endwhile; ?>
                <?php else: ?>
                  <li class="list-group-item">
                    <div class="alert alert-danger">
                      <strong>Nenhuma Disciplina Cadastrada !</strong>
                    </div>
                  </li>
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