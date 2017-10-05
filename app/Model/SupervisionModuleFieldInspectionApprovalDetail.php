<?php

App::uses('AppModel', 'Model');

class SupervisionModuleFieldInspectionApprovalDetail extends AppModel {

    public $belongsTo = array(
        'SupervisionModuleBasicInformation' => array(
            'className' => 'SupervisionModuleBasicInformation',
            'foreignKey' => 'supervision_basic_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, license_no',
            'foreignKey' => false,
            'conditions' => 'BasicModuleBasicInformation.id = SupervisionModuleBasicInformation.org_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = SupervisionModuleFieldInspectionApprovalDetail.inspector_user_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'inspection_approval_id'
        )
    );
    public $validate = array(
        'inspection_approval_id' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => array('notBlank'),
            'on' => 'null',
            'message' => 'Approval status must be selected !'
        )
    );

}
