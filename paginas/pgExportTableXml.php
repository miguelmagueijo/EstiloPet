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
    
    $fp = $xmlDoc->createElement("registos");
    $xmlDoc->appendChild($fp);

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
            $whereExtraArgs = "";

            if (auth_isWorker()) {
                $whereExtraArgs = "AND m.func = ". $_SESSION["userId"];
            } else if (auth_isClient()) {
                $whereExtraArgs = "AND u.idUser = ". $_SESSION["userId"];
            }

            $query = "
                SELECT m.idMarcacao, m.data, m.hora, m.tratamento, m.estado, a.nomeAnimal,
                       u.nomeUser, u.idUser
                FROM marcacoes m 
                    INNER JOIN animal a ON m.idAnimal = a.idAnimal
                    INNER JOIN user u ON m.idUser = u.idUser
                WHERE estado = 0 $whereExtraArgs
            ";

            $res = $conn->query($query);
            break;
        case "user":
            $query = "SELECT * FROM user";
            break;
        default:
            die("Invalid table");
    }

    $res = $conn->query($query);

    while($row = $res->fetch_assoc()) {
        $nivel1 = $xmlDoc->createElement($table);
        $fp->appendChild($nivel1);
        foreach($row as $key => $value){
            if($table == "user" && $key=="tipoUtilizador"){
                switch($value ){
                    case 0:
                        $nivel2=$xmlDoc->createElement($key,"Administrador");
                        $nivel1->appendChild($nivel2);
                        break;
                    case 1:
                        $nivel2=$xmlDoc->createElement($key,"Funcionário");
                        $nivel1->appendChild($nivel2);
                        break;
                    case 2:
                        $nivel2=$xmlDoc->createElement($key,"Cliente");
                        $nivel1->appendChild($nivel2);
                        break;
                    case 3:
                        $nivel2=$xmlDoc->createElement($key,"Cliente por validar");
                        $nivel1->appendChild($nivel2);
                        break;
                    }
                    
            }else{
                $nivel2=$xmlDoc->createElement($key,$value);
                $nivel1->appendChild($nivel2);
            }
            
        }
        
        echo "<br>";
    }

    $xmlDoc->save($table."_export.xml");

    header("Location:".$table."_export.xml");
?>
