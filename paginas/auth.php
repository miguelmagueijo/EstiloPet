<?php
    include_once('../basedados/basedados.h');
    include_once("tiposUtilizadores.php");

    if (!isset($conn) || !$conn->ping()) {
        die("Database failure, please contact the admin.");
    }

    $auth_userType = -1;

    function auth_isAdmin() {
        global $auth_userType;
        return $auth_userType == ADMIN;
    }

    session_start();
    if(!isset($_SESSION["utilizador"])) {
        return;
    }

    $stmt = $conn->prepare("SELECT tipoUtilizador FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $_SESSION["id"]);

    if (!$stmt->execute()) {
        die("AUTH: Could not get data.");
    }

    $authAssoc = $stmt->get_result()->fetch_assoc();

    $auth_userType = $authAssoc["tipoUtilizador"];

    unset($stmt);
    unset($authRes);
?>

