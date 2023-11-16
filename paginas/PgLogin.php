<?php
    session_start();
    if (isset($_SESSION['utilizador'])) {
        header("Refresh:0; url=PgUtilizador.php");
    }

    $currentPage = "login";
?>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>

    <body>
        <?php
            include_once("ComponenteNavbar.php");
        ?>
        <main>
            <?php
                if (isset($_GET["error_msg"])) {
                    echo "
                        <div class='warning-box error'>
                            ". $_GET["error_msg"] ."
                        </div>
                        ";
                }    
            ?>
            
            <div class="login-box">
                <div class="login-head">Login</div>
                <form action="login.php" method="POST">
                    <div class="input-div" id="input-form">
                        Nome de utilizador:
                        <input type="text" name="user" />
                    </div>
                    <div class="input-div" id="input-form">
                        Password:
                        <input type="password" name="pass" />
                    </div>
                    
                    <input class="login-btn" type="submit" value="Login">
                    <input style="margin-top: 0.75rem" class="register-btn" type="submit" value="Criar Conta" formaction="PgRegisto.php">
                    
                </form>
            </div>
            <div style="text-align: center; margin-top: 1.5rem">
                <a class="login-back-href" href="./">Voltar à Página Principal</a>
            </div>
        </main>
        <footer>
            <p>Originalmente projetado por Ana Correia & Clara Aidos</p>
            <p>Alterado por Miguel Magueijo e Miguel Antunes</p>
        </footer>
    </body>
</html>