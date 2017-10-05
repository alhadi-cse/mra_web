<?php

App::uses('AppModel', 'Model');

class BasicModuleFileAttachment extends AppModel {
    public $belongsTo = array(
        'LookupBasicAttachmentType' => array(
            'className' => 'LookupBasicAttachmentType',
            'foreignKey' => 'file_type_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        )
    );
}