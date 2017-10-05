<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP CDBNonMfiFundInfo
 * @author AHI
 */
class CDBNonMfiFundInfo extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'CDBNonMfiBasicInfo' => array(
            'className' => 'CDBNonMfiBasicInfo',
            'foreignKey' => 'org_id'
        ),
        'LookupCDBNonMfiFundingSourceName' => array(
            'className' => 'LookupCDBNonMfiFundingSourceName',
            'foreignKey' => 'funding_source_id'
        )
    );

}
