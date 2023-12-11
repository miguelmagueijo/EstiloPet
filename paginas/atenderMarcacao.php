<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    if (!isset($_GET["idMarcacao"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    if (!auth_isWorker()) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("O seu tipo de utilizador não permite atender marcações");
    }

    $appointmentId = $_GET["idMarcacao"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT func, data, hora FROM marcacoes WHERE idMarcacao = ?");
    $stmt->bind_param("i", $appointmentId);

    if (!$stmt->execute()) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("Ocorreu um problema com a base de dados");
    }

    $res = $stmt->get_result();

    if (!$res) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("Não foi possivel obter os dados de marcação");
    }

    $resAssoc = $res->fetch_assoc();

    if ($_SESSION["userId"] != $resAssoc["func"]) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("Você não é o funcionário desta marcação! Não a pode atender!");
    }

    if (strtotime($resAssoc["data"] ." ". $resAssoc["hora"]) > time()) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("Não pode atender marcações futuras!");
    }

    $stmt = $conn->prepare("UPDATE `marcacoes` SET `estado` = 1 WHERE `idMarcacao` = ?");
    $stmt->bind_param("i", $appointmentId);

    if (!$stmt->execute()) {
        header("Refresh: 2; url=PgUtilizador.php");
        die("Ocorreu um problema ao atender marcação");
    }

    if ($stmt->affected_rows == 1) {
        echo "Marcação atendida com sucesso";
    } else {
        echo "Não foi possivel atender marcação, tente mais tardes";
    }

    header("Refresh: 2; url=PgUtilizador.php");
?>