<?php
    include_once("auth.php");
    /* @var $conn mysqli */

    if (!isset($_GET["tableName"])) {
        die("No table name");
    }

    $table = $_GET["tableName"];

    $xmlDoc = new DOMDocument("1.0", "UTF-8");

    $xmlDoc->formatOutput = true;

    $pi = $xmlDoc->createProcessingInstruction("xml-stylesheet", "type='text/xsl' href='".$table."_style.xslt'");
    $xmlDoc->appendChild($pi);

    $res = $conn->query("SELECT nomeUser, tipoUtilizador, CURRENT_DATE() as 'export-date', CURRENT_TIME() as 'export-time'
                               FROM user
                               WHERE idUser = " . $_SESSION["userId"]);

    $exportInfoElement = $xmlDoc->createElement("who-exported");
    foreach($res->fetch_assoc() as $column => $value) {
        $exportInfoElement->appendChild($xmlDoc->createElement($column, $value));
    }

    /* @var $dbname string */
    $rootElement = $xmlDoc->appendChild($xmlDoc->createElement($dbname));
    $rootElement->appendChild($exportInfoElement);

    switch ($table) {
        case "animal":
            if (auth_isAdmin()) {
                $query = "SELECT * FROM animal";
            } else if (auth_isWorker()) {
                die("O seu tipo de utilizador não permite ter ou consultar animais");
            } else {
                $query = "SELECT * FROM $table WHERE idUser = ". $_SESSION["userId"];
            }

            break;
        case "marcacoes":
            $query = "
                SELECT m.idMarcacao, m.data, m.hora, m.tratamento, m.estado, a.nomeAnimal,
                       u.nomeUser, u.idUser
                FROM marcacoes m 
                    INNER JOIN animal a ON m.idAnimal = a.idAnimal
                    INNER JOIN user u ON m.idUser = u.idUser
            ";

            if (auth_isWorker()) {
                $query .= " WHERE estado = 0 AND m.func = ". $_SESSION["userId"];
            } else if (auth_isClient()) {
                $query .= " WHERE estado = 0 AND u.idUser = ". $_SESSION["userId"];
            }

            $query .= " ORDER BY m.data, m.hora";

            $res = $conn->query($query);
            break;
        case "user":
            if (!auth_isAdmin()) {
                die("O seu tipo de utilizador não permite ter ou consultar os utilizadores");
            }

            $query = "SELECT * FROM user";
            break;
        default:
            die("Invalid table");
    }

    $res = $conn->query($query);
    $tableElement = $xmlDoc->createElement($table);
    $rootElement->appendChild($tableElement);

    while ($row = $res->fetch_assoc()) {
        $registoElement = $xmlDoc->createElement("registo");
        $tableElement->appendChild($registoElement);
        foreach ($row as $key => $value) {
            if ($table == "user" && $key=="tipoUtilizador") {
                $translatedElement = $xmlDoc->createElement("tipoUtilizadorTraduzido");
                switch($value){
                    case ADMIN:
                        $translatedElement->nodeValue = "Administrador";
                        break;
                    case FUNC:
                        $translatedElement->nodeValue = "Funcionário";
                        break;
                    case CLIENTE:
                        $translatedElement->nodeValue = "Cliente";
                        break;
                    case CLIENTE_POR_VALIDAR:
                        $translatedElement->nodeValue = "Cliente por validar";
                        break;
                    default:
                        $translatedElement->nodeValue = "Desconhecido";
                        break;
                }
                $registoElement->appendChild($translatedElement);
            }

            $columnElement = $xmlDoc->createElement($key, $value);
            $registoElement->appendChild($columnElement);
        }
    }

    $xmlDoc->save($table."_export.xml");

    header("Location:".$table."_export.xml");
?>
