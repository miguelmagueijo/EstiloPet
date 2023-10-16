<?php
session_start();

include "tiposUtilizadores.php";

//se existir o utilizador verifica o tipo
if (isset($_SESSION['utilizador'])) {
    if ($_SESSION['tipo'] == ADMIN) {
        echo "Administrador.";
        header("Refresh:1; url=PgUtilizador.php");
    } else if ($_SESSION['tipo'] == FUNC) {
        echo 'Funcionário';
        header("Refresh:1; url=PgUtilizador.php");
    } else if ($_SESSION['tipo'] == CLIENTE) {
        echo 'Cliente.';
        header("Refresh:1; url=PgUtilizador.php");
    } else if ($_SESSION['tipo'] == CLIENTE_POR_VALIDAR) {
        echo 'Aguarde validação pelo Administrador.';
        header("Refresh:1; url=PgInicial.php");
    } else {
        echo "Por favor, faça login.";
        header("Refresh:1; url=logout.php");
    }
} else
    header("Refresh:0; url=login.php");

?>