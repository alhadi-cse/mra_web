<?php

App::uses('AppModel', 'Model');

class AdminModulePeriodDetail extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'AdminModulePeriodDataType' => array(
            'className' => 'AdminModulePeriodDataType',
            'foreignKey' => 'data_type_id'
        ),
        'AdminModulePeriodType' => array(
            'className' => 'AdminModulePeriodType',
            'foreignKey' => 'period_type_id'
        ),
        'AdminModuleUserGroup' => array(
            'className' => 'AdminModuleUserGroup',
            'foreignKey' => 'user_group_id'
        )
    );

}
