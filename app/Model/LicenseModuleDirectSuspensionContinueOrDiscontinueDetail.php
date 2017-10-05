<?php

App::uses('AppModel', 'Model');

class LicenseModuleDirectSuspensionContinueOrDiscontinueDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation', 
            'fields' => array('id','full_name_of_org','short_name_of_org','licensing_state_id','licensing_year','license_no','license_issue_date'),
            'foreignKey' => 'org_id'
        )
    );   
    
}
