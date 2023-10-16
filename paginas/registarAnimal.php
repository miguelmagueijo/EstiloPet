<?php
session_start();

if (isset($_SESSION["utilizador"])) {

    $idUser = $_SESSION['id'];
    $tipoUtilizador = $_SESSION['tipo'];

    include('../basedados/basedados.h');
    include "tiposUtilizadores.php";

    if (!(isset($_GET["nome-animal"]) || isset($_GET["tipo-animal"]) || isset($_GET["porte-animal"]) || isset($_GET["idCliente"]))) {
        header("Refresh:0; url=PgRegistarAnimal.php");
    } else {
        $nomeAnimal = $_GET["nome-animal"];
        $tipoAnimal = $_GET["tipo-animal"];
        $porte = $_GET["porte-animal"];

        $idCliente = $_GET["idCliente"];

        if (!(empty($nomeAnimal) || empty($tipoAnimal) || empty($porte))) {
            if($tipoUtilizador == FUNC || $tipoUtilizador == ADMIN){
                $sql = "INSERT INTO `animal` (`nomeAnimal`, `porte`, `tipoAnimal`, `idUser`) 
                        VALUES ('" . $nomeAnimal . "', '" . $porte . "','" . $tipoAnimal . "','" . $idCliente . "');";
            } else {
                if($idUser == $idCliente){
                    $sql = "INSERT INTO `animal` (`nomeAnimal`, `porte`, `tipoAnimal`, `idUser`) 
                            VALUES ('" . $nomeAnimal . "', '" . $porte . "','" . $tipoAnimal . "','" . $idUser . "');";
                } else {
                    echo "Utilizador inválido.";
                    header("Refresh:1; url=PgRegistarAnimal.php");
                }  
            }
            $res = mysqli_query($conn, $sql);

            echo "Animal registado com sucesso!";
            header("Refresh:1; url=PgUtilizador.php");
        } else {
            echo "Por favor preencha todos os campos!";
            header("Refresh:1; url=PgUtilizador.php#form-registar-animal");
        }
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>