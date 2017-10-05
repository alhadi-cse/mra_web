<?php

App::uses('AppModel', 'Model');

class LookupInitialEvaluationPassMark extends AppModel {

    public $belongsTo = array(
        'LookupInitialEvaluationPassMarkType' => array(
            'className' => 'LookupInitialEvaluationPassMarkType',
            'foreignKey' => 'initial_evaluation_pass_mark_type_id'
        )
    );

}
