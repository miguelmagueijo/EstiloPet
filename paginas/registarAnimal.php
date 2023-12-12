<?php
    include_once("auth.php");

    redirectToIfNotLogged();

    $clientId = $_SESSION["userId"];
    $redirectPage = "PgUtilizador.php?";
    if (isset($_POST["idCliente"])) {
        $clientId = $_POST["idCliente"];
        $redirectPage = "PgRegistarAnimal.php?idCliente=".$clientId."&";
    }

    $invalidFields = array();
    if (!isset($_POST["nome-animal"]) || strlen($_POST["nome-animal"]) < 3) {
        $invalidFields[] = "inv_nome";
    }

    if (!isset($_POST["tipo-animal"]) || !in_array($_POST["tipo-animal"], array("cao", "gato"))) {
        $invalidFields[] = "inv_tipo";
    }

    if (!isset($_POST["porte-animal"]) || !in_array($_POST["porte-animal"], array("grande", "medio", "pequeno"))) {
        $invalidFields[] = "inv_porte";
    }

    if (count($invalidFields) > 0) {
        header("Location: $redirectPage".implode("&", $invalidFields));
        die();
    }

    $nomeAnimal = $_POST["nome-animal"];
    $tipoAnimal = $_POST["tipo-animal"];
    $porte = $_POST["porte-animal"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("INSERT INTO animal (nomeAnimal, porte, tipoAnimal, idUser) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nomeAnimal, $porte, $tipoAnimal, $clientId);

    if (!$stmt->execute()) {
        $msg="Erro com a base de dados ao adcionar o animal";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    if ($stmt->affected_rows == 0) {
        $msg="O animal não foi inserido, tente outro nome";
        header("Location: $redirectPage"."db_error&msg=$msg");
        die();
    }

    header("Location: $redirectPage"."success");
?>