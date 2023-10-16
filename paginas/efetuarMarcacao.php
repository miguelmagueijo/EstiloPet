<html>

<head>
    <meta charset="UTF-8">
</head>

</html>
<?php
session_start();

include('../basedados/basedados.h');
include "tiposUtilizadores.php";

if (isset($_SESSION["utilizador"])) {

    //variavel de sessao
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];

    //variaveis do formulario
    $data = $_GET["data"];
    $hora = $_GET["hora"];
    $tratamento = $_GET["tipo-marcacao"];
    $animal = $_GET["tipo-animal"]; //idAnimal

    //buscar à bd o tipo do animal
    $query_animal = "SELECT idUser, tipoAnimal FROM `animal` WHERE idAnimal = '" . $animal . "'";
    $res_tipo = mysqli_query($conn, $query_animal);
    $row = mysqli_fetch_array($res_tipo);

    $tipoAnimal = $row["tipoAnimal"];
    $donoAnimal = $row["idUser"];

    //buscar à bd o ultimo id para no próximo adicionar 1 (autoincrement)
    $query_id = "SELECT max(idMarcacao) FROM `marcacoes`";
    $res_id = mysqli_query($conn, $query_id);
    $row_id = mysqli_fetch_array($res_id);

    $id = ($row_id == null) ? 1 : $row_id["max(idMarcacao)"] + 1;

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

    if (sizeof($func_disponiveis) == 0) {
        echo "Não é possivél efetuar a marcação, tente outro horário.";
        header("Refresh:1; url=PgUtilizador.php");

    } else {
        if (!(empty($data) || empty($hora) || empty($animal) || empty($tratamento))) {
            if ($tipoUtilizador == FUNC || $tipoUtilizador == ADMIN) {
                //variavel do form
                $idCliente = $_GET["idCliente"];

                $sql = "INSERT INTO `marcacoes` (`idMarcacao`, `data`, `hora`, `idAnimal` , `idUser`, `tratamento`, `func`) 
                VALUES ('" . $id . "','" . $data . "', '" . $hora . "','" . $animal . "','" . $idCliente . "','" . $tratamento . "', '" . $func_disponiveis[$func] . "');";
            } else {
                $sql = "INSERT INTO `marcacoes` (`idMarcacao`, `data`, `hora`, `idAnimal` , `idUser`, `tratamento`, `func`) 
                VALUES ('" . $id . "','" . $data . "', '" . $hora . "','" . $animal . "','" . $idUser . "','" . $tratamento . "', '" . $func_disponiveis[$func] . "');";
            }
            try {
                if ($tipoUtilizador == CLIENTE && $idUser != $donoAnimal) {
                    echo 'Não é possivel efetuar esta marcação';
                    header("Refresh:1; url=PgUtilizador.php");
                } else {
                    $res = mysqli_query($conn, $sql);
                    if (!$res) {
                        die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
                    } else {
                        echo "Marcação registada com sucesso!";
                        header("Refresh:1; url=PgUtilizador.php");
                    }
                }
            } catch (Exception $e) {
                echo "Não foi possível efetuar esta marcação";
                header("Refresh:1; url=PgUtilizador.php");
            }
        } else {
            echo "Por favor preencha todos os campos!";
            header("Refresh:1; url=PgUtilizador.php#marcacoes");
        }
    }

} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>