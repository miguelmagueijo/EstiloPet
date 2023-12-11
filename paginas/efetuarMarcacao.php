<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    if (!isset($_POST["idAnimal"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $clientId = $_POST["idCliente"];
    $redirectPage = "PgEfetuarMarcacao.php?idCliente=$clientId&";
    if (!auth_isAdmin() && !auth_isWorker()) {
        $clientId = $_SESSION["userId"];
        $redirectPage = "PgEfetuarMarcacao.php?";
    }

    $invalidFields = array();

    if (!isset($_POST["data"])) {
        $invalidFields[] = "inv_data";
    }

    if (!isset($_POST["hora"])) {
        $invalidFields[] = "inv_hora";
    }

    if (!isset($_POST["tratamento"]) || !in_array($_POST["tratamento"], array("corte", "banho"))) {
        $invalidFields[] = "inv_tratamento";
    }

    if (count($invalidFields) > 0) {
        header("Location: $redirectPage".implode("&", $invalidFields));
        die();
    }

    $data = $_POST["data"];
    $hora = $_POST["hora"];
    $tratamento = $_POST["tratamento"];
    $animalId = $_POST["idAnimal"];


    // ####### Time restriction
    $appointmentTimeStart = strtotime($data . " " . $hora);
    if (time() > $appointmentTimeStart) {
        header("Location: $redirectPage"."inv_time");
        die();
    }
    $durationInMinutes = $tratamento == "corte" ? 60 : 30;
    $appointmentTimeEnd = strtotime($data . " " . $hora . " + $durationInMinutes minutes");

    $startTime = date("H:i:s", $appointmentTimeStart);
    $endTime = date("H:i:s", $appointmentTimeEnd);

    if (strtotime("18:00:00") < strtotime($endTime)) {
        $msg = "Não atendemos fora do horario de atendimento, marque para amanhã";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    // ##################
    // Check if client owns the animal
    // ##################
    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT idUser, tipoAnimal  FROM animal WHERE idAnimal = ?");
    $stmt->bind_param("i", $animalId);

    if (!$stmt->execute()) {
        $msg = "Houve um erro a consultar o animal da marcação";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        $msg = "Não foi possível encontrar o animal selecionado";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $resAssoc = $res->fetch_assoc();

    if ($clientId != $resAssoc["idUser"]) {
        $msg = "Não pode efetuar marcações com animais de outros utilizadores";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }
    // ##################

    // ###### Get workers
    $stmt = $conn->prepare("SELECT idUser FROM servicos_func WHERE tratamento = ? AND tipoAnimal = ?");
    $stmt->bind_param("ss", $tratamento, $resAssoc["tipoAnimal"]);

    if (!$stmt->execute()) {
        $msg = "Não foi possivel consultar os funcionarios possiveis";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        $msg = "Não existem funcionarios para o novo tipo de serviço";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $possibleWorkers = array();
    while ($rowAssoc = $res->fetch_assoc()) {
        $possibleWorkers[] = $rowAssoc["idUser"];
    }

    $stmt = $conn->prepare("SELECT func FROM marcacoes WHERE data = ? AND hora >= ? AND hora < ?");
    $stmt->bind_param("sss", $data, $startTime, $endTime);

    if (!$stmt->execute()) {
        $msg = "Erro na base dados ao confirmar funcionarios disponiveis";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $res = $stmt->get_result();

    if (!$res) {
        $msg = "Não foi possivel obter os funcionarios disponiveis, tente novamente mais tarde";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $occupiedWorkers = array();
    while ($row = $res->fetch_assoc()) {
        $occupiedWorkers[] = $row["func"];
    }

    $availableWorkers = array_values(array_filter(array_diff($possibleWorkers, $occupiedWorkers)));

    if (count($availableWorkers) == 0) {
        $msg = "Não existem funcionarios para essa data e hora, tente outra";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $choosenWorkerId = $availableWorkers[array_rand($availableWorkers)];
    // #################

    // #################
    // Get last appointment ID
    $query = $conn->query("SELECT max(idMarcacao) as 'maxId' FROM `marcacoes`");

    if (!$query) {
        $msg = "Erro interno";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $res = $query->fetch_assoc();


    $newAppointmentId = ($res == null) ? 1 : $res["maxId"] + 1;

    // #################


    $stmt = $conn->prepare("INSERT INTO `marcacoes` (`idMarcacao`, `data`, `hora`, `idAnimal` , `idUser`, `tratamento`, `func`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiisi", $newAppointmentId, $data, $hora, $animalId, $clientId, $tratamento, $choosenWorkerId);

    if (!$stmt->execute()) {
        $msg = "Houve um erro com a base de dados ao criar a nova marcação";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    if ($stmt->affected_rows == 1) {
        header("Location: $redirectPage"."success");
        die();
    }

    $msg = "Não foi possivel criar, tente novamente mais tarde";
    header("Location: $redirectPage"."db_error&msg=$msg");
?>