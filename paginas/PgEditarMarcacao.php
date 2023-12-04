<?php
    include_once("auth.php");
    redirectToIfNotLogged();

    if (!isset($_GET["idMarcacao"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $marcacaoId = $_GET["idMarcacao"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("
                                SELECT u.idUser as 'idUser', u.nomeUser as 'nomeUser', m.idMarcacao as 'idMarcacao',
                                       m.idAnimal as 'idAnimal', m.hora as 'hora', m.data as 'data', m.func as 'idFunc',
                                       m.tratamento as 'tratamento', a.nomeAnimal as 'nomeAnimal'
                                FROM marcacoes m
                                    INNER JOIN user u ON m.idUser = u.idUser
                                    INNER JOIN animal a on m.idAnimal = a.idAnimal
                                WHERE idMarcacao = ?;
                            ");
    $stmt->bind_param("i", $marcacaoId);

    if (!$stmt->execute()) {
        header("Refresh: 5; url=PgUtilizador.php");
        die("Não foi possivel obter os dados da marcação em questão...");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Location: PgUtilizador.php");
        die();
    }

    $pageData = $res->fetch_assoc();

    $nowTime = strtotime(date("H:m"));
    $todayDate = strtotime(date("Y-m-d"));

    if ($todayDate >= strtotime($pageData["data"])) {
        header("Refresh: 5; url=PgUtilizador.php");
        die("Não pode atualizar marcações antigas ou do próprio dia.");
    }

    $minPossibleDate = $pageData["data"];
    $tomorrowDate = date("Y-m-d", strtotime(date("Y-m-d")." + 1 days"));
    if (strtotime($minPossibleDate) > strtotime($tomorrowDate)) {
        $minPossibleDate = $tomorrowDate;
    }

    // Client has no access to other chekups that aren't his
    $isClientOwnCheckup = auth_isClient() && $pageData["idUser"] == $_SESSION["userId"];
    if (!$isClientOwnCheckup && !auth_isAdmin() && !auth_isWorker()
        || (false)
    ) {
        header("Location: PgUtilizador.php");
        die();
    }
?>

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
    <div class="edit-content-container">
        <h2>Editar Marcação</h2>
        <form action="editarMarcacao.php" method="GET">
            <?php
                if (auth_isAdmin() || auth_isWorker()) {
                    echo "
                        <div class='input-box'>
                            <label>
                                Nome
                                <input type='text' value='". $pageData['nomeUser'] ."' disabled readonly/>
                            </label>
                        </div>
                    ";
                }

            ?>
            <div class="input-box">
                <label>
                    Data
                    <input type="date" name="data" min="<?php echo $minPossibleDate ?>" value="<?php echo $pageData['data'] ?>" required/>
                </label>
            </div>
            <div class="input-box">
                <label>
                    Hora
                    <select>
                        <?php
                            $horarios = array("09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "14:00",
                            "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30");

                            $pageData["hora"] = substr($pageData["hora"], 0, 5);



                            foreach ($horarios as $hora) {
                                echo "<option value='$hora' ". ($pageData["hora"] == $hora ? 'selected' : null) .">$hora</option>";
                            }
                        ?>
                    </select>
                </label>
            </div>
            <div class="input-box">
                <label>
                    <!-- Ana and Clara originally didn't allow treatment change -->
                    Tipo de tratamento
                    <input type="text" style="text-transform: capitalize" value="<?php echo $pageData['tratamento'] ?>" disabled readonly>
                </label>
            </div>
            <div class="input-box">
                <label>
                    <!-- Ana and Clara originally didn't allow animal change -->
                    Animal
                    <input type="text" value="<?php echo $pageData['nomeAnimal'] ?>" disabled readonly>
                </label>
            </div>
            <input type="hidden" name="idMarcacao" value="<?php echo $marcacaoId ?>">
            <button class="form-btn" type="submit">
                Guardar alterações
            </button>
        </form>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a class="go-back-btn" href="PgUtilizador.php">
            Voltar atrás
        </a>
    </div>
    <div id="footer">
        <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
    </div>
</body>

</html>