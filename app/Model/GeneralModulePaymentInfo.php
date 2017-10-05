<?php

App::uses('AppModel', 'Model');

class GeneralModulePaymentInfo extends AppModel {
   public $belongsTo = array(        
        'BasicModuleBasicInformation' => array(
            'className'    => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org, licensing_year, licensing_state_id',
            'foreignKey'   => 'org_id'
         ),
        'LookupPaymentType' => array(
            'className'    => 'LookupPaymentType',
            'foreignKey'   => 'payment_type_id'
         )
    );
   
    public $validate = array(
            'org_id' => array(                            
                            'notBlank' => array(
                            'rule' => array('notBlank'),
                            'message' => 'Select an Organization',
                            'required' => 'true', 
                            'allowEmpty' => false
                        )
            )
    );
}
