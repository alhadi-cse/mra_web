<?php

App::uses('AppModel', 'Model');

class LookupLicenseInspectionParameterGroup extends AppModel {

    public $belongsTo = array(
        'LookupLicenseInspectionType' => array(
            'className' => 'LookupLicenseInspectionType',
            'foreignKey' => 'inspection_type_id'
        )
    );

}
