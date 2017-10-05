<?php

App::uses('AppModel', 'Model');

class LookupLicenseInspectionParameter extends AppModel {

    public $belongsTo = array(
        'LookupLicenseInspectionType' => array(
            'className' => 'LookupLicenseInspectionType',
            'foreignKey' => 'inspection_type_id'
        ),
        'LookupLicenseInspectionParameterGroup' => array(
            'className' => 'LookupLicenseInspectionParameterGroup',
            'foreignKey' => 'parameter_group_id'
        )
    );

}
