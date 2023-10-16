<html>

<head>
    <meta charset="UTF-8">
</head>

</html>
<?php
session_start();

include('../basedados/basedados.h');

if (isset($_SESSION["utilizador"])) {

    //variaveis do formulario
    $idAnimal = $_GET["idAnimal"];
    $nomeAnimal = $_GET["nome-animal"];
    $tipoAnimal = $_GET["tipo-animal"];
    $porte = $_GET["porte-animal"];

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
    } else {
        if (!(empty($nomeAnimal) || empty($tipoAnimal) || empty($porte))) {
            $update = "Update `animal` 
                    SET `nomeAnimal` = '" . $nomeAnimal . "', `tipoAnimal` = '" . $tipoAnimal . "', `porte` = '" . $porte . "'
                    WHERE `idUser` = '" . $idUser . "' and `idAnimal` = '" . $idAnimal . "' ";
    
            $res = mysqli_query($conn, $update);
    
            if (!$res) {
                die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
            }
    
            echo "Update com sucesso!";
            header("Refresh:1; url=PgUtilizador.php");
    
        } else {
            echo "Os campos têm de estar todos preenchidos.";
            header("Refresh:1; url=PgEditarAnimal.php");
        }  
    }
} else {
    echo "Efetue login!";
    header("Refresh:1; url=logout.php");
}
?>