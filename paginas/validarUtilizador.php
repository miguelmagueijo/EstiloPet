<?php
    include_once("auth.php");

    redirectToIfNotAdmin();

    if (!isset($_GET["idUser"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $userId = $_GET["idUser"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT `tipoUtilizador` from `user` WHERE idUser = ?");
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        header("Refresh: 3; url=PgUtilizador.php");
        die("Erro com a base de dados");
    }

    $res = $stmt->get_result();
    if (!$res || $res->num_rows != 1) {
        header("Refresh: 1; url=PgUtilizador.php");
        die("Utilizador não existente");
    }

    if ($res->fetch_assoc()["tipoUtilizador"] != CLIENTE_POR_VALIDAR) {
        header("Refresh: 1; url=PgUtilizador.php");
        die("Só pode validar utilizadores ainda não validados!");
    }

    $stmt = $conn->prepare("UPDATE `user` SET `tipoUtilizador` = 2 WHERE `idUser` = ?");
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        header("Refresh: 3; url=PgUtilizador.php");
        die("Erro com a base de dados");
    }

    if ($stmt->affected_rows == 1) {
        echo "Validado com sucesso!";
    } else {
        echo "Não foi possivel validar o utilizador, tente novamente mais tarde";
    }

    header("Refresh:2; url=PgUtilizador.php");
?>