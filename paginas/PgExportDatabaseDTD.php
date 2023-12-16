<?php
    include_once("auth.php");

    if (!auth_isAdmin()) {
        header("Location: index.php");
        return;
    }

    $filename = "database_export_schema.dtd";

    $tableNames = array();
    /* @var $conn mysqli */
    if (isset($_POST["all_tables"]) || !isset($_POST["table_name"]) || !is_array($_POST["table_name"])) {
        $res = $conn->query("SHOW TABLES");

        if (!$res) {
            die("SQL Error #1");
        }

        while($row = $res->fetch_row()) {
            $tableNames[] = $row[0];
        }
    } else {
        $tableNames = $_POST["table_name"];
    }

    /* @var $dbname string */
    $dtdString = "<!DOCTYPE $dbname [\n";
    $dtdString .= "\t<!ELEMENT $dbname (";

    foreach ($tableNames as $tableName) {
        $dtdString .= "tabela_".$tableName.", ";
    }

    $dtdString = rtrim($dtdString, ", ");
    $dtdString .= ")>\n";

    foreach($tableNames as $tName) {
        $dtdString .= "\t\t<!ELEMENT tabela_$tName ($tName*)>\n";

        $resCols = $conn->query("SHOW COLUMNS FROM $tName");
        $columnNames = array();
        while($row = $resCols->fetch_row()) {
            $columnNames[] = $row[0];
        }

        $dtdString .= "\t\t\t<!ELEMENT $tName (".implode(", ", $columnNames).")>\n";

        foreach($columnNames as $cName) {
            $dtdString .= "\t\t\t\t<!ELEMENT $cName (#PCDATA)>\n";
        }
    }

    $dtdString .= "]>";

    $fp = fopen($filename, "w");
    fwrite($fp, $dtdString);
    fclose($fp);
?>

<!DOCTYPE html>
<html lang="PT">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet - Exportação da base dados para DTD</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />

        <style>
            * {
                box-sizing: border-box;
            }

            .main-container {
                width: 80%;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <?php include_once("navbar.php"); ?>
        <h1 style="margin: 2rem 0; text-align: center;">
            Exportação da base de dados para DTD
        </h1>
        <main class="main-container">
            <div style="display: flex; align-items: center; gap: 1rem; justify-content: space-between;">
                <h2>Código DTD</h2>
                <?php
                    if (file_exists($filename)) {
                        echo "<a class='download-btn' href=$filename download>Download</a>";
                    }
                ?>
            </div>
            <?php
                $currentDate = null;
                try {
                    $currentDate = new DateTime("now", new DateTimeZone("Europe/Lisbon"));
                } catch (Exception $e) {
                    die("Couldn't get current datetime");
                }

                echo "<div>Export generated on ".$currentDate->format("Y-m-d H:i:s")."</div>"
            ?>
            <pre>
                <?php
                    echo htmlentities($dtdString);
                ?>
            </pre>
            <script src="removeSpacePre.js"></script>
        </main>
        <div style="text-align: center;">
            <a class="go-back-btn" href="PgUtilizador.php">
                Voltar atrás
            </a>
        </div>
        <?php include_once("footer.html") ?>
    </body>
</html>

