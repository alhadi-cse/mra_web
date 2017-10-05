<?php

App::uses('AppModel', 'Model');

class SavingsModuleBranchwiseSavingsInformation extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        ),
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            'foreignKey' => 'branch_id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'fields' => 'id, district_name',
            'foreignKey' => false,
            'conditions' => 'BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id'
        ),
        'LookupAdminBoundaryUpazila' => array(
            'className' => 'LookupAdminBoundaryUpazila',
            'fields' => 'id, upazila_name',
            'foreignKey' => false,
            'conditions' => 'BasicModuleBranchInfo.upazila_id = LookupAdminBoundaryUpazila.id'
        ),
        'LookupSaverType' => array(
            'className' => 'LookupSaverType',
            'foreignKey' => 'savers_type_id'
        ),
        'LookupSavingsScheme' => array(
            'className' => 'LookupSavingsScheme',
            'foreignKey' => 'savings_scheme_id'
        )
    );
    public $virtualFields = array('total_savers' => "number_of_male_savers + number_of_female_savers");

}
