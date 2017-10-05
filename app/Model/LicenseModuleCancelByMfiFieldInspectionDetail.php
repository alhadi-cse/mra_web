<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMfiFieldInspectionDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => 'org_id'
        )
    );
}
