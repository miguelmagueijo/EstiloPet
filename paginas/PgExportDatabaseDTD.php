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
        <title>Estilo Pet - Exportação da base dados para DTD</title>
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
            Exportação da base de dados para DTD
        </h1>
        <main class="main-container">
            <div>
                <h2 style="margin-bottom: 1rem;">Código</h2>
                <a class="voltar-btn" href="PgUtilizador.php">Voltar</a>
            </div>
            <?php
                $currentDate = null;
                try {
                    $currentDate = new DateTime("now", new DateTimeZone("Europe/Lisbon"));
                } catch (Exception $e) {
                    die("Couldn't get current datetime");
                }

                echo "<div>Export created on ".$currentDate->format("Y-m-d H:i:s")."</div>"
            ?>
            <!-- TODO: use https://highlightjs.org/ -->
            <pre id="dtd-code">
                <?php
                    /* @var $conn mysqli */
                    $res = $conn->query("SHOW TABLES");

                    if (!$res) {
                        die("SQL Error #1");
                    }

                    $dtdString = "<!DOCTYPE lod_mm_ma [\n";
                    $dtdString .= "\t<!ELEMENT lod_mm_ma (";

                    $tableNames = array();
                    while($row = $res->fetch_row()) {
                        $tableNames[] = $row[0];
                        $dtdString .= "tabela_".$row[0].", ";
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
                    echo htmlentities($dtdString);
                ?>
            </pre>
            <script>
                // Remove leading spaces of <pre> first line
                const xmlCodeElement = document.getElementById("dtd-code");
                xmlCodeElement.innerText = xmlCodeElement.innerText.trim();
            </script>
        </main>
        <?php include_once("footer.html") ?>
    </body>
</html>

