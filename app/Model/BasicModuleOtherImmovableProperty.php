<?php

App::uses('AppModel', 'Model');

class BasicModuleOtherImmovableProperty extends AppModel {
    public $belongsTo = array(        
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
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => 'org_id'
        ) 
    );
}
