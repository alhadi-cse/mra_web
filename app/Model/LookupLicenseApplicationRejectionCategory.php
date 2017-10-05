<?php

App::uses('AppModel', 'Model');

class LookupLicenseApplicationRejectionCategory extends AppModel {

    public $belongsTo = array(
        'LookupLicenseApplicationRejectionType' => array(
            'className' => 'LookupLicenseApplicationRejectionType',
            'foreignKey' => 'rejection_type_id'
        )
    );

//    public $hasMany = array(
//        'LookupLicenseApplicationRejectionReason' => array(
//            'className' => 'LookupLicenseApplicationRejectionReason',
//            'foreignKey' => 'rejection_category_id'
//    ));
}
