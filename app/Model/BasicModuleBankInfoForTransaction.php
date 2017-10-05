<?php

App::uses('AppModel', 'Model');

class BasicModuleBankInfoForTransaction extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        )
    );
}
