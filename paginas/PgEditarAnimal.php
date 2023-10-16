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
    <div id="container">
        <?php
        session_start();

        if (isset($_SESSION["utilizador"])) {
            //variaveis de sessão
            $utilizador = $_SESSION["utilizador"];
            $tipoUtilizador = $_SESSION["tipo"];
            $idUser = $_SESSION["id"];

            //variaveis do formulario
            $idAnimal = $_GET["idAnimal"];

            include('../basedados/basedados.h');
            include "tiposUtilizadores.php";

            //verificar se é o utlizador correto
            $query = "SELECT * FROM animal WHERE idAnimal = '" . $idAnimal . "'";
            $ret = mysqli_query($conn, $query);

            if (!$ret) {
                die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
            }

            $row_animal = mysqli_fetch_array($ret);

            if ($idUser != $row_animal["idUser"]) {
                echo "Utilizador inválido!";
                header("Refresh:1; url=logout.php");
            } else {

                if ($tipoUtilizador == CLIENTE_POR_VALIDAR /*FALTA VEREIFICAR O CLIENTE APAGADO*/) {
                    header("Refresh:2; url=logout.php");
                } else {
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
                            <div id="body-accordion">
                                <button class="accordion active">
                                    <h3>Editar Animal</h3>
                                </button>
                                <div class="panel" style="display: block;">
                                    <div id="form-registar-animal" class="edit-animal">
                                        <form action="editarAnimal.php" method="GET">
                                            <label for="nome-animal">Nome do Animal:</label>
                                            <input type="text" id="nome-animal" name="nome-animal" value="' . $row_animal['nomeAnimal'] . '"required/><br><br>
                                            <div>
                                                <label>Tipo de Animal:</label>';
                    if ($row_animal['tipoAnimal'] == 'cao') {
                        echo '
                                                <input type="radio" id="cao" name="tipo-animal" value="cao" checked >Cão</input>
                                                <input type="radio" id="gato" name="tipo-animal" value="gato"/>Gato</input>';
                    } else if ($row_animal['tipoAnimal'] == 'gato') {
                        echo '
                                                <input type="radio" id="cao" name="tipo-animal" value="cao">Cão</input>
                                                <input type="radio" id="gato" name="tipo-animal" value="gato"checked >Gato</input>';
                    }
                        echo '              </div>
                                            <label for="porte-animal">Porte:</label>
                                            <select id="porte-animal" name="porte-animal">';
                    if ($row_animal['porte'] == 'grande') {
                        echo '
                                                <option value="grande" selected>Grande</option>
                                                <option value="medio">Médio</option>
                                                <option value="pequeno">Pequeno</option>';
                    } else if ($row_animal['porte'] == 'medio') {
                        echo '
                                                <option value="grande">Grande</option>
                                                <option value="medio" selected>Médio</option>
                                                <option value="pequeno">Pequeno</option>';
                    } else if ($row_animal['porte'] == 'pequeno') {
                        echo '
                                                <option value="grande">Grande</option>
                                                <option value="medio" selected>Médio</option>
                                                <option value="pequeno" selected>Pequeno</option>';
                    }
                    echo '   
                                            </select><br><br>
                                            <input type="hidden" id="nome-animal" name="idAnimal" value="' . $row_animal['idAnimal'] . '">
                                            <input type="submit" value="Guardar"/>
                                        </form>
                                    </div>
                                    <div id="table-pesos">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Porte</th>
                                                    <th>Peso do Cão</th>
                                                    <th>Peso do Gato</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Pequeno</td>
                                                    <td>Até 10kg</td>
                                                    <td>Até 4kg</td>
                                                </tr>
                                                <tr>
                                                    <td>Médio</td>
                                                    <td>10kg - 25kg</td>
                                                    <td>4kg - 6kg</td>
                                                </tr>
                                                <tr>
                                                    <td>Grande</td>
                                                    <td>Acima de 25kg</td>
                                                    <td>Acima de 6kg</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>';
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
    </div>
</body>

</html>