<?php
require 'credenciais.php';

$conn = mysqli_connect($servername, $username, $password);
if (!$conn) 
{
    die("Falha na Conexão: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE $dbname";
if (mysqli_query($conn, $sql)) 
{
    echo "Database created successfully<br>";
} 
else 
{
    echo "Error creating database<br> " . mysqli_error($conn);
}

$sql = "USE $dbname";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>Database changed";
} 
else 
{
    echo "<br>Error creating database: " . mysqli_error($conn);
}

$sql = "CREATE TABLE $user (
  idUser int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(50) NOT NULL,
  nome VARCHAR(60) NOT NULL,
  sexo ENUM('Feminino', 'Masculino'),
  senha VARCHAR(40) NOT NULL,
  idade int(2) NOT NULL,
  foto VARCHAR(250) DEFAULT 'pic/user-default',
  formacao VARCHAR(60),
  CHECK (idade>=18)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>user created successfully";
} 
else 
{
    echo "<br>Error creating table: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $exe (
  idExe int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(35) NOT NULL,
  enunciado VARCHAR(350) NOT NULL,
  resposta VARCHAR(350) NOT NULL,
  alt1 VARCHAR(350) NOT NULL,
  alt2 VARCHAR(350) NOT NULL,
  alt3 VARCHAR(350),
  alt4 VARCHAR(350),
  likes int(4) DEFAULT 0,
  deslikes int(4) DEFAULT 0,
  idUser int(5) UNSIGNED
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>exe created successfully";
}
else 
{
    echo "<br>Error creating table: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $ass (
  idAss int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomeAss VARCHAR(50) NOT NULL,
  idUser int(5) UNSIGNED
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>ass created successfully";
}
else 
{
    echo "<br>Error creating table: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $list (
  idList int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomeList VARCHAR(50) NOT NULL,
  idUser int(5) UNSIGNED,
  idAss int(5) UNSIGNED,
  FOREIGN KEY (idAss) REFERENCES $ass(idAss)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>list created successfully";
} 
else 
{
    echo "<br>Error creating table: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $curso (
  idCur int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomeCur VARCHAR(50) NOT NULL,
  idUser int(5) UNSIGNED
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>Curso created successfully";
} 
else 
{
    echo "<br>Error creating table: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $dis (
  idDis int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nomeDis VARCHAR(50) NOT NULL,
  idUser int(5) UNSIGNED
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>dis created successfully";
} 
else 
{
    echo "<br>Error creating table: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $listex (
  idExe int(5) UNSIGNED,
  idAss int(5) UNSIGNED,
  FOREIGN KEY (idAss) REFERENCES $ass(idAss),
  FOREIGN KEY (idExe) REFERENCES $exe(idExe),
  PRIMARY KEY (idExe, idAss)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>listex created successfully";
} 
else 
{
    echo "<br>Error creating table listex: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $listass (
  idDis int(5) UNSIGNED,
  idAss int(5) UNSIGNED,
  FOREIGN KEY (idAss) REFERENCES $ass(idAss),
  FOREIGN KEY (idDis) REFERENCES $dis(idDis),
  PRIMARY KEY (idDis, idAss)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>listass created successfully";
} 
else 
{
    echo "<br>Error creating table listass: " . mysqli_error($conn);
}


$sql = "CREATE TABLE $listdis (
  idCur int(5) UNSIGNED,
  idDis int(5) UNSIGNED,
  FOREIGN KEY (idCur) REFERENCES $curso(idCur),
  FOREIGN KEY (idDis) REFERENCES $dis(idDis),
  PRIMARY KEY (idDis, idCur)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>listdis created successfully";
} 
else 
{
    echo "<br>Error creating table listdis: " . mysqli_error($conn);
}

$sql = "CREATE TABLE $feito (
  idList int(5) UNSIGNED,
  acertos int(4),
  erros int (4),
  nomeAluno VARCHAR(50),
  FOREIGN KEY (idList) REFERENCES $list(idList),
  PRIMARY KEY (idList, nomeAluno)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>feito created successfully";
} 
else 
{
    echo "<br>Error creating table feito: " . mysqli_error($conn);
}

$sql = "CREATE TABLE $temlist (
  idExe int(5) UNSIGNED,
  idList int(5) UNSIGNED,
  FOREIGN KEY (idList) REFERENCES $list(idList),
  FOREIGN KEY (idExe) REFERENCES $exe(idExe),
  PRIMARY KEY (idExe, idList)
)";
if (mysqli_query($conn, $sql)) 
{
    echo "<br>temlist created successfully";
} 
else 
{
    echo "<br>Error creating table temlist: " . mysqli_error($conn);
}

mysqli_close($conn);
?>