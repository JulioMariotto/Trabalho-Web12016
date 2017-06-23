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

    $sql = "SELECT idAss, nomeAss, idUser FROM $ass WHERE idAss = '$id'";
    if(!($assun = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($assun) != 1) 
    {
      die("Id de assunto incorreto.");
    }

    $sql = "SELECT idExe FROM $listex WHERE idAss = '$id'";
    if(!($exer = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
     
    $sql = "SELECT idList,nomeList FROM $list WHERE idAss = '$id'";
    if(!($listas = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
           mysqli_error($conn));
    } 
  }
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
  if (isset($_POST["id"]) && isset($_POST["assunto"])) 
  {

    $id = sanitize($_POST["id"]);
    $id = mysqli_real_escape_string($conn, $id);
    $assunto = sanitize($_POST["assunto"]);
    $assunto = mysqli_real_escape_string($conn, $assunto);
    
    $sql = "UPDATE $ass SET nomeAss='$assunto' WHERE idAss = '$id'";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas no BD!<br>".
           mysqli_error($conn));
    }
    else
    {
      $info = true;
    }

    $sql = "DELETE FROM $listex WHERE idAss = '$id'";

    if(!mysqli_query($conn,$sql))
    {
      die("Problemas no BD!<br>".
           mysqli_error($conn));
    }

    $c = count($_POST['exerc']);
    if($c > 0)
    {
      for($x = 0; $x < $c; $x++) 
      {
        $exerc = $_POST['exerc'];
        $exerc = $exerc[$x];
        $sql = "INSERT INTO $listex (idExe, idAss) VALUES ('$exerc', '$id')";
        if(!mysqli_query($conn,$sql))
        {
          die("Problemas para inserir os exercicios no BD!<br>".
               mysqli_error($conn));
        }
      }
    }
    $sql = "SELECT idAss, nomeAss, idUser FROM $ass WHERE idAss = '$id'";
    if(!($assun = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($assun) != 1) 
    {
      die("Id de assunto incorreto.");
    }

    $sql = "SELECT idExe FROM $listex WHERE idAss = '$id'";
    if(!($exer = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }

    $sql = "SELECT idList,nomeList FROM $list WHERE idAss = '$id'";
    if(!($listas = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
           mysqli_error($conn));
    } 
  }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
  <?php $assun = mysqli_fetch_assoc($assun); ?>
    <title><?php echo $assun["nomeAss"] ?></title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/sisexe.js?<?php echo rand(1, 99); ?>"></script>
    <script >
      $(function(){
      $(".btn-delete").on("click",function(){
        return confirm("Você tem certeza que deseja remover <?php echo $assun["nomeCur"] ?>? Excluira tambem qualquer lista que seja deste assunto tambem !!");
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
      <h1>  <?php echo $assun["nomeAss"] ?></h1><br>
        <div class="col-md-8">
          <?php if($info): ?>
          <div class="alert alert-success">
            Assunto Editado com Sucesso!
          </div>
          <?php endif; ?>
          <div class="panel panel-default">
              <div class="panel-body">
               <ul class="list-group">
               <?php if(strcmp($assun["idUser"], $user_id) == 0 || $prev): ?>
                <a href="<?php echo "Edita_Assunto.php?id=" . $assun["idAss"]?>">
                  <button type="button" class="btn btn-warning active">Editar Assunto</button>
                </a>
                <a class="btn-delete" href="<?php echo "Assuntos.php?id=" . $assun["idAss"] . "&" . "acao=del"?>">
                  <button type="button" class="btn btn-danger active">Excluir Assunto</button>
                </a>
                <?php endif; ?>
                <a href="Exercicios.php?id=<?php echo $assun["idAss"] . "&acao=max&nome=" . $assun["nomeAss"] ?>">
                  <button type="button" class="btn btn-info active">Mais Votados</button>
                </a>
                <h2>Exercicios</h2>
                <?php if (mysqli_num_rows($exer) > 0): ?>
                  <?php while($row = mysqli_fetch_assoc($exer)): ?>
                    <?php 
                      $conn = mysqli_connect($servername,$username,$password,$dbname);
                      if (!$conn) 
                      {
                        die("Problemas ao conectar com o BD!<br>".
                             mysqli_connect_error());
                      }
                      $sql = "SELECT titulo FROM $exe WHERE idExe =" . $row["idExe"];
                      if(!($exerc = mysqli_query($conn,$sql)))
                      {
                        die("Problemas para carregar o assunto do BD!<br>".
                             mysqli_error($conn));
                      }
                      $exerc = mysqli_fetch_assoc($exerc);
                      mysqli_close($conn);
                    ?>                            
                    <br>
                    <a href="Exercicio.php?id=<?php echo $row["idExe"]; ?>">
                      <li class="list-group-item"><?php echo $exerc["titulo"] ?></li>
                    </a>
                  <?php endwhile; ?>
                <?php else: ?>
                  <li class="list-group-item">
                    <div class="alert alert-danger">
                      <strong>Nenhum Exericio No Sistema !</strong>
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
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <h1>Listas</h1><br>
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
                      <strong>Nenhuma Lista Com Este Assunto !</strong>
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