<?php
    include_once("auth.php");
    redirectToIfNotLogged();

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT * FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $_SESSION["userId"]);

    if (!$stmt->execute()) {
        header("Refresh: 3; url=PgUtilizador.php");
        die("<h1>Erro na consulta da base de dados...</h1>");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Refresh: 3; url=PgUtilizador.php");
        die("<h2>Existiu um erro a encontrar os seus dados</h2>");
    }

    $userData = $res->fetch_assoc();

    $CURR_PAGE_NAME = "personal_data";
?>


<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet - Editar dados pessoais</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />
    </head>

    <body>
        <?php
            include_once("navbar.php");

            if (isset($_GET["bad_connection"])) {
                echo "
                        <div class='edit-content-warning error'>
                            Não foi possível atualizar os dados
                        </div>
                    ";
            } else if (isset($_GET["success"])) {
                echo "
                        <div class='edit-content-warning success'>
                            Dados atualizados com sucesso
                        </div>
                    ";
            } else if (isset($_GET["bad_connection_password"])) {
                echo "
                    <div class='edit-content-warning error'>
                        As passwords não correspondem
                    </div>
                ";
            } else if (count($_GET) >= 1) {
                echo "
                        <div class='edit-content-warning error'>
                            Possui dados inválidos
                        </div>
                    ";
            }
        ?>
        <div class="edit-content-container">
            <h2>Editar Dados Pessoais</h2>
            <form action="editarDados.php" method="POST">
                <div class="input-box <?php echo isset($_GET['inv_nome']) ? 'invalid' : '' ?>">
                    Nome de utilizador:
                    <input type="text" name="username" value="<?php echo $userData['nomeUser'] ?>" required/>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_morada']) ? 'invalid' : '' ?>">
                    Morada:
                    <input type="text" name="morada" value="<?php echo $userData['morada'] ?>" required/>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_email']) ? 'invalid' : '' ?>">
                    E-mail:
                    <input type="email" name="email" value="<?php echo $userData['email'] ?>" required/>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_telemovel']) ? 'invalid' : '' ?>">
                    Contacto:
                    <input type="tel" name="telemovel" value="<?php echo $userData['telemovel'] ?>" required/>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_password']) ? 'invalid' : '' ?>">
                    Password:
                    <input type="password" name="pass" />
                </div>
                <div class="input-box <?php echo isset($_GET['inv_confirmacao_password']) ? 'invalid' : '' ?>">
                    Confirmar Password:
                    <input type="password" name="ConfPass" />
                </div>
                <button class="form-btn" type="submit">
                    Guardar alterações
                </button>
            </form>
        </div>
    </body>
</html>