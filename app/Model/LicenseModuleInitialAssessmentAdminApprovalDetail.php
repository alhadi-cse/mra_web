<?php

App::uses('AppModel', 'Model');

class LicenseModuleInitialAssessmentAdminApprovalDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, licensing_year',
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'approval_status_id'
        ),
        'LicenseModuleInitialAssessmentMark' => array(
            'className' => 'LicenseModuleInitialAssessmentMark',
            'fields' => 'total_assessment_marks, total_assessment_marks_assessor',
            'foreignKey' => false,
            'conditions' => 'LicenseModuleInitialAssessmentMark.org_id = LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'
        )
    );

//public $virtualFields = array('total_assessment_marks' => "SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)");
//    public $belongsTo = array(
//        'BasicModuleBasicInformation' => array(
//            'className' => 'BasicModuleBasicInformation',
//            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_year',
//            'foreignKey' => 'org_id'
//        ),
//        'LookupLicenseApprovalStatus' => array(
//            'className' => 'LookupLicenseApprovalStatus',
//            'foreignKey' => 'approval_status_id'
//        )
//        
//        ,
//        'LicenseModuleInitialAssessmentDetail' => array(
//            'className' => 'LicenseModuleInitialAssessmentDetail',
//            //'fields' => array('total_assessment_marks' => "SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)"),
//            //'group' => 'LicenseModuleInitialAssessmentDetail.org_id',
//            'foreignKey' => false,
//            'conditions' => array('LicenseModuleInitialAssessmentDetail.org_id' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id')
//        ),
//        'LookupLicenseInitialAssessmentParameterOption' => array(
//            'className' => 'LookupLicenseInitialAssessmentParameterOption',
//            'foreignKey' => false,
//            'conditions' => array('LicenseModuleInitialAssessmentDetail.assess_parameter_option_id' => 'LookupLicenseInitialAssessmentParameterOption.id')
//        )
//    );
//    
//    public $virtualFields = array('total_assessment_marks' => "SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)");
//    
//        'LicenseModuleInitialAssessmentDetail' => array(
//                'id' => '230',
//                'org_id' => '44',
//                'parameter_id' => '2',
//                'assess_parameter_option_id' => '5',
//                'assessors_parameter_option_id' => '5',
//                'total_assessment_marks' => '178.7'
//        ),
//    public $validate = array(
//        'org_id' => array(
//            'notBlank' => array(
//                'rule' => array('notBlank'),
//                'message' => 'Select an Organization',
//                'required' => 'true',
//                'allowEmpty' => false
//            )
//        )
//    );
}
