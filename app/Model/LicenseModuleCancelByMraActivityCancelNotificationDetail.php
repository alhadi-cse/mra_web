<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMraActivityCancelNotifyDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => 'org_id'
        )
    );
}
