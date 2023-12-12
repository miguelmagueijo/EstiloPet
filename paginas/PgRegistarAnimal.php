<?php
    include_once("auth.php");
    redirectToIfNotLogged();

    if (!isset($_GET["idCliente"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $clientId = $_GET["idCliente"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT nomeUser, tipoUtilizador FROM user WHERE idUser = ?");
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

    if ($clientData["tipoUtilizador"] != CLIENTE) {
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
    <?php
    include_once("navbar.php");

    if (isset($_GET["db_error"])) {
        echo "
                <div class='form-warning error'>
                    ". $_GET["msg"] ."
                </div>
            ";
    } else if (isset($_GET["success"])) {
        echo "
                <div class='form-warning success'>
                    Animal criado com sucesso
                </div>
            ";
    } else if (count($_GET) > 1) {
        echo "
                <div class='form-warning error'>
                    Dados de registo inválidos
                </div>
            ";
    }
    ?>
    <div class="edit-content-container">
        <h2>Registar Animal</h2>
        <form action="registarAnimal.php" method="POST">
            <div class="input-box">
                <label>
                    Nome do cliente
                    <input type="text" name="useless" value="<?php echo $clientData['nomeUser'] ?>" disabled readonly>
                </label>
            </div>
            <div class="input-box <?php echo isset($_GET['inv_nome']) ? 'invalid' : '' ?>">
                <label>
                    Nome do Animal
                    <input type="text" name="nome-animal" minlength="3" required>
                </label>
            </div>
            <div class="input-box <?php echo isset($_GET['inv_tipo']) ? 'invalid' : '' ?>">
                <label>
                    Tipo de Animal
                </label>
                <div style="display: flex; align-items: center; gap: 2rem; margin-top: 0.5rem; font-size: 1.2rem">
                    <div>
                        Cão <input type="radio" name="tipo-animal" value="cao"/>
                    </div>
                    <div>
                        Gato <input type="radio" name="tipo-animal" value="gato"/>
                    </div>
                </div>
            </div>
            <div class="input-box <?php echo isset($_GET['inv_porte']) ? 'invalid' : '' ?>">
                <label>
                    Porte
                    <select name="porte-animal">
                        <option value="grande">Grande</option>
                        <option value="medio">Médio</option>
                        <option value="pequeno">Pequeno</option>
                    </select>
                </label>
            </div>
            <input type="hidden" name="idCliente" value="<?php echo $clientId ?>">
            <button class="form-btn" type="submit">
                Registar animal
            </button>
        </form>
    </div>
    <div style="margin-top: 2rem" id="table-pesos">
        <table>
            <thead style="background-color: #fdba74">
            <tr>
                <th>Porte</th>
                <th>Peso do Cão</th>
                <th>Peso do Gato</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Pequeno</td>
                <td>Até 10kg</td>
                <td>Até 4kg</td>
            </tr>
            <tr>
                <td>Médio</td>
                <td>10kg - 25kg</td>
                <td>4kg - 6kg</td>
            </tr>
            <tr>
                <td>Grande</td>
                <td>Acima de 25kg</td>
                <td>Acima de 6kg</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a class="go-back-btn" href="PgUtilizador.php">
            Voltar atrás
        </a>
    </div>
    <?php include_once("footer.html") ?>
</body>

</html>