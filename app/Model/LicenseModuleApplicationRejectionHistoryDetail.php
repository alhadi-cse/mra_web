<?php

App::uses('AppModel', 'Model');

class LicenseModuleApplicationRejectionHistoryDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LicenseModuleStateName' => array(
            'className' => 'LicenseModuleStateName',
            'fields' => 'id, state_title',
            'foreignKey' => 'previous_licensing_state_id'
        ),
        'LookupLicenseApplicationRejectionType' => array(
            'className' => 'LookupLicenseApplicationRejectionType',
            'fields' => 'id, rejection_type',
            'foreignKey' => 'rejection_type_id'
        ),
        'LookupLicenseApplicationRejectionCategory' => array(
            'className' => 'LookupLicenseApplicationRejectionCategory',
            'fields' => 'id, rejection_category',
            'foreignKey' => 'rejection_category_id'
        ),
        'LookupLicenseApplicationRejectionReason' => array(
            'className' => 'LookupLicenseApplicationRejectionReason',
            'fields' => 'id, rejection_reason',
            'foreignKey' => 'rejection_reason_id'
        )
    );

}

?>
