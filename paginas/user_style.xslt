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
                                <th>Nome</th>  
                                <th>E-Mail</th>
                                <th>Contacto</th>
                                <th>Morada</th>
                                <th>Tipo de Ultizador</th>
                                <th>Editar</th>
                                <th>Apagar</th>
                            </tr>
                        </thread>
                        <xsl:for-each select="registos/user">
                            <tr>
                                <th style="text-align:left"><xsl:value-of select="nomeUser"/></th>
                                <th  style="text-align:left"><xsl:value-of select="email"/></th>
                                <th  style="text-align:left"><xsl:value-of select="telemovel"/></th>
                                <th  style="text-align:left"><xsl:value-of select="morada"/></th> 
                                <th  style="text-align:left"><xsl:value-of select="tipoUtilizador"/></th>
                                <th>
                                    <a href="PgEditarUtilizador.php?idUser={./idUser}" target="_top">Editar</a>
                                </th>
                                <th>
                                    <a href="apagarUtilizador.php?idUser={./idUser}" target="_top">Apagar</a>
                                </th>
                                <xsl:if test="tipoUtilizador='Cliente por validar'">
                                    <th><a href="validarUtilizador.php?idUser={./idUser}" target="_top">Validar</a></th>
                                </xsl:if>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
