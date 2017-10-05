
<?php
//echo $this->Html->script("map/high-maps");
echo $this->Html->script("chart/high-charts");
echo $this->Html->script("map/high-map");

//echo $this->Html->script("map/bd-all-div");
?>

<!--<script src="../js/high-maps.js"></script>

<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/bd/bd-all.js"></script>-->


<!--<div id="container"></div>--> 

<!--<input type="checkbox" id="chkDataLabels" checked='checked' />
<label for="chkDataLabels" style="display: inline">Data labels</label>
<div id="infoBox">
    <h4>This map</h4>
    <div id="download"></div>
</div>-->



<fieldset>
    <legend>Shape Map</legend>

    <!--    <div id="map_content" style="margin:5px auto; padding:5px; height:375px; overflow:auto;">
            <div class="loading">
                <i class="icon-spinner icon-spin icon-large"></i>
                Loading map data... . .
            </div>
        </div>-->

    <div id="container" style="margin:5px auto; padding:5px; height:375px; overflow:auto;">
        <div class="loading">
            <i class="icon-spinner icon-spin icon-large"></i>
            Loading map data... . .
        </div>
    </div>



    <div style="margin:5px auto; padding:5px; height:370px; overflow:auto;">

        <?php
        require_once('shapefile\src\shapefile.php');

        //capitals
        $shape_file = APP . "view\ShapeFileViewers\data\bc_hospitals.shp";

// Return streets as GeoJSON
        $geojson = array('type' => 'FeatureCollection', 'features' => array());


        try {

            $shape_file = APP . "\ShapeFiles\Dist\dist01.shp";
            $ShapeFile = new ShapeFile($shape_file);
            while ($shape_data = $ShapeFile->getRecord(SHAPEFILE::GEOMETRY_WKT)) {

                //debug($shape_data);
                if ($shape_data['dbf']['deleted'])
                    continue;
                
//                debug($shape_data['shp']);
//                debug(json_decode($shape_data['shp'], true));
                

                $feature = array(
                    'type' => 'Feature',
                    'geometry' => json_decode($shape_data['shp'], true),
                    'crs' => array(
                        'type' => 'EPSG',
                        'properties' => array('code' => '4326')
                    ),
                    'properties' => $shape_data['dbf']
                );

                array_push($geojson['features'], $feature);


//                try {
//                    echo "<pre>";
//                    print_r($shape_data['shp']);    // Geometry
//                    print_r($shape_data['dbf']);    // DBF Data
//                    echo "</pre>";
//                } catch (Exception $ex) {
//                    
//                }
            }
        } catch (ShapeFileException $e) {
            debug('Error ' . $e->getCode() . ': ' . $e->getMessage());
            //exit('Error ' . $e->getCode() . ': ' . $e->getMessage());
        }


        // Return  result
        header('Content-type: application/json', true);
        echo json_encode($geojson);
        ?>


        <?php
//        // Return streets as GeoJSON
//        $geojson = array('type' => 'FeatureCollection', 'features' => array());
//
//        // Add edges to GeoJSON array
//        while ($shape_data = pg_fetch_assoc($query)) {
//
//            $feature = array(
//                'type' => 'Feature',
//                'geometry' => json_decode($shape_data['geojson'], true),
//                'crs' => array(
//                    'type' => 'EPSG',
//                    'properties' => array('code' => '4326')
//                ),
//                'properties' => array(
//                    'id' => $shape_data['id'],
//                    'length' => $shape_data['length']
//                )
//            );
//
//            // Add feature array to feature collection array
//            array_push($geojson['features'], $feature);
//        }
//        
//        // Close database connection
//        pg_close($dbcon);
//        
//        // Return  result
//        header('Content-type: application/json', true);
//        echo json_encode($geojson);
        ?>


    </div>

</fieldset>

<script>

<?php //echo $script;           ?>

    $(function () {

        var data1 = [
            {
                "geo-code": "10",
                "value": 0
            },
            {
                "geo-code": "20",
                "value": 1
            },
            {
                "geo-code": "30",
                "value": 2
            },
            {
                "geo-code": "40",
                "value": 3
            },
            {
                "geo-code": "50",
                "value": 4
            },
            {
                "geo-code": "55",
                "value": 5
            },
            {
                "geo-code": "60",
                "value": 6
            }
        ];
        
    var data = [
            {
                "div_code": "10",
                "value": 0
            },
            {
                "div_code": "20",
                "value": 1
            },
            {
                "div_code": "30",
                "value": 2
            },
            {
                "div_code": "40",
                "value": 3
            },
            {
                "div_code": "50",
                "value": 4
            },
            {
                "div_code": "55",
                "value": 5
            },
            {
                "div_code": "60",
                "value": 6
            }
        ];

        // Initiate the chart
        $('#container').highcharts('Map', {

            title: {
                text: 'All Division of Bangladesh'
            },
//            subtitle: {
//                text: 'Source map: <a href="https://code.highcharts.com/mapdata/countries/bd/bd-all.js">Bangladesh</a>'
//            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    align: 'right',
                    verticalAlign: 'top'
                }
            },
            legend: {
                title: {
                    text: 'Individuals per km²',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.textColor) || '#1177aa'//,
                                //backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(200, 230, 250, 0.5)'
                    }
                },
                align: 'right',
                verticalAlign: 'bottom',
                floating: true,
                layout: 'vertical',
                valueDecimals: 0,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(200, 230, 250, 0.5)',
                symbolRadius: 0,
                symbolHeight: 14
            },
            colorAxis: {
                dataClasses: [{
                        to: 0.0
                    }, {
                        from: 0.0,
                        to: 0.5
                    }, {
                        from: 0.5,
                        to: 1.5
                    }, {
                        from: 1.5,
                        to: 2.5
                    }, {
                        from: 2.5,
                        to: 3.5
                    }, {
                        from: 3.5,
                        to: 4.5
                    }, {
                        from: 4.5,
                        to: 5.5
                    }, {
                        from: 5.5,
                        to: 6.5
                    }, {
                        from: 7.0
                    }]
            },
            series: [{
                    data: data1,
                    mapData: bd_all["dist"], //Bangladesh['div/shape'], //Bangladesh['admin_boundary/div'], //Highcharts.maps['bd/all-div'],
                    joinBy: 'div_code1',
                    name: 'Total Area',
                    animation: true,
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.div_name}'
                    },
                    tooltip: {
                        valueSuffix: '/km²'
                    }
                }]
        });
    });



//            ,
//            error: function () {
//                $('#container').html('<div class=\"loading\">' +
//                        '<i class=\"icon-frown icon-large\"></i> ' +
//                        'Error loading data from Google Spreadsheets' +
//                        '</div>');
//            }


//    $(function () {
//
//        Highcharts.data({
//
//            googleSpreadsheetKey: '0AoIaUO7wH1HwdFJHaFI4eUJDYlVna3k5TlpuXzZubHc',
//
//            // custom handler when the spreadsheet is parsed
//            parsed: function (columns) {
//
//                // Read the columns into the data array
//                var data = [];
//                $.each(columns[0], function (i, code) {
//                    data.push({
//                        code: code.toUpperCase(),
//                        value: parseFloat(columns[2][i]),
//                        name: columns[1][i]
//                    });
//                });
//
//
//                // Initiate the chart
//                $('#container').highcharts('Map', {
//                    chart: {
//                        borderWidth: 1
//                    },
//
//                    colors: ['rgba(19,64,117,0.05)', 'rgba(19,64,117,0.2)', 'rgba(19,64,117,0.4)',
//                        'rgba(19,64,117,0.5)', 'rgba(19,64,117,0.6)', 'rgba(19,64,117,0.8)', 'rgba(19,64,117,1)'],
//
//                    title: {
//                        text: 'Population density by country (/km²)'
//                    },
//
//                    mapNavigation: {
//                        enabled: true
//                    },
//
//                    legend: {
//                        title: {
//                            text: 'Individuals per km²',
//                            style: {
//                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
//                            }
//                        },
//                        align: 'left',
//                        verticalAlign: 'bottom',
//                        floating: true,
//                        layout: 'vertical',
//                        valueDecimals: 0,
//                        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255, 255, 255, 0.85)',
//                        symbolRadius: 0,
//                        symbolHeight: 14
//                    },
//
//                    colorAxis: {
//                        dataClasses: [{
//                                to: 3
//                            }, {
//                                from: 3,
//                                to: 10
//                            }, {
//                                from: 10,
//                                to: 30
//                            }, {
//                                from: 30,
//                                to: 100
//                            }, {
//                                from: 100,
//                                to: 300
//                            }, {
//                                from: 300,
//                                to: 1000
//                            }, {
//                                from: 1000
//                            }]
//                    },
//
//                    series: [{
//                            data: data,
//                            mapData: Highcharts.maps['custom/world'],
//                            joinBy: ['iso-a2', 'code'],
//                            animation: true,
//                            name: 'Population density',
//                            states: {
//                                hover: {
//                                    color: '#BADA55'
//                                }
//                            },
//                            tooltip: {
//                                valueSuffix: '/km²'
//                            }
//                        }]
//                });
//            },
//            error: function () {
//                $('#container').html('<div class="loading">' +
//                        '<i class="icon-frown icon-large"></i> ' +
//                        'Error loading data from Google Spreadsheets' +
//                        '</div>');
//            }
//        });
//    });


</script>
