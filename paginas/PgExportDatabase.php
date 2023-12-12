<?php
    include_once("auth.php");

    if (!auth_isAdmin()) {
        header("Location: index.php");
        return;
    }

    $filename = "database_export.xml";

    /* @var $conn mysqli */
    $res = $conn->query("SHOW TABLES");

    if (!$res) {
        die("SQL Error #1");
    }

    $currentDate = null;
    try {
        $currentDate = new DateTime("now", new DateTimeZone("Europe/Lisbon"));
    } catch (Exception $e) {
        die("Couldn't get current datetime");
    }

    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->formatOutput = true;
    $xmlRoot = $xmlDoc->createElement("lod_mm_ma"); // TODO: use variable from basededados.h

    $exportDateElement = $xmlDoc->createElement("export_date", $currentDate->format("Y-m-d H:i:s"));
    $exportDateElement->setAttribute("timezone", $currentDate->getTimezone()->getName());
    $xmlRoot->appendChild($exportDateElement);

    while($rowTables = $res->fetch_row()) {
        $tableName = $rowTables[0];
        $tableElement = $xmlDoc->createElement("tabela_$tableName");

        $resTableData = $conn->query("SELECT * FROM $tableName");

        // TODO: confirm if this code stays
        if ($resTableData->num_rows == 0) {
            $tableElement->nodeValue = "NO DATA";
            $xmlRoot->appendChild($tableElement);
            continue;
        }

        while($tRow = $resTableData->fetch_assoc()) {
            $tableRowElement = $xmlDoc->createElement($tableName);

            foreach($tRow as $colName => $colValue) {
                $tableRowElement->appendChild($xmlDoc->createElement($colName, $colValue));
            }

            $tableElement->appendChild($tableRowElement);
        }
        $xmlRoot->appendChild($tableElement);
    }

    $xmlDoc->appendChild($xmlRoot);
    $xmlDoc->save($filename);
?>

<!DOCTYPE html>
<html lang="PT">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet - Exportação da base dados para XML</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />

        <style>
            .main-container {
                width: 80%;
                margin: 0 auto;
            }
        </style>
    </head>

    <body>
        <?php include_once("navbar.php"); ?>
        <h1 style="margin: 2rem 0; text-align: center;">
            Exportação da base de dados para XML
        </h1>
        <main class="main-container">
            <div style="display: flex; align-items: center; gap: 1rem; justify-content: space-between;">
                <h2>Código XML</h2>
                <?php
                if (file_exists($filename)) {
                    echo "<a class='download-btn' href=$filename download>Download</a>";
                }
                ?>
            </div>
            <pre>
                <?php
                    echo htmlentities($xmlDoc->saveXML());
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

