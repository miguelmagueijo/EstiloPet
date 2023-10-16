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
            $utilizador = $_SESSION["utilizador"];

            if ($tipoUtilizador == CLIENTE_POR_VALIDAR /*FALTA VEREIFICAR O CLIENTE APAGADO*/) {
                header("Refresh:2; url=logout.php");
            } else {
                echo "<div id='header'>
                            <img class='logo' src='logo.png' alt=''>
                            <h1>Estilo Pet</h1>
                            <ul id='nav'>
                                <li><a href='PgUtilizador.php' class='activa'>Home</a></li>
                                <li><a href='PgDadosPessoais.php'>Dados Pessoais</a></li>
                                <li><a href='contactos.php'>Contactos</a></li>
                                <li id='logout'><a href='logout.php'>Logout</a></li>
                            </ul>
                        </div>";
            }
        } else {
            echo '<div id="header">
                        <img class="logo" src="logo.png" alt="">
                        <h1>Estilo Pet</h1>
                        <ul id="nav">
                            <li><a href="index.php"class="activa">Home</a></li>
                            <li><a href="PgLogin.php">Login</a></li>
                            <li><a href="contactos.php">Contactos</a></li>
                        </ul>
                    </div>';
        }
        ?>
        </div>
        <div>
            <div class="banner">
                <img src="tobby.jpg">
                <img src="fiona.jpg">
                <img src="xicos.jpg">
                <img src="shrek.jpg">
                <img src="rex.jpg">
                <img src="ze.jpg">
            </div>

            <div id="text">
                <h3>Bem-vindo ao Estilo Pet </h3>
                <h4>O salão de beleza para cães e gatos que cuida da higiene e aparência <br>dos
                    seus pets com carinho e profissionalismo. </h4>

                <p> Nós somos especializados em serviços de lavagem e corte, e
                    oferecemos uma variedade de opções de penteados para deixar seu animal de estimação com um visual
                    incrível.</p>
                <p>
                    O nosso site foi criado para tornar a sua experiência de agendamento fácil e conveniente. Pode
                    selecionar os serviços que deseja para o seu pet, escolher um horário disponível e agendar sua
                    consulta em apenas alguns cliques. </p>
                <p>
                    Além disso, temos uma equipa de profissionais altamente treinados
                    que são apaixonados por animais e estão prontos para cuidar do seu pet com todo o amor e atenção que
                    ele merece.
                </p>
            </div>
            <div id="marcacoes">
                <?php 
                    if (isset($_SESSION["utilizador"])) {
                        if ($tipoUtilizador != CLIENTE_POR_VALIDAR){
                            echo'<form action="PgUtilizador.php" method="GET">
                                    <input type="submit" value="Fazer uma marcação">
                                </form>';
                        }
                    } else {
                        echo'<form action="PgLogin.php" method="GET">
                                    <input type="submit" value="Fazer uma marcação">
                                </form>';
                    }
                ?>
            </div>
            <div id="table">
                <h1>Tabela de Preços</h1>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Animal</th>
                            <th>Corte</th>
                            <th>Banho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3">Cão</td>
                            <td>Grande</td>
                            <td>€90,00</td>
                            <td>€50,00</td>
                        </tr>
                        <tr>
                            <td>Médio</td>
                            <td>€70,00</td>
                            <td>€40,00</td>
                        </tr>
                        <tr>
                            <td>Pequeno</td>
                            <td>€50,00</td>
                            <td>€30,00</td>
                        </tr>
                        <tr>
                            <td rowspan="3">Gato</td>
                            <td>Grande</td>
                            <td>€80,00</td>
                            <td>€40,00</td>
                        </tr>
                        <tr>
                            <td>Médio</td>
                            <td>€60,00</td>
                            <td>€30,00</td>
                        </tr>
                        <tr>
                            <td>Pequeno</td>
                            <td>€40,00</td>
                            <td>€20,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="contacto"><a href="contactos.php">Contactos</a></div>
        </div>
        <div id="footer">
            <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </div>
</body>

</html>