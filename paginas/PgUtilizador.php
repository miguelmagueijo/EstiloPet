<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <link rel="stylesheet" type="text/css" href="estiloPgUtilizador.css" />
    <link rel="stylesheet" type="text/css" href="pgUtilizador.css">
</head>

<body>
        <?php
        session_start();

        if (isset($_SESSION["utilizador"])) {

            $utilizador = $_SESSION["utilizador"];
            $tipoUtilizador = $_SESSION["tipo"];
            $idUser = $_SESSION["id"];

            include('../basedados/basedados.h');
            include "tiposUtilizadores.php";

            $sql = "SELECT * FROM user WHERE nomeUser = '" . $utilizador . "'";
            $res = mysqli_query($conn, $sql);

            if (!$res) {
                die('Could not get data: ' . mysqli_error($conn)); // se não funcionar dá erro
            }

            $row = mysqli_fetch_array($res);

            $CURR_PAGE_NAME = "user_page";
            include_once("navbar.php");
            
            //cada tipo de utilizador tem um layout diferente
            switch ($row["tipoUtilizador"]) {
                case ADMIN:
                    #gerir reservas
                    #gerir dados pessoais
                    #gerir utilizadores
                    opcoesFuncAdmin($idUser, $tipoUtilizador);
                    gerirUtilizadores();
                    break;

                case FUNC:
                    #gerir reservas
                    #gerir dados pessoais
                    opcoesFuncAdmin($idUser, $tipoUtilizador);
                    break;

                case CLIENTE:
                    opcoesCliente($idUser);
                    break;
            }

        } else {
            echo"Efetue login!";
            header("Refresh:1; url=logout.php");
        }

        function opcoesCliente($idUser){
            include('../basedados/basedados.h');
            echo
            '   <div id="container">
                    <div id="body-accordion">
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Registar o meu animal</h3>
                        </button>
                        <div class="panel">
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
                                    <input type="hidden" id="nome-animal" name="idCliente" value="'. $idUser .'">
                                    <input type="submit" value="Registar">
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
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>O(s) meu(s) animal(ais)</h3>
                        </button>
                        <div class="panel">
                            <div id="table-animais">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nome do animal</th>
                                            <th>Tipo do animal</th>
                                            <th>Porte</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>';
        
            //buscar os dados à base de Dados
            $query = "SELECT * FROM animal WHERE idUser = '". $idUser ."'";
            $retval = mysqli_query( $conn, $query);
            
            if(! $retval ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
            
            while($row = mysqli_fetch_array($retval)) {
                        echo    '   <tbody>
                                        <tr>
                                            <td>'.$row["nomeAnimal"].'</td>
                                            <td>'.$row["tipoAnimal"].'</td>
                                            <td>'.$row["porte"].'</td>
                                            <td><a href="PgEditarAnimal.php?idAnimal='.$row['idAnimal'].'">Editar</a></td>
                                            <td><a href="apagarAnimal.php?idAnimal='.$row['idAnimal'].'">Eliminar</a></td>
                                        </tr>';
            }
                echo    '                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Efetuar uma Marcação</h3>
                        </button>
                        <div class="panel">
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
            $retval = mysqli_query( $conn, $query);
            if(! $retval ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
            while($row = mysqli_fetch_array($retval)) {
                echo '                  <option value="'.$row['idAnimal'].'">'.$row['nomeAnimal'].'</option>';
            }           
                    echo '          </select>
                                    <a href="#form-registar-animal">Registar Animal</a>
                                    <div id="efetuar-marcacao"><input type="submit" value="Efetuar Marcação"></div>
                                </form>
                            </div>
                        </div>
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Gerir as minhas Marcações</h3>
                        </button>
                        <div class="panel">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Hora</th>
                                        <th>Tipo de Tratamento</th>
                                        <th>Animal</th>
                                        <th>Editar</th>
                                        <th>Apagar</th>
                                    </tr>
                                </thead>';
            //buscar os dados à base de Dados
            $query_marcacoes = "SELECT m.idMarcacao, m.data, m.hora, m.tratamento, a.nomeAnimal FROM marcacoes m INNER JOIN animal a
            ON m.idAnimal = a.idAnimal WHERE m.idUser = '". $idUser ."' AND m.estado = 0";
            $res = mysqli_query( $conn, $query_marcacoes);
            
            if(! $res ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
                            
            while($row_marcacao = mysqli_fetch_array($res)) {
                        echo '  <tbody>
                                    <tr>
                                        <td>'.$row_marcacao["data"].'</td>
                                        <td>'.$row_marcacao["hora"].'</td>
                                        <td>'.$row_marcacao["tratamento"].'</td>
                                        <td>'.$row_marcacao["nomeAnimal"].'</td>';
                        if($row_marcacao["data"] < date("Y-m-d")){
                            echo'       <td>Não é possível<br>editar</td>';
                        } else {
                            echo'       <td><a href="PgEditarMarcacao.php?idMarcacao='.$row_marcacao['idMarcacao'].'">Editar</a></td>';
                        }
                        echo'           <td><a href="apagarMarcacao.php?idMarcacao='.$row_marcacao['idMarcacao'].'">Eliminar</a></td>
                                    </tr>
                                </tbody>';
            }
                echo '      </table>
                        </div>
                    </div>
                </div>';
        }

        function opcoesFuncAdmin($idUser, $tipoUtilizador){
            include('../basedados/basedados.h');
            echo
                '<div id="container">   
                    <div id="body-accordion">
                        <button class="accordion">
                            <img src="seta.svg" />';
            if($tipoUtilizador == ADMIN){
                echo'       <h3>Registar Utilizadores</h3>';
            } else if ($tipoUtilizador == FUNC){
                echo'       <h3>Registar Clientes</h3>';
            }            
            echo '      </button>
                        <div class="panel">
                            <div id="registo-box-func">
                                <form action="registar.php" method="POST">
                                    <div class="input-div" id="input-form">
                                        Nome do cliente:
                                        <input type="text" name="username" required/>
                                    </div>
                                    <div class="input-div" id="input-form">
                                        Morada:
                                        <input type="text" name="morada" required/>
                                    </div>
                                    <div class="input-div" id="input-form">
                                        E-mail:
                                        <input type="e-mail" name="email" required/>
                                    </div>
                                    <div class="input-div" id="input-form">
                                        Contacto:
                                        <input type="tel" name="telemovel" required/>
                                    </div>
                                    <div class="input-div" id="input-form">
                                        Password (Provisória):
                                        <input type="password" name="pass" required/>
                                    </div>
                                    <div class="input-div" id="input-form">
                                        Confirmar Password:
                                        <input type="password" name="ConfPass" required/>
                                    </div>
                                    <div id="acoes">
                                        <input type="submit" value="Registar">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Registar Animais</h3>
                        </button>
                        <div class="panel">
                            <div id="marcacoes">
                                <form action="PgRegistarAnimal.php" method="GET">
                                    <label for="cliente">Cliente:</label>
                                    <select id="cliente" name="idCliente" required>
                                        <option value="">Selecione o cliente</option>';
            //buscar os dados dos clientes à base de Dados
            $query_user = "SELECT idUser, nomeUser FROM user WHERE tipoUtilizador = 2 ORDER BY nomeUser";                            
            $ret = mysqli_query( $conn, $query_user);
            if(! $ret ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
            while($row_user = mysqli_fetch_array($ret)) {
                echo '                  <option value="'.$row_user['idUser'].'">'.$row_user['nomeUser'].'</option>';
            }                  
                        echo '      </select>
                                    <div id="efetuar-marcacao"><input type="submit" value="Próximo"></div>
                                </form>
                            </div>
                        </div>
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Efetuar Marcação</h3>
                        </button>
                        <div class="panel">
                            <div id="marcacoes">
                                <form action="PgEfetuarMarcacao.php" method="GET">
                                    <label for="cliente">Cliente:</label>
                                    <select id="cliente" name="idCliente" required>
                                        <option value="">Selecione o cliente</option>';
            //buscar os dados dos clientes à base de Dados
            $query_user = "SELECT idUser, nomeUser FROM user WHERE tipoUtilizador = 2 ORDER BY nomeUser";                            
            $ret = mysqli_query( $conn, $query_user);
            if(! $ret ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
            while($row_user = mysqli_fetch_array($ret)) {
                echo '                  <option value="'.$row_user['idUser'].'">'.$row_user['nomeUser'].'</option>';
            }                  
            echo '                  </select>
                                    <div id="efetuar-marcacao"><input type="submit" value="Próximo"></div>
                                </form>
                            </div>
                        </div>
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Gerir Marcações</h3>
                        </button>
                        <div class="panel">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Animal</th>
                                        <th>Data</th>
                                        <th>Hora</th>
                                        <th>Tipo de Tratamento</th>
                                        <th>Editar</th>
                                        <th>Apagar</th>';
            if($tipoUtilizador == FUNC){
                echo'                   <th>Atender</th>';
            }                        
            echo'                    </tr>
                                </thead>';
            if($tipoUtilizador == FUNC){
                //buscar os dados à base de Dados
                $query_marcacoes = "SELECT m.idMarcacao, m.data, m.hora, m.tratamento, m.estado, a.nomeAnimal, u.nomeUser FROM marcacoes m INNER JOIN animal a
                ON m.idAnimal = a.idAnimal INNER JOIN user u ON m.idUser = u.idUser WHERE m.func = '". $idUser ."' AND estado = 0";
            } elseif ($tipoUtilizador == ADMIN){
                $query_marcacoes = "SELECT m.idMarcacao, m.data, m.hora, m.tratamento, m.estado, a.nomeAnimal, u.nomeUser FROM marcacoes m INNER JOIN animal a
                ON m.idAnimal = a.idAnimal INNER JOIN user u ON m.idUser = u.idUser WHERE m.estado = 0";
            }
            
            $res = mysqli_query( $conn, $query_marcacoes);
            
            if(! $res ){
                die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
                            
            while($row_marcacao = mysqli_fetch_array($res)) {
                        echo '  <tbody>
                                    <tr>
                                        <td>'.$row_marcacao["nomeUser"].'</td>
                                        <td>'.$row_marcacao["nomeAnimal"].'</td>
                                        <td>'.$row_marcacao["data"].'</td>
                                        <td>'.$row_marcacao["hora"].'</td>
                                        <td>'.$row_marcacao["tratamento"].'</td>
                                        <td><a href="PgEditarMarcacao.php?idMarcacao='.$row_marcacao['idMarcacao'].'">Editar</a></td>
                                        <td><a href="apagarMarcacao.php?idMarcacao='.$row_marcacao['idMarcacao'].'">Eliminar</a></td>';
                        if($tipoUtilizador == FUNC){
                            if ($row_marcacao['estado'] == 0)
                            echo '      <td><a href="atenderMarcacao.php?idMarcacao='.$row_marcacao['idMarcacao'].'">Atender</a></td>';
                        }
                        echo '      </tr>
                                </tbody>';
            }
                echo '      </table>
                        </div>
                    </div>';
        }

        function gerirUtilizadores(){
            include('../basedados/basedados.h');
            echo '
                    <div id="body-accordion">
                        <button class="accordion">
                            <img src="seta.svg" />
                            <h3>Gerir Utilizadores</h3>
                        </button>
                        <div class="panel">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo de Ultizador</th>
                                        <th>Editar</th>
                                        <th>Apagar</th>
                                        <th>Validar</th>
                                    </tr>
                                </thead>';
            //buscar os dados à base de Dados os dados dos utilizadores
            $query_users = "SELECT * FROM user ORDER BY idUser DESC";
            $res = mysqli_query( $conn, $query_users);

            if(! $res ){
            die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
            }
                
            while($row_users = mysqli_fetch_array($res)) {
                echo '          <tbody>
                                    <tr>
                                        <td>'.$row_users["nomeUser"].'</td>';
                switch($row_users["tipoUtilizador"]){
                    case ADMIN:
                        echo'           <td>Administrador</td>';
                        break;
                    case FUNC:
                        echo'           <td>Funcionário</td>';
                        break;
                    case CLIENTE:
                        echo'           <td>Cliente</td>';
                        break;
                    case CLIENTE_POR_VALIDAR:
                        echo'           <td>Cliente por validar</td>';
                        break;
                }
                echo'                   <td><a href="PgEditarUtilizador.php?idUser='.$row_users['idUser'].'">Editar</a></td>
                                        <td><a href="apagarUtilizador.php?idUser='.$row_users['idUser'].'">Eliminar</a></td>';
                if ($row_users["tipoUtilizador"] == 3)
                    echo '              <td><a href="validarUtilizador.php?idUser='.$row_users['idUser'].'">Validar</a></td>';
                else
                    echo '<td>Validado</td>';
                echo '              </tr>
                                </tbody>';
            }
            echo '          </table>
                        </div>
                    </div>
                </div>';
        }
        ?>

        <?php
            /* TODO: remove accordion, adopt menu-box */
            echo "
                <div class='menu-box hidden' style='margin-bottom: 5rem'>
                    <div class='menu-title-container'>
                        <h2 class='menu-title'>Funções XML</h2>
                        <img class='menu-title-arrow' src='down-arrow.webp'>
                    </div>
                    <div class='menu-content'>
                        <div class='menu-content-option'>
                            <h3>Dados gerais</h3>
                            <div>
                                <a class='menu-action-button' href='#'>Dados do website</a>
                            </div>
                        </div>";

            if ($row["tipoUtilizador"] == ADMIN || $row["tipoUtilizador"] == FUNC) {
                echo "
                            <div class='menu-content-option'>
                                <h3>Reservas</h3>
                                <div>
                                    <a class='menu-action-button' href='#'>Visualizar em XML</a>
                                </div>
                            </div>
                    ";
            }

            /* @var $conn mysqli */
            $query_tabelas = $conn->query("SHOW TABLES");
            $arr_tabelas = array();
            while($r = $query_tabelas->fetch_row()) {
                $arr_tabelas[] = $r[0];
            }

            if ($row["tipoUtilizador"] == ADMIN) {
                echo "
                            <div class='menu-content-option'>
                                <h3>Base de dados</h3>
                                <form method='POST'>
                                    <button class='menu-action-button' formaction='PgExportDatabase.php'>Exportar para XML</button>
                                    <button class='menu-action-button' formaction='PgExportDatabaseDTD.php'>Exportar para DTD</button>
                                    <button class='menu-action-button' formaction='PgExportDatabaseXSD.php'>Exportar para XML Schema</button>
                                    <h4 style='margin: 5px 0 0 0 '>Tabelas a exportar</h4>
                                    <label>
                                        Todas <input type='checkbox' value='all' id='all_tables' name='all_tables' checked />
                                    </label>
                ";
                foreach ($arr_tabelas as $tb) {
                    echo "
                        <label style='text-transform: capitalize; margin-left: 1rem;'>
                            $tb<input type='checkbox' value='$tb' name='table_name[]' checked />
                        </label>
                    ";
                }
                echo    "
                                </form>
                            </div>
                ";
            }

            echo "
                        </div>                          
                    </div>
                </div>
            ";
        ?>

    <?php include_once("footer.html") ?>
    <!-- TODO: remove accordion, adopt menu-box -->
    <script>
        window.onload = () => {
            const menuChoices = document.getElementsByClassName("menu-title-container");
            for (const mc of menuChoices) {
                mc.addEventListener("click", (evt) => {
                    evt.currentTarget.parentElement.classList.toggle("hidden");
                })
            }

            const allTablesCb = document.getElementById("all_tables");
            const tablesCb = Array.from(document.getElementsByName("table_name[]"));

            allTablesCb.addEventListener("click", () => {
                for (const tCb of tablesCb) {
                    tCb.checked = allTablesCb.checked;
                }
            });

            for (const tCb of tablesCb) {
                tCb.addEventListener("click", () => {
                    if (tCb.checked === false) {
                        allTablesCb.checked = false;
                    }

                    allTablesCb.checked = !tablesCb.some((cb) => cb.checked === false);
                });
            }
        }
    </script>

    <!--JavaScript para o accordion funcionar-->
    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                /* Toggle between adding and removing the "active" class,
                to highlight the button that controls the panel */
                this.classList.toggle("active");

                /* Toggle between hiding and showing the active panel */
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    </script>
</body>
</html>