<?php

App::uses('AppModel', 'Model');

class LicenseModuleAdministrativeApproval extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, licensing_year',
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'approval_status_id'
        )
    );
    public $validate = array(
        'org_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Select an Organization',
                'required' => 'true',
                'allowEmpty' => false
            )
        )
    );

}
