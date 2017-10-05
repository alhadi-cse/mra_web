<?php

App::uses('AppModel', 'Model');

class CDBNonMfiDistrictWiseMicrocreditActivity extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'CDBNonMfiBasicInfo' => array(
            'className' => 'CDBNonMfiBasicInfo',
            'foreignKey' => 'org_id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => 'district_id'
        )
    );
    
    public $virtualFields = array('total_clients' => "no_of_male_clients + no_of_female_clients",
        'total_borrowers' => "no_of_male_borrowers + no_of_female_borrowers",
        'total_recovery' => "recovery_principal + recovery_service_charge");
    
//    ,'total_savers' => "no_of_male_savers + no_of_female_savers"
}
