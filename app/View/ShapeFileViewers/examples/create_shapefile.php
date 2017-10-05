<?php
/**
 * phpMyAdmin ShapeFile library
 * <https://github.com/phpmyadmin/shapefile/>
 *
 * Copyright 2006-2007 Ovidio <ovidio AT users.sourceforge.net>
 * Copyright 2016 Michal Čihař <michal@cihar.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, you can download one from
 * https://www.gnu.org/copyleft/gpl.html.
 */
use ShapeFile\ShapeFile;
use ShapeFile\ShapeRecord;

require_once '../vendor/autoload.php';

$shp = new ShapeFile(1, array("xmin" => 464079.002268, "ymin" => 2120153.74792, "xmax" => 505213.52849, "ymax" => 2163205.70036));

$record0 = new ShapeRecord(1);
$record0->addPoint(array("x" => 482131.764567, "y" => 2143634.39608));

$record1 = new ShapeRecord(1);
$record1->addPoint(array("x" => 472131.764567, "y" => 2143634.39608));

$record2 = new ShapeRecord(1);
$record2->addPoint(array("x" => 492131.764567, "y" => 2143634.39608));

$shp->addRecord($record0);
$shp->addRecord($record1);
$shp->addRecord($record2);

$shp->setDBFHeader(
    array(
        array('ID', 'N', 8, 0),
        array('DESC', 'C', 50, 0)
    )
);

$shp->records[0]->DBFData['ID'] = '1';
$shp->records[0]->DBFData['DESC'] = 'AAAAAAAAA';

$shp->records[1]->DBFData['ID'] = '2';
$shp->records[1]->DBFData['DESC'] = 'BBBBBBBBBB';

$shp->records[2]->DBFData['ID'] = '3';
$shp->records[2]->DBFData['DESC'] = 'CCCCCCCCCCC';

$shp->saveToFile('../data/new_shape.*');

echo "The ShapeFile was created.<br />\n";
echo "Return to the <a href='index.php'>index</a>.";