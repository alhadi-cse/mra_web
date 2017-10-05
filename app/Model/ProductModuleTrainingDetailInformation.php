<?php

App::uses('AppModel', 'Model');

class ProductModuleTrainingDetailInformation extends AppModel {
    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupProductTrainingType' => array(
            'className' => 'LookupProductTrainingType',            
            'foreignKey' => 'training_type_id'
        ),
        'LookupProductTrainingParticipantType' => array(
            'className' => 'LookupProductTrainingParticipantType',            
            'foreignKey' => 'training_participant_type_id'
        ),
        'LookupProductTrainingCourse' => array(
            'className' => 'LookupProductTrainingCourse',            
            'foreignKey' => 'training_course_id'
        )
    );
}