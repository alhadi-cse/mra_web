<?php

App::uses('AppModel', 'Model');

class AdminModuleUserGroup extends AppModel {
    public $belongsTo = array(
        'AdminModuleUserOrgType' => array(
            'className' => 'AdminModuleUserOrgType',
            'foreignKey' => 'user_org_type_id'
        )
    );
}