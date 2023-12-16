<?php
    include_once('../basedados/basedados.h');
    include_once("tiposUtilizadores.php");
    date_default_timezone_set("Europe/Lisbon");

    /* @var $conn mysqli */
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

    function auth_isWorker() {
        global $auth_userType;
        return $auth_userType == FUNC;
    }

    function auth_isClient() {
        global $auth_userType;
        return $auth_userType == CLIENTE;
    }

    function redirectToIfNotAdmin($location = "index.php") {
        if (!auth_isAdmin()) {
            header("Location: $location");
            die();
        }
    }

    function redirectToIfNotLogged($location = "index.php") {
        if (!auth_isLogged()) {
            header("Location: $location");
            die();
        }
    }

    function redirectToIfLogged($location = "index.php") {
        if (auth_isLogged()) {
            header("Location: $location");
            die();
        }
    }

    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    if(!isset($_SESSION["userId"])) {
        return;
    }

    $stmt = $conn->prepare("SELECT tipoUtilizador FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $_SESSION["userId"]);

    if (!$stmt->execute()) {
        die("AUTH: Could not get data.");
    }

    $auth_userType = $stmt->get_result()->fetch_assoc()["tipoUtilizador"];

    if ($auth_userType === null || $auth_userType == CLIENTE_POR_VALIDAR) {
        header("Location: logout.php");
        die();
    }

    unset($stmt);
?>

