<?php

App::uses('AppModel', 'Model');

class LicenseModuleFieldInspectionDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, license_no',
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseInspectionType' => array(
            'className' => 'LookupLicenseInspectionType',
            'foreignKey' => 'inspection_type_id'
        )
    );

}
