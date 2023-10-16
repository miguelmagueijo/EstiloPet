<?php
session_start();
if (isset($_SESSION["utilizador"])) {

    include('../basedados/basedados.h');
    include "tiposUtilizadores.php";

    //variavel do formulario
    $idMarcacao=$_GET["idMarcacao"];

    //variavel de sessao
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];

    //verificar se é o utlizador correto
    $query = "SELECT idUser, func FROM marcacoes WHERE idMarcacao = '" . $idMarcacao . "'";
    $ret = mysqli_query($conn, $query);

    if (!$ret) {
        die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
    }

    $row_marcacao = mysqli_fetch_array($ret);

    $func = $row_marcacao["func"];
   
    if($tipoUtilizador == FUNC && $func != $idUser) {
        echo "Não é possível eliminar esta marcação.";
        header("Refresh:2; url=PgUtilizador.php");
    } else {
        if ($idUser == $row_marcacao["idUser"] || $tipoUtilizador == ADMIN || $tipoUtilizador == FUNC) {
            $delete="DELETE FROM marcacoes WHERE idMarcacao= '$idMarcacao'";
            $res = mysqli_query($conn, $delete);
    
            if (mysqli_affected_rows ($conn) == 1) {
                echo "Eliminado com sucesso!";
                header("Refresh:1; url=PgUtilizador.php");
            } else {
                echo "Algo falhou...";
                header("Refresh:1; url=PgUtilizador.php");
            }
        } else {
            echo "Utilizador inválido!";
            header("Refresh:1; url=logout.php"); 
        }
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>