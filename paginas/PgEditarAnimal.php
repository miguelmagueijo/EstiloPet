<?php
    include_once("auth.php");
    redirectToIfNotLogged();


    if (!isset($_GET["idAnimal"])) {
        header("Location: PgUtilizador.php");
        die();
    }

    $animalId = $_GET["idAnimal"];

    /* @var $conn mysqli */
    $stmt = $conn->prepare("SELECT nomeAnimal, porte, tipoAnimal, u.idUser as 'idUser', u.nomeUser as 'nomeUser'
                                  FROM animal a
                                      INNER JOIN user u ON a.idUser = u.idUser
                                  WHERE a.idAnimal = ?");
    $stmt->bind_param("i", $animalId);

    if (!$stmt->execute()) {
        header("Refresh: 5; url=PgUtilizador.php");
        die("Não foi possivel obter os dados do animal para esse utilizador...");
    }

    $res = $stmt->get_result();

    if (!$res || $res->num_rows == 0) {
        header("Location: PgUtilizador.php");
        die();
    }

    $animalData = $res->fetch_assoc();

    if ((!auth_isAdmin() && $_SESSION["userId"] != $animalData["idUser"]) || auth_isWorker()) {
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
                    Animal editado com sucesso
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
        <h2>Editar Animal</h2>
        <form action="editarAnimal.php" method="POST">
            <?php
                if (auth_isAdmin()) {
                    echo "
                        <div class='input-box'>
                            <label>
                                Nome do dono
                                <input type='text' value='". $animalData["nomeUser"] ."' disabled/>
                            </label>
                        </div>  
                    ";
                }
            ?>
            <div class="input-box <?php echo isset($_GET['inv_nome']) ? 'invalid' : '' ?>">
                <label>
                    Nome
                    <input type="text" name="nome-animal" value="<?php echo $animalData['nomeAnimal'] ?>" minlength="3" required/>
                </label>
            </div>
            <div class="input-box <?php echo isset($_GET['inv_tipo']) ? 'invalid' : '' ?>">
                <label>
                    Tipo de Animal
                </label>
                <div style="display: flex; align-items: center; gap: 2rem; margin-top: 0.5rem; font-size: 1.2rem">
                    <div>
                        Cão <input type="radio" name="tipo-animal" value="cao" <?php echo $animalData["tipoAnimal"] == "cao" ? "checked" : null ?>/>
                    </div>
                    <div>
                        Gato <input type="radio" name="tipo-animal" value="gato" <?php echo $animalData["tipoAnimal"] == "gato" ? "checked" : null ?>/>
                    </div>
                </div>
            </div>
            <div class="input-box <?php echo isset($_GET['inv_porte']) ? 'invalid' : '' ?>">
                <label>
                    Porte
                    <select id="porte-animal" name="porte-animal">';
                        <option value="grande" <?php echo $animalData["porte"] == "grande" ? "selected" : null ?>>Grande</option>
                        <option value="medio" <?php echo $animalData["porte"] == "medio" ? "selected" : null ?>>Médio</option>
                        <option value="pequeno" <?php echo $animalData["porte"] == "pequeno" ? "selected" : null ?>>Pequeno</option>';
                    </select>
                </label>
            </div>
            <input type="hidden" name="idAnimal" value="<?php echo $animalId ?>">
            <button class="form-btn" type="submit">
                Guardar alterações
            </button>
        </form>
    </div>
    <div style="margin-top: 2rem;">
        <table style="width: 650px;">
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