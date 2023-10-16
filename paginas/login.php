<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

</body>

</html>
<?php
session_start();

if (isset($_SESSION['utilizador'])) {
    header("Refresh:0; url=PgUtilizador.php");
} else {
    if (!(isset($_POST["user"]) && isset($_POST["pass"]))) {
        header("Refresh:0; url=PgLogin.php");
    } else {
        //variaveis do formulario
        $user = $_POST["user"];
        $pass = md5($_POST["pass"]);

        include('../basedados/basedados.h');

        // Querie
        $sql = "SELECT `idUser`,`nomeUser`, `pass`, `tipoUtilizador` FROM user WHERE nomeUser = '$user' AND pass = '$pass'";

        $retval = mysqli_query($conn, $sql);

        // se a querie não funcionar dá erro
        if (!$retval) {
            die('Could not get data: ' . mysqli_error($conn));
        }

        //cria as variaveis de sessão se a linha (dados retirados da base de dados)) não estiver vazia
        if (($row = mysqli_fetch_array($retval)) != null) {
            $_SESSION['utilizador'] = $row['nomeUser'];
            $_SESSION['tipo'] = $row['tipoUtilizador'];
            $_SESSION['id'] = $row['idUser'];


            echo '<div id="autenticacao"><h4>A autenticar...</h4></div>';
            header("Refresh:2; url=secreta.php");

        } else {
            echo 'UPS, algo falhou!';
            header("Refresh:1; url=PgLogin.php");
        }
    }
}
?>