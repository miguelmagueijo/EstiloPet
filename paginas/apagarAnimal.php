<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    if (!isset($_GET["idAnimal"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $animalId = $_GET["idAnimal"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT idUser FROM animal WHERE idAnimal = ?");
    $stmt->bind_param("i", $animalId);

    if (!$stmt->execute()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Houve problemas com a base de dados");
    }

    $res = $stmt->get_result();
    if(!$res || $res->num_rows == 0) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Animal (id: $animalId) não encontrado");
    }

    $userId = $res->fetch_assoc()["idUser"];

    if ($userId != $_SESSION["userId"] && !auth_isAdmin()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Só o dono do animal pode apagar o seu animal");
    }

    $stmt = $conn->prepare("DELETE FROM animal WHERE idAnimal = ?");
    $stmt->bind_param("i", $animalId);

    if (!$stmt->execute()) {
        header("Refresh:2; url=PgUtilizador.php");
        die("Houve problemas com a base de dados ao apagar o animal");
    }

    if ($stmt->affected_rows == 1) {
        echo "Animal eliminado com sucesso!";
    } else {
        echo "Não foi possivel eliminar o animal pretendido, tente novamente mais tarde.";
    }

    header("Refresh:2; url=PgUtilizador.php");
?>