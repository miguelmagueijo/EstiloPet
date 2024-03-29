<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Estilo Pet</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
</head>

<body>
    <?php
        $CURR_PAGE_NAME = "index";
        include_once("navbar.php");
    ?>
    <div id="container">
        <div>
            <div class="banner">
                <img src="tobby.jpg">
                <img src="fiona.jpg">
                <img src="xicos.jpg">
                <img src="shrek.jpg">
                <img src="rex.jpg">
                <img src="ze.jpg">
            </div>
            <a class="info-voltar-btn" style="margin-bottom: 0" href="info.xml">Ver dados do website em XML!</a>
            <div id="text">
                <h3>Bem-vindo ao Estilo Pet </h3>
                <h4>O salão de beleza para cães e gatos que cuida da higiene e aparência <br>dos
                    seus pets com carinho e profissionalismo. </h4>

                <p> Nós somos especializados em serviços de lavagem e corte, e
                    oferecemos uma variedade de opções de penteados para deixar seu animal de estimação com um visual
                    incrível.</p>
                <p>
                    O nosso site foi criado para tornar a sua experiência de agendamento fácil e conveniente. Pode
                    selecionar os serviços que deseja para o seu pet, escolher um horário disponível e agendar sua
                    consulta em apenas alguns cliques. </p>
                <p>
                    Além disso, temos uma equipa de profissionais altamente treinados
                    que são apaixonados por animais e estão prontos para cuidar do seu pet com todo o amor e atenção que
                    ele merece.
                </p>
            </div>
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <a class="goto-marcacoes-btn" href="<?php echo auth_isLogged() ? 'PgUtilizador.php' : 'PgLogin.php' ?>">
                    Fazer uma marcação
                </a>
            </div>
            <div>
                <h2 style="text-align: center">Horário de atendimento</h2>
                <table style="width: 100%">
                    <thead>
                    <tr>
                        <th style="font-size: 1.1rem;">Dia da semana</th>
                        <th style="font-size: 1rem;">Horário</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Segunda-feira
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                9:00 - 18:00
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Terça-feira
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                9:00 - 18:00
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Quarta-feira
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                9:00 - 18:00
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Quinta-feira
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                9:00 - 18:00
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Sexta-feira
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                9:00 - 18:00
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Sábado
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                <b>Fechados</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 1rem;"><xsl:value-of select="dia_semana"/>
                                Domingo
                            </td>
                            <td style="text-align: center; font-size: 1rem;">
                                <b>Fechados</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <h1 style="text-align: center; font-size: 1.4rem;">Tabela de Preços</h1>
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="2">Animal</th>
                            <th>Corte</th>
                            <th>Banho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3">Cão</td>
                            <td>Grande</td>
                            <td>€90,00</td>
                            <td>€50,00</td>
                        </tr>
                        <tr>
                            <td>Médio</td>
                            <td>€70,00</td>
                            <td>€40,00</td>
                        </tr>
                        <tr>
                            <td>Pequeno</td>
                            <td>€50,00</td>
                            <td>€30,00</td>
                        </tr>
                        <tr>
                            <td rowspan="3">Gato</td>
                            <td>Grande</td>
                            <td>€80,00</td>
                            <td>€40,00</td>
                        </tr>
                        <tr>
                            <td>Médio</td>
                            <td>€60,00</td>
                            <td>€30,00</td>
                        </tr>
                        <tr>
                            <td>Pequeno</td>
                            <td>€40,00</td>
                            <td>€20,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin: 1.5rem 0 5rem 0;">
                <a class="goto-marcacoes-btn" href="contactos.php">Necessito de ajuda</a>
            </div>
        </div>
    </div>
    <?php include_once("footer.html") ?>
</body>

</html>