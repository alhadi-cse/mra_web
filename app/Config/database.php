<?php

class DATABASE_CONFIG {
    
        ///===For localhost===///
	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost:3306',
		'login' => 'root',
		'password' => '',
		'database' => 'mra_web_db',
		'prefix' => '',
		'encoding' => 'utf8',
	);

	///===For Server===///
//	public $default = array(
//		'datasource' => 'Database/Mysql',
//		'persistent' => false,
//		'host' => 'localhost',
//		'login' => 'root',
//		'password' => 'sunbath',
//		'database' => 'mra_web_db',
//		'prefix' => '',
//		'encoding' => 'utf8',
//	);

}
