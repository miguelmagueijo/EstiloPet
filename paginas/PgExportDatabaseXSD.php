<?php
    include_once("auth.php");

    if (!auth_isAdmin()) {
        header("Location: index.php");
        return;
    }

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
<h1 style="margin: 2rem 0; text-align: center;">
    Exportação da base de dados para XML Schema
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

        echo "<div>Export created on ".$currentDate->format("Y-m-d H:i:s")."</div>";
    ?>
    <!-- TODO: use https://highlightjs.org/ -->
    <pre id="xml-schema-code">
        <?php
            /* @var $conn mysqli */
            $res = $conn->query("SHOW TABLES");

            if (!$res) {
                die("SQL Error #1");
            }

            $xsdDoc = new DOMDocument("1.0", "UTF-8");
            $xsdDoc->formatOutput = true;
            $xsdRoot = $xsdDoc->createElement("xs:schema"); // TODO: use variable from basededados.h
            $xsdRoot->setAttribute("xmlns:xs", "http://www.w3.org/2001/XMLSchema");

            $dbElement = $xsdDoc->createElement("xs:element");
            $dbElement->setAttribute("name", "lod_mm_ma");

            $dbElementComplexType = $xsdDoc->createElement("xs:complexType");
            $dbElementSequence = $xsdDoc->createElement("xs:sequence");

            $exportDateElement = $xsdDoc->createElement("xs:element");
            $exportDateElement->setAttribute("name", "export_date");
            $exportDateElement->setAttribute("type", "xs:dateTime");

            //$exportDateElement->setAttribute("timezone", $currentDate->getTimezone()->getName());

            while($rowTables = $res->fetch_row()) {
                $tableName = $rowTables[0];

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
            echo htmlentities($xsdDoc->saveXML());
        ?>
    </pre>
    <script>
        // Remove leading spaces of <pre> first line
        const xmlCodeElement = document.getElementById("xml-schema-code");
        xmlCodeElement.innerText = xmlCodeElement.innerText.trim();
    </script>
</main>
</body>
</html>