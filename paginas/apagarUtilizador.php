<?php
    include_once("auth.php");

    redirectToIfNotAdmin();

    if (!isset($_GET["idUser"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $userId = $_GET["idUser"];

    if ($userId == $_SESSION["userId"]) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Não se pode apagar a si mesmo!");
    }

    /* @var $conn mysqli */
    $stmt = $conn->prepare("DELETE FROM marcacoes WHERE idUser = ?");
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Ocorreu um erro a apagar as marcações do utilizador");
    }

    $stmt = $conn->prepare("DELETE FROM animal WHERE idUser = ?");
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Ocorreu um erro a apagar os animais do utilizador");
    }

    $stmt = $conn->prepare("DELETE FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Ocorreu um erro a apagar utilizador");
    }

    if ($stmt->affected_rows == 1) {
        echo "Eliminado com sucesso!";
    } else {
        echo "Não foi possivel apagar o utilizador id: $userId";
    }
    header("Refresh:2; url=PgUtilizador.php");
?>