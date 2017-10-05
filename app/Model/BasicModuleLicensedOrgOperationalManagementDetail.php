<?php

App::uses('AppModel', 'Model');

class BasicModuleLicensedOrgOperationalManagementDetail extends AppModel {

    public $belongsTo = array(
        'AdminModulePeriodList' => array(
            'className' => 'AdminModulePeriodList',
            'foreignKey' => 'period_id'
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        )
    );
    
    public $virtualFields = array(
        'total_gb_member' => 'no_of_male_gb_member + no_of_female_gb_member',
        'total_eb_member' => 'no_of_male_eb_member + no_of_female_eb_member',
        'total_at_branch_in_mc_prog' => 'no_of_male_at_branch_in_mc_prog + no_of_female_at_branch_in_mc_prog',
        'total_at_regional_office_in_mc_prog' => 'no_of_male_at_regional_office_in_mc_prog + no_of_female_at_regional_office_in_mc_prog',
        'total_at_head_office_in_mc_prog' => 'no_of_male_at_head_office_in_mc_prog + no_of_female_at_head_office_in_mc_prog',
        'total_at_branch_in_org' => 'no_of_male_at_branch_in_org + no_of_female_at_branch_in_org',
        'total_at_regional_office_in_org' => 'no_of_male_at_regional_office_in_org + no_of_female_at_regional_office_in_org',
        'total_at_head_office_in_org' => 'no_of_male_at_head_office_in_org + no_of_female_at_head_office_in_org');
}
