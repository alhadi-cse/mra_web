<?php

App::uses('AppModel', 'Model');

class AdminModulePeriodList extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodType' => array(
            'className' => 'AdminModulePeriodType',
            'foreignKey' => 'type_id'
        )
    );
    
    public $virtualFields = array(
        'period' => "CASE WHEN AdminModulePeriodList.type_id != 3 THEN CONCAT_WS(' to ', DATE_FORMAT(AdminModulePeriodList.from_date, '%b/%Y'), DATE_FORMAT(AdminModulePeriodList.to_date, '%b/%Y')) ELSE DATE_FORMAT(AdminModulePeriodList.to_date, '%M %d, %Y') END",
        'as_on' => "DATE_FORMAT(AdminModulePeriodList.to_date, '%M %d, %Y')"
    );}


//    'period' => "CONCAT_WS(' to ', DATE_FORMAT(from_date, '%b/%Y'), DATE_FORMAT(to_date, '%b/%Y'))",
//    public $virtualFields = array('period' => "CONCAT_WS('', CASE WHEN type_id != 3 THEN CONCAT_WS(' to ', DATE_FORMAT(from_date, '%b/%Y')) ELSE '', DATE_FORMAT(to_date, '%b/%Y'))",
//        'as_on' => "CONCAT_WS('','',DATE_FORMAT(AdminModulePeriodList.to_date, '%M %d, %Y'))"
//    );
//    public $virtualFields = array('period' => "CONCAT_WS(' to ', DATE_FORMAT(AdminModulePeriodList.from_date, '%b/%Y'), DATE_FORMAT(AdminModulePeriodList.to_date, '%b/%Y'))");

/*
App::uses('AppModel', 'Model');

class AdminModulePeriodList extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodType' => array(
            'className' => 'AdminModulePeriodType',
            'foreignKey' => 'type_id'
        )
    );
    
    public $virtualFields = array('period' => "CONCAT_WS(' to ', DATE_FORMAT(AdminModulePeriodList.from_date, '%b/%Y'), DATE_FORMAT(AdminModulePeriodList.to_date, '%b/%Y'))");

    //public $virtualFields = array('period' => "CONCAT_WS(' to ', DATE_FORMAT(AdminModulePeriodList.from_date, '%b/%Y'), DATE_FORMAT(AdminModulePeriodList.to_date, '%b/%Y'))");
}
*/
