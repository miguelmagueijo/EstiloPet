<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    if (!isset($_GET["idMarcacao"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $appointmentId = $_GET["idMarcacao"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT idUser, func, data, hora FROM marcacoes WHERE idMarcacao = ?");
    $stmt->bind_param("i", $appointmentId);

    if (!$stmt->execute()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Houve problemas com a base de dados");
    }

    $res = $stmt->get_result();

    if(!$res || $res->num_rows == 0) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Não existe nenhuma marcação com id $appointmentId");
    }

    $resAssoc = $res->fetch_assoc();

    if ((auth_isWorker() && $resAssoc["func"] == $_SESSION["userId"]) ||
        auth_isAdmin() || (auth_isClient() && $resAssoc["idUser"] == $_SESSION["userId"])) {

        if (!auth_isAdmin() && strtotime($resAssoc["data"] ." ". $resAssoc["hora"]) <= time()) {
            header("Refresh:2; url=PgUtilizador.php");
            die("Apenas o ADMIN pode apagar marcações antigas, contacte um administrador para apagar.");
        }

        $stmt = $conn->prepare("DELETE FROM marcacoes WHERE idMarcacao = ?");
        $stmt->bind_param("i", $appointmentId);

        if (!$stmt->execute()) {
            header("Refresh:2; url=PgUtilizador.php");
            die("Houve problemas com a base de dados ao apagar marcação");
        }

        if ($stmt->affected_rows == 1) {
            echo "Marcação apagada com sucesso";
        } else {
            echo "Ocorreu um erro ao apagar a marcação, tente novamente mais tarde";
        }
    } else {
        echo "Não tem permissões para esta operação";
    }

    header("Refresh:2; url=PgUtilizador.php");
?>