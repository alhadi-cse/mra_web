<?php

App::uses('AppModel', 'Model');

class LicenseModuleInitialAssessmentAssessorDetail extends AppModel {

    public $belongsTo = array(
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'assessor_user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = AdminModuleUser.id'
        )
    );

}
