<?php
// Ligar há base de dados
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if(! $conn ){
    echo "Erro ao conectar ao MySQL.";
	exit;
}

//Seleciona a base de dados
mysqli_select_db($conn,'lpi');

?>