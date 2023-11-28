<?php
    include_once("auth.php");

    if (isset($auth_userType) && $auth_userType == CLIENTE) {
        header("Location: PgUtilizador.php");
        die();
    }

    $redirectPageName = auth_isLogged() ? "PgUtilizador.php" : "PgRegisto.php";
    $invalidFields = array();

    if (!isset($_POST["username"]) || strlen($_POST["username"]) <= 2) {
        $invalidFields[] = "inv_nome";
    }

    if (!isset($_POST["morada"]) || strlen($_POST["morada"]) <= 2) {
        $invalidFields[] = "inv_morada";
    }

    if (!isset($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $invalidFields[] = "inv_email";
    }

    if (!isset($_POST["telemovel"]) || strlen($_POST["telemovel"]) < 9) {
        $invalidFields[] = "inv_telemovel";
    }

    if (!isset($_POST["pass"]) || strlen($_POST["pass"]) <= 2) {
        $invalidFields[] = "inv_password";
    }

    if (!isset($_POST["ConfPass"]) || strlen($_POST["ConfPass"]) <= 2 || strcmp($_POST["pass"], $_POST["ConfPass"]) != 0) {
        $invalidFields[] = "inv_confirmacao_password";
    }

    if (count($invalidFields) > 0) {
        header("Location: $redirectPageName?".implode("&", $invalidFields));
        die();
    }

    $userTypeId = auth_isLogged() ? CLIENTE : CLIENTE_POR_VALIDAR;

    /* @var $conn mysqli */
    $stmt = $conn->prepare("INSERT INTO `user` (`nomeUser`, `morada`, `email`, `telemovel`, `pass`, `tipoUtilizador`)
                            VALUES (?, ?, ?, ?, MD5(?), $userTypeId)");

    $stmt->bind_param("sssss", $_POST["username"], $_POST["morada"], $_POST["email"], $_POST["telemovel"], $_POST["pass"]);

    if (!$stmt->execute() || $stmt->affected_rows == 0) {
        header("Location: $redirectPageName?create_error");
        die();
    }

    header("Location: $redirectPageName?success");
?>