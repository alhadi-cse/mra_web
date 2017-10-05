<?php

App::uses('AppModel', 'Model');

class LicenseModuleFieldInspectionApprovalDetail extends AppModel {

    public $belongsTo = array(
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = LicenseModuleFieldInspectionApprovalDetail.inspector_user_id'
        ),
        'LookupLicenseInspectionType' => array(
            'className' => 'LookupLicenseInspectionType',
            'foreignKey' => 'inspection_type_id'
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
