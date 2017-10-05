<?php

App::uses('AppModel', 'Model');

class BasicModuleNewMfiFieldWiseNote extends AppModel {
    public $belongsTo = array(
        'LookupModelFieldDefinition' => array(
            'className' => 'LookupModelFieldDefinition',
            'foreignKey' => 'field_id'
        )
    );
}
