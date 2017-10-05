<?php

App::uses('AppModel', 'Model');

class LicenseModuleInitialAssessmentMark extends AppModel {

    public $useTable = 'v_license_module_initial_assessment_marks';

}

/*
class LicenseModuleInitialAssessmentMarkTest extends AppModel {

    public $useTable = false;
    public $belongsTo = array(
        'LicenseModuleInitialAssessmentDetail' => array(
            'className' => 'LicenseModuleInitialAssessmentDetail',
            'foreignKey' => false, //'org_id'
            'fields' => 'org_id, LicenseModuleInitialAssessmentDetail.*',
            'joins' => array(
                array(
                    //'className' => 'LicenseModuleInitialAssessmentDetail',
                    'table' => 'lookup_license_initial_assessment_parameter_options',
                    'alias' => 'LookupLicenseInitialAssessmentParameterOption',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'LicenseModuleInitialAssessmentDetail.assess_parameter_option_id = LookupLicenseInitialAssessmentParameterOption.id'
                    ),
                )
//                , array(
//                    'table' => 'lookup_license_initial_assessment_parameter_options_assessor',
//                    'alias' => 'LookupLicenseInitialAssessmentParameterOptionAssessor',
//                    'type' => 'LEFT',
//                    'conditions' => array(
//                        'LicenseModuleInitialAssessmentDetail.assessors_parameter_option_id = LookupLicenseInitialAssessmentParameterOptionAssessor.id'
//                    ),
//                )
            ),
            'group' => 'LicenseModuleInitialAssessmentDetail.org_id'
        )
    );
    
    public $hasMany = array(
        'UserTo' => array(
            'className' => 'User',
            'foreignKey' => 'to'
        )
    );

    
    
//    public $hasOne = array(
//        'BasicModuleBasicInformation' => array(
//            'className' => 'BasicModuleBasicInformation',
//            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, licensing_year',
//            'foreignKey' => 'org_id'
//        )
//    );
//    
//    
//    public $virtualFields = array('name_of_org' => "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (<strong>', short_name_of_org, '</strong>)') ELSE short_name_of_org END)");
//    
//    
//
//    public $useTable = false;
//    
//    //public $actsAs = array('Containable');
//    public $belongsTo = array(
//    //public $hasOne = array(
//        'LicenseModuleInitialAssessmentDetail' => array(
//            'className' => 'LicenseModuleInitialAssessmentDetail',
//            'foreignKey' => false, //'org_id'
//            'fields' => 'org_id',
//            'joins' => array(
//                array(
//                    //'className' => 'LicenseModuleInitialAssessmentDetail',
//                    'table' => 'lookup_license_initial_assessment_parameter_options',
//                    'alias' => 'LookupLicenseInitialAssessmentParameterOption',
//                    'type' => 'LEFT',
//                    'conditions' => array(
//                        'LicenseModuleInitialAssessmentDetail.assess_parameter_option_id = LookupLicenseInitialAssessmentParameterOption.id'
//                    ),
//                )
////                , array(
////                    'table' => 'lookup_license_initial_assessment_parameter_options_assessor',
////                    'alias' => 'LookupLicenseInitialAssessmentParameterOptionAssessor',
////                    'type' => 'LEFT',
////                    'conditions' => array(
////                        'LicenseModuleInitialAssessmentDetail.assessors_parameter_option_id = LookupLicenseInitialAssessmentParameterOptionAssessor.id'
////                    ),
////                )
//            ),
//            'group' => 'LicenseModuleInitialAssessmentDetail.org_id'
//        )
//    );
//    
//    public $virtualFields = array('total_assessment_marks' => "SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)",
//        'total_assessment_marks_assessor' => "SUM(LookupLicenseInitialAssessmentParameterOptionAssessor.assessment_marks)");
//
////    
////    SELECT
////  license_module_initial_assessment_details.org_id AS org_id,
////  SUM(lookup_license_initial_assessment_parameter_options.assessment_marks) AS total_assessment_marks,
////  SUM(lookup_license_initial_assessment_parameter_options_assessor.assessment_marks) AS total_assessment_marks_assessor
////FROM ((license_module_initial_assessment_details
////    LEFT JOIN lookup_license_initial_assessment_parameter_options
////      ON ((license_module_initial_assessment_details.assess_parameter_option_id = lookup_license_initial_assessment_parameter_options.id)))
////   LEFT JOIN lookup_license_initial_assessment_parameter_options lookup_license_initial_assessment_parameter_options_assessor
////     ON ((license_module_initial_assessment_details.assessors_parameter_option_id = lookup_license_initial_assessment_parameter_options_assessor.id)))
////GROUP BY license_module_initial_assessment_details.org_id)$$
    
}
*/