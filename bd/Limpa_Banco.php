<?php
require 'credenciais.php';


$conn = mysqli_connect($servername, $username, $password);
if (!$conn) 
{
    die("Falha na Conexão: " . mysqli_connect_error());
} 

$sql = "DROP DATABASE $dbname";
if (mysqli_query($conn, $sql)) 
{
    echo "Sucesso na exclusao do Banco!";
} 
else 
{
    echo "Falha na exclusao do Banco!  " . mysqli_connect_error();
}

mysqli_close($conn);
?>