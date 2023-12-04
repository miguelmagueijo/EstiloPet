<?php
    include_once("auth.php");
    redirectToIfLogged("PgUtilizador.php");
    $CURR_PAGE_NAME = "login";
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
</head>

<body>
    <?php
        include_once("navbar.php");
    ?>
    <div id="container">
        <div style="margin-top: 2rem;">
            <?php
                $invalidUser = isset($_GET["bad_user"]);

                if ($invalidUser) {
                    echo "
                        <div class='form-warning error'>
                            Dados de utilizador inválidos!
                        </div>
                    ";
                } else if (isset($_GET["not_validated"])) {
                    echo "
                        <div class='form-warning'>
                            Utilizador ainda não validado!
                        </div>
                    ";
                }
            ?>
            <div class="login-register-box">
                <h1 class="login-register-title">Login</h1>
                <form class="login-register-form" action="login.php" method="POST">
                    <div class="input-box <?php if ($invalidUser) echo 'invalid' ?>">
                        <input type="text" name="user" placeholder="Nome de utilizador" />
                    </div>
                    <div class="input-box <?php if ($invalidUser) echo 'invalid' ?>">
                        <input type="password" name="pass" placeholder="Password" />
                    </div>
                    <button class="form-btn" type="submit">
                        Login
                    </button>
                    <div style="text-align: center">
                        Ainda não tem conta?<br><a style="font-weight: bold" href="PgRegisto.php">Crie uma agora!</a>
                    </div>
                </form>
            </div>
            <div style="text-align: center; margin-top: 2.5rem;">
                <a class="go-back-btn" href="index.php">Voltar à Página Principal</a>
            </div>
        </div>
        <div id="footer">
            <p>Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </div>
</body>

</html>