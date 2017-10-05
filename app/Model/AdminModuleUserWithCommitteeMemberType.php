<?php

App::uses('AppModel', 'Model');

class AdminModuleUserWithCommitteeMemberType extends AppModel {
    public $belongsTo = array(        
        'LookupUserCommitteeMemberType' => array(
            'className'  => 'LookupUserCommitteeMemberType',            
            'foreignKey' => 'committe_member_type_id'
        )
    );
    
//    public $validate = array(
//        'committe_member_type_id' => array(
//            'required' => true,
//            'allowEmpty' => false,
//            'rule' => array('notBlank'),
//            'on' => 'null',
//            'message' => 'A committe member type is required'
//        )
//    );
}
