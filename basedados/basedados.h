<?php
    error_reporting(E_ERROR | E_PARSE);

    $database_name = "lpi";

    $conn = mysqli_connect("localhost", "root", "", $database_name);

    if(!$conn) {
        die("Erro ao conectar ao MySQL. Verifique que a base de dados \"$database_name\" existe");
    }
?>