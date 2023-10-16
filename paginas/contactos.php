<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
</head>

<body>
    <div id="container">
        <?php
        session_start();

        include "tiposUtilizadores.php";

        if (isset($_SESSION["utilizador"])) {

            $tipoUtilizador = $_SESSION["tipo"];

            //$utilizador = $_SESSION["utilizador"];
            if ($tipoUtilizador == CLIENTE_POR_VALIDAR /*FALTA VEREIFICAR O CLIENTE APAGADO*/) {
                header("Refresh:2; url=logout.php");
            } else {
                echo "<div id='header'>
                            <img class='logo' src='logo.png' alt=''>
                            <h1>Estilo Pet</h1>
                            <ul id='nav'>
                                <li><a href='index.php'>Home</a></li>
                                <li><a href='PgDadosPessoais.php'>Dados Pessoais</a></li>
                                <li><a href='contactos.php' class='activa'>Contactos</a></li>
                                <li id='logout'><a href='logout.php'>Logout</a></li>
                            </ul>
                        </div>";
            }
        } else {
            echo '<div id="header">
                        <img class="logo" src="logo.png" alt="">
                        <h1>Estilo Pet</h1>
                        <ul id="nav">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="PgLogin.php">Login</a></li>
                            <li><a href="contactos.php"class="activa">Contactos</a></li>
                        </ul>
                    </div>';
        }
        ?>
        <div class="divisao">
            <div class="left">
                <h4>Localização</h4>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3064.441810959371!2d-7.514567984545646!3d39.81951097943905!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd3d5ea6bb2280e1%3A0x1c460157bc4b46c8!2sEscola%20Superior%20de%20Tecnologia%20-%20Instituto%20Polit%C3%A9cnico%20de%20Castelo%20Branco!5e0!3m2!1spt-PT!2spt!4v1681199379771!5m2!1spt-PT!2spt"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="right">
                <div id="contactos">
                    <h4>Contatos</h4>
                    <li>+351 272 000 000</li>
                    <li>estilopet@estilopet.pt</li>
                </div>
                <div id="horario">
                    <h4>Horário</h4>
                    <li>Segunda - Domingo</li>
                    <li>9h - 13h</li>
                    <li>14h - 18h</li>
                </div>
            </div>
        </div>
        <div id="footer">
            <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </div>
</body>

</html>