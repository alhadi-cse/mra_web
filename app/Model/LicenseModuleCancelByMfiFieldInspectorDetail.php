<?php

App::uses('AppModel', 'Model');

class LicenseModuleCancelByMfiFieldInspectorDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'foreignKey' => 'org_id'
        ),
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'inspector_user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = LicenseModuleCancelByMfiFieldInspectorDetail.inspector_user_id'
        ),
        'AdminModuleUserGroupDistribution' => array(
            'className' => 'AdminModuleUserGroupDistribution',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserGroupDistribution.user_id = AdminModuleUser.id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => 'district_id'
        )
    );
    public $virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", CONCAT_WS("", "<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user), AdminModuleUserProfile.div_name_in_office)');
}