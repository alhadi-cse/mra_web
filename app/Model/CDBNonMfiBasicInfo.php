<?php

App::uses('AppModel', 'Model');

class CDBNonMfiBasicInfo extends AppModel {

    //public $actsAs = array('Containable');
    public $belongsTo = array(
        'LookupCDBNonMfiType' => array(
            'className' => 'LookupCDBNonMfiType',
            'foreignKey' => 'type_id'
        ),
        'LookupCDBNonMfiMinistryAuthorityName' => array(
            'className' => 'LookupCDBNonMfiMinistryAuthorityName',
            'foreignKey' => 'ministry_or_authority_id'
        )
    );
    
    
//    public $hasMany = array(
//        'CDBNonMfiCoveredDistrict' => array(
//            'className' => 'CDBNonMfiCoveredDistrict',
//            'foreignKey' => 'org_id',
//            'order' => 'district_id'
//        )
//    );

//        ,
//        'LookupAdminBoundaryDistrict' => array(
//            'className' => 'LookupAdminBoundaryDistrict',
//            //'foreignKey' => 'district_id',
//            'foreignKey' => false,
//            'conditions' => 'CDBNonMfiCoveredDistrict.district_id = LookupAdminBoundaryDistrict.id'
//        )
//    public $virtualFields = array(
//        'district_with_code' => "CONCAT(LookupAdminBoundaryDistrict.district_name, ' (', LookupAdminBoundaryDistrict.id, ')')"
//    );
}
