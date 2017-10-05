<?php

App::uses('AppModel', 'Model');

class ProductModuleWelfareActivityDetailInformation extends AppModel {
    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupProductWelfareActivityName' => array(
            'className' => 'LookupProductWelfareActivityName',            
            'foreignKey' => 'activity_id'
        )
    );
}