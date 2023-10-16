
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

    //variaveis do formulario
    $idCliente = $_GET["idUser"];

    //variavel de sessao
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];

    if ($tipoUtilizador == ADMIN) {
        $update = "Update `user` SET `tipoUtilizador` = 2
                    WHERE `idUser` = '" . $idCliente . "'";

        $res = mysqli_query($conn, $update);

        if (!$res) {
            die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
        } else {
            echo "Validado com sucesso!";
            header("Refresh:1; url=PgUtilizador.php");
        }

    } else {
        echo "Não pode validar clientes";
        header("Refresh:1; url=PgUtilizador.php");
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>