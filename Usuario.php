<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';
require 'lib/autentica.php';

if(!$login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
  exit();
}

$upok = 1;
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
  if(isset($_GET["id"])) 
  {
    $sql = "";
    $id = sanitize($_GET["id"]);
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT nome, email, sexo, idade, foto, formacao FROM $user WHERE idUser = ". $id;
    if(!($use = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($use) != 1) 
    {
      die("Id de tarefa incorreto.");
    } 
  }
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if($_POST["acao"] == "cng")
  {
    if (isset($_POST["id"])) 
    {
      $id = sanitize($_POST["id"]);
      $id = mysqli_real_escape_string($conn, $id);

      if(($_FILES["foto"]["name"]) || ($_FILES["foto"]["tmp_name"]))
      {
        
        $dir = "pic/";
        $file = $dir . basename($_FILES["foto"]["name"]);
        $upok = 1;
        $type = pathinfo($file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check === false) 
        {
            $upok = 0;
            $error = "Não foi possivel carregar esta Imagem !!";
        }
        if (file_exists($file)) 
        {
            $upok = 0;
            $error = "Renomeie a Imagem e tente novamente mais tarde!";
        }
        if ($_FILES["foto"]["size"] > 2000000) 
        {
            $upok = 0;
            $error = "Imagem maior que 2MB !";
        }
        if($type != "jpg" && $type != "png" && $type != "jpeg" && $type != "JPG" && $type != "PNG" && $type != "JPEG")
        {
            $upok = 0;
            $error = "Apenas os formatos JPG, JPEG e PNG são Suportados";
        }
      
        if ($upok == 1)
        {
            if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $file)) 
            {
                $upok = 0;
                $error = "Não foi possivel carregar esta Imagem !!";
            }
            else
            {
              $info = true;
            }
        }
      }
      else
      {
        $upok = 0;
        $error = "Não foi possivel carregar esta Imagem !!";
      }
      if($upok == 1)
      {
        $_SESSION["user_foto"] = $file;
        $sql = "UPDATE $user SET foto = '$file' WHERE idUser = '$id'";
         if(!mysqli_query($conn,$sql))
        { 
          $error = "Não foi possivel carregar esta Imagem !!";
          $upok = 0;
        }
        else
        {
          setcookie("user", "", time() - 3600);
          setcookie("id", "", time() - 3600);
          setcookie("email", "", time() - 3600);
          setcookie("foto", "", time() - 3600);
          $user_foto = $file;
          $info = true;
        }
      }
    }
  }
  elseif (isset($_POST["id"]) && isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["sex"]) && isset($_POST["idade"]) && isset($_POST["formacao"])) 
  {

    $id = sanitize($_POST["id"]);
    $id = mysqli_escape_string($conn, $id);
    $nome = sanitize($_POST["nome"]);
    $nome = mysqli_real_escape_string($conn, $nome);
    $email = sanitize($_POST["email"]);
    $email = mysqli_real_escape_string($conn, $email);
    $email = strtolower($email);
    $sex = sanitize($_POST["sex"]);
    $sex = mysqli_real_escape_string($conn, $sex);
    $idade = sanitize($_POST["idade"]);
    $idade = mysqli_real_escape_string($conn, $idade);
    $formacao = sanitize($_POST["formacao"]);
    $formacao = mysqli_real_escape_string($conn, $formacao);

    $sql = "SELECT idUser FROM $user WHERE email = '$email'";
    if(!($verf = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($verf) == 0) 
    {
      setcookie("user", "", time() - 3600);
      setcookie("id", "", time() - 3600);
      setcookie("email", "", time() - 3600);
      setcookie("foto", "", time() - 3600);

      $sql = "UPDATE $user SET nome = '$nome',  email = '$email', sexo = '$sex', idade = '$idade',  formacao = '$formacao' WHERE idUser = '$id'";
      if(!mysqli_query($conn,$sql))
      { 
        die("Problemas p inserir nova tarefa no BD!<br>".
             mysqli_error($conn));
      }
      else
      {
        $info = true;
      }
    }
    else
    {
      $error .= "  Ja existe um Usuario com este email: <strong>$email</strong> ";
      $upok = 0;
    }
  }
}

$sql = "SELECT nome, email, sexo, idade, foto, formacao FROM $user WHERE idUser = '$id'" ;
if(!($use = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o exercicio do BD!<br>".
       mysqli_error($conn));
}
if (mysqli_num_rows($use) != 1) 
{
  die("Id de tarefa incorreto.");
}

$sql = "SELECT idExe, titulo FROM $exe WHERE idUser = '$id'";
if(!($exercicios = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
       mysqli_error($conn));
}

$sql = "SELECT idList, nomeList FROM $list WHERE idUser = '$id'";
if(!($listas = mysqli_query($conn,$sql)))
{
  die("Problemas para carregar o conteudo, recarregue a pagina!<br>".
       mysqli_error($conn));
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
  <?php $use = mysqli_fetch_assoc($use); ?>
    <title>Usuario</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="jquery-3.2.0.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/user.js"></script>
    <script >
      $(function(){
      $(".btn-delete").on("click",function(){
        return confirm("Você tem certeza que deseja remover <?php echo $use["nome"] ?>?");
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
      <?php if($id != 1 || $prev): ?>
        <div class="col-md-2">
          <img id="image" src="<?php echo $use["foto"] ?>" style="width: 100%">
          <div style="text-align: center">
            <br>
            <?php if($id == $user_id || $prev): ?>
              <a style="cursor: pointer;" onclick="delef('<?php echo $id ?>')">excluir foto</a>
              <br>
              <a style="cursor: pointer;" id="upft">alterar foto</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-6">
        <?php if($info): ?>
          <div class="alert alert-success">
            Editado com Sucesso!
          </div>
        <?php endif; ?>
        <?php if($upok == 0): ?>
          <div class="alert alert-warning">
            <?php echo $error ?>
          </div>
        <?php endif; ?>
          <h1><?php echo $use["nome"] ?></h1><br>
          <h4><b>Email: </b><?php echo $use["email"] ?></h4><br>
          <h4><b>Idade: </b><?php echo $use["idade"] ?></h4><br>
          <h4><b>Sexo: </b><?php echo $use["sexo"] ?></h4><br>
          <h4><b>Especialidade: </b><?php echo $use["formacao"] ?></h4><br>
          <?php if($id == $user_id || $prev): ?>
            <a href="<?php echo "Nova_Senha.php?id=" . $id?>">
              <button type="button" class="btn btn-info active">Mudar Senha
              </button>
            </a>
            <a href="<?php echo "Edita_Usuario.php?id=" . $id?>">
              <button type="button" class="btn btn-warning active">Editar Perfil
              </button>
            </a>
            <a class="btn-delete" href="<?php echo "Usuarios.php?id=" . $id . "&" . "acao=del"?>">
              <button type="button" class="btn btn-danger active" <?php if(!$prev): ?> disabled <?php endif; ?>>Excluir Perfil
              </button>
            </a>
          <?php endif; ?>
          <h3>Exercicios</h3>
          <div class="panel panel-default">
            <div class="new-panel">
              <ul class="list-group">
                <?php if(mysqli_num_rows($exercicios) > 0): ?>
                    <?php while($exer = mysqli_fetch_assoc($exercicios)): ?>
                      <a href="Exercicio.php?id=<?php echo $exer["idExe"] ?>">
                        <li class="list-group-item">
                          <?php echo $exer["titulo"]?>  
                        </li>
                      </a>
                    <?php endwhile; ?>
                <?php else: ?>
                  <li class="list-group-item">
                    <div class="alert alert-danger">
                        Nenhum exercicio !!!
                    </div>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div><br>
          <h3>Listas</h3>
          <div class="panel panel-default">
            <div class="new-panel">
              <ul class="list-group">
                <?php if(mysqli_num_rows($listas) > 0): ?>
                    <?php while($list = mysqli_fetch_assoc($listas)): ?>
                      <a href="Lista.php?id=<?php echo $list["idList"] ?>">
                        <li class="list-group-item">
                          <?php echo $list["nomeList"]?>  
                        </li>
                      </a>
                    <?php endwhile; ?>
                <?php else: ?>
                  <li class="list-group-item">
                    <div class="alert alert-danger">
                        Nenhuma Lista !!!
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
        <div class="col-md-3"></div>
        <div class="modal fade" id="Mod" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header" style="padding:15px 10px;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4><span class="glyphicon glyphicon-picture"></span>  Alterar Foto</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form role="form" method="POST" action="Usuario.php" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="foto">Foto:</label>
                      <input type="hidden" name="id" value="<?php echo $id ?>">
                      <input type="hidden" name="acao" value="cng">
                      <input type="file" name="foto">
                      <br>
                      <input type="submit" value="Trocar">
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
    <?php endif; ?>
       </div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>