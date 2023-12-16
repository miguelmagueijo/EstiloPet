<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/lod_mm_ma">
        <html> 
            <head>
                <link rel="stylesheet" type="text/css" href="style.css" />
                <link rel="stylesheet" type="text/css" href="estilo.css" />   
            </head>
            <body class="no-bg">
                <div class="panel">
                    <table>
                        <thead>
                            <tr>
                                <th>Nome do animal</th>  
                                <th>Tipo do animal</th>
                                <th>Porte</th>
                            </tr>
                        </thead>
                        <xsl:for-each select="animal/registo">
                            <tr>
                                <td style="text-align:left"><xsl:value-of select="nomeAnimal"/></td>
                                <td  style="text-align:left"><xsl:value-of select="tipoAnimal"/></td>
                                <td  style="text-align:left"><xsl:value-of select="porte"/></td>
                                <xsl:if test="../../who-exported/tipoUtilizador = 0 or ../../who-exported/tipoUtilizador = 2">
                                    <td><a href="PgEditarAnimal.php?idAnimal={idAnimal}" target="_top">Editar</a></td>
                                    <td><a href="apagarAnimal.php?idAnimal={idAnimal}" target="_top">Eliminar</a></td>
                                </xsl:if>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
