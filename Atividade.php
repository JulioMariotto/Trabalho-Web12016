<?php
require 'lib/sanitize.php';
require 'lib/credenciais.php';

$i = 0;
$acc = 0;
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
    $sql = "";
    $id = sanitize($_GET["id"]);
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT nomeList FROM $list WHERE idList = ". $id;
    if(!($lista = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    }

    $sql = "SELECT idExe FROM $temlist WHERE idList = ". $id;
    if(!($exer = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    } 
  }
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
  if(isset($_POST["id"])) 
  {
    $id = sanitize($_POST["id"]);
    $id = mysqli_real_escape_string($conn, $id);
    $nome = sanitize($_POST["nome"]);
    $nome = mysqli_escape_string($conn, $nome);
    
    $sql = "SELECT nomeList FROM $list WHERE idList = ". $id;
    if(!($lista = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    }

    $sql = "SELECT idExe FROM $temlist WHERE idList = ". $id;
    if(!($exer = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o exercicio do BD!<br>".
           mysqli_error($conn));
    }

    if (mysqli_num_rows($exer) > 0)
    {
      $a = array("");
      
      while($row = mysqli_fetch_assoc($exer))
      {
        
        if(isset($_POST[$row["idExe"]]))
        {
          $ver = sanitize($_POST[$row["idExe"]]);
          $ver = mysqli_real_escape_string($conn, $ver);
          
          $sql = "SELECT resposta FROM $exe WHERE idExe = ". $row["idExe"];
          if(!($exerc = mysqli_query($conn,$sql)))
          {
            die("Problemas para carregar o exercicio do BD!<br>".
                 mysqli_error($conn));
          }
          
          $exerc = mysqli_fetch_assoc($exerc);
          array_push($a, $ver);
          
          if(strcmp($ver, $exerc["resposta"]) == 0)
          {
            $acc++;
          }
          $i++;
        }
      }
      $err = $i - $acc;

      $sql = "INSERT INTO $feito (idList, acertos, erros, nomeAluno) VALUES ('$id', '$acc', '$err', '$nome')";
      if(!mysqli_query($conn,$sql))
      {
        
        $sql = "UPDATE $feito SET acertos = '$acc', erros = '$err' WHERE idList = '$id' AND nomeAluno = '$nome'";
        if(!mysqli_query($conn,$sql))
        {
          die("Problemas no BD!<br>".
               mysqli_error($conn));
        }
      }
      
      $sql = "SELECT idExe FROM $temlist WHERE idList = ". $id;
      if(!($exer = mysqli_query($conn,$sql)))
      {
        die("Problemas para carregar o exercicio do BD!<br>".
             mysqli_error($conn));
      }

      $info = true;
    }
  }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
  <?php $lista = mysqli_fetch_assoc($lista); ?>
    <title><?php echo $lista["nomeList"] ?></title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="jquery-3.2.0.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/user.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body style="height: 100%">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand">SisExe</a>
        </div>
        </div>
      </div>
    </nav>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-3"></div>
        <div class="col-md-6">
        <?php if($info): ?>
          <?php if($acc == $i): ?>
            <h2>Parabens <?php echo $nome ?>, Acertou <?php echo $acc . " de " . $i ?></h2>
          <?php else: ?>
              <h2><?php echo $nome ?>, Acertou <?php echo $acc . " de " . $i ?></h2>
          <?php endif; ?>
          <?php if (mysqli_num_rows($exer) > 0): ?>
            <?php $i = 1; ?>
          <br><br>
          <?php while($row = mysqli_fetch_assoc($exer)): ?>
            <?php 
              $conn = mysqli_connect($servername,$username,$password,$dbname);
              if (!$conn) 
              {
                die("Problemas ao conectar com o BD!<br>".
                     mysqli_connect_error());
              }
              $sql = "SELECT enunciado,resposta,alt1,alt2,alt3,alt4 FROM $exe WHERE idExe = ". $row["idExe"];
              if(!($exerc = mysqli_query($conn,$sql)))
              {
                die("Problemas para carregar o assunto do BD!<br>".
                     mysqli_error($conn));
              }
              $exerc = mysqli_fetch_array($exerc);
              mysqli_close($conn);
            ?>
                <div class="form-group">
                  <label>
                    <h2><?php echo $exerc["0"] ?></h2>
                  </label>
                </div>
                <?php if ($slc = $exerc["1"]): ?>
                <div <?php if(strcmp($a[$i], $slc) == 0): ?> class="alert alert-success" <?php else: ?> class="radio" <?php endif; ?>>
                  <label>
                      <input type="radio" name="<?php echo $i ?>" <?php if(strcmp($a[$i], $slc) == 0): ?> checked <?php endif; ?>>
                        <b><?php echo $slc ?></b>
                    </label>
                </div>
              <?php endif; ?>
              <?php if ($slc = $exerc["2"]): ?>
                <div <?php if(strcmp($a[$i], $slc) == 0): ?> class="alert alert-danger" <?php else: ?> class="radio" <?php endif; ?>>
                  <label>
                      <input type="radio" name="<?php echo $i ?>" <?php if(strcmp($a[$i], $slc) == 0): ?> checked <?php endif; ?>>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
                <?php if ($slc = $exerc["3"]): ?>
                <div <?php if(strcmp($a[$i], $slc) == 0): ?> class="alert alert-danger" <?php else: ?> class="radio" <?php endif; ?>>
                  <label>
                      <input type="radio" name="<?php echo $i ?>" <?php if(strcmp($a[$i], $slc) == 0): ?> checked  <?php endif; ?>>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
                <?php if ($slc = $exerc["4"]): ?>
                <div <?php if(strcmp($a[$i], $slc) == 0): ?> class="alert alert-danger" <?php else: ?> class="radio" <?php endif; ?>>
                    <label>
                      <input type="radio" name="<?php echo $i ?>" <?php if(strcmp($a[$i], $slc) == 0): ?> checked  <?php endif; ?>>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
              <?php if ($slc = $exerc["5"]): ?>
                <div <?php if(strcmp($a[$i], $slc) == 0): ?> class="alert alert-danger" <?php else: ?> class="radio" <?php endif; ?>>
                    <label>
                      <input type="radio" name="<?php echo $i ?>" <?php if(strcmp($a[$i], $slc) == 0): ?> checked  <?php endif; ?>>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
              <?php $i++; ?>
              <br><br>        
            <?php endwhile; ?>
            <br>
            <a href="<?php echo "Atividade.php?id=" . $id ?>">
              <button  class="btn btn-danger active">Tentar Novamente</button>
            </a>                  
          <?php endif; ?>
        <?php else: ?>
        <?php if (mysqli_num_rows($exer) > 0): ?>
          <h1><?php echo $lista["nomeList"] ?></h1>
          <br>
          <form action="Atividade.php" method="POST">
          <input type="hidden" name="id" value="<?php echo $id ?>">
          <label for="name">Nome do Aluno:</label>
          <input type="text" class="form-control" name="nome" required>
          <br>
          <?php while($row = mysqli_fetch_assoc($exer)): ?>
            <?php 
              $conn = mysqli_connect($servername,$username,$password,$dbname);
              if (!$conn) 
              {
                die("Problemas ao conectar com o BD!<br>".
                     mysqli_connect_error());
              }
              $sql = "SELECT enunciado,resposta,alt1,alt2,alt3,alt4 FROM $exe WHERE idExe = ". $row["idExe"];
              if(!($exerc = mysqli_query($conn,$sql)))
              {
                die("Problemas para carregar o assunto do BD!<br>".
                     mysqli_error($conn));
              }
              $exerc = mysqli_fetch_array($exerc);
              mysqli_close($conn);
            ?>
            <?php 
              $a = 1;
              $b = 1;
              while ($a == $b || $a == $c || $a == $d || $a == $e || $b == $c || $b == $d || $b == $e || $c == $d || $c == $e || $d == $e) 
              {
                $a = rand(1, 5);
                $b = rand(1, 5);
                $c = rand(1, 5);
                $d = rand(1, 5);
                $e = rand(1, 5);
              }
            ?>
                <div class="form-group">
                  <label>
                    <h2><?php echo $exerc["0"] ?></h2>
                  </label>
                </div>
                <?php if ($slc = $exerc[$a]): ?>
                <div class="radio">
                  <label>
                      <input type="radio" name="<?php echo $row['idExe'] ?>" value="<?php echo $slc ?>" checked>
                        <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
              <?php if ($slc = $exerc[$b]): ?>
                <div class="radio">
                  <label>
                      <input type="radio" name="<?php echo $row['idExe'] ?>" value="<?php echo $slc ?>"
                      checked>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
                <?php if ($slc = $exerc[$c]): ?>
                <div class="radio">
                  <label>
                      <input type="radio" name="<?php echo $row['idExe'] ?>" value="<?php echo $slc ?>" checked>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
                <?php if ($slc = $exerc[$d]): ?>
                <div class="radio">
                    <label>
                      <input type="radio" value="<?php echo $slc ?>" name="<?php echo $row['idExe'] ?>" checked>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
              <?php if ($slc = $exerc[$e]): ?>
                <div class="radio">
                    <label>
                      <input type="radio" name="<?php echo $row['idExe'] ?>" value="<?php echo $slc ?>" checked>
                          <?php echo $slc ?>
                    </label>
                </div>
              <?php endif; ?>
              <br><br>        
            <?php endwhile; ?>                  
          <?php else: ?>
            <div class="alert alert-danger">
              <strong>OPS! Parece que algo deu errado, Tente Novamente!</strong>
            </div>
            <?php die(); ?>
          <?php endif; ?>
            <a class="btn-confirm">
              <button type="submit" class="btn btn-success active">Enviar</button>
            </a>
          </form>
        <?php endif; ?>
        </div>
        <div class="col-md-3"></div>
      </div>
      <div id="ft">
        TI161 - Desenvolvimento de aplicações Web 1
      </div>
    </div>
  </body>
</html>