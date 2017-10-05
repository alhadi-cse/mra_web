<?php

App::uses('AppModel', 'Model');

class BasicModuleProposedAddress extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, form_serial_no, full_name_of_org, short_name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => 'district_id'
        ),
        'LookupAdminBoundaryUpazila' => array(
            'className' => 'LookupAdminBoundaryUpazila',
            'foreignKey' => 'upazila_id'
        ),
        'LookupAdminBoundaryUnion' => array(
            'className' => 'LookupAdminBoundaryUnion',
            'foreignKey' => 'union_id'
        ),
        'LookupAdminBoundaryMauza' => array(
            'className' => 'LookupAdminBoundaryMauza',
            'foreignKey' => 'mauza_id'
        ),
        'LookupBasicProposedAddressType' => array(
            'className' => 'LookupBasicProposedAddressType',
            'foreignKey' => 'address_type_id'
        )
    );

}
