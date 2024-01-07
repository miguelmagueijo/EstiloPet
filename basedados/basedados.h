<?php
// Ligar há base de dados
$dbname = "lod_mm_ma";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if(! $conn ){
    echo "Erro ao conectar ao MySQL.";
	exit;
}

//Seleciona a base de dados
mysqli_select_db($conn, $dbname);

?>