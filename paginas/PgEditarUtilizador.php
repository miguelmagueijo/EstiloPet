<?php
    include_once("auth.php");
    redirectToIfNotAdmin();

    if (!isset($_GET["idUser"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $clientId = $_GET["idUser"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT * FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $clientId);

    if (!$stmt->execute()) {
        header("Refresh: 3; url=PgUtilizador.php");
        die("<h1>Erro na consulta da base de dados...</h1>");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Refresh: 3; url=PgUtilizador.php");
        die("<h2>Não foi encontrado nenhum utilizado com id: '".$_GET["idUser"]."'</h2>");
    }

    $clientData = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />
        <style>

        </style>
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
            } else if (count($_GET) > 1) {
                echo "
                    <div class='edit-content-warning error'>
                        Possui dados inválidos
                    </div>
                ";
            }
        ?>
        <div class="edit-content-container">
            <h2>Editar Dados do Utilizador</h2>
            <form action="editarDados.php" method="POST">
                <div class="input-box <?php echo isset($_GET['inv_nome']) ? 'invalid' : '' ?>">
                    <label>
                        Nome de utilizador
                        <input type="text" name="username" value="<?php echo $clientData['nomeUser'] ?>" minlength="3" required/>
                    </label>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_morada']) ? 'invalid' : '' ?>">
                    <label>
                        Morada
                        <input type="text" name="morada" value="<?php echo $clientData['morada'] ?>" minlength="3"  required/>
                    </label>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_email']) ? 'invalid' : '' ?>">
                    <label>
                        E-mail
                        <input type="email" name="email" value="<?php echo $clientData['email'] ?>" required/>
                    </label>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_telemovel']) ? 'invalid' : '' ?>">
                    <label>
                        Contacto
                        <input type="tel" name="telemovel" value="<?php echo $clientData['telemovel'] ?>" required/>
                    </label>
                </div>
                <div class="input-box <?php echo isset($_GET['inv_tipoUtilizador']) ? 'invalid' : '' ?>">
                    <label>
                        Tipo de Utilizador
                        <select id="cliente" name="tipoUtilizador" required>
                            <option value="" disabled>Selecione o tipo de utilizador</option>';
                            <option value="<?php echo ADMIN ?>" <?php echo $clientData['tipoUtilizador'] === ADMIN ? "selected" : null ?>>Administrador</option>
                            <option value="<?php echo FUNC ?>" <?php echo $clientData['tipoUtilizador'] === FUNC ? "selected" : null ?>>Funcionário</option>
                            <option value="<?php echo CLIENTE ?>" <?php echo $clientData['tipoUtilizador'] === CLIENTE ? "selected" : null ?>>Cliente</option>
                            <option value="<?php echo CLIENTE_POR_VALIDAR ?>" <?php echo $clientData['tipoUtilizador'] === CLIENTE_POR_VALIDAR ? "selected" : null ?>>Cliente por validar</option>
                        </select>
                    </label>
                </div>
                <input type="hidden" name="idUser" value="<?php echo $clientId ?>">
                <button class="form-btn" type="submit">
                    Guardar alterações
                </button>
            </form>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a class="go-back-btn" href="PgUtilizador.php">
                Voltar atrás
            </a>
        </div>
        <?php include_once("footer.html") ?>
    </body>
</html>