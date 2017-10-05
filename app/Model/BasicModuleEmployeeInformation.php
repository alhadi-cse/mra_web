<?php

App::uses('AppModel', 'Model');

class BasicModuleEmployeeInformation extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
        )
    );
}
