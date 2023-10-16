<html>

<head>
    <meta charset="UTF-8">
</head>

</html>
<?php
session_start();
if (isset($_SESSION["utilizador"])) {

    include('../basedados/basedados.h');

    //variavel do formulario
    $idAnimal=$_GET["idAnimal"];

    //variavel de sessao
    $idUser = $_SESSION["id"];

    //verificar se é o utlizador correto
    $query = "SELECT idUser FROM animal WHERE idAnimal = '" . $idAnimal . "'";
    $ret = mysqli_query($conn, $query);

    if (!$ret) {
        die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
    }

    $row_animal = mysqli_fetch_array($ret);

    if ($idUser != $row_animal["idUser"]) {
        echo "Utilizador inválido!";
        header("Refresh:1; url=logout.php");
    } else {

        $delete="DELETE FROM animal WHERE idAnimal= '$idAnimal'";
        $res = mysqli_query($conn, $delete);

        if (mysqli_affected_rows ($conn) == 1) {
            echo "Eliminado com sucesso!";
            header("Refresh:1; url=PgUtilizador.php");
        } else {
            echo "Algo falhou...";
            header("Refresh:1; url=PgUtilizador.php");
        }
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>