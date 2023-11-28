<?php
    session_start();
    if (isset($_SESSION["userId"])) {
        header("Location: PgUtilizador.php");
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
    <div id="container-registar">
        <div id="header">
            <img class="logo" src="logo.png" alt="">
            <h1>Estilo Pet</h1>
            <ul id="nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="PgLogin.php">Login</a></li>
                <li><a href="PgRegisto.php" class="activa">Criar Conta</a></li>
                <li><a href="contactos.php">Contactos</a></li>
            </ul>
        </div>
        <div>
            <?php
                $invalidUser = isset($_GET["bad_user"]);

                if (isset($_GET["create_error"])) {
                    echo "
                            <div class='form-warning error'>
                                Nome de utilizador já em uso!
                            </div>
                        ";
                } else if (isset($_GET["success"])) {
                    echo "
                            <div class='form-warning success'>
                                Conta criada com sucesso.<br>Aguarde confirmação da mesma.
                            </div>
                        ";
                } else if (count($_GET) > 0) {
                    echo "
                            <div class='form-warning error'>
                                Dados de registo inválidos
                            </div>
                        ";
                }
            ?>
            <div class="login-register-box">
                <h1 class="login-register-title">Criar conta</h1>
                <form class="login-register-form" action="registar.php" method="POST">
                    <div class="input-box <?php echo isset($_GET['inv_nome']) ? 'invalid' : '' ?>">
                        <label>
                            Nome de utilizador
                            <input type="text" name="username" id="username" minlength="3" />
                        </label>
                    </div>
                    <div class="input-box <?php echo isset($_GET['inv_morada']) ? 'invalid' : '' ?>">
                        <label>
                            Morada
                            <input type="text" name="morada" minlength="3" />
                        </label>
                    </div>
                    <div class="input-box <?php echo isset($_GET['inv_email']) ? 'invalid' : '' ?>">
                        <label>
                            Email
                            <input type="email" name="email" required />
                        </label>
                    </div>
                    <div class="input-box <?php echo isset($_GET['inv_telemovel']) ? 'invalid' : '' ?>">
                        <label>
                            Contacto
                            <input type="tel" name="telemovel" required />
                        </label>
                    </div>
                    <div class="input-box <?php echo isset($_GET['inv_password']) ? 'invalid' : '' ?>">
                        <label>
                            Password
                            <input type="password" name="pass" minlength="3" required />
                        </label>
                    </div>
                    <div class="input-box <?php echo isset($_GET['inv_confirmacao_password']) ? 'invalid' : '' ?>">
                        <label>
                            Confirmar Password
                            <input type="password" name="ConfPass" minlength="3" required />
                        </label>
                    </div>
                    <button class="form-btn" type="submit">
                        Registar
                    </button>
                    <div style="text-align: center">
                        Já se registou?<br><a style="font-weight: bold" href="PgLogin.php">Efetuar login!</a>
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