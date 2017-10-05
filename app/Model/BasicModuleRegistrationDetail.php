<?php

App::uses('AppModel', 'Model');

class BasicModuleRegistrationDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => 'org_id'
        ),
        'LookupBasicRegistrationAuthority' => array(
            'className' => 'LookupBasicRegistrationAuthority',
            'foreignKey' => 'reg_authority_id'
        )
    );

}
