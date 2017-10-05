<?php

App::uses('AppModel', 'Model');

class LookupLicenseInitialAssessmentPassMark extends AppModel {
    public $belongsTo = array(        
        'LookupLicenseInitialAssessmentPassMarkType' => array(
            'className'  => 'LookupLicenseInitialAssessmentPassMarkType',            
            'foreignKey' => 'initial_evaluation_pass_mark_type_id'
            )
    );
}
