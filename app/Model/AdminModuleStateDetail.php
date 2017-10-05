<?php

App::uses('AppModel', 'Model');

class AdminModuleStateDetail extends AppModel {
    public $belongsTo = array(
        'AdminModuleStateName' => array(
            'className'  => 'AdminModuleStateName',
            'foreignKey' => 'state_id'
        )
    );
}