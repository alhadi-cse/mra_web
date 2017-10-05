
<?php
echo $this->Html->script("map/high-maps");
echo $this->Html->script("map/bd-all-div");
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

    <div id="map">
    </div>

    <div id="container">
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

        try {
            //$shape_file = APP . "\ShapeFiles\Dist\dist01.shp";

            $ShapeFile = new ShapeFile($shape_file);
            while ($record = $ShapeFile->getRecord(SHAPEFILE::GEOMETRY_WKT)) {
                //debug($record);
                if ($record['dbf']['deleted'])
                    continue;

                try {
                    echo "<pre>";
                    print_r($record['shp']);    // Geometry
                    print_r($record['dbf']);    // DBF Data
                    echo "</pre>";
                } catch (Exception $ex) {
                    
                }
            }
        } catch (ShapeFileException $e) {
            debug('Error ' . $e->getCode() . ': ' . $e->getMessage());
            //exit('Error ' . $e->getCode() . ': ' . $e->getMessage());
        }
        ?>

    </div>

</fieldset>

<script>

<?php //echo $script; ?>

    $(function () {

        // Prepare demo data
        var data = [
            {
                "div-code": "bd-da",
                "value": 0
            },
            {
                "div-code": "bd-kh",
                "value": 1
            },
            {
                "div-code": "bd-ba",
                "value": 2
            },
            {
                "div-code": "bd-cg",
                "value": 3
            },
            {
                "div-code": "bd-sy",
                "value": 4
            },
            {
                "div-code": "bd-rj",
                "value": 5
            },
            {
                "div-code": "bd-rp",
                "value": 6
            }
        ];

        // Initiate the chart
        $('#container').highmaps('Map', {

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
                        color: (Highmaps.theme && Highmaps.theme.textColor) || '#17a'//,
                                //backgroundColor: (Highmaps.theme && Highmaps.theme.legendBackgroundColor) || 'rgba(200, 230, 250, 0.5)'
                    }
                },
                align: 'right',
                verticalAlign: 'bottom',
                floating: true,
                layout: 'vertical',
                valueDecimals: 0,
                backgroundColor: (Highmaps.theme && Highmaps.theme.legendBackgroundColor) || 'rgba(200, 230, 250, 0.5)',
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
                    data: data,
                    mapData: Highmaps.maps['bd/all-div'],
                    joinBy: 'div-code',
                    name: 'Population density',
                    animation: true,
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    },
                    tooltip: {
                        valueSuffix: '/km²'
                    }
                }]
        });
    });



//    $(function () {
//
//        Highmaps = new Highmaps();
//
//        Highmaps.data({
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
//                                color: (Highmaps.theme && Highmaps.theme.textColor) || 'black'
//                            }
//                        },
//                        align: 'left',
//                        verticalAlign: 'bottom',
//                        floating: true,
//                        layout: 'vertical',
//                        valueDecimals: 0,
//                        backgroundColor: (Highmaps.theme && Highmaps.theme.legendBackgroundColor) || 'rgba(255, 255, 255, 0.85)',
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
//                            mapData: Highmaps.maps['custom/world'],
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
//                $('#container').html('<div class=\"loading\">' +
//                        '<i class=\"icon-frown icon-large\"></i> ' +
//                        'Error loading data from Google Spreadsheets' +
//                        '</div>');
//            }
//        });
//
//    });


//    $(function () {
//
//        Highmaps.data({
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
//                                color: (Highmaps.theme && Highmaps.theme.textColor) || 'black'
//                            }
//                        },
//                        align: 'left',
//                        verticalAlign: 'bottom',
//                        floating: true,
//                        layout: 'vertical',
//                        valueDecimals: 0,
//                        backgroundColor: (Highmaps.theme && Highmaps.theme.legendBackgroundColor) || 'rgba(255, 255, 255, 0.85)',
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
//                            mapData: Highmaps.maps['custom/world'],
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
