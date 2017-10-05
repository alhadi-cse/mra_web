<?php

App::uses('AppModel', 'Model');

class LookupModelFieldSubGroup extends AppModel {

    public $belongsTo = array(
        'LookupModelFieldGroup' => array(
            'className' => 'LookupModelFieldGroup',
            'foreignKey' => 'field_group_id'
        )
    );

}
