<?php

App::uses('AppModel', 'Model');

class SupervisionModuleOrgSelectionDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => 'org_id'
        ),
        'LookupSupervisionCategory' => array(
            'className' => 'LookupSupervisionCategory',
            'foreignKey' => 'supervision_category_id'
        ),
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            'foreignKey' => false,
            'conditions' => array('BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id', 'BasicModuleBranchInfo.office_type_id' => 1)
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => false,
            'conditions' => array('BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id')
        )
    );

}
