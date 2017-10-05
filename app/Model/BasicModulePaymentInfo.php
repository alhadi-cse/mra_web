<?php

App::uses('AppModel', 'Model');

class BasicModulePaymentInfo extends AppModel {
    public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'  => 'BasicModuleBasicInformation',
            'fields' => 'id,full_name_of_org,short_name_of_org',
            'foreignKey' => 'org_id'
         ),
        'LookupPaymentType' => array(
            'className'  => 'LookupPaymentType',
            'foreignKey' => 'paymentType_id'
         )
    ); 
}
