<?php
require 'sanitize.php';
require 'credenciais.php';
require 'autentica.php';

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
if (isset($_GET["id"])) 
{
  $id = sanitize($_GET["id"]);
  $id = mysqli_real_escape_string($conn, $id);
  $foto = "pic/user-default.png";

  $_SESSION["user_foto"] = $foto;
  $sql = "UPDATE $user SET foto = '$foto' WHERE idUser = '$id'";
  if(!mysqli_query($conn,$sql))
  { 
    die("Problemas no Banco de Dados!!!");
  }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
pic/user-default.png
</body>
</html>