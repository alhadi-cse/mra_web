<?php

App::uses('AppModel', 'Model');

class LicenseModulePaymentDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_state_id, license_no',
            'foreignKey' => 'org_id'
        ),
        'LookupLicensePaymentType' => array(
            'className' => 'LookupLicensePaymentType',
            'foreignKey' => 'payment_type_id'
        ),
        'LicenseModulePaymentReminderDetail' => array(
            'className' => 'LicenseModulePaymentReminderDetail',
            'foreignKey' => false,
            'conditions' => 'LicenseModulePaymentDetail.id = LicenseModulePaymentReminderDetail.payment_id',
            'order' => 'LicenseModulePaymentReminderDetail.id'
        )
    );
    
    //'conditions' => array('LicenseModulePaymentDetail.id = LicenseModulePaymentReminderDetail.payment_id AND LicenseModulePaymentReminderDetail.reminder_is_active = 1'), 
//    public $hasOne = array(
//        'LicenseModulePaymentReminderDetail' => array(
//            'className' => 'LicenseModulePaymentReminderDetail',
//            'foreignKey' => 'payment_id = LicenseModulePaymentDetail.id',
//            //'foreignKey' => 'id = Journey.toCity'
//            //'foreignKey' => false,
//            'conditions' => array('LicenseModulePaymentReminderDetail.payment_id = LicenseModulePaymentDetail.id', 'LicenseModulePaymentReminderDetail.reminder_is_active' => '1'),
//            'order' => array('LicenseModulePaymentReminderDetail.id')
//        )
//    );

    public $validate = array(
        'org_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Select an Organization',
                'required' => 'true',
                'allowEmpty' => false
            )
        ),
        'payment_type_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Select a Payment Type',
                'required' => 'true',
                'allowEmpty' => false
            )
        )
    );

}
