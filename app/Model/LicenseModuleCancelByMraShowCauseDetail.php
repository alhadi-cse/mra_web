<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMraShowCauseDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        )
    );
}