<?php

App::uses('AppModel', 'Model');

class LookupModelFieldDefinition extends AppModel {

    public $belongsTo = array(
        'LookupModelDefinition' => array(
            'className' => 'LookupModelDefinition',
            'foreignKey' => 'model_id'
        ),
        'LookupModelFieldGroup' => array(
            'className' => 'LookupModelFieldGroup',
            'foreignKey' => false,
            'conditions' => 'LookupModelFieldGroup.model_id = LookupModelFieldDefinition.model_id AND LookupModelFieldGroup.id = LookupModelFieldDefinition.field_group_id',
        ),
        'LookupModelFieldSubGroup' => array(
            'className' => 'LookupModelFieldSubGroup',
            'foreignKey' => false,
            'conditions' => 'LookupModelFieldSubGroup.model_id = LookupModelFieldDefinition.model_id AND LookupModelFieldGroup.id = LookupModelFieldDefinition.field_group_id AND LookupModelFieldSubGroup.id = LookupModelFieldDefinition.field_sub_group_id',
        )
    );
}
