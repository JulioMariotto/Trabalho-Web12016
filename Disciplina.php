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

    $sql = "SELECT idDis, nomeDis, idUser FROM $dis WHERE idDis = '$id'";
    if(!($disc = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($disc) != 1) 
    {
      die("Id de disciplina incorreto.");
    }
    
    $sql = "SELECT idAss FROM $listass WHERE idDis = '$id'";
    if(!($assun = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
      
  }
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
  if (isset($_POST["id"]) && isset($_POST["disc"])) 
  {

    $id = sanitize($_POST["id"]);
    $id = mysqli_real_escape_string($conn, $id);
    $disc = sanitize($_POST["disc"]);
    $disc = mysqli_real_escape_string($conn, $disc);
    
    $sql = "UPDATE $dis SET nomeDis='$disc' WHERE idDis = '$id'";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas no BD!<br>".
           mysqli_error($conn));
    }
    else
    {
      $info = true;
    }

    $sql = "DELETE FROM $listass WHERE idDis = '$id'";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas no BD!<br>".
           mysqli_error($conn));
    }

    $c = count($_POST['assun']);
    if($c > 0)
    {
      for($x = 0; $x < $c; $x++) 
      {
        $assun = $_POST['assun'];
        $assun = $assun[$x];
        $sql = "INSERT INTO $listass (idAss, idDis) VALUES ('$assun', '$id')";
        if(!mysqli_query($conn,$sql))
        {
          die("Problemas para inserir os exercicios no BD!<br>".
               mysqli_error($conn));
        }
      }
    }
    $sql = "SELECT idDis, nomeDis, idUser FROM $dis WHERE idDis = '$id'";
    if(!($disc = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($disc) != 1) 
    {
      die("Id de disciplina incorreto.");
    }

    $sql = "SELECT idAss FROM $listass WHERE idDis = '$id'";
    if(!($assun = mysqli_query($conn,$sql)))
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
  <?php $dis = mysqli_fetch_assoc($disc); ?>
    <title><?php echo $dis["nomeDis"] ?></title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/sisexe.js"></script>
    <script >
      $(function(){
      $(".btn-delete").on("click",function(){
        return confirm("Você tem certeza que deseja remover <?php echo $dis["nomeDis"] ?>?");
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
            Disciplina Editada com Sucesso!
          </div>
          <?php endif; ?>
          <h1><?php echo $dis["nomeDis"] ?></h1><br>
            <div class="panel panel-default">
              <div class="panel-body">
               <ul class="list-group">
                <?php if(strcmp($dis["idUser"], $user_id) == 0 || $prev): ?>
                  <a href="<?php echo "Edita_Disciplina.php?id=" . $dis["idDis"]?>">
                  <button type="button" class="btn btn-warning active">
                    Editar Disciplina
                  </button>
                </a>
                <a class="btn-delete" href="<?php echo "Disciplinas.php?id=" . $dis["idDis"] . "&" . "acao=del"?>">
                  <button type="button" class="btn btn-danger active">
                    Excluir Disciplina
                  </button>
                </a>
              <?php endif; ?>
                <h2>Assuntos</h2>
                <?php if (mysqli_num_rows($assun) > 0): ?>
                  <?php while($row = mysqli_fetch_assoc($assun)): ?>
                    <?php 
                      $conn = mysqli_connect($servername,$username,$password,$dbname);
                      if (!$conn) 
                      {
                        die("Problemas ao conectar com o BD!<br>".
                             mysqli_connect_error());
                      }
                      $sql = "SELECT nomeAss FROM $ass WHERE idAss =" . $row["idAss"];
                      if(!($assunt = mysqli_query($conn,$sql)))
                      {
                        die("Problemas para carregar o assunto do BD!<br>".
                             mysqli_error($conn));
                      }
                      $assunt = mysqli_fetch_assoc($assunt);
                      mysqli_close($conn);
                    ?>                            
                    <br>
                    <a href="Assunto.php?id=<?php echo $row["idAss"]; ?>">
                      <li class="list-group-item"><?php echo $assunt["nomeAss"] ?></li>
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