<?php
    include_once("auth.php");

    if (!auth_isAdmin()) {
        header("Location: index.php");
        return;
    }

    $filename = "database_export_schema.xsd";

    /* @var $domDoc DOMDocument */
    function sqlColumnToXmlSchema(&$domDoc, $colName, $typeName, $isNull, $defaultValue = null) {
        $element = $domDoc->createElement("xs:element");
        $element->setAttribute("name", $colName);

        if ($isNull == "NO") {
            $element->setAttribute("use", "required");
        }

        if ($defaultValue !== null) {
            $element->setAttribute("default", $defaultValue);
        }

        if (substr_count($typeName, "varchar")) {
            $simpleType = $domDoc->createElement("xs:simpleType");
            $restriction = $domDoc->createElement("xs:restriction");
            $restriction->setAttribute("base", "xs:string");
            $maxLength = $domDoc->createElement("xs:maxLength");
            $maxLength->setAttribute("value", substr($typeName, 8, -1));

            $restriction->appendChild($maxLength);
            $simpleType->appendChild($restriction);
            $element->appendChild($simpleType);
        } else if (substr_count($typeName, "int")) {
            $element->setAttribute("type", "xs:integer");
        } else if (substr_count($typeName, "date")) {
            $element->setAttribute("type", "xs:date");
        } else if (substr_count($typeName, "time")) {
            $element->setAttribute("type", "xs:time");
        }

        return $element;
    }


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

    $xsdDoc = new DOMDocument("1.0", "UTF-8");
    $xsdDoc->formatOutput = true;
    $xsdRoot = $xsdDoc->createElement("xs:schema");
    $xsdRoot->setAttribute("xmlns:xs", "http://www.w3.org/2001/XMLSchema");

    $dbElement = $xsdDoc->createElement("xs:element");
    /* @var $dbname string */
    $dbElement->setAttribute("name", $dbname);

    $dbElementComplexType = $xsdDoc->createElement("xs:complexType");
    $dbElementSequence = $xsdDoc->createElement("xs:sequence");

    $exportDateElement = $xsdDoc->createElement("xs:element");
    $exportDateElement->setAttribute("name", "export_date");
    $exportDateElement->setAttribute("type", "xs:dateTime");

    //$exportDateElement->setAttribute("timezone", $currentDate->getTimezone()->getName());

    foreach ($tableNames as $tableName) {
        $resTableColumns = $conn->query("SHOW COLUMNS FROM $tableName");
        if(!$resTableColumns) {
            die("SQL error #2");
        }

        $tableOuterElement = $xsdDoc->createElement("xs:element");
        $tableOuterElement->setAttribute("name", "tabela_$tableName");
        $tableOuterElementComplexType = $xsdDoc->createElement("xs:complexType");
        $tableOuterElementSequence = $xsdDoc->createElement("xs:sequence");


        $tableElement = $xsdDoc->createElement("xs:element");
        $tableElement->setAttribute("name", $tableName);
        $tableElement->setAttribute("minOccurs", "0");
        $tableElement->setAttribute("maxOccurs", "unbounded");
        $tableElementComplexType = $xsdDoc->createElement("xs:complexType");
        $tableElementSequence = $xsdDoc->createElement("xs:sequence");

        while($cRow = $resTableColumns->fetch_assoc()) {
            $tableElementSequence->appendChild(sqlColumnToXmlSchema($xsdDoc, $cRow["Field"], $cRow["Type"], $cRow["Null"], $cRow["Default"]));
        }

        $tableElementComplexType->appendChild($tableElementSequence);
        $tableElement->appendChild($tableElementComplexType);
        $tableOuterElementSequence->appendChild($tableElement);
        $tableOuterElementComplexType->appendChild($tableOuterElementSequence);
        $tableOuterElement->appendChild($tableOuterElementComplexType);
        $dbElementSequence->appendChild($tableOuterElement);
    }

    $dbElementComplexType->appendChild($dbElementSequence);
    $dbElement->appendChild($dbElementComplexType);
    $xsdRoot->appendChild($dbElement);
    $xsdDoc->appendChild($xsdRoot);
    $xsdDoc->save($filename);
?>

<!DOCTYPE html>
<html lang="PT">
    <head>
        <meta charset="UTF-8">
        <title>Estilo Pet - Exportação da base dados para XML Schema</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="estilo.css" />

        <style>
            * {
                margin: 0;
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
            Exportação da base de dados para XML Schema
        </h1>

        <main class="main-container">
            <div style="display: flex; align-items: center; gap: 1rem; justify-content: space-between;">
                <h2>Código XSD</h2>
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

                echo "<div>Export created on ".$currentDate->format("Y-m-d H:i:s")."</div>";
            ?>
            <pre>
                <?php
                    echo htmlentities($xsdDoc->saveXML());
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