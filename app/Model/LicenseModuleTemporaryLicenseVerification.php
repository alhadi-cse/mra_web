<?php

App::uses('AppModel', 'Model');

class LicenseModuleTemporaryLicenseVerification extends AppModel {
    public $belongsTo = array(        
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => 'user_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        )
    ); 
}
