<?php
    session_start();
    if (isset($_SESSION['utilizador'])) {
        header("Refresh:0; url=PgUtilizador.php");
        die();
    }
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
    <div id="container">
        <div id="header">
            <img class="logo" src="logo.png" alt="">
            <h1>Estilo Pet</h1>
            <ul id="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="PgLogin.php" class="activa">Login</a></li>
                <li><a href="contactos.php">Contactos</a></li>
            </ul>
        </div>
        <div>
            <div id="aviso">
                <p>Deve fazer login ou registar-se para conseguir fazer uma marcação!</p>
            </div>
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
            <div class="login-box">
                <h1 class="login-title">Login</h1>
                <form class="login-form" action="login.php" method="POST">
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