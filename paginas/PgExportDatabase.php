<?php
    include_once("auth.php");

    if (!auth_isAdmin()) {
        header("Location: index.php");
        return;
    }
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

            pre {
                font-size: 16px;
                margin: 0 0 5rem 0;
                border: 2px solid black;
                border-radius: 5px;
                padding: 1rem 1rem;
            }

            .voltar-btn {
                display: inline-block;
                padding: 0.5rem 1rem;
                border: 2px solid black;
                border-radius: 5px;
                text-decoration: none;
                color: white;
                font-weight: bolder;
                font-family: Calibri, sans-serif;
                font-size: 1.2rem;
                margin-bottom: 1rem;
                background: cornflowerblue;
                transition-duration: 300ms;
            }

            .voltar-btn:hover {
                background: #3662bb;
            }
        </style>
    </head>

    <body>
        <?php include_once("navbar.php"); ?>
        <h1 style="margin: 2rem 0; text-align: center;">
            Exportação da base de dados para XML
        </h1>
        <main class="main-container">
            <div>
                <h2 style="margin-bottom: 1rem;">Código</h2>
                <a class="voltar-btn" href="PgUtilizador.php">Voltar</a>
            </div>
            <!-- TODO: use https://highlightjs.org/ -->
            <pre id="xml-code">
                <?php
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
                    echo htmlentities($xmlDoc->saveXML());
                ?>
            </pre>
            <script>
                // Remove leading spaces of <pre> first line
                const xmlCodeElement = document.getElementById("xml-code");
                xmlCodeElement.innerText = xmlCodeElement.innerText.trim();
            </script>
        </main>
    </body>
</html>

