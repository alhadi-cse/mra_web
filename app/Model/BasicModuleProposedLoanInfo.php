<?php

App::uses('AppModel', 'Model');

class BasicModuleProposedLoanInfo extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupBasicProposedLoanProgram' => array(
            'className'  => 'LookupBasicProposedLoanProgram',            
            'foreignKey' => 'proposed_loan_program_id'
        )
    );
}
