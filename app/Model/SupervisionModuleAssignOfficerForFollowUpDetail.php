<?php

App::uses('AppModel', 'Model');

class SupervisionModuleAssignOfficerForFollowUpDetail extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        ),
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'follow_up_officer_user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = SupervisionModuleAssignOfficerForFollowUpDetail.follow_up_officer_user_id'
        )
    );
}
