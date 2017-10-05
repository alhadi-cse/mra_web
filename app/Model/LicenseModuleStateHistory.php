<?php

App::uses('AppModel', 'Model');

class LicenseModuleStateHistory extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_year, license_no',
            'foreignKey' => 'org_id'
        ),
        'LicenseModuleStateName' => array(
            'className' => 'LicenseModuleStateName',
            'foreignKey' => 'state_id'
        )
    );

}
