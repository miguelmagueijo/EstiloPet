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
    session_start();
    if (isset($_SESSION['utilizador'])) {
        header("Refresh:0; url=PgUtilizador.php");
    }

    ?>
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
            <div id="registo-box">
                <div id="login-cabecalho">Criar Conta</div>
                <form action="registar.php" method="POST">
                    <div class="input-div" id="input-form">
                        Nome de utilizador:
                        <input type="text" name="username" />
                    </div>
                    <div class="input-div" id="input-form">
                        Morada:
                        <input type="text" name="morada" />
                    </div>
                    <div class="input-div" id="input-form">
                        E-mail:
                        <input type="e-mail" name="email" />
                    </div>
                    <div class="input-div" id="input-form">
                        Contacto:
                        <input type="tel" name="telemovel" />
                    </div>
                    <div class="input-div" id="input-form">
                        Password:
                        <input type="password" name="pass" />
                    </div>
                    <div class="input-div" id="input-form">
                        Confirmar Password:
                        <input type="password" name="ConfPass" />
                    </div>
                    <div id="acoes">
                        <input type="submit" value="Registar">
                        <div id="registo"><a href="PgLogin.php">Voltar ao Login</a></div>
                        <br><br>
                        <div id="volta"><a href="index.php">Voltar à Página Principal</a></div>
                    </div>
                </form>
            </div>
        </div>
        <div id="footer">
            <p>Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </div>
</body>

</html>