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
                                <th>Cliente</th>  
                                <th>Animal</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Tipo de Tratamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="marcacoes/registo">
                                <tr>
                                    <td style="text-align:left"><xsl:value-of select="nomeUser"/></td>
                                    <td style="text-align:left"><xsl:value-of select="nomeAnimal"/></td>
                                    <td style="text-align:left"><xsl:value-of select="data"/></td>
                                    <td style="text-align:left"><xsl:value-of select="hora"/></td>
                                    <td style="text-align:left"><xsl:value-of select="tratamento"/></td>
                                    <td><a href="PgEditarMarcacao.php?idMarcacao={./idMarcacao}" target="_top">Editar</a></td>
                                    <td><a href="apagarMarcacao.php?idMarcacao={./idMarcacao}" target="_top">Eliminar</a></td>
                                    <xsl:if test="estado=0 and ../../who-exported/tipoUtilizador=1">
                                        <td><a href="atenderMarcacao.php?idMarcacao={./idMarcacao}" target="_top">Atender</a></td>
                                    </xsl:if>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>