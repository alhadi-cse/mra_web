
<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMfiAdministrativeApprovalDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        ),
        'LookupLicenseApprovalStatus' => array(
            'className' => 'LookupLicenseApprovalStatus',
            'foreignKey' => 'approval_status_id'
        )
    );

}

