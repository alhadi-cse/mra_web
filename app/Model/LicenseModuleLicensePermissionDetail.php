<?php

App::uses('AppModel', 'Model');

class LicenseModuleLicensePermissionDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, licensing_year, license_no, license_issue_date',
            'foreignKey' => 'org_id'
        ),
        'LicenseModuleInitialAssessmentMark' => array(
            'className' => 'LicenseModuleInitialAssessmentMark',
            'fields' => 'total_assessment_marks, total_assessment_marks_assessor',
            'foreignKey' => false,
            'conditions' => 'LicenseModuleInitialAssessmentMark.org_id = LicenseModuleLicensePermissionDetail.org_id'
        )
    );

}
