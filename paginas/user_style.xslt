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
                                <th>Nome</th>  
                                <th>E-Mail</th>
                                <th>Contacto</th>
                                <th>Morada</th>
                                <th>Tipo de Ultizador</th>
                                <xsl:if test="who-exported/tipoUtilizador=0">
                                    <th>Editar</th>
                                    <th>Apagar</th>
                                    <th>Validar</th>
                                </xsl:if>
                            </tr>
                        </thead>
                        <xsl:for-each select="user/registo">
                            <tr>
                                <td style="text-align:left"><xsl:value-of select="nomeUser"/></td>
                                <td style="text-align:left"><xsl:value-of select="email"/></td>
                                <td style="text-align:left"><xsl:value-of select="telemovel"/></td>
                                <td style="text-align:left"><xsl:value-of select="morada"/></td>
                                <td style="text-align:left"><xsl:value-of select="tipoUtilizadorTraduzido"/></td>
                                <xsl:if test="../../who-exported/tipoUtilizador=0">
                                    <td>
                                        <a href="PgEditarUtilizador.php?idUser={idUser}" target="_top">Editar</a>
                                    </td>
                                    <td>
                                        <a href="apagarUtilizador.php?idUser={idUser}" target="_top">Apagar</a>
                                    </td>
                                    <xsl:choose>
                                        <xsl:when test="tipoUtilizador=3">
                                            <td><a href="validarUtilizador.php?idUser={idUser}" target="_top">Validar</a></td>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <td>Já validado</td>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:if>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
