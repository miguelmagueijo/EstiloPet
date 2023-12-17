<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/info">
        <html> 
            <head>
                <link rel="stylesheet" type="text/css" href="style.css" />
                <link rel="stylesheet" type="text/css" href="estilo.css" />
                <style>
                    * { color: #422006; }
                    h1 {
                        text-align: center;
                    }

                    .slogan {
                        font-weight: 500;
                        font-size: 1.2rem;
                        text-align: center;
                    }

                    .desc {
                        text-align: justify;
                        margin-top: 2rem;
                    }

                    main {
                        width: 650px;
                        margin: 0rem auto 5rem auto;
                    }


                </style>
            </head>
            <body>
                <main>
                    <a class="info-voltar-btn" href="index.php">Voltar à página principal</a>
                    <h1><xsl:value-of select="capa/titulo"/></h1>
                    <div class="slogan"><xsl:value-of select="capa/descricao"/></div>
                    <p class="desc"><xsl:value-of select="capa/texto"/></p>
                    <div>
                        <h2>Horário de atendimento</h2>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th style="font-size: 1.1rem;">Dia da semana</th>
                                    <th style="font-size: 1rem;">Horário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <xsl:for-each select="horario_funcionamento/horario">
                                    <tr>
                                        <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/></td>
                                        <td style="text-align: center; font-size: 1rem;">
                                            <xsl:choose>
                                                <xsl:when test="hora_funcionamento[@isBold='true']">
                                                    <b>
                                                        <xsl:value-of select="hora_funcionamento"/>
                                                    </b>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <xsl:value-of select="hora_funcionamento"/>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h2>Tabela de Preços</h2>
                        <table width="100%">
                            <thead>
                                <tr bgcolor="#9acd32">
                                    <th colspan="2" style="text-align:left">Animal</th>
                                    <th style="text-align:left">Corte</th>
                                    <th style="text-align:left">Banho</th>
                                </tr>
                            </thead>
                            <tbody>
                                <xsl:for-each select="tabela_precos_servico/servico">
                                    <tr>
                                        <td style="text-align:left"> <xsl:value-of select="animal"/></td>
                                        <td style="text-align:left"> <xsl:value-of select="porte"/></td>
                                        <td style="text-align:left"> <xsl:value-of select="preco_corte"/></td>
                                        <td style="text-align:left"> <xsl:value-of select="preco_banho"/></td>
                                    </tr>
                                </xsl:for-each>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h2>Morada</h2>
                        <xsl:value-of select="morada/localizacao"/><br/>
                        <xsl:value-of select="morada/rua"/><br/>
                        <xsl:value-of select="morada/codigo_postal"/>
                    </div>
                    <div>
                        <h2>Contactos</h2>
                        <xsl:value-of select="contatos/telefone"/>
                        <br/>
                        <xsl:value-of select="contatos/email"/>
                    </div>
                    <a class="info-voltar-btn" href="info.xml" download="EstiloPet.xml">Quero o XML!</a>
                    <a class="info-voltar-btn" href="info.zip" download="EstiloPetInfo.zip">Quero o XML e XSLT!</a>
                </main>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
