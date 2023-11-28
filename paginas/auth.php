<?php
    include_once('../basedados/basedados.h');
    include_once("tiposUtilizadores.php");

    if (!isset($conn) || !$conn->ping()) {
        die("Database failure, please contact the admin.");
    }

    $auth_userType = -1;

    function auth_isLogged() {
        global $auth_userType;
        return $auth_userType != -1;
    }

    function auth_isAdmin() {
        global $auth_userType;
        return $auth_userType == ADMIN;
    }

    session_start();
    if(!isset($_SESSION["utilizador"])) { // TODO use userId, utilizador will be deprecated
        return;
    }

    $stmt = $conn->prepare("SELECT tipoUtilizador FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $_SESSION["id"]);

    if (!$stmt->execute()) {
        die("AUTH: Could not get data.");
    }

    $auth_userType = $stmt->get_result()->fetch_assoc()["tipoUtilizador"];

    unset($stmt);
?>

