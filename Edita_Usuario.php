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

    $sql = "SELECT nome, email, idade, formacao FROM $user WHERE idUser = ". $id;
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

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Editar Perfil</title>
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
      <?php $use = mysqli_fetch_assoc($use); ?>
      <?php if(strcmp($id, $user_id) != 0 && !$prev): ?>
        <div class="alert alert-danger">
            <strong>Acesso Negado</strong>
          </div>
        <?php else: ?>
        <h1>Editar Perfil</h1><br>
        <form action="Usuario.php" method="post">
          <div class="form-group">
            <label>Nome:</label>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="text" class="form-control" name="nome" value="<?php echo $use["nome"] ?>" required>
          </div>
          <div class="form-group">
            <label>E-mail:</label>
            <input type="email" class="form-control" name="email" value="<?php echo $use["email"] ?>" required>
          </div>
          <div class="form-group">
            <label>Sexo:</label>
            <select class="form-control" name="sex" required>
              <option>Masculino</option>
              <option>Feminino</option>
            </select>
          </div>
          <div class="form-group">
            <label>Idade:</label>
            <input type="number" class="form-control" name="idade" min="18" value="<?php echo $use["idade"] ?>" required>
          </div>
          <div class="form-group">
            <label>Especialidade:</label>
            <input type="text" class="form-control" name="formacao" value="<?php echo $use["formacao"] ?>">
          </div>
          <br>
          <button type="submit" class="btn btn-success">Salvar</button>
        </form>
      <?php endif; ?>
      <br><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="back">
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