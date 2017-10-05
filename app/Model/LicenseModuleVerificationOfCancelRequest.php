<?php

App::uses('AppModel', 'Model');

class LicenseModuleVerificationOfCancelRequest extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id,  full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'user_id'
        )
    ); 
}
