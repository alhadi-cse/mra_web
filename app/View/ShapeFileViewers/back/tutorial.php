<?php

header('Content-Type: text/plain');

require 'ShapeFile.inc.php';

$options = array('noparts' => false);
$shp = new ShapeFile("5961.shp", $options); // along this file the class will use file.shx and file.dbf

// opening a file for writing
$out = fopen ("mapdata.csv", "w");

while ($record = $shp->getNext()) {
	// read meta data
	$dbf_data = $record->getDbfData();
	$data = array(
		trim($dbf_data['ISO_3_CODE']), 
		trim($dbf_data['ISO_2_CODE']),
		trim($dbf_data['NAME'])
	);
	
	// read shape data
	$shp_data = $record->getShpData();
	
	// store number of parts
	$data[] = $shp_data['numparts'];

	foreach ($shp_data['parts'] as $part) {
		$coords = array();
		foreach ($part['points'] as $point) {
			$coords[] = round($point['x'],2).','.round($point['y'],2);
		}
		$data[] = implode(';', $coords);
	}
	// write data as tab-seperated values into file
	fputs($out, implode("\t", $data) . "\n");
	
}

fclose($out);

