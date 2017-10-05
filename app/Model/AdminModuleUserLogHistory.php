<?php

App::uses('AppModel', 'Model');

class AdminModuleUserLogHistory extends AppModel {
    public $belongsTo = array(
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'user_id'
        )
    );
}
