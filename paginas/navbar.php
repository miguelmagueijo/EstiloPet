<?php
    include_once("auth.php");

    if (!isset($CURR_PAGE_NAME)) {
        $CURR_PAGE_NAME = "none";
    }
?>

<nav>
    <div class="navbar">
        <a href="index.php" style="text-decoration: none" class="navbar-logo-container">
            <img class="logo" src="logo.png" alt='Estilo Pet logotipo'>
            <h1>Estilo Pet</h1>
        </a>
        <ul class="navbar-list">
            <li><a class="navbar-link <?php if (strcmp($CURR_PAGE_NAME, "index") == 0) echo "active" ?>" href="index.php">Home</a></li>
            <li><a class="navbar-link <?php if (strcmp($CURR_PAGE_NAME, "contacts") == 0) echo "active" ?>" href="contactos.php">Contactos</a></li>
            <?php
                if (auth_isLogged()) {
                    echo "
                        <li><a class='navbar-link ". (strcmp($CURR_PAGE_NAME, "user_page") == 0 ? "active" : null) ."' href='PgUtilizador.php'>Painel utilizador</a></li>
                        <li><a class='navbar-link ". (strcmp($CURR_PAGE_NAME, "personal_data") == 0 ? "active" : null) ."' href='PgDadosPessoais.php'>Dados Pessoais</a></li>
                        <li><a class='nav-logout-btn' href='logout.php'>Logout</a></li>
                    ";
                } else {
                    echo "
                        <li><a class='nav-login-btn ". (strcmp($CURR_PAGE_NAME, "login") == 0 ? "active" : null) ."' href='PgLogin.php'>Login</a></li>
                        <li><a class='nav-register-btn ". (strcmp($CURR_PAGE_NAME, "register") == 0 ? "active" : null) ."' href='PgRegisto.php'>Registar</a></li>
                    ";
                }
            ?>
        </ul>
    </div>
</nav>