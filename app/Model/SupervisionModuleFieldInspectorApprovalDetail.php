<?php

App::uses('AppModel', 'Model');

class SupervisionModuleFieldInspectorApprovalDetail extends AppModel {

    public $belongsTo = array(
        'SupervisionModuleBasicInformation' => array(
            'className' => 'SupervisionModuleBasicInformation',
            'foreignKey' => 'supervision_basic_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'approval_status_id'
        )
    );

}
