<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMraShowcauseCancelNotifyDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',            
            'foreignKey' => 'org_id'
        ),
        'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail' => array(
            'className' => 'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail',            
            'foreignKey' => false,
            'conditions' => 'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id = LicenseModuleCancelByMraShowcauseCancelNotifyDetail.org_id'       
        )        
    );
}
