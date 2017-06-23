<?php
require 'credenciais.php';

$info = false;
$conn = mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) 
{
  die("Problemas ao conectar com o BD!<br>".
       mysqli_connect_error());
}

$id = intval($_GET['id']);

$sql = "SELECT idExe FROM $listex WHERE idAss = '$id'";
    if(!($exer = mysqli_query($conn,$sql)))
    {
      die("Problemas para carregar o assunto do BD!<br>".
           mysqli_error($conn));
    }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php if (mysqli_num_rows($exer) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($exer)): ?>
    <?php 
      $conn = mysqli_connect($servername,$username,$password,$dbname);
      if (!$conn) 
      {
        die("Problemas ao conectar com o BD!<br>".
             mysqli_connect_error());
      }
      $sql = "SELECT idExe,titulo FROM $exe WHERE idExe =" . $row["idExe"];
      if(!($exerc = mysqli_query($conn,$sql)))
      {
        die("Problemas para carregar o assunto do BD!<br>".
             mysqli_error($conn));
      }
      $exerc = mysqli_fetch_assoc($exerc);
      mysqli_close($conn);
    ?>
    <div class="panel-body">
        <input type="checkbox" name="exerc[]" value="<?php echo $exerc["idExe"]?>">  <?php echo $exerc["titulo"]?><br>
    </div> 
 <?php endwhile; ?>
<?php endif; ?>

</body>
</html>