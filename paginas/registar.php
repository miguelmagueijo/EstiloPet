<?php
session_start();

include "tiposUtilizadores.php";
include('../basedados/basedados.h');

if (isset($_SESSION['utilizador'])) {
                if (($_SESSION['tipo']) == ADMIN || ($_SESSION['tipo']) == FUNC) {

        //variaveis de sessão
        $tipoUtilizador = $_SESSION["tipo"];

        //variaveis do formulario
        $nomeUser = $_POST["username"];
        $morada = $_POST["morada"];
        $email = $_POST["email"];
        $telemovel = $_POST["telemovel"];
        $pass = $_POST["pass"];
        $confPass = $_POST["ConfPass"];

        //o funcionario so pode criar clientes
        if ($tipoUtilizador == FUNC) {
            $sql = "INSERT INTO `user` (`nomeUser`, `morada`, `email`, `telemovel`, `pass`, `tipoUtilizador`) 
                    VALUES ('" . $nomeUser . "', '" . $morada . "','" . $email . "','" . $telemovel . "','" . md5($pass) . "', '2');";
            try {
                $res = mysqli_query($conn, $sql);

                echo "Cliente registado com sucesso! <br> Pode efetuar marcações";
                header("Refresh:2; url=PgUtilizador.php");
            } catch (Exception $e) {
                echo "Não foi possível registar o utilizador";
                header("Refresh:1; url=PgUtilizador.php");
            }
        } else if ($tipoUtilizador == ADMIN) {
            $sql = "INSERT INTO `user` (`nomeUser`, `morada`, `email`, `telemovel`, `pass`, `tipoUtilizador`) 
                    VALUES ('" . $nomeUser . "', '" . $morada . "','" . $email . "','" . $telemovel . "','" . md5($pass) . "', '3');";
            try {
                $res = mysqli_query($conn, $sql);
                echo "Registado com sucesso! <br> Faça a validação do utilizador.";
                header("Refresh:2; url=PgUtilizador.php");
            } catch (Exception $e) {
                echo "Não foi possível registar o utilizador";
                header("Refresh:1; url=PgUtilizador.php");
            }
        }
    } else {
        header("Refresh:0; url=PgUtilizador.php");
    }
} else {
    if (!(isset($_POST["username"]) || isset($_POST["morada"]) || isset($_POST["email"]) || isset($_POST["morada"]) || isset($_POST["telemovel"])
            || isset($_POST["pass"]) || isset($_POST["ConfPass"]))) {

        header("Refresh:0; url=PgRegisto.php");
    } else {
        //variaveis do formulario
        $nomeUser = $_POST["username"];
        $morada = $_POST["morada"];
        $email = $_POST["email"];
        $telemovel = $_POST["telemovel"];
        $pass = $_POST["pass"];
        $confPass = $_POST["ConfPass"];

        if (!(empty($nomeUser) || empty($morada) || empty($email) || empty($telemovel || empty($pass)) || empty($confPass))) {
            if ($pass != $confPass) {
                echo '<p>Passwords não correspondem!</p>';
                header("Refresh:1; url=PgRegisto.php");
            } else {
                $sql = "INSERT INTO `user` (`nomeUser`, `morada`, `email`, `telemovel`, `pass`, `tipoUtilizador`) 
                        VALUES ('" . $nomeUser . "', '" . $morada . "','" . $email . "','" . $telemovel . "','" . md5($pass) . "', '3');";
                try {
                    $res = mysqli_query($conn, $sql);

                    echo "Registado com sucesso! <br> Aguarde validação do Administrador.";
                    header("Refresh:2; url=PgLogin.php");

                } catch (Exception $e) {
                    echo "Não foi possível registar o utilizador";
                    header("Refresh:1; url=PgRegisto.php");
                }
            }
        } else {
            echo "Por favor preencha todos os campos!";
            header("Refresh:1; url=PgRegisto.php");
        }

    }

}

?>