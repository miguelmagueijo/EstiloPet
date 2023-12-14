<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html> 
            <head>
                <link rel="stylesheet" type="text/css" href="style.css" />
                <link rel="stylesheet" type="text/css" href="estilo.css" />   
            </head>
            <body>
                <div class="panel">
                    <table>
                        <thread>
                            <tr>
                                <th>Nome do animal</th>  
                                <th>Tipo do animal</th>
                                <th>Porte</th>
                            </tr>
                        </thread>
                        <xsl:for-each select="registos/animal">
                            <tr>
                                <th style="text-align:left"><xsl:value-of select="nomeAnimal"/></th>
                                <th  style="text-align:left"><xsl:value-of select="tipoAnimal"/></th>
                                <th  style="text-align:left"><xsl:value-of select="porte"/></th>
                                <td><a href="PgEditarAnimal.php?idAnimal={./idAnimal}" target="_top">Editar</a></td>
                                <td><a href="apagarAnimal.php?idAnimal={./idAnimal}" target="_top">Eliminar</a></td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
