<?php
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    $pageName = "";

    if (isset($currentPage)) {
        $pageName = $currentPage;
    }


?>

<nav class="navbar">
    <div>
        <a style="display: flex; gap: 1rem; align-items: center;" href="./">
            <img class="logo" src="logo.png" alt="">
            <h1>Estilo Pet</h1>    
        </a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="PgLogin.php" <?php if ($pageName === "login") { echo "class='active'"; } ?> >Login</a></li>
            <li><a href="contactos.php">Contactos</a></li>
        </ul>
    </div>
</nav>