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
            <div id="login-box">
                <div id="login-cabecalho">Login</div>
                <form action="login.php" method="POST">
                    <div class="input-div" id="input-form">
                        Nome de utilizador:
                        <input type="text" name="user" />
                    </div>
                    <div class="input-div" id="input-form">
                        Password:
                        <input type="password" name="pass" />
                    </div>
                    <div id="acoes">
                        <input type="submit" value="Login">
                        <input type="submit" value="Criar Conta" formaction="PgRegisto.php">
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