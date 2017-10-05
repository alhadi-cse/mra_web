<?php

App::uses('AppModel', 'Model');

class LicenseModuleLicenseEvaluationVerificationDetail extends AppModel {

    public $belongsTo = array(
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = LicenseModuleLicenseEvaluationVerificationDetail.verification_committee_user_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, licensing_year',
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'verification_status_id'
        ),
        'LicenseModuleInitialAssessmentMark' => array(
            'className' => 'LicenseModuleInitialAssessmentMark',
            'fields' => 'total_assessment_marks, total_assessment_marks_assessor',
            'foreignKey' => false,
            'conditions' => 'LicenseModuleInitialAssessmentMark.org_id = LicenseModuleLicenseEvaluationVerificationDetail.org_id'
        )
    );

}
