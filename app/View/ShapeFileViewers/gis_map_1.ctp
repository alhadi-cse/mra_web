
<fieldset>
    <legend>Shape Map</legend>

    <div style="margin:5px auto; padding:5px; height:370px; overflow:auto;">

<?php

require_once('shapefile\src\shapefile.php');

try {
    //capitals
    $shape_file = APP . "view\ShapeFileViewers\data\bc_hospitals.shp";
    
    $ShapeFile = new ShapeFile($shape_file);
    while ($record = $ShapeFile->getRecord(SHAPEFILE::GEOMETRY_WKT)) {
        //debug($record);
        if ($record['dbf']['deleted']) continue;
        
        echo "<pre>";
        print_r($record['shp']);    // Geometry
        print_r($record['dbf']);    // DBF Data
        echo "</pre>";
    }
    
} catch (ShapeFileException $e) {
    debug($e);
    exit('Error '.$e->getCode().': '.$e->getMessage());
}

//return;


//
//
//
//header('Content-Type: text/plain');
//
//require 'ShapeFile.inc.php';
//
//$options = array('noparts' => false);
//$shp = new ShapeFile("5961.shp", $options); // along this file the class will use file.shx and file.dbf
//
//// opening a file for writing
//$out = fopen ("mapdata.csv", "w");
//
//while ($record = $shp->getNext()) {
//	// read meta data
//	$dbf_data = $record->getDbfData();
//	$data = array(
//		trim($dbf_data['ISO_3_CODE']), 
//		trim($dbf_data['ISO_2_CODE']),
//		trim($dbf_data['NAME'])
//	);
//	
//	// read shape data
//	$shp_data = $record->getShpData();
//	
//	// store number of parts
//	$data[] = $shp_data['numparts'];
//
//	foreach ($shp_data['parts'] as $part) {
//		$coords = array();
//		foreach ($part['points'] as $point) {
//			$coords[] = round($point['x'],2).','.round($point['y'],2);
//		}
//		$data[] = implode(';', $coords);
//	}
//	// write data as tab-seperated values into file
//	fputs($out, implode("\t", $data) . "\n");
//	
//}
//
//fclose($out);
?>


    </div>
</fieldset>
