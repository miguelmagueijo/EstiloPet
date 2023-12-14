<?php
    include_once("auth.php");
    redirectToIfNotLogged();

    /* @var $conn mysqli */
    function showUsers() {
        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>Gerir utilizadores</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <embed type='text/html' src='pgExportTableXml.php?tableName=user' width='100%' style='height: 450px'/>
                        </div>
                    </div>
                ";
    }

    function showUserRegister() {
        $title = "";
        if(auth_isAdmin()){
            $title = "Registar Utilizador";
        } else if (auth_isWorker()){
            $title = "Registar Clientes";
        } else {
            die();
        }

        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>$title</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <form class='menu-form' action='registar.php' method='POST'>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Nome:
                                        <input type='text' name='username' minlength='3' required/>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Morada:
                                        <input type='text' name='morada' minlength='3' required/>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        E-mail:
                                        <input type='email' name='email' required/>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Contacto:
                                        <input type='tel' name='telemovel' required/>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Password:
                                        <input type='password' name='pass' minlength='3' required/>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Confirmar Password:
                                        <input type='password' name='ConfPass' minlength='3' required/>
                                    </label>
                                </div>
                                <button class='form-btn' type='submit'>
                                    Criar
                                </button>
                            </form>
                        </div>
                    </div>
                ";
    }

    function showClientSelectAnimalRegister() {
        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>Registar um animal a cliente</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <form class='menu-form' action='PgRegistarAnimal.php' method='GET'>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Cliente:
                                        <select id='cliente' name='idCliente' required>
                                            <option disabled selected>Selecione o cliente</option>
                ";

        global $conn; // get variable from outside function
        $res = $conn->query("SELECT idUser, nomeUser FROM user WHERE tipoUtilizador = '". CLIENTE ."' ORDER BY nomeUser");

        if(!$res){
            die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
        }

        while($row = $res->fetch_assoc()) {
            echo "<option value='".$row["idUser"]."'>".$row["nomeUser"]."</option>";
        }

        echo "
                                        </select>
                                    </label>
                                </div>
                                <button class='form-btn' type='submit'>
                                    Próximo passo
                                </button>
                            </form>
                        </div>
                    </div>
                ";
    }

    function showAnimalRegister() {
        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>Registar animal</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <form class='menu-form' action='registarAnimal.php' method='POST'>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Nome:
                                        <input type='text' name='nome-animal' minlength='3' required/>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Tipo de Animal:
                                        <div style='margin-top: 0.25rem'>
                                            <span>
                                                Cão <input type='radio' id='cao' name='tipo-animal' value='cao' />
                                            </span>
                                            <span>
                                                Gato <input type='radio' id='gato' name='tipo-animal' value='gato'/> 
                                            </span>
                                        </div>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Porte:
                                        <select name='porte-animal'>
                                            <option disabled selected>Selecione o porte</option>
                                            <option value='grande'>Grande</option>
                                            <option value='medio'>Médio</option>
                                            <option value='pequeno'>Pequeno</option>
                                        </select>
                                    </label>
                                </div>
                                <button class='form-btn' type='submit'>
                                    Adicionar animal
                                </button>
                            </form>
                            <div style='margin-top: 2rem'>
                                <table style='width: 500px'>
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
                    </div>
                ";
    }

    function showClientSelectAppointmentRegister() {
        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>Agendar uma marcação para cliente</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <form class='menu-form' action='PgEfetuarMarcacao.php' method='GET'>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Cliente:
                                        <select id='cliente' name='idCliente' required>
                                            <option disabled selected>Selecione o cliente</option>
                ";

        global $conn; // get variable from outside function
        $res = $conn->query("SELECT idUser, nomeUser FROM user WHERE tipoUtilizador = '". CLIENTE ."' ORDER BY nomeUser");

        if(!$res){
            die('Could not get data: ' . mysqli_error($conn));// se não funcionar dá erro
        }

        while($row = $res->fetch_assoc()) {
            echo "<option value='".$row["idUser"]."'>".$row["nomeUser"]."</option>";
        }

        echo "
                                        </select>
                                    </label>
                                </div>
                                <button class='form-btn' type='submit'>
                                    Próximo passo
                                </button>
                            </form>
                        </div>
                    </div>
                ";
    }

    function showAppointmentRegister() {
        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>Agendar nova marcação</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <form class='menu-form' action='efetuarMarcacao.php' method='POST'>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Data:
                                        <input type='date' name='data' min='". date("Y-m-d") . "' value='".date("Y-m-d")."'>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Hora:
                                        <select name='hora' required>
                                            <option value='' disabled>Selecione a hora</option>
                ";

        $horarios = array("09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "14:00", "14:30",
            "15:00", "15:30", "16:00", "16:30", "17:00", "17:30");

        foreach ($horarios as $hora) {
            echo "<option value='$hora'>$hora</option>";
        }

        echo "
                                        </select>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Tipo de Tratamento:
                                        <select name='tratamento' required>
                                            <option value='' disabled>Selecione o tratamento</option>
                                            <option value='corte'>Corte</option>
                                            <option value='banho'>Banho</option>
                                        </select>
                                    </label>
                                </div>
                                <div class='menu-form-input-box'>
                                    <label>
                                        Animal:
                                        <select name='idAnimal' required>
                                            <option selected disabled>Selecione o animal</option>
                ";

        global $conn; // get variable from outside function
        $res = $conn->query("SELECT idAnimal, nomeAnimal FROM animal WHERE idUser = " . $_SESSION["userId"]);

        if(!$res){
            die("Could not get data: " . mysqli_error($conn));
        }

        while($row = $res->fetch_assoc()) {
            echo "<option value='".$row["idAnimal"]."'>".$row["nomeAnimal"]."</option>";
        }

        echo "
                                        </select>
                                    </label>
                                </div>
                                <button class='form-btn' type='submit'>
                                    Agendar
                                </button>
                            </form>
                        </div>
                    </div>
                ";
    }

    function showAnimals() {
        $title = auth_isAdmin() ? "Gerir animais" : "Os meus animais";

        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>$title</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <embed type='text/html' src='pgExportTableXml.php?tableName=animal' width='100%' style='height: 450px'/>
                        </div>
                    </div>
                ";
    }

    function showAppointments() {
        $title = auth_isAdmin() ? "Gerir marcações" : "As minhas marcações";

        echo "
                    <div class='menu-box hidden'>
                        <div class='menu-title-container'>
                            <h2 class='menu-title'>$title</h2>
                            <img class='menu-title-arrow' src='down-arrow.webp'>
                        </div>
                        <div class='menu-content'>
                            <embed type='text/html' src='pgExportTableXml.php?tableName=marcacoes' width='100%' height='400px'>
                        </div>
                    </div>
                ";
    }
?>



<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="pgUtilizador.css">
</head>

<body>
    <?php
        $CURR_PAGE_NAME = "user_page";
        include_once("navbar.php");

        if (isset($_GET["bad_connection"])) {
            echo "
                <div class='edit-content-warning error'>
                    Não foi possível realizar a sua operação
                </div>
            ";
        } else if (isset($_GET["success"])) {
            echo "
                <div class='edit-content-warning success'>
                    Operação realizada com sucesso
                </div>
            ";
        } else if (count($_GET) >= 1) {
            echo "
                <div class='edit-content-warning error'>
                    Operação realizada com dados inválidos
                </div>
            ";
        }

        if (auth_isAdmin()) {
            showUserRegister();
            showUsers();
            showClientSelectAnimalRegister();
            showAnimals();
            showClientSelectAppointmentRegister();
            showAppointments();
        } else if (auth_isWorker()) {
            showAppointments();
            showClientSelectAppointmentRegister();
            showUserRegister();
        } else if (auth_isClient()) {
            showAppointments();
            showAppointmentRegister();
            showAnimalRegister();
            showAnimals();
        }

        echo "
            <div class='menu-box hidden' style='margin-bottom: 5rem'>
                <div class='menu-title-container'>
                    <h2 class='menu-title'>XML</h2>
                    <img class='menu-title-arrow' src='down-arrow.webp'>
                </div>
                <div class='menu-content'>
                    <div class='menu-content-option'>
                        <h3>Dados gerais</h3>
                        <div>
                            <form action='pgExportTableXml.php' method='GET'>
                                <a class='menu-action-button' href='info.xml'>Dados do website</a>
                                <button type='submit' class='menu-action-button' name='tableName' value='marcacoes'>Marcações</button>
        ";

        if (auth_isClient()) {
            echo "<button type='submit' class='menu-action-button' name='tableName' value='animal'>Animais</button>";
        }

        if (auth_isAdmin()) {
            echo "<button type='submit' class='menu-action-button' name='tableName' value='user'>Utilizadores</button>";
        }

        echo "
                            </form>
                        </div>
                    </div>
        ";

        if (auth_isAdmin()) {
            /* @var $conn mysqli */
            $query_tabelas = $conn->query("SHOW TABLES");
            $arr_tabelas = array();
            while($r = $query_tabelas->fetch_row()) {
                $arr_tabelas[] = $r[0];
            }

            echo "
                    <div class='menu-content-option'>
                        <h3>Exportar base de dados para</h3>
                        <form method='POST'>
                            <button class='menu-action-button' formaction='PgExportDatabase.php'>XML</button>
                            <button class='menu-action-button' formaction='PgExportDatabaseXSD.php'>XML Schema</button>
                            <button class='menu-action-button' formaction='PgExportDatabaseDTD.php'>DTD</button>
                            <h4 style='margin: 0.2rem 0 0.5rem 0;'>Tabelas a exportar</h4>
                            <label class='checkbox-label-group'>
                                Todas <input type='checkbox' value='all' id='all_tables' name='all_tables' checked />
                            </label> |
            ";
            foreach ($arr_tabelas as $tb) {
                echo "
                            <label class='checkbox-label-group' style='margin-left: 0.75rem;'>
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

        include_once("footer.html");
    ?>

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

            if (allTablesCb !== null) {
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
        }
    </script>
</body>
</html>