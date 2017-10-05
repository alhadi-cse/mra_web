<?php

App::uses('AppModel', 'Model');

class LicenseModuleFieldInspectionInspectorDetail extends AppModel {

    public $belongsTo = array(
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, full_name_of_org, short_name_of_org, form_serial_no, license_no',
            'foreignKey' => 'org_id'
        ),
        'AdminModuleUser' => array(
            'className' => 'AdminModuleUser',
            'foreignKey' => 'inspector_user_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => 'AdminModuleUserProfile.user_id = LicenseModuleFieldInspectionInspectorDetail.inspector_user_id'
        ),
        'LookupLicenseInspectionType' => array(
            'className' => 'LookupLicenseInspectionType',
            'foreignKey' => 'inspection_type_id'
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => 'district_id'
        ),
        'LookupLicenseAdminApprovalStatus' => array(
            'className' => 'LookupLicenseAdminApprovalStatus',
            'foreignKey' => false,
            'conditions' => 'LookupLicenseAdminApprovalStatus.approval_status_id = LicenseModuleFieldInspectionInspectorDetail.is_approved'
        )
    );
    public $virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", CONCAT_WS("", "<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user), AdminModuleUserProfile.div_name_in_office)');

    //public $virtualFields = array('name_with_designation_and_dept' => 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office)');
	//public $virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office)');

}
