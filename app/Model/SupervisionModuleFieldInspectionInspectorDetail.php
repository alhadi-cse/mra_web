<?php

App::uses('AppModel', 'Model');

class SupervisionModuleFieldInspectionInspectorDetail extends AppModel {

    public $belongsTo = array(
        'SupervisionModuleAssignedInspectorApprovalDetail' => array(
            'className' => 'SupervisionModuleAssignedInspectorApprovalDetail',
            'foreignKey' => 'inspection_schedule_id'
        ),
        'AdminModuleUserProfile' => array(
            'className' => 'AdminModuleUserProfile',
            'foreignKey' => false,
            'conditions' => array('AdminModuleUserProfile.user_id = SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id')
        ),
        'SupervisionModuleBasicInformation' => array(
            'className' => 'SupervisionModuleBasicInformation',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id = SupervisionModuleBasicInformation.id')
        ),
        'SupervisionModuleOrgSelectionDetail' => array(
            'className' => 'SupervisionModuleOrgSelectionDetail',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleBasicInformation.supervision_case_id = SupervisionModuleOrgSelectionDetail.id', 'SupervisionModuleOrgSelectionDetail.is_running_case' => 1)
        ),
        'LookupSupervisionCategory' => array(
            'className' => 'LookupSupervisionCategory',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleOrgSelectionDetail.supervision_category_id = LookupSupervisionCategory.id')
        ),
        'BasicModuleBasicInformation' => array(
            'className' => 'BasicModuleBasicInformation',
            'fields' => 'id, license_no, full_name_of_org, short_name_of_org, name_of_org',
            'foreignKey' => false,
            'conditions' => array('SupervisionModuleBasicInformation.org_id = BasicModuleBasicInformation.id')
        ),
        'BasicModuleBranchInfo' => array(
            'className' => 'BasicModuleBranchInfo',
            'fields' => 'office_type_id, branch_name, branch_code, mailing_address, district_id, email_address, phone_no, mobile_no, fax, branch_with_address, contract_info',
            'foreignKey' => false,
            'conditions' => array('BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id', 'BasicModuleBranchInfo.office_type_id' => 1)
        ),
        'LookupAdminBoundaryDistrict' => array(
            'className' => 'LookupAdminBoundaryDistrict',
            'foreignKey' => false,
            'conditions' => array('BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id')
        )
    );
    public $virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>"), AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office)');

    //public $virtualFields = array('name_with_designation_and_dept' => 'GROUP_CONCAT(CONCAT_WS(", ", CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>), AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office) SEPARATOR "<br /> ")');
}
