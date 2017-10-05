<?php

App::uses('AppModel', 'Model');

class BasicModuleLicensedOrgWorkingArea extends AppModel {

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
    
    public $virtualFields = array('total_based_groups' => 'no_of_male_based_groups + no_of_female_based_groups',
        'total_members' => 'no_of_male_members + no_of_female_members',
        'total_borrowers' => 'no_of_male_borrowers + no_of_female_borrowers');

}
