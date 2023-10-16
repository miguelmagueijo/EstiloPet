<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION["utilizador"])) {

        include('../basedados/basedados.h');

        //variaveis de sessao
        $idUser = $_SESSION["id"];

        //vai buscar o id do utilizador
        $sql = "SELECT * FROM user WHERE idUser = '" . $idUser . "'";

        $res = mysqli_query($conn, $sql);

        if (($row = mysqli_fetch_array($res)) != null) {
            $nomeUser = $row["nomeUser"];
            $morada = $row["morada"];
            $email = $row["email"];
            $telemovel = $row["telemovel"];

            echo
            "   <div id='header'>
                    <img class='logo' src='logo.png' alt=''>
                    <h1>Estilo Pet</h1>
                    <ul id='nav'>
                        <li><a href='PgUtilizador.php'>Voltar</a></li>
                        <li><a href='PgDadosPessoais.php' class='activa'>Dados Pessoais</a></li>
                        <li><a href='contactos.php'>Contactos</a></li>
                        <li id='logout'><a href='logout.php'>Logout</a></li>
                    </ul>
                </div>
                <div id='container'>
                    <div id='editar-dados-box'>
                        <div id='login-cabecalho'>Editar Dados Pessoais</div>
                        <form action='editarDados.php' method='POST'>
                            <div class='input-div' id='input-form'>
                                Nome de utilizador:
                                <input type='text' name='username' value='" . $nomeUser . "'required/>
                            </div>
                            <div class='input-div' id='input-form'>
                                Morada:
                                <input type='text' name='morada' value='" . $morada . "'required/>
                            </div>
                            <div class='input-div' id='input-form'>
                                E-mail:
                                <input type='e-mail' name='email' value='" . $email . "'required/>
                            </div>
                            <div class='input-div' id='input-form'>
                                Contacto:
                                <input type='tel' name='telemovel' value='" . $telemovel . "'required/>
                            </div>
                            <div class='input-div' id='input-form'>
                                Password:
                                <input type='password' name='pass' />
                            </div>
                            <div class='input-div' id='input-form'>
                                Confirmar Password:
                                <input type='password' name='ConfPass' />
                            </div>
                            <div id='acoes'>
                                <input type='hidden' name='idUser' value='" . $idUser . "'>
                                <input type='submit' value='Guardar'>
                            </div>
                        </form>
                    </div>
                </div>
                <div id='footer'>
                    <p id='esq'>Realizado por Ana Correia & Clara Aidos</p>
                </div>";
        } else {
            echo "Algo correu mal...";
            header("Refresh:1; url=PgUtilizador.php");
        }
    } else {
        echo "Efetue login!";
        header("Refresh:1; url=logout.php");
    }
    ?>
</body>

</html>