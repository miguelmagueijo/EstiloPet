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
        include_once("navbar.php");
    ?>
    <div id="container">
        <?php

        if (isset($_SESSION["utilizador"])) {
            //variaveis de sessão
            $utilizador = $_SESSION["utilizador"];
            $tipoUtilizador = $_SESSION["tipo"];
            $idUser = $_SESSION["id"];

            //variaveis do formulario
            $idCliente = $_GET["idCliente"];

            if ($tipoUtilizador == CLIENTE_POR_VALIDAR /*FALTA VEREIFICAR O CLIENTE APAGADO*/) {
                header("Refresh:2; url=logout.php");
            } else {
                echo '      <div id="body-accordion">
                                <button class="accordion active">
                                    <h3>Efetuar Marcação</h3>
                                </button>
                            <div class="panel" style="display: block;">
                                <div id="marcacoes">
                                    <form action="efetuarMarcacao.php" method="GET">
                                    <label for="data">Data:</label>
                                    <input type="date" id="data" name="data" min="' . date("Y-m-d") . '" value="' . date("Y-m-d") . '">
                    
                                    <label for="hora">Hora:</label>
                                    <select id="hora" name="hora" required>
                                        <option value="">Selecione a hora</option>';
            // Intervalos de hora
            $horarios = array("09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "14:00",
                "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30");

            // Loop para exibir as opções do dropdown
            foreach ($horarios as $hora) {
                echo"                   <option value=\"$hora\">$hora</option>";
            }
                        echo '      </select>
                                    <label for="tipo-marcacao">Tipo de Tratamento:</label>
                                    <select id="tipo-marcacao" name="tipo-marcacao" required>
                                        <option value="">Selecione o tratamento</option>
                                        <option value="corte">Corte</option>
                                        <option value="banho">Banho</option>
                                    </select>
                                    <label for="tipo-animal">Animal:</label>
                                    <select id="tipo-animal" name="tipo-animal" required>
                                        <option value="">Selecione o animal</option>';
            #resolver este problema
            //buscar os dados à base de Dados
            $query = "SELECT * FROM animal WHERE idUser = '". $idCliente ."'";                            
            $retval = mysqli_query( $conn, $query);
            if(! $retval ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
            while($row = mysqli_fetch_array($retval)) {
                echo '                  <option value="'.$row['idAnimal'].'">'.$row['nomeAnimal'].'</option>';
            }           
                    echo '          </select>
                                    <a href="PgUtilizador.php#form-registar-animal">Registar Animal</a>
                                    <input type="hidden" id="nome-animal" name="idCliente" value="' . $idCliente . '">
                                    <div id="efetuar-marcacao"><input type="submit" value="Efetuar Marcação"></div>
                                </form>
                            </div>
                        </div>';
            }
        } else {
            echo "Efetue login!";
            header("Refresh:1; url=logout.php");
        }
        ?>

        <?php include_once("footer.html") ?>
    </div>
</body>

</html>