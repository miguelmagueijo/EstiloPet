<?php
session_start();

include('../basedados/basedados.h');
include "tiposUtilizadores.php";

if (isset($_SESSION["utilizador"])) {

    //variaveis do formulario
    $idMarcacao = $_GET["idMarcacao"];
    $data = $_GET["data"];
    $hora = $_GET["hora"];
    $tratamento = $_GET["tipo-marcacao"];
    $idAnimal = $_GET["idAnimal"];

    $idCliente = $_GET["idCliente"];

    //buscar à bd o tipo do animal
    $query_animal = "SELECT tipoAnimal FROM `animal` WHERE idAnimal = '" . $idAnimal . "'";
    $res_tipo = mysqli_query($conn, $query_animal);
    $row = mysqli_fetch_array($res_tipo);

    $tipoAnimal = $row["tipoAnimal"];

    //variavel de sessao
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];

    //verificar se é o utlizador correto
    $query = "SELECT idUser FROM marcacoes WHERE idMarcacao = '" . $idMarcacao . "'";
    $ret = mysqli_query($conn, $query);

    if (!$ret) {
        die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
    }

    $row_marcacao = mysqli_fetch_array($ret);

    if ($idUser == $row_marcacao["idUser"] || $tipoUtilizador == ADMIN || $tipoUtilizador == FUNC) {
        //buscar à bd os funcionarios que fazem o servico
        $query_func = "SELECT idUser FROM `servicos_func` WHERE tratamento = '" . $tratamento . "' AND tipoAnimal = '" . $tipoAnimal . "'";
        $res_func = mysqli_query($conn, $query_func);

        //guarda num array os func que fazem o servico
        $funcs = array();
        while ($result = mysqli_fetch_array($res_func)) {
            $funcs[] = $result["idUser"];
        }

        //verificar na bd se esses func estão disponiveis a essa hora
        $query_horario = "SELECT func FROM `marcacoes` WHERE data = '" . $data . "' and hora = '" . $hora . "'";
        $ret = mysqli_query($conn, $query_horario);

        //funcionarios que têm marcaçoes na hora e data selecionada
        $func_ocupados = array();
        while ($res_func_ocupados = mysqli_fetch_array($ret)) {
            $func_ocupados[] = $res_func_ocupados["func"];
        }

        $func_disponiveis = array_values(array_filter(array_diff($funcs, $func_ocupados)));

        //escolher um dos funcionários disponiveis
        $func = rand(0, sizeof($func_disponiveis) - 1);
        //echo $func;

        if (sizeof($func_disponiveis) == 0) {
            echo "Não é possivel efetuar a marcação, tente outro horário.";
            header("Refresh:1; url=PgUtilizador.php");

        } else {
            if (!(empty($data) || empty($hora) || empty($tratamento) || empty($idAnimal))) {
                if($tipoUtilizador == FUNC || $tipoUtilizador == ADMIN){
                    $update = "Update `marcacoes` 
                    SET `data` = '" . $data . "', `hora` = '" . $hora . "', `idUser` = '" . $idCliente . "',
                    `idAnimal` = '" . $idAnimal . "', `tratamento` = '" . $tratamento . "', `func` = '" . $func_disponiveis[$func] . "'
                    WHERE `idUser` = '" .  $row_marcacao["idUser"] . "' and `idMarcacao` = '" . $idMarcacao . "' ";
                } else {
                    $update = "Update `marcacoes` 
                    SET `data` = '" . $data . "', `hora` = '" . $hora . "', `idUser` = '" . $idUser . "',
                    `idAnimal` = '" . $idAnimal . "', `tratamento` = '" . $tratamento . "', `func` = '" . $func_disponiveis[$func] . "'
                    WHERE `idUser` = '" . $idUser . "' and `idMarcacao` = '" . $idMarcacao . "' ";
                }
                
                $res = mysqli_query($conn, $update);

                if (!$res) {
                    die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
                } else {
                    echo "Update com sucesso!";
                    header("Refresh:1; url=PgUtilizador.php");
                }
            } else {
                echo "Os campos têm de estar todos preenchidos.";
                header("Refresh:1; url=PgEditarAnimal.php");
            }
        }
    } else {
        echo "Utilizador inválido!";
        header("Refresh:1; url=PgUtilizador.php");
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>