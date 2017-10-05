<?php

App::uses('AppModel', 'Model');

class LicenseModuleInitialAssessmentDetail extends AppModel {

//    public $belongsTo = array(
//        'BasicModuleBasicInformation' => array(
//            'className' => 'BasicModuleBasicInformation',
//            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_year',
//            'foreignKey' => 'org_id'
//        ),
//        'LicenseModuleInitialAssessmentMark' => array(
//            'className' => 'LicenseModuleInitialAssessmentMark',
//            'fields' => 'total_assessment_marks, total_assessment_marks_assessor',
//            'foreignKey' => false,
//            'conditions' => 'LicenseModuleInitialAssessmentMark.org_id = LicenseModuleInitialAssessmentDetail.org_id'
//        ),
//        'LookupLicenseInitialAssessmentParameter' => array(
//            'className' => 'LookupLicenseInitialAssessmentParameter',
//            'foreignKey' => 'parameter_id',
//            'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'),
//            'order' => 'sorting_order asc'
//        ),
//        'LookupLicenseInitialAssessmentParameterOption' => array(
//            'className' => 'LookupLicenseInitialAssessmentParameterOption',
//            //'fields' => 'assessment_marks',
//            'foreignKey' => 'assess_parameter_option_id',
//            'order' => 'assessment_marks desc, parameter_option asc'
//        )
//        
////        ,
////        'LookupLicenseInitialAssessmentParameterAssessmentOption' => array(
////            'className' => 'LookupLicenseInitialAssessmentParameterOption',
////            'foreignKey' => 'assess_parameter_option_id'//,
////            //'order' => 'LookupLicenseInitialAssessmentParameterAssessmentOption.assessment_marks desc, LookupLicenseInitialAssessmentParameterAssessmentOption.parameter_option asc'
////        ),
////        'LookupLicenseInitialAssessmentParameterAssessorsOption' => array(
////            'className' => 'LookupLicenseInitialAssessmentParameterOption',
////            'foreignKey' => 'assessors_parameter_option_id'//,
////            //'order' => 'LookupLicenseInitialAssessmentParameterAssessorsOption.assessment_marks desc, LookupLicenseInitialAssessmentParameterAssessorsOption.parameter_option asc'
////        )
//    );
    
    //public $virtualFields = array('total_assessment_marks' => 'LicenseModuleInitialAssessmentMark.total_assessment_marks');
    //public $virtualFields = array('total_assessment_marks' => "SUM(LookupLicenseInitialAssessmentParameterAssessmentOption.assessment_marks)");
    
    
    
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_year',
            'foreignKey' => 'org_id'
        ),
        'LicenseModuleInitialAssessmentMark' => array(
            'className' => 'LicenseModuleInitialAssessmentMark',
            'fields' => 'total_assessment_marks, total_assessment_marks_assessor',
            'foreignKey' => false,
            'conditions' => 'LicenseModuleInitialAssessmentMark.org_id = LicenseModuleInitialAssessmentDetail.org_id'
        ),
        'LookupLicenseInitialAssessmentParameter' => array(
            'className' => 'LookupLicenseInitialAssessmentParameter',
            'foreignKey' => 'parameter_id',
            'order' => 'sorting_order asc',
            'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1')
        ),
        'LookupLicenseInitialAssessmentParameterOption' => array(
            'className' => 'LookupLicenseInitialAssessmentParameterOption',
            //'fields' => 'assessment_marks',
            'foreignKey' => 'assess_parameter_option_id',
        //'order' => 'assessment_marks desc, parameter_option asc'
        ),
        'LookupLicenseInitialReAssessmentParameterOption' => array(
            'className' => 'LookupLicenseInitialAssessmentParameterOption',
            'foreignKey' => 'assessors_parameter_option_id',
            //'fields' => 'assessment_marks',
        //'order' => 'LookupLicenseInitialAssessmentParameterOption.assessment_marks desc, parameter_option asc'
        )
    );

//    
//    public $virtualFields = array('total_assessment_marks' => "SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)", 'committee_evaluation_marks' => "SUM(LookupLicenseInitialReAssessmentParameterOption.assessment_marks)");
//    
//    //public $virtualFields = array('total_assessment_marks' => "SUM(LookupLicenseInitialReAssessmentParameterOption.committee_evaluation_marks)");
}
