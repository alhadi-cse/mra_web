<?php

App::uses('AppModel', 'Model');

class BasicModuleGeneralBodyMembersOtherBusinessInvolvment extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupBasicBusinessInvolvementNature' => array(
            'className'  => 'LookupBasicBusinessInvolvementNature',            
            'foreignKey' => 'nature_of_involvement_id'
        ),
        'BasicModuleGeneralBodyMemberInfo' => array(
            'className'  => 'BasicModuleGeneralBodyMemberInfo',
            'fields' => 'id,name',
            'foreignKey' => 'gb_member_id'
        )
    );
}
