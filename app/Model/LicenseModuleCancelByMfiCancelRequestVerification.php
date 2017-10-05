<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMfiCancelRequestVerification extends AppModel {
    public $belongsTo = array(        
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = AdminModuleUser.id'
        ),
        'AdminModuleUserGroupDistribution' => array(
            'className' => 'AdminModuleUserGroupDistribution',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserGroupDistribution.user_id = AdminModuleUser.id'
        ),
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        )
    ); 
}
