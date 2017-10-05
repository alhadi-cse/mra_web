<?php

App::uses('AppModel', 'Model');

class AdminModuleMessageSendingDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        )
    );
}
