<?php

App::uses('AppModel', 'Model');

class LicenseModuleSuspensionAcceptanceOfReviewDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        )
    );
}
