<?php

App::uses('AppModel', 'Model');

class LookupModelFieldNoteDefinition extends AppModel {
    public $belongsTo = array(
        'LookupModelDefinition' => array(
            'className' => 'LookupModelDefinition',
            'foreignKey' => 'model_id'
        )
    );
}
