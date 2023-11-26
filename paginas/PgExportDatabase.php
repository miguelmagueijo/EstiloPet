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
        <title>Estilo Pet</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />
        <link rel="stylesheet" type="text/css" href="estiloPgUtilizador.css" />

        <style>
            * {
                margin: 0;
            }

            .main-container {
                width: 80%;
                margin: 0 auto;
            }

            pre {
                margin: 0;
                border: 2px solid black;
                border-radius: 5px;
                padding: 1rem 1rem;
            }
        </style>
    </head>

    <body>
        <h1 style="margin: 2rem 0; text-align: center;">
            Exportação da base de dados para XML
        </h1>

        <main class="main-container">
            <h2 style="margin-bottom: 1rem;">Código</h2>
            <!-- TODO: use https://highlightjs.org/ -->
            <pre id="xml-code">
                <?php
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

                $xmlDoc = new DOMDocument();
                    $xmlDoc->formatOutput = true;
                    $xmlRoot = $xmlDoc->createElement("lod_mm_ma"); // TODO: use variable from basededados.h
                    $exportDateElement = $xmlDoc->createElement("export_date", $currentDate->format("Y-m-d H:i:s"));
                    $exportDateElement->setAttribute("timezone", $currentDate->getTimezone()->getName());
                    $xmlRoot->appendChild($exportDateElement);

                    $xmlDoc->appendChild($xmlRoot);
                    echo htmlentities($xmlDoc->saveXML());

                    $tableNames = array();
                    while ($row = $tableNames) {
                        array_push($tableNames, $tableNames[0]);
                    }
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

