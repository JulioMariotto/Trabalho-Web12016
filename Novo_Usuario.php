<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';
require 'lib/autentica.php';

if(!$login)
{
  header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
  exit();
}
if(!$prev)
{
 header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . "/Home.php");
  exit(); 
}

$upok = 1;
$error = "";
$info = false;
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) {
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["senha"]) && isset($_POST["sex"]) && isset($_POST["idade"]) && isset($_POST["formacao"])) 
  {

    if(($_FILES["foto"]["name"]) || ($_FILES["foto"]["tmp_name"]))
    {
      
      $dir = "pic/";
      $file = $dir . basename($_FILES["foto"]["name"]);
      $upok = 1;
      $type = pathinfo($file, PATHINFO_EXTENSION);
      $check = getimagesize($_FILES["foto"]["tmp_name"]);
      if($check === false) 
      {
          $error = "Não foi possivel carregar esta Imagem !!";
          $upok = 0;
          $file = "user-default.png";
      }
      if (file_exists($file)) 
      {
          $error = "Renomeie a Imagem e tente novamente mais tarde!";
          $upok = 0;
          $file = "pic/user-default.png";
      }
      if ($_FILES["foto"]["size"] > 2000000) 
      {
          $error = "Imagem maior que 2MB !";
          $upok = 0;
          $file = "pic/user-default.png";
      }
      if($type != "jpg" && $type != "png" && $type != "jpeg" && $type != "JPG" && $type != "PNG" && $type != "JPEG")
      {
          $error = "Apenas os formatos JPG, JPEG e PNG são Suportados";
          $upok = 0;
          $file = "pic/user-default.png";
      }
    
      if ($upok == 1)
      {
          if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $file)) 
          {
              $error = "Não foi possivel carregar esta Imagem !!";
              $upok = 0;
              $file = "pic/user-default.png";
          }
      }
    }
    else
    {
      $upok = 1;
      $file = "pic/user-default.png";
    }

    $nome = sanitize($_POST["nome"]);
    $nome = mysqli_real_escape_string($conn, $nome);
    $email = sanitize($_POST["email"]);
    $email = mysqli_real_escape_string($conn, $email);
    $email = strtolower($email);
    $senha = sanitize($_POST["senha"]);
    $senha = mysqli_real_escape_string($conn, $senha);
    $senha = md5($senha);
    $sex = sanitize($_POST["sex"]);
    $sex = mysqli_real_escape_string($conn, $sex);
    $idade = sanitize($_POST["idade"]);
    $idade = mysqli_real_escape_string($conn, $idade);
    $formacao = sanitize($_POST["formacao"]);
    $formacao = mysqli_real_escape_string($conn, $formacao);
    $file = mysqli_escape_string($conn, $file);

    $sql = "SELECT idUser FROM $user WHERE email = '$email'";
    if(!($verf = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    }
    if (mysqli_num_rows($verf) == 0) 
    {
      $sql = "INSERT INTO $user (nome, email, senha, sexo, idade, foto, formacao)
            VALUES ('$nome', '$email', '$senha', '$sex', '$idade', '$file', '$formacao')";
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

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Novo Usuario</title>
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
      <?php if($upok == 0): ?>
          <div class="alert alert-danger">
            <?php echo $error ?>
          </div>
        <?php endif; ?>
      <?php if($info): ?>
          <div class="alert alert-success">
            Usuario Criado com Sucesso!
          </div>
        <?php endif; ?>
        <h1>Novo Usuario</h1><br>
        <form action="Novo_Usuario.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" class="form-control" name="nome" required>
          </div>
          <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" name="email" required>
          </div>
            <div class="form-group">
              <label>Senha:</label>
              <input type="password" class="form-control" name="senha" id="pssw" minlength="6" required>
            </div>
            <div class="form-group" id="dng">
                <label>Confirme a Senha:</label>
                <input type="password" class="form-control" name="confirm" minlength="6" onkeyup="verf(this.value)" required>
            </div>
          <label id="lbl">*As senhas não são iguais !!<br><br></label>
          <div class="form-group">
            <label for="sexo">Sexo:</label>
            <select class="form-control" name="sex" required>
              <option>Masculino</option>
              <option>Feminino</option>
            </select>
          </div>
          <div class="form-group">
            <label for="idade">Idade:</label>
            <input type="number" class="form-control" name="idade" min="18" required>
          </div>
          <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" name="foto">
          </div>
          <div class="form-group">
            <label for="formacao">Especialidade:</label>
            <input type="text" class="form-control" name="formacao">
          </div>
          <br>
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