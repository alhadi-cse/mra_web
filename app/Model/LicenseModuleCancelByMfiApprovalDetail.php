<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMfiApprovalDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseApprovalStatus_AD' => array(
            'className' => 'LookupLicenseApprovalStatus',            
            'foreignKey' => 'approval_status_ad'
        ),
        'LookupLicenseApprovalStatus_SAD' => array(
            'className' => 'LookupLicenseApprovalStatus',            
            'foreignKey' => 'approval_status_sad'
        ),
        'LookupLicenseApprovalStatus_DD' => array(
            'className' => 'LookupLicenseApprovalStatus',            
            'foreignKey' => 'approval_status_dd'
        ),
        'LookupLicenseApprovalStatus_SDD' => array(
            'className' => 'LookupLicenseApprovalStatus',            
            'foreignKey' => 'approval_status_sdd'
        ),
        'LookupLicenseApprovalStatus_Director' => array(
            'className' => 'LookupLicenseApprovalStatus',            
            'foreignKey' => 'approval_status_director'
        ),
        'LookupLicenseApprovalStatus_EVC' => array(
            'className' => 'LookupLicenseApprovalStatus',            
            'foreignKey' => 'approval_status_evc'
        )
    );
}
