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

            if ($tipoUtilizador == CLIENTE_POR_VALIDAR) {
                header("Refresh:2; url=logout.php");
            } else {
                echo "  <div id='header'>
                            <img class='logo' src='logo.png' alt=''>
                            <h1>Estilo Pet</h1>
                            <ul id='nav'>
                                <li><a href='index.php' class='activa'>Home</a></li>
                                <li><a href='PgDadosPessoais.php?'>Dados Pessoais</a></li>
                                <li><a href='contactos.php'>Contactos</a></li>
                                <li id='logout'><a href='logout.php'>Logout</a></li>
                            </ul>
                        </div>";
            }
            
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
        <div id="footer">
            <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </div>

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