<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    if (!isset($_POST["idMarcacao"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $appointmentId = $_POST["idMarcacao"];
    $redirectPage = "PgEditarMarcacao.php?idMarcacao=$appointmentId&";
    $invalidFields = array();

    if (!isset($_POST["data"])) {
        $invalidFields[] = "inv_data";
    }

    if (!isset($_POST["hora"])) {
        $invalidFields[] = "inv_hora";
    }

    if (count($invalidFields) > 0) {
        header("Location: $redirectPage".implode("&", $invalidFields));
        die();
    }

    $data = $_POST["data"];
    $hora = $_POST["hora"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("
                                    SELECT u.idUser as 'idUser', u.nomeUser as 'nomeUser', m.idMarcacao as 'idMarcacao',
                                           m.idAnimal as 'idAnimal', m.hora as 'hora', m.data as 'data', m.func as 'idFunc',
                                           m.tratamento as 'tratamento', a.nomeAnimal as 'nomeAnimal', a.tipoAnimal as 'tipoAnimal',
                                           m.estado as 'estadoMarcacao'
                                    FROM marcacoes m
                                        INNER JOIN user u ON m.idUser = u.idUser
                                        INNER JOIN animal a on m.idAnimal = a.idAnimal
                                    WHERE idMarcacao = ?;
                                ");
    $stmt->bind_param("i", $appointmentId);

    if (!$stmt->execute()) {
        $msg = "Erro com a base de dados ao obter os dados da marcação";
        header("Refresh: 2; url=$redirectPage"."db_error&msg=$msg");
        die();
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        $msg = "Não foi possivel obter os dados da marcação em questão";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $appointmentData = $res->fetch_assoc();

    if (strpos($appointmentData["hora"], $hora) === 0 && $data == $appointmentData["data"]) {
        header("Location: $redirectPage"."success");
        die();
    }

    // Tell user that it was updated even nothing needs to change!
    if ($appointmentData["estadoMarcacao"] == 1 && !auth_isAdmin()) {
        $msg = "Não pode alterar uma marcação já realizada";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    if ($appointmentData["idUser"] != $_SESSION["userId"] && !auth_isAdmin() && !auth_isWorker()) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("Não tem permissões para alterar esta marcação");
    }

    // ####### Time restriction
    $appointmentTimeStart = strtotime($data . " " . $hora);
    if (time() > $appointmentTimeStart && !auth_isAdmin()) { // admin can bypass time restrictions to fix previous appointments
        header("Location: $redirectPage"."inv_time");
        die();
    }
    $durationInMinutes = $appointmentData["tratamento"] == "corte" ? 60 : 30;
    $appointmentTimeEnd = strtotime($data . " " . $hora . " + $durationInMinutes minutes");

    // ###### Get workers
    $stmt = $conn->prepare("SELECT idUser FROM servicos_func WHERE tratamento = ? AND tipoAnimal = ?");
    $stmt->bind_param("ss", $appointmentData["tratamento"], $appointmentData["tipoAnimal"]);

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

    $startTime = date("H:i:s", $appointmentTimeStart);
    $endTime = date("H:i:s", $appointmentTimeEnd);

    if (strtotime("18:00:00") < strtotime($endTime) && !auth_isAdmin()) { // Admin can bypass time restrictions for eventual extra time service
        $msg = "Não atendemos fora do horario de atendimento, marque para amanhã";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $stmt = $conn->prepare("SELECT func FROM marcacoes WHERE data = ? AND hora >= ? AND hora < ? AND idMarcacao != ?");
    $stmt->bind_param("sssi", $data, $startTime, $endTime, $appointmentId);

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

    $stmt = $conn->prepare("UPDATE `marcacoes` SET `data` = ?, `hora` = ?, `func` = ? WHERE `idMarcacao` = ?");
    $stmt->bind_param("ssii", $data, $startTime, $choosenWorkerId, $appointmentId);

    if (!$stmt->execute()) {
        $msg = "Houve um erro com a base de dados ao atualizar a marcação";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    if ($stmt->affected_rows == 1) {
        header("Location: $redirectPage"."success");
        die();
    }

    $msg = "Não foi feita alteração, tente novamente mais tarde";
    header("Location: $redirectPage"."db_error&msg=$msg");
?>