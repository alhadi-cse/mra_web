<?php

App::uses('AppModel', 'Model');

class BasicModulePrimaryRegActDetail extends AppModel {
     public $belongsTo = array(        
        'LookupBasicPrimaryRegistrationAct' => array(
            'className'  => 'LookupBasicPrimaryRegistrationAct',
            'foreignKey' => 'primary_reg_act_id'
        )
    );
}
