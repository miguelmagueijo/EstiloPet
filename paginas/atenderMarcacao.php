<?php
session_start();

include('../basedados/basedados.h');
include "tiposUtilizadores.php";

if (isset($_SESSION["utilizador"])) {

    //variaveis do formulario
    $idMarcacao = $_GET["idMarcacao"];

    //variavel de sessao
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];

    if ($tipoUtilizador != FUNC) {
        echo "Não pode atender marcações.";
        header("Refresh:1; url=PgUtilizador.php");
    } else {
        //verificar se é o utlizador correto - funcionario que pode atender
        $query = "SELECT func, data FROM marcacoes WHERE idMarcacao = '" . $idMarcacao . "'";
        $ret = mysqli_query($conn, $query);

        if (!$ret) {
            die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
        }

        $row_marcacao = mysqli_fetch_array($ret);

        if ($idUser != $row_marcacao["func"]) {
            echo "Não pode atender esta marcação!";
            header("Refresh:1; url=PgUtilizador.php");
        } else {
            if ($row_marcacao["data"] <= date('Y-m-d')) {
                $update = "Update `marcacoes` SET `estado` = 1
                    WHERE `func` = '" . $idUser . "' and `idMarcacao` = '" . $idMarcacao . "' ";

                $res = mysqli_query($conn, $update);
                if (!$res) {
                    die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
                } else {
                    echo "Marcação atendida com sucesso!";
                    header("Refresh:1; url=PgUtilizador.php");
                }
            } else {
                echo "Não é possível atender marcações futuras.";
                header("Refresh:1; url=PgUtilizador.php");
            }
        }
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>