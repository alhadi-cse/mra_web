<?php

App::uses('AppModel', 'Model');

class LookupLicenseApplicationRejectionReason extends AppModel {

    public $belongsTo = array(
        'LookupLicenseApplicationRejectionType' => array(
            'className' => 'LookupLicenseApplicationRejectionType',
            'foreignKey' => 'rejection_type_id'
        ),
        'LookupLicenseApplicationRejectionCategory' => array(
            'className' => 'LookupLicenseApplicationRejectionCategory',
            'foreignKey' => 'rejection_category_id'
        )
    );

}
