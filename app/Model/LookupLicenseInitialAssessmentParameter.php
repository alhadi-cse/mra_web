<?php

App::uses('AppModel', 'Model');

class LookupLicenseInitialAssessmentParameter extends AppModel {
    public $belongsTo = array( 
        'LookupLicenseInitialAssessmentParameterType' => array(
            'className'    => 'LookupLicenseInitialAssessmentParameterType',
            'foreignKey'   => 'parameter_type_id'
        ),
        'LookupModelDefinition' => array(
            'className'    => 'LookupModelDefinition',
            'foreignKey'   => 'model_id'
        ),
        'LookupModelFieldDefinition' => array(
            'className'    => 'LookupModelFieldDefinition',
            'foreignKey'   => 'field_id'
        ),
        'LookupTypeOfOperationOnParameter' => array(
            'className'    => 'LookupTypeOfOperationOnParameter',
            'foreignKey'   => 'operation_type_id'
        )        
    );  
    
    public $hasMany = array( 
        'LookupLicenseInitialAssessmentParameterOption' => array(
            'className'    => 'LookupLicenseInitialAssessmentParameterOption',
            'foreignKey'   => 'parameter_id'
        )
    );
    
    public $virtualFields = array('max_marks' => 'SELECT MAX(LookupLicenseInitialAssessmentParameterOption.assessment_marks) FROM lookup_license_initial_assessment_parameter_options AS LookupLicenseInitialAssessmentParameterOption WHERE LookupLicenseInitialAssessmentParameterOption.parameter_id = LookupLicenseInitialAssessmentParameter.id GROUP BY LookupLicenseInitialAssessmentParameterOption.parameter_id');
    
//    public $hasOne = array( 
//        'LookupModelDefinition' => array(
//        'className'    => 'LookupModelDefinition',
//        'foreignKey'   => 'model_id'
//        )
//    );
}
