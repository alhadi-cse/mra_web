<?php

App::uses('AppModel', 'Model');

class AdminModuleUserProfile extends AppModel {
    public $belongsTo = array(
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'user_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            'fields' => 'id, branch_name, branch_with_address',
            'foreignKey' => 'branch_id'
        )
    );
    public $virtualFields = array('name_with_designation_and_division' => 'CONCAT_WS(", ", AdminModuleUserProfile.full_name_of_user, AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office)');
    
    public $validate = array(
//        'branch_id' => array(
//            'required' => true,
//            'allowEmpty' => false,
//            'rule' => array('notBlank'),
//            'on' => 'null',
//            'message' => 'Select a Branch'
//        ),
        'full_name_of_user' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'Enter Full Name of User'
        ),
        'designation_of_user' => array(
            //'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'Enter the Designation of User'
        ),
        'org_name' => array(
            //'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'Enter the Organization Name of User'
        ),
        'mobile_no' => array(
            //'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'Enter a valid mobile no'
        ),
        'email' => array(
            //'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'A email is required'
        )
    );
}
