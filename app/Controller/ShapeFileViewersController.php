<?php

App::uses('AppController', 'Controller');

//App::import('Vendor', 'ShapeFile/ShapeFile');
//use Cake\Filesystem\Folder;
//use Cake\Filesystem\File;
//App::import('Vendor', 'phpmyadmin/shapefile');
/**
 * CakePHP ReportModuleReportDefinitionsController
 * @author AHI
 */
class ShapeFileViewersController extends AppController {

    public function shap_read($id = null) {
        
    }

    public function gis_map() {
        
        //return;

        //$shape_file = APP . "ShapeFiles\Div\div01.shp";
        //C:\wamp\www\mra_web\app\webroot\ShapeFile\bd_all\dist11
        //C:\wamp\www\mra_web\app\webroot\ShapeFile\bd_all\dist11
        $shape_file = WEBROOT_DIR . "ShapeFile\bd_all\dist11\dist11.shp";
        //$result = $Folder->inPath(WWW_ROOT . 'img' . DS, true);
//        $shape_file = APP . "view\ShapeFileViewer\capitals.shp";
//        debug($shape_file);
//        
        debug(file_exists($shape_file));
        debug(is_file($shape_file));
        debug(is_readable($shape_file));
        
        
        $this->set(compact('shape_file'));
        
        return;
        try {
            //$shape_file = APP . "\ShapeFiles\Dist\dist01.shp";
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

        return;


//        $script = "$(function () {
//        
//        var data = [
//            {
//                'div-code': 'bd-da',
//                'value': 0
//            },
//            {
//                'div-code': 'bd-kh',
//                'value': 1
//            },
//            {
//                'div-code': 'bd-ba',
//                'value': 2
//            },
//            {
//                'div-code': 'bd-cg',
//                'value': 3
//            },
//            {
//                'div-code': 'bd-sy',
//                'value': 4
//            },
//            {
//                'div-code': 'bd-rj',
//                'value': 5
//            },
//            {
//                'div-code': 'bd-rp',
//                'value': 6
//            }
//        ];
//        
//        
//        // Initiate the chart
//        $('#container').highcharts('Map', {
//
//            title: {
//                text: 'All Division of Bangladesh'
//            },
////            subtitle: {
////                text: 'Source map: <a href='https://code.highcharts.com/mapdata/countries/bd/bd-all.js'>Bangladesh</a>'
////            },
//
//            mapNavigation: {
//                enabled: true,
//                buttonOptions: {
//                    align: 'right',
//                    verticalAlign: 'top'
//                }
//            },
//            legend: {
//                title: {
//                    text: 'Individuals per km²',
//                    style: {
//                        color: (Highcharts.theme && Highcharts.theme.textColor) || '#1177aa'//,
//                                //backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(200, 230, 250, 0.5)'
//                    }
//                },
//                align: 'right',
//                verticalAlign: 'bottom',
//                floating: true,
//                layout: 'vertical',
//                valueDecimals: 0,
//                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(200, 230, 250, 0.5)',
//                symbolRadius: 0,
//                symbolHeight: 14
//            },
//            colorAxis: {
//                dataClasses: [{
//                        to: 0.0
//                            }, {
//                                from: 0.0,
//                                to: 0.5
//                            }, {
//                                from: 0.5,
//                                to: 1.5
//                            }, {
//                                from: 1.5,
//                                to: 2.5
//                            }, {
//                                from: 2.5,
//                                to: 3.5
//                            }, {
//                                from: 3.5,
//                                to: 4.5
//                            }, {
//                                from: 4.5,
//                                to: 5.5
//                            }, {
//                                from: 5.5,
//                                to: 6.5
//                            }, {
//                                from: 7.0
//                            }]
//                    },
//                    series: [{
//                            data: data,
//                            mapData: Bangladesh['div/shape'], //Bangladesh['admin_boundary/div'], //Highcharts.maps['bd/all-div'],
//                            joinBy: 'div-code',
//                            name: 'Population density',
//                            animation: true,
//                            states: {
//                                hover: {
//                                    color: '#BADA55'
//                                }
//                            },
//                            dataLabels: {
//                                enabled: true,
//                                format: '{point.name}'
//                            },
//                            tooltip: {
//                                valueSuffix: '/km²'
//                            }
//                        }]
//                });
//            });";
//
//        $this->set(compact('script'));
    }

}
