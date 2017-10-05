<?php

App::uses('AppModel', 'Model');

class CDBNonMfiCoveredDistrict extends AppModel {

    public $actsAs = array('Containable');
    public $belongsTo = array(
        'CDBNonMfiBasicInfo' => array(
            'className' => 'CDBNonMfiBasicInfo',
            'foreignKey' => 'org_id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => 'district_id'
        )
    );
    public $virtualFields = array(
        'district_with_code' => "CONCAT(LookupAdminBoundaryDistrict.district_name, ' (', LookupAdminBoundaryDistrict.id, ')')"
    );

    //'district_name' => "LookupAdminBoundaryDistrict.district_name",
}
