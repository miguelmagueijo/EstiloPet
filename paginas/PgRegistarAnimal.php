<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <link rel="stylesheet" type="text/css" href="estiloPgUtilizador.css" />
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION["utilizador"])) {
        //variaveis de sessão
        $utilizador = $_SESSION["utilizador"];
        $tipoUtilizador = $_SESSION["tipo"];
        $idUser = $_SESSION["id"];

        if (!(isset($_GET["idCliente"]))) {
            header("Refresh:0; url=PgUtilizador.php");
        } else {
            //variaveis do formulario
            $idCliente = $_GET["idCliente"];

            include('../basedados/basedados.h');
            include "tiposUtilizadores.php";

            if ($tipoUtilizador == CLIENTE_POR_VALIDAR) {
                header("Refresh:2; url=logout.php");
            } else {
                if ($idUser != $idCliente && ($tipoUtilizador == FUNC || $tipoUtilizador == ADMIN)) {
                    echo "  <div id='header'>
                            <img class='logo' src='logo.png' alt=''>
                            <h1>Estilo Pet</h1>
                            <ul id='nav'>
                                <li><a href='PgUtilizador.php'>Voltar</a></li>
                                <li><a href='PgDadosPessoais.php'>Dados Pessoais</a></li>
                                <li><a href='contactos.php'>Contactos</a></li>
                                <li id='logout'><a href='logout.php'>Logout</a></li>
                            </ul>  
                        </div>";
                    echo '  
                    <div id="container">
                        <div id="body-accordion">
                            <button class="accordion active">
                                <h3>Registar Animal</h3>
                            </button>
                            <div class="panel" style="display: block;">       
                                <div id="form-registar-animal">
                                    <form action="registarAnimal.php" method="GET">
                                        <label for="nome-animal">Nome do Animal:</label>
                                        <input type="text" id="nome-animal" name="nome-animal" required><br><br>
                                        <div>
                                            <label>Tipo de Animal:</label>
                                            <input type="radio" id="cao" name="tipo-animal" value="cao">Cão</input>
                                            <input type="radio" id="gato" name="tipo-animal" value="gato"/>Gato</input> 
                                        </div>
                                        <label for="porte-animal">Porte:</label>
                                        <select id="porte-animal" name="porte-animal">
                                            <option value="grande">Grande</option>
                                            <option value="medio">Médio</option>
                                            <option value="pequeno">Pequeno</option>
                                        </select><br><br>
                                        <input type="hidden" id="nome-animal" name="idCliente" value="' . $idCliente . '">
                                        <input type="submit" value="Registar">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
                } else {
                    echo "Utilizador inválido.";
                    header("Refresh:1; url=PgUtilizador.php");
                }
            }
        }

    } else {
        echo "Efetue login!";
        header("Refresh:1; url=logout.php");
    }
    ?>
    <div id="footer">
        <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
    </div>
</body>

</html>