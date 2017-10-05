<?php

App::uses('AppModel', 'Model');

class CDBNonMfiYearlyMicrocreditInfo extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'CDBNonMfiBasicInfo' => array(
            'className' => 'CDBNonMfiBasicInfo',
            'foreignKey' => 'org_id'
        )
    );

}
