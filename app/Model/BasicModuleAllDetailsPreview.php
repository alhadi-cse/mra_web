<?php

App::uses('AppModel', 'Model');

class BasicModuleAllDetailsPreview extends AppModel {

    public $belongsTo = array(
        'LookupLicensingStatus' => array(
            'className'  => 'LookupLicensingStatus',
            'foreignKey' => 'licensing_status_id'
         ),
        'LookupTypeOfOrganization' => array(
            'className'  => 'LookupTypeOfOrganization',
            'foreignKey' => 'type_of_organization_id'
         ),
        'LookupBasicPrimaryRegistrationAct' => array(
            'className'  => 'LookupBasicPrimaryRegistrationAct',
            'foreignKey' => 'mra_act_id'
         ),
        'LookupBasicRegistrationAuthority' => array(
            'className'  => 'LookupBasicRegistrationAuthority',
            'foreignKey' => 'registration_authority_id'
         )
    );  
    
    
    
//    public $belongsTo = array(
//        'LookupLicensingStatus' => array(
//        'className'  => 'LookupLicensingStatus',
//        'foreignKey' => 'licensing_status_id'
//         ),
//        'LookupTypeOfOrganization' => array(
//        'className'  => 'LookupTypeOfOrganization',
//        'foreignKey' => 'type_of_organization_id'
//         ),
//        'LookupBasicPrimaryRegistrationAct' => array(
//        'className'  => 'lookup_m_r_a_acts',
//        'foreignKey' => 'id'
//         ),
//        'LookupBasicRegistrationAuthority' => array(
//        'className'  => 'LookupBasicRegistrationAuthority',
//        'foreignKey' => 'registration_authority_id'
//         )
//    );  
}
