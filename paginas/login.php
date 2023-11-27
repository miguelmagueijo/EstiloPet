<?php
    session_start();
    if (isset($_SESSION["utilizador"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    if (!isset($_POST["user"]) || !isset($_POST["pass"])) {
        header("Location: PgLogin.php");
        die();
    }

    $user = $_POST["user"];
    $pass = md5($_POST["pass"]);

    include_once('../basedados/basedados.h');

    /* @var $conn mysqli */
    if (!isset($conn) || !$conn->ping()) {
        die("Houve um problema com a base de dados!<br><a href='index.php'>Voltar à página inicial</a>");
    }

    $stmt = $conn->prepare("SELECT `idUser`, `nomeUser`, `tipoUtilizador` FROM user WHERE nomeUser = ? AND pass = ?");
    $stmt->bind_param("ss", $user, $pass);

    if (!$stmt->execute()) {
        die("Houve um problema a encontrar o utilizador!<br><a href='index.php'>Voltar à página inicial</a>");
    }

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $userData = $result->fetch_assoc();

        include_once "tiposUtilizadores.php";

        if ($userData["tipoUtilizador"] == CLIENTE_POR_VALIDAR) {
            header("Location: PgLogin.php?not_validated");
            die();
        }

        $_SESSION["userId"] = $userData["idUser"];

        // TODO: remove legacy code
        $_SESSION["utilizador"] = $userData["nomeUser"];
        $_SESSION["tipo"] = $userData["tipoUtilizador"];
        $_SESSION["id"] = $userData["idUser"];

        header("Location: PgUtilizador.php");
    } else {
        header("Location: PgLogin.php?bad_user");
    }
?>