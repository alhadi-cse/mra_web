<?php

App::uses('AppModel', 'Model');

class LookupLicenseInitialAssessmentParameterOption extends AppModel {
    
    public $virtualFields = array('option_with_marks' => "CONCAT_WS('', LookupLicenseInitialAssessmentParameterOption.parameter_option, ' - Marks(', LookupLicenseInitialAssessmentParameterOption.assessment_marks, ')')");
	//public $virtualFields = array('option_with_marks' => "CONCAT(LookupLicenseInitialAssessmentParameterOption.parameter_option, ' - Marks(', LookupLicenseInitialAssessmentParameterOption.assessment_marks, ')')");

    public $belongsTo = array(
        'LookupLicenseInitialAssessmentParameter' => array(
            'className'    => 'LookupLicenseInitialAssessmentParameter',
            'foreignKey'   => 'parameter_id'
         ),
        'LookupLicenseInitialAssessmentParameterType' => array(
            'className'    => 'LookupLicenseInitialAssessmentParameterType',
            'foreignKey'   => 'parameter_type_id'
        )
    );
} 
