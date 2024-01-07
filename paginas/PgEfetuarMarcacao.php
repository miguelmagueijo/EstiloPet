<?php
    include_once("auth.php");
    redirectToIfNotLogged();

    if (!isset($_GET["idCliente"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $clientId = $_GET["idCliente"];
    $nowTime = strtotime(date("H:m"));
    $todayDate = strtotime(date("Y-m-d"));
    $tomorrowDate = date("Y-m-d", strtotime(date("Y-m-d") . " + 1 days"));

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT nomeUser FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $clientId);

    if (!$stmt->execute()) {
        die("Por favor recarregue a página, se persistir contacte um administrador #1");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Cliente não existe (id: ".$clientId.")");
    }

    $clientName = $res->fetch_row()[0];

    $stmt = $conn->prepare("SELECT idAnimal, nomeAnimal FROM animal WHERE idUser = ?");
    $stmt->bind_param("i", $clientId);

    if (!$stmt->execute()) {
        die("Por favor recarregue a página, se persistir contacte um administrador #2");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Cliente não possui qualquer animal associado, crie um antes de efetuar uma marcação");
    }

    $clientAnimals = array();
    while($row = $res->fetch_assoc()) {
        $clientAnimals[] = array("id" => $row["idAnimal"], "name" => $row["nomeAnimal"]);
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet - Criar marcação</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />
        <link rel="stylesheet" type="text/css" href="estiloPgUtilizador.css" />
    </head>
    <body>
        <?php
            include_once("navbar.php");

            if (isset($_GET["db_error"])) {
                echo "
                    <div class='form-warning error'>
                        ". $_GET["msg"] ."
                    </div>
                ";
            } else if (isset($_GET["success"])) {
                echo "
                    <div class='form-warning success'>
                        Marcação editada com sucesso
                    </div>
                ";
            } else if (count($_GET) > 1) {
                $extraMsg = "";
                if (isset($_GET["inv_time"])) {
                    $extraMsg = "<br>Não pode marcar consultas no passado";
                }

                echo "
                    <div class='form-warning error'>
                        Dados de registo inválidos $extraMsg
                    </div>
                ";
            }
        ?>
        <div class="edit-content-container">
            <h2>Agendar nova marcação</h2>
            <form action="efetuarMarcacao.php" method="POST">
                <div class="input-box">
                    <label>
                        Nome do cliente
                        <input type="text" disabled value="<?php echo $clientName ?>"/>
                    </label>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_time']) ? 'invalid' : '' ?>">
                    <label>
                        Data
                        <input type="date" name="data" min="<?php echo date('Y-m-d') ?>" value="<?php echo date('Y-m-d') ?>" required/>
                    </label>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_time']) ? 'invalid' : '' ?>">
                    <label>
                        Hora
                        <select name="hora">
                            <?php
                                $horarios = array("09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30",
                                    "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30");

                                foreach ($horarios as $hora) {
                                    echo "<option value='$hora'>$hora</option>";
                                }
                            ?>
                        </select>
                    </label>
                </div>
                <div class="input-box">
                    <label for="tratamento">Tipo de Tratamento:</label>
                    <select id="tratamento" name="tratamento" required>
                        <option value="">Selecione o tratamento</option>
                        <option value="corte">Corte</option>
                        <option value="banho">Banho</option>
                    </select>
                </div>
                <div class="input-box">
                    <label for="idAnimal">Animal:</label>
                    <select id="idAnimal" name="idAnimal" required>
                        <option disabled>Selecione o animal</option>
                        <?php
                            foreach ($clientAnimals as $animal) {
                                echo "<option value='".$animal["id"]."'>".$animal["name"]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="idCliente" value="<?php echo $clientId ?>"/>
                <button class="form-btn" type="submit">
                    Agendar
                </button>
            </form>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a class="go-back-btn" href="PgUtilizador.php">
                Voltar atrás
            </a>
        </div>
        <?php include_once("footer.html") ?>
    </body>
</html>