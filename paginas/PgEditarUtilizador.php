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
        header("Refresh: 5; url=PgUtilizador.php");
        die("Não foi possivel obter os dados do utilizador...");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Location: PgUtilizador.php");
        die();
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
        ?>
        <div class="edit-content-container">
            <h2>Editar Dados do Utilizador</h2>
            <form action="editarDados.php" method="POST">
                <div class="input-box">
                    <label>
                        Nome de utilizador
                        <input type="text" name="username" value="<?php echo $clientData['nomeUser'] ?>" minlength="3" required/>
                    </label>
                </div>
                <div class="input-box">
                    <label>
                        Morada
                        <input type="text" name="morada" value="<?php echo $clientData['morada'] ?>" minlength="3"  required/>
                    </label>
                </div>
                <div class="input-box">
                    <label>
                        E-mail
                        <input type="email" name="email" value="<?php echo $clientData['email'] ?>" required/>
                    </label>
                </div>
                <div class="input-box">
                    <label>
                        Contacto
                        <input type="tel" name="telemovel" value="<?php echo $clientData['telemovel'] ?>" required/>
                    </label>
                </div>
                <div class="input-box">
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
        <div id="footer">
            <p id="esq">Realizado por Ana Correia & Clara Aidos</p>
        </div>
    </body>
</html>