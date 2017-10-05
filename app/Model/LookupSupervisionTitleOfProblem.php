<?php

App::uses('AppModel', 'Model');

class LookupSupervisionTitleOfProblem extends AppModel {
    public $belongsTo = array(        
        'LookupSupervisionTypeOfProblem' => array(
            'className'  => 'LookupSupervisionTypeOfProblem',            
            'foreignKey' => 'type_of_problem_id'
        )
    );
}
