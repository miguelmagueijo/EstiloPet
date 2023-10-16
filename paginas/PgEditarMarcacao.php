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

            //variaveis do formulario
            $idMarcacao = $_GET["idMarcacao"];

            include('../basedados/basedados.h');
            include "tiposUtilizadores.php";

            //buscar à base de dados os dados da marcacao
            $query = "SELECT * FROM marcacoes m WHERE idMarcacao = '" . $idMarcacao . "'";
            $ret = mysqli_query($conn, $query);

            if (!$ret) {
                die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
            }

            $row_marcacao = mysqli_fetch_array($ret);

            $idCliente = $row_marcacao["idUser"];
            $func = $row_marcacao["func"];

            //verificar se é o funcionário correspondente à marcação
            if($idUser != $func && $tipoUtilizador == FUNC) {
                echo "Não é possível editar esta marcação.";
                header("Refresh:2; url=PgUtilizador.php");
            } else {
                if ($idUser == $idCliente || $tipoUtilizador == ADMIN || $tipoUtilizador == FUNC) {
                    if ($tipoUtilizador == CLIENTE_POR_VALIDAR) {
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
                        echo '<div id="container-marcacao">
                                <div id="body-accordion">
                                    <button class="accordion active">
                                        <h3>Editar Marcação</h3>
                                    </button>
                                <div class="panel" style="display: block;">
                                    <div id="marcacoes">
                                        <form action="editarMarcacao.php" method="GET">';
                        //buscar nome cliente à bd
                        $query_nome_user = "SELECT * FROM user WHERE idUser = '".$idCliente."'";
                        $res = mysqli_query($conn, $query_nome_user);
    
                        if (!$res) {
                            die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
                        }
    
                        $row_user = mysqli_fetch_array($res);
    
                        if($tipoUtilizador == ADMIN || $tipoUtilizador == FUNC){
                                echo '      <label for="data">Cliente:</label>
                                            <input type="text" id="data" name="nomeCliente" value="'.$row_user['nomeUser'].'" readonly/><br><br>';
                        }
                                echo '      <label for="data">Data:</label>
                                            <input type="date" id="data" name="data" min="' . date("Y-m-d") . '" value="'.$row_marcacao['data'].'"/>
                                            <label for="hora">Hora:</label>
                                            <select id="hora" name="hora" required>
                                                <option value="'.$row_marcacao['hora'].'">'.$row_marcacao['hora'].'</option>';
            
                        // Intervalos de hora
                        $horarios = array("09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "14:00",
                        "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30");
    
                        // Loop para exibir as opções do dropdown
                        foreach ($horarios as $hora) {
                            echo "              <option value=\"$hora\">$hora</option>";
                        }
    
                        echo '              </select>
                                        <label for="tipo-marcacao">Tipo de Tratamento:</label>
                                        <select id="tipo-marcacao" name="tipo-marcacao" required>';
                        if ($row_marcacao['tratamento'] == 'corte') {
                            echo '
                                            <option value="">Selecione o tratamento</option>
                                            <option value="corte" selected >Corte</option>
                                            <option value="banho">Banho</option>';
                        } else if ($row_marcacao['tratamento'] == 'banho') {
                            echo '
                                            <option value="">Selecione o tratamento</option>
                                            <option value="corte">Corte</option>
                                            <option value="banho" selected >Banho</option>';
                        }
                        echo '          </select>
                                        <label for="tipo-animal">Animal:</label>
                                        <select id="tipo-animal" name="tipo-animal" disabled>';
    
                        //não é possível editar o animal - terá de efetuar outra marcação para outro animal
                        $query_animais = "SELECT nomeAnimal FROM animal WHERE idUser = '" . $idCliente . "' AND idAnimal = '" . $row_marcacao['idAnimal'] . "'";
                        $retval = mysqli_query($conn, $query_animais);
                        if (!$retval) {
                            die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
                        }
                        $row = mysqli_fetch_array($retval);
                        echo '              <option value="'.$row['idAnimal'].'">'.$row['nomeAnimal'].'</option>
                                        </select>
                                        <a href="PgUtilizador.php#form-registar-animal">Registar Animal</a>
                                        <input type="hidden" id="nome-animal" name="idMarcacao" value="' . $row_marcacao['idMarcacao'] . '">
                                        <input type="hidden" id="nome-animal" name="idAnimal" value="' . $row_marcacao['idAnimal'] . '">
                                        <input type="hidden" id="nome-animal" name="idCliente" value="' . $idCliente . '">
                                        <div id="efetuar-marcacao"><input type="submit" value="Guardar"></div>
                                    </form>
                                </div>
                            </div>';
                    }                            
                } else {
                    echo "Utilizador inválido!";
                    header("Refresh:1; url=logout.php");
                }
            }
        } else {
            echo "Efetue login!";
            header("Refresh:1; url=logout.php");
        }
        ?>
        </div>
        <div id="footer">
            <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
        </div>
</body>

</html>