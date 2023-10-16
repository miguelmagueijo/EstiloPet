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

    //variavel do formulario - do user que vai ser apagado
    $idUtilizador=$_GET["idUser"];

    //variavel de sessao - id do admin
    $idUser = $_SESSION["id"];
    $tipoUtilizador = $_SESSION["tipo"];


    if ($tipoUtilizador == ADMIN && $idUser == $idUtilizador) {
        echo "O ADMIN não pode ser apagado.";
        header("Refresh:1; url=PgUtilizador.php");
    } else {
        if($tipoUtilizador != ADMIN) {
            echo "Não tem permissão para eliminar utilizadores";
            header("Refresh:1; url=PgUtilizador.php");
        } else {
            $delete_marcacoes = "DELETE FROM marcacoes WHERE idUser= '$idUtilizador'";
            $res = mysqli_query($conn, $delete_marcacoes);

            $delete_animais = "DELETE FROM animal WHERE idUser= '$idUtilizador'";
            $res = mysqli_query($conn, $delete_animais);
            
            $delete = "DELETE FROM user WHERE idUser= '$idUtilizador'";
            $res = mysqli_query($conn, $delete);

            if (mysqli_affected_rows ($conn) == 1) {
                echo "Eliminado com sucesso!";
                header("Refresh:1; url=PgUtilizador.php");
            } else {
                echo "Algo falhou...";
                header("Refresh:1; url=PgUtilizador.php");
            }
        }
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>