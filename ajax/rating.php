<?php
require 'sanitize.php';
require 'credenciais.php';

$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) 
{
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

$id = intval($_GET['id']);

if($_GET["acao"] == "like")
{
  $sql = "UPDATE $exe SET likes = likes + 1  WHERE idExe = '$id'";
    if(!mysqli_query($conn,$sql))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
  $sql = "SELECT likes FROM $exe WHERE idExe = '$id'";
  if(!($like = mysqli_query($conn,$sql)))
      {
        die("Problemas para carregar o assunto do BD!<br>".
             mysqli_error($conn));
      }
  $like = mysqli_fetch_assoc($like);
  $res = $like["likes"];
}



elseif($_GET["acao"] == "deslike")
{
  $sql = "UPDATE $exe SET deslikes = deslikes + 1  WHERE idExe = '$id'";
      if(!mysqli_query($conn,$sql))
      {
        die("Problemas para carregar o assunto do BD!<br>".
             mysqli_error($conn));
      }
  $sql = "SELECT deslikes FROM $exe WHERE idExe = '$id'";
  if(!($deslike = mysqli_query($conn,$sql)))
      {
        die("Problemas para carregar o assunto do BD!<br>".
             mysqli_error($conn));
      }
  $deslike = mysqli_fetch_assoc($deslike);   
  $res = $deslike["deslikes"];
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php echo $res ?>
</body>
</html>