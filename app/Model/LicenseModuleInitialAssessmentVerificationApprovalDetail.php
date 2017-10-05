<?php

App::uses('AppModel', 'Model');

class LicenseModuleInitialAssessmentVerificationApprovalDetail extends AppModel {

    public $belongsTo = array(
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'committee_user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = AdminModuleUser.id'
        ),
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

}
