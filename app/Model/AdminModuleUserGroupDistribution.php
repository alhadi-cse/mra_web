<?php

App::uses('AppModel', 'Model');

class AdminModuleUserGroupDistribution extends AppModel {

    public $belongsTo = array(
        'AdminModuleUserGroup' => array(
            'className' => 'AdminModuleUserGroup',
            'foreignKey' => 'user_group_id'
        ),
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = AdminModuleUser.id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => false,
            'conditions' => 'BasicModuleBasicInformation.id = AdminModuleUserProfile.org_id'
        ),
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            'foreignKey' => false,
            'conditions' => 'BasicModuleBranchInfo.id = AdminModuleUserProfile.branch_id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => false,
            'conditions' => 'LookupAdminBoundaryDistrict.id = BasicModuleBranchInfo.district_id'
        ),
        'LookupAdminBoundaryUpazila' => array(
            'className' => 'LookupAdminBoundaryUpazila',
            'foreignKey' => false,
            'conditions' => 'LookupAdminBoundaryUpazila.id = BasicModuleBranchInfo.upazila_id'
        )
    );
    public $validate = array(
//        'user_group_id' => array(
//            'required' => array( 
//                'required' => true,
//                'allowEmpty' => false,
//                'rule' => array('notBlank'),
//                'on' => 'null',                        
//                'message' => 'User group is required'
//            )
//        )
    );

}
