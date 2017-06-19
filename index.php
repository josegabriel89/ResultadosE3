<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Resultados E3</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <style>
            #cuerpo{
                padding: 0;
                background-color: #FFC74C
            }
            #imglogoe3{
                width: 20%;
                margin-top: 7%;
                position: absolute;
                z-index: 20;margin-left: 39%;}
            #fondorojo{
                background-color: #ED4842;
                height: 37%;
                position: absolute;
                width: 100%;
                z-index: 1;
                border-radius: 0px 0px 50% 50%;
                -moz-border-radius: 0px 0px 50% 50%;
                -webkit-border-radius: 0px 0px 50% 50%;
                border: 100px solid #ED4842
            }
            @media (max-width: 500px) {
                #fondorojo{
                    height: 16%;
                    border: 20px solid #ED4842
                } 
            }
            @media (min-width: 500px) and (max-width: 768px) {
                #fondorojo{
                    height: 20%;
                    border: 50px solid #ED4842
                } 
            }
            @media (min-width: 768px) {
                #fondorojo{
                    height: 37%;
                    border: 100px solid #ED4842
                } 
            }


            .estadisticas{
                background-color: white;
                margin-bottom: 20px;
            }
            #resultadosgenerales{
                margin-top: 30%;
                margin-bottom: 100px;
            }
            .Empresa{
                text-align:center;
                /*                background-color: #f0f0f0;*/
                padding-bottom: 20px;
                padding-top: 20px;
            }
            .textonotas{
                background-color: #E94842;
                padding-top: 8px;
                padding-bottom: 8px;
                color: #FFC34C;
                border-radius: 8px;
                margin-top: 5px;
            }
            .notamedia{
                margin-top: 20px;
                margin-bottom: 20px;
                font-size: medium;
                font-weight: bold;
            }
            .Empresa:last-child{
                background-color: #FFAC1A;                
            }      
            #textoexplicativo{
                color: #ED4842;
            }
            #textocreadores{
                padding-right: 5px;
                background-color: #FFAC1A;
                margin: 0px;
            }
            .titulotablas{
                background-color: #FFAC1A;
                color: #ED4842;
                font-weight: bolder;
                font-family: Calibri;
                font-size: 24px;
                text-align: center;
                max-height: 37px;
            }
            .graficas{
                margin: 0px -15px 20px -15px;
                padding: 5px 5px 5px 30px;
            }
            .tablafinal{
                margin: 0px 0px 0px 0px;
                padding: 5px 5px 5px 30px;
            }
        </style>
        <link rel="stylesheet" href="estadisticas.css">
    </head>
    <body>
        <div class="container-fluid" id="cuerpo">
            <?php
            $empresas = array("EA", "Microsoft", "Bethesda", "PC Gaming", "Ubisoft", "Sony", "Nintendo");
            $urls = array("EA" => "http://www.strawpoll.me/13156578/r",
                "Microsoft" => "http://www.strawpoll.me/13165714/r",
                "Bethesda" => "http://www.strawpoll.me/13167346/r",
                "PC Gaming" => "http://www.strawpoll.me/13171666/r",
                "Ubisoft" => "http://www.strawpoll.me/13173653/r",
                "Sony" => "http://www.strawpoll.me/13175514/r",
                "Nintendo" => "http://www.strawpoll.me/13179354/r");

            $arraydatos = array();

            function getDomUrl($url) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_REFERER, "");
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_ENCODING, "ISO-8859-1,UTF-8;q=0.7,*;q=0.7");
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 
               (Macintosh; Intel Mac OS X 10_6_6) AppleWebKit/535.19 
               (KHTML, like Gecko) Chrome/18.0.1025.151 Safari/535.19");

                $body = (curl_exec($ch));
                curl_close($ch);
                return $body;
            }

            function getDomXPath($domh, $path) {
                $xpath = new DOMXPath($domh);
                return $xpath->query($path);
            }

            function datosConferencia($urlstrawpoll) {
                $arrayresultado = array();
                $dom = new DOMDocument();
                $domhtml = getDomUrl($urlstrawpoll);
                @$dom->loadHTML($domhtml);
                $resultados = getDomXPath($dom, "//div[@class='results']/div/p/span[@class='option-count']");
                foreach ($resultados as $entry) {
                    $arrayresultado[$entry->previousSibling->previousSibling->nodeValue] = $entry->nodeValue;
                }
                return $arrayresultado;
            }

            function notaMedia($empresa) {
                global $arraydatos;
                $notamedia = 0;
                $votostotales = 0;
                for ($index = 1; $index <= count($arraydatos[$empresa]); $index++) {
                    $notamedia += ($index * $arraydatos[$empresa][(string) $index]);
                    $votostotales += $arraydatos[$empresa][(string) $index];
                }
                if ($votostotales != 0) {
                    $notamedia /= $votostotales;
                };
                return $notamedia;
            }

            foreach ($empresas as $nombre) {
                $arraydatos[$nombre] = datosConferencia($urls[$nombre]);
            }
            $notamediaea = notaMedia("EA");
            $notamediamicrosoft = notaMedia("Microsoft");
            $notamediabethesda = notaMedia("Bethesda");
            $notamediapcgaming = notaMedia("PC Gaming");
            $notamediaubisoft = notaMedia("Ubisoft");
            $notamediasony = notaMedia("Sony");
            $notamedianintendo = notaMedia("Nintendo");
            ?>
            <div id="fondorojo"></div>
            <img id="imglogoe3" src="img/iconoE3.png" class=" center-block img-responsive">
            <div id="resultadosgenerales" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="Empresa col-xs-4 col-sm-3 col-md-1 col-md-offset-2">
                    <img id="imgempresaea" class="imgempresa img-rounded img-responsive" alt="ea" src="img/logoea.png" onclick="activarEmpresa(1)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia lead" id="notamediaea"><?php echo number_format($notamediaea, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1">
                    <img id="imgempresamicro" class="imgempresa img-rounded img-responsive" alt="microsoft" src="img/logomicrosoft.png" onclick="activarEmpresa(2)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamediamicro"><?php echo number_format($notamediamicrosoft, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1">
                    <img id="imgempresabethesda" class="imgempresa img-rounded img-responsive" alt="bethesda" src="img/logobethesda.png" onclick="activarEmpresa(3)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamediabethesda"><?php echo number_format($notamediabethesda, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1">
                    <img id="imgempresapcgaming" class="imgempresa img-rounded img-responsive" alt="pc" src="img/logopcgaming.png" onclick="activarEmpresa(4)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamediapcgaming"><?php echo number_format($notamediapcgaming, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1">
                    <img id="imgempresaubi" class="imgempresa img-rounded img-responsive"  alt="ubisoft" src="img/logoubisoft.png" onclick="activarEmpresa(5)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamediaubi"><?php echo number_format($notamediaubisoft, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1">
                    <img id="imgempresasony" class="imgempresa img-rounded img-responsive"  alt="sony" src="img/logosony.png" onclick="activarEmpresa(6)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamediasony"><?php echo number_format($notamediasony, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1">
                    <img id="imgempresanintendo" class="imgempresa img-rounded img-responsive" alt="nintendo" src="img/logonintendo.png" onclick="activarEmpresa(7)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamedianintendo"><?php echo number_format($notamedianintendo, 2) ?></span></div>
                </div>
                <div class="Empresa col-xs-4 col-sm-3 col-md-1" id="3eresumen">
                    <img id="imgempresae3" class="imgempresa img-rounded img-responsive" alt="E3" src="img/logoe3.png" onclick="activarEmpresa(0)"></img>
                    <div class="textonotas container-fluid"><span class="notamedia" id="notamediae3"><?php echo number_format((($notamediaea + $notamediamicrosoft + $notamediabethesda + $notamediapcgaming + $notamediaubisoft + $notamediasony + $notamedianintendo) / 7), 2) ?></span></div>
                </div>
            </div>
            <div id="container" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <div class="estadisticas col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h4 class="titulotablas graficas">Progresión de nota</h4>
                    <div id="line-chart"></div>
                </div>
                <div class="estadisticas col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h4 class="titulotablas graficas">Comparación de medias</h4>
                    <div id="pie-chart"></div>
                </div>
                <div class="estadisticas col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 class="titulotablas graficas">Cantidad de votos</h4>
                    <div id="bar-chart"></div>
                </div>
            </div>
            <div id="yablaresultados" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h4 class="titulotablas tablafinal">Tabla de votos</h4>
                <div class="table-responsive">
                    <table class="estadisticas table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>
                                <th>Votos Totales</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php
                            //$colores = array("#D42613", "#DE4514", "#C75E1C", "#DE8014", "#D49013", "#D4A51D", "#DEC01E", "#C7BE25", "#A8DBA8", "#CFF09E");
                            for ($i = 0; $i < count($arraydatos); $i++) {
                                $vtotales = 0;
                                echo "<tr><th>" . $empresas[$i] . "</th>";
                                for ($u = 1; $u <= count($arraydatos[$empresas[$i]]); $u++) {
                                    echo "<td>" . $arraydatos[$empresas[$i]][(string) $u] . "</td>";
                                    $vtotales += $arraydatos[$empresas[$i]][(string) $u];
                                }
                                echo "<td bgcolor='#CCC'>" . $vtotales . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div id="container" class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-lg-offset-3">
                <h3 class="text-center" id="textoexplicativo">Esta pagina recopila los votos de los strawpoll del E3 2017 creados por AlexElCapo <a href="https://twitter.com/EvilAFM">@EvilAFM</a> despues de sus directos</h3>
            </div>

        </div>
        <p class="text-right" id="textocreadores">Creado por: Programador <a href="https://twitter.com/JoseGHell">@JoseGHell</a> y Diseñador <a href="https://twitter.com/Elthorcazo">@Elthorcazo</a></p>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
                        google.load("visualization", "1", {packages: ["corechart"]});
                        google.setOnLoadCallback(redibujartablas);
                        var empresasactivas = [true, true, true, true, true, true, true, true];
                        var notasmostradas = new Array();
                        var notas = [
<?php
echo "                    ['Nota'";
foreach ($empresas as $nombre) {
    echo ", '" . $nombre . "'";
}
echo "]";
for ($i = 1; $i <= 10; $i++) {
    echo ",
                    ['" . $i . "'";
    for ($u = 0; $u < count($arraydatos); $u++) {
        echo ", " . $arraydatos[$empresas[$u]][(string) $i];
    }
    echo "]";
}
?>
                        ];
                        var coloresactualizados = new Array();
                        var colores = ["#FFC400",
                            "#007D00",
                            "#FF7BAA",
                            "#222222",
                            "#58D3F7",
                            "#064695",
                            "#FF000A"];
                        var mediassactualizadas = new Array();
                        var medias = [['Empresa', 'Nota Media'],
                            ['EA', <?php echo number_format($notamediaea, 2) ?>],
                            ['Microsoft', <?php echo number_format($notamediamicrosoft, 2) ?>],
                            ['Bethesda', <?php echo number_format($notamediabethesda, 2) ?>],
                            ['PC Gaming', <?php echo number_format($notamediapcgaming, 2) ?>],
                            ['Ubisoft', <?php echo number_format($notamediaubisoft, 2) ?>],
                            ['Sony', <?php echo number_format($notamediasony, 2) ?>],
                            ['Nintendo', <?php echo number_format($notamedianintendo, 2) ?>]
                        ];

                        /**
                         * Array que actualiza las array de datos, medias y colores para recargar las tablas.
                         */
                        function prepararDatos() {
                            notasmostradas = [];
                            coloresactualizados = [];
                            mediassactualizadas = [];
                            for (i = 0; i < notas.length; i++) {
                                var arraytransito = new Array();
                                for (u = 0; u < notas[i].length; u++) {
                                    if (empresasactivas[u]) {
                                        arraytransito.push(notas[i][u]);
                                    }
                                }
                                notasmostradas.push(arraytransito);
                                if (empresasactivas[i] && i != 0) {
                                    coloresactualizados.push(colores[i - 1]);
                                }
                                if (empresasactivas[i]) {
                                    mediassactualizadas.push(medias[i]);
                                }
                            }
                        }

                        /**
                         * Funcion para activar o desactivar una empresa de las tablas tras pulsar sobre cada logo.
                         * El logo del E3 reactiva todas. Si no queda ninguna activa se reactivan todas.
                         * @param {int} emp numero correspondiente de cada empresa 
                         * @returns {undefined}
                         */
                        function activarEmpresa(emp) {
                            if (emp == 0) {
                                empresasactivas = [true, true, true, true, true, true, true, true];
                            } else {
                                if (empresasactivas[emp]) {
                                    empresasactivas[emp] = false;
                                    if (estanTodasDesactivadas()) {
                                        empresasactivas = [true, true, true, true, true, true, true, true];
                                    }
                                } else {
                                    empresasactivas[emp] = true;
                                }
                            }
                            prepararDatos();
                            redibujartablas();
                        }
                        /**
                         * Se comprueba si estan todas las empresas activadas y se devuelve un bolean segun corresponda.
                         * @returns {Boolean} variable bandera para saber si estan todas las empresas desactivadas
                         */
                        function estanTodasDesactivadas() {
                            var comprobacion = true;
                            for (i = 1; i < empresasactivas.length; i++) {
                                if (empresasactivas[i]) {
                                    comprobacion = false;
                                }
                            }
                            return comprobacion;
                        }

                        /**
                         * Funcion que pinta todas las tablas.
                         */
                        function DibujarCharts() {
                            var barOptions = {
                                focusTarget: 'category',
                                backgroundColor: 'transparent',
                                colors: coloresactualizados,
                                fontName: 'Open Sans',
                                chartArea: {
                                    left: 50,
                                    top: 10,
                                    width: '100%',
                                    height: '70%'
                                },
                                bar: {
                                    groupWidth: '80%'
                                },
                                hAxis: {
                                    title: 'Puntuación',
                                    textStyle: {
                                        fontSize: 10
                                    }
                                },
                                vAxis: {
                                    title: 'Nº de Votos',
                                    minValue: 0,
                                    maxValue: 12000,
                                    baselineColor: '#DDD',
                                    gridlines: {
                                        color: '#DDD',
                                        count: 8
                                    },
                                    textStyle: {
                                        fontSize: 9
                                    }
                                },
                                legend: {
                                    position: 'bottom',
                                    textStyle: {
                                        fontSize: 12
                                    }
                                },
                                animation: {
                                    duration: 1200,
                                    easing: 'out',
                                    startup: true
                                }
                            };
                            var barData = google.visualization.arrayToDataTable(notasmostradas);
                            var barChart = new google.visualization.ColumnChart(document.getElementById('bar-chart'));
                            barChart.draw(barData, barOptions);
                            var lineOptions = {
                                backgroundColor: 'transparent',
                                curveType: 'function',
                                colors: coloresactualizados,
                                fontName: 'Open Sans',
                                focusTarget: 'category',
                                chartArea: {
                                    left: 50,
                                    top: 10,
                                    width: '100%',
                                    height: '70%'
                                },
                                hAxis: {
                                    title: 'Puntuación',
                                    textStyle: {
                                        fontSize: 10
                                    },
                                    baselineColor: 'transparent',
                                    gridlines: {
                                        color: 'transparent'
                                    }
                                },
                                vAxis: {
                                    title: 'Nº de votos',
                                    minValue: 0,
                                    maxValue: 1200,
                                    baselineColor: '#DDD',
                                    gridlines: {
                                        color: '#DDD',
                                        count: 12
                                    },
                                    textStyle: {
                                        fontSize: 10
                                    }
                                },
                                legend: {
                                    position: 'bottom',
                                    textStyle: {
                                        fontSize: 12
                                    }
                                },
                                animation: {
                                    duration: 1200,
                                    easing: 'out',
                                    startup: true
                                }
                            };
                            var data = google.visualization.arrayToDataTable(notasmostradas);
                            var chart = new google.visualization.LineChart(document.getElementById('line-chart'));
                            chart.draw(data, lineOptions);
                            var pieOptions = {
                                backgroundColor: 'transparent',
                                pieHole: 0.4,
                                colors: coloresactualizados,
                                pieSliceText: 'value',
                                tooltip: {
                                    text: 'percentage'
                                },
                                fontName: 'Open Sans',
                                chartArea: {
                                    width: '100%',
                                    height: '94%'
                                },
                                legend: {
                                    textStyle: {
                                        fontSize: 13
                                    }
                                }
                            };
                            var pieData = google.visualization.arrayToDataTable(mediassactualizadas);
                            var pieChart = new google.visualization.PieChart(document.getElementById('pie-chart'));
                            pieChart.draw(pieData, pieOptions);
                        }
                        prepararDatos();
                        window.onresize = redibujartablas;
                        
                        /**
                         * Funcion que lanza la funcion de redibujar las tablas.
                         * Fue necesaria para utilizarla en el onresize sin que provocara fallos
                         */
                        function redibujartablas() {
                            DibujarCharts();
                        }
        </script>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
