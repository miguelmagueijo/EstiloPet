<html>
    <head>
        <meta charset="UTF-8">
    </head>
</html>
<?php
    include_once("auth.php");
    redirectToIfNotLogged("logout.php");

    $userId = isset($_POST["idUser"]) ? $_POST["idUser"] : $_SESSION["userId"];
    $editingOwnData = $userId == $_SESSION["userId"];
    $invalidFields = array();
    $redirectPage = $editingOwnData ? "PgDadosPessoais.php?" : "PgEditarUtilizador.php?idUser=$userId&";

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

    if (count($invalidFields) > 0) {
        header("Location: $redirectPage".implode("&", $invalidFields));
        die();
    }

    $username = $_POST["username"];
    $address = $_POST["morada"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["telemovel"];
    $newUserType = $auth_userType;

    /* @var $conn mysqli */
    $stmt = null;
    if (!$editingOwnData) {
        redirectToIfNotAdmin("PgUtilizador.php");
        $newUserType = isset($_POST["tipoUtilizador"]) ? $_POST["tipoUtilizador"] : $newUserType;
        if ($newUserType != ADMIN && $newUserType != CLIENTE_POR_VALIDAR && $newUserType != CLIENTE && $newUserType != FUNC) {
            header("Location: $redirectPage"."inv_tipoUtilizador");
            die();
        }
    }

    $stmt = $conn->prepare("UPDATE `user` SET `nomeUser` = ?, `morada` = ?, `email` = ?, `telemovel` = ?, `tipoUtilizador` = ? WHERE `idUser` = ?");
    $stmt->bind_param("ssssii", $username, $address, $email, $phoneNumber, $newUserType, $userId);

    if (!$stmt->execute()) {
        header("Location: $redirectPage"."bad_connection");
        die();
    }

    // Force same user check because admin cannot change user password in the website
    if ($editingOwnData && isset($_POST["pass"]) && isset($_POST["ConfPass"]) && !empty($_POST["pass"]) && !empty($_POST["ConfPass"])) {
        if (strlen($_POST["pass"]) <= 2) {
            $invalidFields[] = "inv_password";
        }

        if (strlen($_POST["ConfPass"]) <= 2 || strcmp($_POST["pass"], $_POST["ConfPass"]) != 0) {
            $invalidFields[] = "inv_confirmacao_password";
        }

        if (count($invalidFields) > 0) {
            header("Location: $redirectPage".implode("&", $invalidFields));
            die();
        }

        $pass = $_POST["pass"];

        $stmt = $conn->prepare("UPDATE `user` SET `pass` = MD5(?) WHERE `idUser` = ?");
        $stmt->bind_param("si", $pass, $userId);

        if (!$stmt->execute()) {
            header("Location: $redirectPage"."bad_connection_password");
            die();
        }
    }

    header("Location: $redirectPage"."success");
?>