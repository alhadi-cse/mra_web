<?php

App::uses('AppModel', 'Model');

class LicenseModuleFieldInspectionFieldDetail extends AppModel {

    public $belongsTo = array(
        'LicenseModuleFieldInspectionDetail' => array(
            'className' => 'LicenseModuleFieldInspectionDetail',
            'foreignKey' => 'inspection_id'
        ),
        'LookupLicenseInspectionParameter' => array(
            'className' => 'LookupLicenseInspectionParameter',
            'foreignKey' => 'parameter_id'
        )
        , 'LookupLicenseInspectionParameterGroup' => array(
            'className' => 'LookupLicenseInspectionParameterGroup',
            'foreignKey' => false,
            'conditions' => 'LookupLicenseInspectionParameterGroup.id = LookupLicenseInspectionParameter.parameter_group_id'
        )
    );

}
