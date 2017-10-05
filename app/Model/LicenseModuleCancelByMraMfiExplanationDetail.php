<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMraMfiExplanationDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        )
    );
}