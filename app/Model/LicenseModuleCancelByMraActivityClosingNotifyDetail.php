<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMraActivityClosingNotifyDetail extends AppModel {
    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => 'org_id'
        ),
        'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail' => array(
            'className' => 'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail',            
            'foreignKey' => false,
            'conditions' => 'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id = LicenseModuleCancelByMraActivityClosingNotifyDetail.org_id'       
        )
    );
}
