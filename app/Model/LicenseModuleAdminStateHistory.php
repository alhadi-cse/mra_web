<?php

App::uses('AppModel', 'Model');

class LicenseModuleAdminStateHistory extends AppModel {
    public $belongsTo = array(
        'LicenseModuleAdminStateName' => array(
            'className'  => 'LicenseModuleAdminStateName',
            'foreignKey' => 'licensing_state_id'
        )
    );
}
