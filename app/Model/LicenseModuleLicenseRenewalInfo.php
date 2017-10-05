<?php

App::uses('AppModel', 'Model');

class LicenseModuleLicenseRenewalInfo extends AppModel {        
    
    public $belongsTo = array(        
        'BasicModuleasicInformation' => array(
        'className' => 'BasicModuleBasicInformation',
        'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, licensing_year',
        'foreignKey' => 'org_id'
         )
    );    
}
