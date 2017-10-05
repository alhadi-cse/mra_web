<?php

App::uses('AppModel', 'Model');

class BasicModuleGeneralBodyMembersEducationDetail extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupBasicExamType' => array(
            'className'  => 'LookupBasicExamType',            
            'foreignKey' => 'exam_type_id'
        ),
        'BasicModuleGeneralBodyMemberInfo' => array(
            'className'  => 'BasicModuleGeneralBodyMemberInfo',
            'fields' => 'id,name',
            'foreignKey' => 'gb_member_id'
        )
    );
}