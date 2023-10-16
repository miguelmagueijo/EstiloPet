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
            $idCliente = $_GET["idUser"];

            include('../basedados/basedados.h');
            include "tiposUtilizadores.php";

            if ($tipoUtilizador != ADMIN) {
                echo "Não pode editar dados de outro utilizador!";
                header("Refresh:1; url=logout.php");
            } else {
                $sql = "SELECT * FROM user WHERE idUser = '". $idCliente ."'";

                $res = mysqli_query($conn, $sql);

                $row = mysqli_fetch_array($res);
                   
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
                                    <h3>Editar Dados do Utilizador</h3>
                                </button>
                                <div class="panel" style="display: block;">
                                    <div id="registo-box-func">
                                        <form action="editarDados.php" method="POST">
                                            <div class="input-div" id="input-form">
                                                Nome de utilizador:
                                                <input type="text" name="username" value="' . $row["nomeUser"] . '" required/>
                                            </div>
                                            <div class="input-div" id="input-form">
                                                Morada:
                                                <input type="text" name="morada" value="' . $row["morada"] . '" required/>
                                            </div>
                                            <div class="input-div" id="input-form">
                                                E-mail:
                                                <input type="e-mail" name="email" value="' . $row["email"] . '" required/>
                                            </div>
                                            <div class="input-div" id="input-form">
                                                Contacto:
                                                <input type="tel" name="telemovel" value="' . $row["telemovel"] . '"required/>
                                            </div>
                                            <div class="input-div" id="input-form">
                                                Tipo de Utilizador:
                                                <select id="cliente" name="tipoUtilizador" required>
                                                    <option value="">Selecione o tipo de utilizador</option>';                             
                if($row["tipoUtilizador"] == 0) {
                    echo'                       <option value="0"selected>Administrador</option>
                                                    <option value="1">Funcionário</option>
                                                    <option value="2">Cliente</option>
                                                    <option value="3">Cliente por validar</option>';
                } else if ($row["tipoUtilizador"] == 1) {
                    echo'                       <option value="0">Administrador</option>
                                                    <option value="1"selected>Funcionário</option>
                                                    <option value="2">Cliente</option>
                                                    <option value="3">Cliente por validar</option>';
                } else if ($row["tipoUtilizador"] == 2) {
                    echo'                       <option value="0">Administrador</option>
                                                    <option value="1">Funcionário</option>
                                                    <option value="2"selected>Cliente</option>
                                                    <option value="3">Cliente por validar</option>';
                } else if ($row["tipoUtilizador"] == 3){
                    echo'                       <option value="0">Administrador</option>
                                                    <option value="1">Funcionário</option>
                                                    <option value="2">Cliente</option>
                                                    <option value="3"selected>Cliente por validar</option>';
                }                                  
                echo '                         </select>
                                            </div>
                                            <div id="acoes">
                                                <input type="hidden" name="idUser" value="'. $idCliente .'">
                                                <input type="submit" value="Guardar">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>';
            }
        }
        ?>

        <div id="footer">
            <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </div>
</body>

</html>