<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    if (!isset($_POST["idAnimal"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $invalidFields = array();
    if (!isset($_POST["nome-animal"]) || strlen($_POST["nome-animal"]) <= 2) {
        $invalidFields[] = "inv_nome";
    }

    if (!isset($_POST["tipo-animal"]) || !in_array($_POST["tipo-animal"], array("cao", "gato"))) {
        $invalidFields[] = "inv_tipo";
    }

    if (!isset($_POST["porte-animal"]) || !in_array($_POST["porte-animal"], array("grande", "medio", "pequeno"))) {
        $invalidFields[] = "inv_porte";
    }

    $redirectPage = "PgEditarAnimal.php?idAnimal=".$_POST["idAnimal"]."&";

    if (count($invalidFields) > 0) {
        header("Location: $redirectPage".implode("&", $invalidFields));
        die();
    }

    $animalId = $_POST["idAnimal"];
    $animalName = $_POST["nome-animal"];
    $animalType = $_POST["tipo-animal"];
    $animalSize = $_POST["porte-animal"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT idUser FROM animal WHERE idAnimal = ?");
    $stmt->bind_param("i", $animalId);

    if (!$stmt->execute()) {
        $msg="Erro com a base de dados ao procurar o animal";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $res = $stmt->get_result();
    if (!$res || $res->num_rows == 0) {
        $msg="Animal não encontrado";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $userId = $res->fetch_assoc()["idUser"];

    if ($userId != $_SESSION["userId"] && !auth_isAdmin()) {
        $msg="Só pode editar os seus próprios animais";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    $stmt = $conn->prepare("UPDATE `animal` SET `nomeAnimal` = ?, `tipoAnimal` = ?, `porte` = ? WHERE `idUser` = ? and `idAnimal` = ?");
    $stmt->bind_param("sssii", $animalName, $animalType, $animalSize, $userId, $animalId);

    if (!$stmt->execute()) {
        $msg="Erro na base de dados ao editar animal";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    if ($stmt->affected_rows == 1) {
        header("Location: $redirectPage"."success");
        die();
    } else {
        $msg="Não foi possível editar animal, tente novamente mais tarde";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }
?>