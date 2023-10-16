<html>

<head>
    <meta charset="UTF-8">
</head>

</html>
<?php
session_start();

if (isset($_SESSION["utilizador"])) {

    include('../basedados/basedados.h');
    include "tiposUtilizadores.php";

    //variaveis de sessao
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];

    //variaveis do formulario
    $nomeUser = $_POST["username"];
    $morada = $_POST["morada"];
    $email = $_POST["email"];
    $telemovel = $_POST["telemovel"];

    $idCliente = $_POST["idUser"];

    if ($idUser != $idCliente) {
        if ($tipoUtilizador != ADMIN) {
            echo "Ultilizador inválido";
        } else if ($tipoUtilizador == ADMIN) {
            $tipoUser = $_POST["tipoUtilizador"];

            $update = "Update `user` 
                        SET `nomeUser` = '" . $nomeUser . "', `morada` = '" . $morada . "', `email` = '" . $email . "', 
                        `telemovel` = '" . $telemovel . "', `tipoUtilizador` = '" . $tipoUser . "'
                        WHERE `idUser` = '" . $idCliente . "'";

            $res = mysqli_query($conn, $update);

            echo "Update com sucesso!";
            header("Refresh:1; url=PgEditarUtilizador.php?idUser=$idCliente");
        }
    } else {
        //restantes variaveis do formulario
        $pass = $_POST["pass"];
        $confPass = $_POST["ConfPass"];

        if (!(empty($nomeUser) || empty($morada) || empty($email) || empty($telemovel))) {
            if (!(empty($pass) && empty($confPass))) {
                if ($pass != $confPass) {
                    echo '<p>Passwords não correspondem!</p>';
                    header("Refresh:1; url=PgDadosPessoais.php");
                } else {
                    $update = "Update `user` 
                        SET `nomeUser` = '" . $nomeUser . "', `morada` = '" . $morada . "', `email` = '" . $email . "', `telemovel` = '" . $telemovel . "',
                        `pass` =  '" . md5($pass) . "' 
                        WHERE `idUser` = '" . $idUser . "'";

                    $res = mysqli_query($conn, $update);

                    echo "Update com sucesso!";
                    header("Refresh:1; url=PgDadosPessoais.php");
                }
            } else {
                $update = "Update `user` 
                            SET `nomeUser` = '" . $nomeUser . "', `morada` = '" . $morada . "', `email` = '" . $email . "',
                            `telemovel` = '" . $telemovel . "'
                            WHERE `idUser` = '" . $idUser . "'";

                $res = mysqli_query($conn, $update);

                echo "Update com sucesso!";
                header("Refresh:1; url=PgDadosPessoais.php");
            }

        } else {
            echo "Os campos têm de estar todos preenchidos.";
            header("Refresh:1; url=PgDadosPessoais.php");

        }

    }

} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>