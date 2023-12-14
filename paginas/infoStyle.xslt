<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html> 
            <head>
                <link rel="stylesheet" type="text/css" href="style.css" />
                <link rel="stylesheet" type="text/css" href="estilo.css" />   
            </head>
            <body>
                <form  action="PgUtilizador.php">
                    <input style="
                        margin: 5 auto;
                        display: block;
                        padding: 10px 20px;
                        background-color: #333;
                        color: #fff;
                        border: none;
                        border-radius: 3px;
                        cursor: pointer;" 
                        type="submit" value="Voltar"
                    />
                </form>
                <div id="text">
                    <h3><xsl:value-of select="info/capa/titulo"/></h3>
                    <h4><xsl:value-of select="info/capa/descricao"/></h4>
                    <p><xsl:value-of select="info/capa/texto"/></p>
                </div>
                <div id="table">
                    <h1>Tabela de Preços</h1>
                    <table border="1">
                        <tr bgcolor="#9acd32">
                            <th colspan="2" style="text-align:left">Animal</th>
                            <th style="text-align:left">Corte</th>
                            <th style="text-align:left">Banho</th>
                        </tr>
                        <xsl:for-each select="info/tabela_precos">
                            <tr>
                                <th style="text-align:left"> <xsl:value-of select="animal"/></th>

                                <th style="text-align:left"> <xsl:value-of select="porte"/></th>
                                <th style="text-align:left"> <xsl:value-of select="preco_corte"/></th>
                                <th style="text-align:left"> <xsl:value-of select="preco_banho"/></th>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
