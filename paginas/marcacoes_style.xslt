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
                                <th>Cliente</th>  
                                <th>Animal</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Tipo de Tratamento</th>
                            </tr>
                        </thread>
                        <xsl:for-each select="registos/marcacoes">
                            <tr>
                                <th style="text-align:left"><xsl:value-of select="nomeUser"/></th>
                                <th  style="text-align:left"><xsl:value-of select="nomeAnimal"/></th>
                                <th  style="text-align:left"><xsl:value-of select="data"/></th>
                                <th  style="text-align:left"><xsl:value-of select="hora"/></th> 
                                <th  style="text-align:left"><xsl:value-of select="tratamento"/></th>
                                <xsl:if test="estado=0">
                                    <th><a href="atenderMarcacao.php?idMarcacao={./idMarcacao}" target="_top">Atender</a></th>
                                </xsl:if>
                                <xsl:if test="tipoUtilizador=2">
                                    <td><a href="PgEditarMarcacao.php?idMarcacao={./idMarcacao}" target="_top">Editar</a></td>
                                    <td><a href="apagarMarcacao.php?idMarcacao={./idMarcacao}" target="_top">Eliminar</a></td>
                                </xsl:if>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
