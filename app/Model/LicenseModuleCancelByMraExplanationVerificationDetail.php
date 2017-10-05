<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMraExplanationVerificationDetail extends AppModel {
       
    public $belongsTo = array(
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = LicenseModuleCancelByMraExplanationVerificationDetail.verification_committee_user_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, license_no, licensing_year',
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'verification_status_id'
        )        
    );
}

