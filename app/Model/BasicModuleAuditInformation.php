<?php

App::uses('AppModel', 'Model');

class BasicModuleAuditInformation extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
        ),        
        'QuestionOnExternalAudit' => array(
            'className'  => 'LookupYesNoQuestion',
            'foreignKey' => 'external_audit_carried_out_id'
        )       
    );
}
