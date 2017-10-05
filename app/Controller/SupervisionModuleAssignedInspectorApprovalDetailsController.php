<?php

App::uses('AppController', 'Controller');

class SupervisionModuleAssignedInspectorApprovalDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view() {
        $approval_states = $this->request->query('approval_states');
        if (!empty($approval_states))
            $this->Session->write('Current.ApprovalStates', $approval_states);
        else
            $approval_states = $this->Session->read('Current.ApprovalStates');

        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $user_group_ids = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');

        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);

            if (count($thisStateIds) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $isAdminApproval = in_array(10, $user_group_ids);

        $inspector_group_id = $this->request->query('inspector_group_id');
        if (empty($inspector_group_id))
            $inspector_group_id = $this->Session->read('Inspector.GroupId');
        else
            $this->Session->write('Inspector.GroupId', $inspector_group_id);

        $office_type_id = $this->request->query('office_type_id');

        if (!empty($office_type_id))
            $this->Session->write('Address.TypeId', $office_type_id);
        else
            $office_type_id = $this->Session->read('Address.TypeId');

        $parameter = array('this_state_ids' => $this_state_ids, 'inspector_group_id' => $inspector_group_id, 'office_type_id' => $office_type_id);
        $redirect_url = array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'view', '?' => $parameter);
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $options['limit'] = 10;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
        $options['group'] = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id');

        $options['fields'] = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id',
            'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id', 'SupervisionModuleAssignedInspectorApprovalDetail.inspection_date',
            'SupervisionModuleBasicInformation.id', 'LookupSupervisionCategory.case_categories',
            'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'BasicModuleBranchInfo.id', 'LookupAdminBoundaryDistrict.district_name');
        $operator = !empty($approvalStates[0]) ? ">=" : "=";
        $not_approved_condition = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1,
            'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0],
            "OR" => array("SupervisionModuleAssignedInspectorApprovalDetail.is_approved" => 0,
                "SupervisionModuleAssignedInspectorApprovalDetail.is_approved $operator" => (int) $approvalStates[0]),
            "SupervisionModuleAssignedInspectorApprovalDetail.is_completed" => 0);

        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');

//        $this->paginate = $options;
//        $this->paginate['conditions'] = $not_approved_condition;
//        $this->Paginator->settings = $this->paginate;

        $options['conditions'] = $not_approved_condition;

        $this->Paginator->settings = $options;
        $values_not_approved = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

        $fields = array('SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', 'SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept', 'SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id');
        $not_approved_inspectors_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('conditions' => $not_approved_condition, 'fields' => $fields, 'recursive' => 0));

        $options['group'] = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id');
        $options['fields'] = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id',
            'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id', 'SupervisionModuleAssignedInspectorApprovalDetail.inspection_date',
            'SupervisionModuleBasicInformation.id', 'LookupSupervisionCategory.case_categories',
            'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'BasicModuleBranchInfo.id', 'LookupAdminBoundaryDistrict.district_name');

        $approved_condition = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1, 'SupervisionModuleBasicInformation.supervision_state_id' => $isAdminApproval ? (int) $thisStateIds[1] : (int) $thisStateIds[0],
            'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => (int) $approvalStates[1],
            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
        $options['conditions'] = $approved_condition;

//        $this->paginate = $options;
//        $this->paginate['conditions'] = $approved_condition;
//        $this->Paginator->settings = $this->paginate;

        $this->Paginator->settings = $options;
        $values_approved = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

        $fields = array('SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', 'SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept', 'SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id');
        $approved_inspectors_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('conditions' => $approved_condition, 'fields' => $fields, 'recursive' => 0));

        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));

        $this->set(compact('values_not_approved', 'values_approved', 'not_approved_inspectors_list', 'approved_inspectors_list', 'approvalType'));
    }

    public function preview($supervision_basic_id = null, $branch_id = null) {
        $this->SupervisionModuleAssignedInspectorApprovalDetail->bindModel(
                array('belongsTo' => array(
                        'SupervisionModuleBasicInformation' => array(
                            'foreignKey' => 'supervision_basic_id'
                        )
                    )
                )
        );

        $allDetails = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        $case_id = $allDetails['SupervisionModuleBasicInformation']['supervision_case_id'];
        $this->set(compact('supervision_basic_id', 'branch_id', 'case_id'));
    }

    public function approval_details($supervision_basic_id = null, $case_id = null) {
        $this->SupervisionModuleAssignedInspectorApprovalDetail->bindModel(
                array('belongsTo' => array(
                        'SupervisionModuleBasicInformation' => array(
                            'foreignKey' => 'supervision_basic_id'
                        )
                    )
                )
        );

        $allDetails = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));

        $fields = array('BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'LookupSupervisionCategory.case_categories', 'SupervisionModuleOrgSelectionDetail.supervision_reason');
        $this->loadModel('SupervisionModuleOrgSelectionDetail');
        $caseDetails = $this->SupervisionModuleOrgSelectionDetail->find('first', array('fields' => $fields, 'conditions' => array('SupervisionModuleOrgSelectionDetail.id' => $case_id), 'recursive' => 0));
        $this->set(compact('allDetails', 'caseDetails', 'case_id'));
    }

    public function approve($supervision_basic_id = null) {
        $CurrentApprovalStates = $this->Session->read('Current.ApprovalStates');

        if (!empty($CurrentApprovalStates)) {
            $approvalStates = explode('_', $CurrentApprovalStates);
            if (count($approvalStates) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid approval states Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid approval states Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        //Change states
        $isAdminApproval = in_array(10, $user_group_ids);
        if (!$isAdminApproval && !empty($approvalStates) && $approvalStates[1] == 1) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'For Final Administrative Approval, Please Login as EVC User Group !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));
        $this->loadModel('SupervisionModuleBasicInformation');
        $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
//        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';
//        $org_id = $org_infos['BasicModuleBasicInformation']['id'];
        $orgName = $org_infos['BasicModuleBasicInformation']['short_name_of_org'];
        $orgFullName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'];
        $license_no = $org_infos['BasicModuleBasicInformation']['license_no'];

        $orgName = $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
        if (!empty($license_no))
            $orgName = "$orgName (License No.: $license_no)";

        $org_id = $org_infos['BasicModuleBasicInformation']['id'];
        $this->loadModel('BasicModuleBranchInfo');
        $branch_infos = $this->BasicModuleBranchInfo->find('first', array('fields' => array('BasicModuleBranchInfo.id'), 'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => 1), 'recursive' => -1));
        $branch_id = $branch_infos['BasicModuleBranchInfo']['id'];
        switch ($approvalStates[1]) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $approval_title = 'Approval';
                $btn_title = 'Approve';
                $success_msg = 'Inspection Schedule has been approved successfully';
                $error_msg = 'Approval failed';
                break;
            default:
                break;
        }
        $this->set(compact('supervision_basic_id', 'orgName', 'branch_id', 'this_state_ids', 'approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg'));

        if ($this->request->is('post')) {
            try {
                $tier_wise_comments = $this->replace_escape_chars($this->request->data['SupervisionModuleAssignedInspectorApprovalDetail']['tier_wise_comments']);
                $tier_wise_comments = "'$tier_wise_comments'";
                $data_to_update = array('is_approved' => $approvalStates[1]);

                switch ($approvalStates[1]) {
                    case 1:
                        $data_to_update['comments_of_evc'] = $tier_wise_comments;
                        break;
                    case 2:
                        $data_to_update['comments_of_director'] = $tier_wise_comments;
                        break;
                    case 3:
                        $data_to_update['comments_of_sdd'] = $tier_wise_comments;
                        break;
                    case 4:
                        $data_to_update['comments_of_dd'] = $tier_wise_comments;
                        break;
                    case 5:
                        $data_to_update['comments_of_sad'] = $tier_wise_comments;
                        break;
                    case 6:
                        $data_to_update['comments_of_ad'] = $tier_wise_comments;
                        break;
                    default:
                        break;
                }

                $conditions = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                    'OR' => array('SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 0,
                        'SupervisionModuleAssignedInspectorApprovalDetail.is_approved >=' => $approvalStates[0]),
                    'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);

                $done = $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll($data_to_update, $conditions);
                if ($done) {
                    if ($isAdminApproval) {
                        $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]));
                        $org_state_history = array(
                            'supervision_basic_id' => $supervision_basic_id,
                            'supervision_state_id' => $thisStateIds[1],
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('SupervisionModuleStateHistory');
                        $this->SupervisionModuleStateHistory->create();
                        $this->SupervisionModuleStateHistory->save($org_state_history);
                    }

                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                    if (empty($redirect_url))
                        $redirect_url = array('action' => 'view');
                    $this->redirect($redirect_url);
                    return;
                }
            } catch (Exception $ex) {
                $done = false;
            }
        }
    }

    public function cancel_approval($supervision_basic_id = null) {
        $CurrentApprovalStates = $this->Session->read('Current.ApprovalStates');
        if (!empty($CurrentApprovalStates)) {
            $approvalStates = explode('_', $CurrentApprovalStates);

            if (count($approvalStates) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid approval states Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid approval states Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));
        $this->loadModel('SupervisionModuleBasicInformation');
        $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';
        $org_id = $org_infos['BasicModuleBasicInformation']['id'];
        $this->loadModel('BasicModuleBranchInfo');
        $branch_infos = $this->BasicModuleBranchInfo->find('first', array('fields' => array('BasicModuleBranchInfo.id'), 'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => 1), 'recursive' => -1));
        $branch_id = $branch_infos['BasicModuleBranchInfo']['id'];
        switch ($approvalStates[1]) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $approval_title = 'Cancellation of Approval';
                $btn_title = 'Cancel Approval';
                $success_msg = 'Approval of Inspection Schedule has been cancelled successfully';
                $error_msg = 'Cancellation of Approval failed';
                break;
            default:
                break;
        }
        $this->set(compact('supervision_basic_id', 'orgName', 'branch_id', 'this_state_ids', 'approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg'));

        if ($this->request->is('post')) {

            $tier_wise_comments = $this->replace_escape_chars($this->request->data['SupervisionModuleAssignedInspectorApprovalDetail']['tier_wise_comments']);
            $tier_wise_comments = "'" . $tier_wise_comments . "'";
            $data_to_update = array(
                'is_approved' => 0
            );
            switch ($approvalStates[1]) {
                case 1:
                    $data_to_update['comments_of_evc'] = $tier_wise_comments;
                    break;
                case 2:
                    $data_to_update['comments_of_director'] = $tier_wise_comments;
                    break;
                case 3:
                    $data_to_update['comments_of_sdd'] = $tier_wise_comments;
                    break;
                case 4:
                    $data_to_update['comments_of_dd'] = $tier_wise_comments;
                    break;
                case 5:
                    $data_to_update['comments_of_sad'] = $tier_wise_comments;
                    break;
                case 6:
                    $data_to_update['comments_of_ad'] = $tier_wise_comments;
                    break;
                default:
                    break;
            }

            $conditions = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                'OR' => array('SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 0, 'SupervisionModuleAssignedInspectorApprovalDetail.is_approved >=' => $approvalStates[1]),
                'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
            $done = $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll($data_to_update, $conditions);

            if ($done) {
                //can change states
                $isAdminApproval = in_array(10, $user_group_ids);

                if ($isAdminApproval) {
                    $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]));

                    $org_state_history = array(
                        'supervision_basic_id' => $supervision_basic_id,
                        'supervision_state_id' => $thisStateIds[0],
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('SupervisionModuleStateHistory');
                    $this->SupervisionModuleStateHistory->create();
                    $this->SupervisionModuleStateHistory->save($org_state_history);
                }

                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view');
                $this->redirect($redirect_url);
                return;
            }
        }
    }

    public function approve_all() {
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('AdminModuleUserGroup');
        $approvalType = $this->AdminModuleUserGroup->field('group_name', array('id' => $user_group_ids));

        //Change States
        $isAdminApproval = in_array(10, $user_group_ids);
        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_states = $this->request->query('approval_states');
        if (empty($approval_states))
            $approval_states = $this->Session->read('Current.ApprovalStates');

        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
            if (count($approvalStates) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid approval states Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid approval states Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
//        $options['joins'] = array(
//            array('table' => 'supervision_module_assigned_inspector_approval_details',
//                'alias' => 'SupervisionModuleAssignedInspectorApprovalDetail',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id = SupervisionModuleAssignedInspectorApprovalDetail.id'
//                )
//            ),
//            array('table' => 'admin_module_user_profiles',
//                'alias' => 'AdminModuleUserProfile',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'AdminModuleUserProfile.user_id = SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id'
//                )
//            ),
//            array('table' => 'supervision_module_basic_informations',
//                'alias' => 'SupervisionModuleBasicInformation',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id = SupervisionModuleBasicInformation.id'
//                )
//            ),
//            array('table' => 'supervision_module_org_selection_details',
//                'alias' => 'SupervisionModuleOrgSelectionDetail',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleBasicInformation.supervision_case_id = SupervisionModuleOrgSelectionDetail.id',
//                    'SupervisionModuleOrgSelectionDetail.is_running_case' => 1
//                )
//            ),
//            array('table' => 'lookup_supervision_categories',
//                'alias' => 'LookupSupervisionCategory',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleOrgSelectionDetail.supervision_category_id = LookupSupervisionCategory.id'
//                )
//            ),
//            array('table' => 'basic_module_basic_informations',
//                'alias' => 'BasicModuleBasicInformation',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleBasicInformation.org_id = BasicModuleBasicInformation.id'
//                )
//            ),
//            array('table' => 'basic_module_branch_infos',
//                'alias' => 'BasicModuleBranchInfo',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id',
//                    'BasicModuleBranchInfo.office_type_id' => 1
//                )
//            ),
//            array('table' => 'lookup_admin_boundary_districts',
//                'alias' => 'LookupAdminBoundaryDistrict',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id'
//                )
//            )
//        );
//        $options['fields'] = array('SupervisionModuleOrgSelectionDetail.is_running_case', 'SupervisionModuleBasicInformation.id', 'SupervisionModuleFieldInspectionInspectorDetail.*', 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) AS name_with_designation_and_dept');
//        $options['fields'] = array('SupervisionModuleOrgSelectionDetail.is_running_case', 'SupervisionModuleBasicInformation.id', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
//            'SupervisionModuleAssignedInspectorApprovalDetail.inspection_date', 'LookupAdminBoundaryDistrict.district_name', 'SupervisionModuleFieldInspectionInspectorDetail.*',
//            'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) AS name_with_designation_and_dept');

        $options['fields'] = array('SupervisionModuleOrgSelectionDetail.is_running_case', 'SupervisionModuleBasicInformation.id', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'SupervisionModuleAssignedInspectorApprovalDetail.inspection_date', 'LookupAdminBoundaryDistrict.district_name', 'SupervisionModuleFieldInspectionInspectorDetail.*');

        $operator = !empty($approvalStates[0]) ? ">=" : "=";
        $not_approved_condition = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0],
            "OR" => array("SupervisionModuleAssignedInspectorApprovalDetail.is_approved" => 0,
                "SupervisionModuleAssignedInspectorApprovalDetail.is_approved $operator" => (int) $approvalStates[0]),
            "SupervisionModuleAssignedInspectorApprovalDetail.is_completed" => 0);
        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');

        $this->paginate = $options;
        $this->paginate['conditions'] = $not_approved_condition;
        $this->Paginator->settings = $this->paginate;
        $not_approved_inspectors_infos = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

        $options['fields'] = array('SupervisionModuleOrgSelectionDetail.*', 'LookupAdminBoundaryDistrict.*', 'LookupSupervisionCategory.*', 'SupervisionModuleBasicInformation.*', 'BasicModuleBasicInformation.*', 'BasicModuleBranchInfo.id', 'SupervisionModuleAssignedInspectorApprovalDetail.*');
        $options['group'] = array('SupervisionModuleBasicInformation.id');
        $this->paginate = $options;
        $this->Paginator->settings = $this->paginate;
        $schedule_and_approval_infos = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

        $not_approved_inspectors_infos_combined = Hash::combine($not_approved_inspectors_infos, '{n}.SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', '{n}.SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept', '{n}.SupervisionModuleBasicInformation.id');
        $formatted_not_approved_inspectors_infos = array();
        $values_not_approved = array();
        foreach ($not_approved_inspectors_infos_combined as $key => $not_approved_inspectors_infos) {
            $inspectors_name = "";
            $i = 0;
            foreach ($not_approved_inspectors_infos as $inspectors_info) {
                if (count($not_approved_inspectors_infos) - 1 == $i) {
                    $inspectors_name = $inspectors_info . ";  " . $inspectors_name;
                } else {
                    $inspectors_name = $inspectors_name . $inspectors_info;
                }
                $i++;
            }
            $formatted_not_approved_inspectors_infos['name_with_designation_and_dept'] = $inspectors_name;
            foreach ($schedule_and_approval_infos as $schedule_info) {
                if ($schedule_info['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id'] == $key) {
                    $values_not_approved [] = array_merge($schedule_info, $formatted_not_approved_inspectors_infos);
                }
            }
        }
        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));
        $this->set(compact('values_not_approved', 'approvalType', 'isAdminApproval'));

        if ($this->request->is('post')) {
            try {
                if (!$isAdminApproval && !empty($approvalStates) && $approvalStates[1] == 1) {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'For Final Administrative Approval, Please Login as EVC User Group !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
                $newData = $this->request->data['SupervisionModuleAssignedInspectorApprovalDetailAll'];

                if (!empty($newData)) {
                    $supervision_basic_ids = Hash::extract($newData, '{n}[is_approved=1].supervision_basic_id');
                    if (empty($supervision_basic_ids)) {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => "Select at least one organization's information to approve !"
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                    foreach ($newData as $data) {
                        $supervision_basic_id = $data['supervision_basic_id'];
                        if (in_array($supervision_basic_id, $supervision_basic_ids)) {
                            $tier_wise_comments = $this->replace_escape_chars($data['tier_wise_comments']);
                            $tier_wise_comments = "'" . $tier_wise_comments . "'";
                            $data_to_update = array(
                                'is_approved' => $approvalStates[1]
                            );
                            $conditions = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                                'OR' => array('SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 0, 'SupervisionModuleAssignedInspectorApprovalDetail.is_approved >=' => $approvalStates[0]),
                                'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
                            switch ($approvalStates[1]) {
                                case 1:
                                    $data_to_update['comments_of_evc'] = $tier_wise_comments;
                                    break;
                                case 2:
                                    $data_to_update['comments_of_director'] = $tier_wise_comments;
                                    break;
                                case 3:
                                    $data_to_update['comments_of_sdd'] = $tier_wise_comments;
                                    break;
                                case 4:
                                    $data_to_update['comments_of_dd'] = $tier_wise_comments;
                                    break;
                                case 5:
                                    $data_to_update['comments_of_sad'] = $tier_wise_comments;
                                    break;
                                case 6:
                                    $data_to_update['comments_of_ad'] = $tier_wise_comments;
                                    break;
                                default:
                                    break;
                            }
                            $done = $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll($data_to_update, $conditions);
                        }
                    }
                    if ($done) {
                        if ($isAdminApproval) {
                            $this->loadModel('SupervisionModuleBasicInformation');
                            $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_ids));

                            $all_org_state_history = array();
                            foreach ($supervision_basic_ids as $supervision_basic_id) {
                                if (!empty($supervision_basic_id)) {
                                    $org_state_history = array(
                                        'supervision_basic_id' => $supervision_basic_id,
                                        'supervision_state_id' => $thisStateIds[1],
                                        'date_of_state_update' => date('Y-m-d'),
                                        'date_of_starting' => date('Y-m-d'),
                                        'user_name' => $this->Session->read('User.Name'));

                                    $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                                }
                            }

                            if (!empty($all_org_state_history)) {
                                $this->loadModel('SupervisionModuleStateHistory');
                                $this->SupervisionModuleStateHistory->create();
                                $this->SupervisionModuleStateHistory->saveAll($all_org_state_history);
                            }
                        }

                        $redirect_url = $this->Session->read('Current.RedirectUrl');
                        if (empty($redirect_url))
                            $redirect_url = array('action' => 'view');
                        $this->redirect($redirect_url);
                        return;
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));
            }
        }
    }

    public function cancel_all() {
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('AdminModuleUserGroup');
        $approvalType = $this->AdminModuleUserGroup->field('group_name', array('id' => $user_group_ids));

        //change states
        $isAdminApproval = in_array(10, $user_group_ids);

        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_states = $this->request->query('approval_states');
        if (empty($approval_states))
            $approval_states = $this->Session->read('Current.ApprovalStates');

        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
            if (count($approvalStates) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid approval states Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid approval states Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
//        $options['joins'] = array(
//            array('table' => 'supervision_module_assigned_inspector_approval_details',
//                'alias' => 'SupervisionModuleAssignedInspectorApprovalDetail',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id = SupervisionModuleAssignedInspectorApprovalDetail.id'
//                )
//            ),
//            array('table' => 'admin_module_user_profiles',
//                'alias' => 'AdminModuleUserProfile',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'AdminModuleUserProfile.user_id = SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id'
//                )
//            ),
//            array('table' => 'supervision_module_basic_informations',
//                'alias' => 'SupervisionModuleBasicInformation',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id = SupervisionModuleBasicInformation.id'
//                )
//            ),
//            array('table' => 'supervision_module_org_selection_details',
//                'alias' => 'SupervisionModuleOrgSelectionDetail',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleBasicInformation.supervision_case_id = SupervisionModuleOrgSelectionDetail.id',
//                    'SupervisionModuleOrgSelectionDetail.is_running_case' => 1
//                )
//            ),
//            array('table' => 'lookup_supervision_categories',
//                'alias' => 'LookupSupervisionCategory',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleOrgSelectionDetail.supervision_category_id = LookupSupervisionCategory.id'
//                )
//            ),
//            array('table' => 'basic_module_basic_informations',
//                'alias' => 'BasicModuleBasicInformation',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'SupervisionModuleBasicInformation.org_id = BasicModuleBasicInformation.id'
//                )
//            ),
//            array('table' => 'basic_module_branch_infos',
//                'alias' => 'BasicModuleBranchInfo',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id',
//                    'BasicModuleBranchInfo.office_type_id' => 1
//                )
//            ),
//            array('table' => 'lookup_admin_boundary_districts',
//                'alias' => 'LookupAdminBoundaryDistrict',
//                'type' => 'LEFT',
//                'conditions' => array(
//                    'BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id'
//                )
//            )
//        );
        //$options['fields'] = array('SupervisionModuleBasicInformation.id', 'SupervisionModuleFieldInspectionInspectorDetail.*', 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) AS name_with_designation_and_dept');

        $options['fields'] = array('SupervisionModuleBasicInformation.id', 'SupervisionModuleFieldInspectionInspectorDetail.*');
        $approved_condition = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1, 'SupervisionModuleBasicInformation.supervision_state_id' => $isAdminApproval ? (int) $thisStateIds[1] : (int) $thisStateIds[0],
            'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => (int) $approvalStates[1],
            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
        $this->paginate = $options;
        $this->paginate['conditions'] = $approved_condition;
        $this->Paginator->settings = $this->paginate;
        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
        $approved_inspectors_infos = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

        $approved_inspectors_infos_combined = Hash::combine($approved_inspectors_infos, '{n}.SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', '{n}.SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept', '{n}.SupervisionModuleBasicInformation.id');
        $formated_approved_inspectors_infos = array();
        $values_approved = array();

        foreach ($approved_inspectors_infos_combined as $key => $approved_inspectors_infos) {
            $inspectors_name = "";
            $i = 0;
            foreach ($approved_inspectors_infos as $inspectors_info) {
                if (count($approved_inspectors_infos) - 1 == $i) {
                    $inspectors_name = $inspectors_info . ";  " . $inspectors_name;
                } else {
                    $inspectors_name = $inspectors_name . $inspectors_info;
                }
                $i++;
            }
            $formated_approved_inspectors_infos['name_with_designation_and_dept'] = $inspectors_name;

            $options['fields'] = array('SupervisionModuleOrgSelectionDetail.*', 'LookupAdminBoundaryDistrict.*', 'LookupSupervisionCategory.*', 'SupervisionModuleBasicInformation.*', 'BasicModuleBasicInformation.*', 'BasicModuleBranchInfo.id', 'SupervisionModuleAssignedInspectorApprovalDetail.*');
            $options['group'] = array('SupervisionModuleBasicInformation.id');
            $this->paginate = $options;
            $this->Paginator->settings = $this->paginate;
            $schedule_and_approval_infos = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

            foreach ($schedule_and_approval_infos as $schedule_info) {
                if ($schedule_info['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id'] == $key) {
                    $values_approved [] = array_merge($schedule_info, $formated_approved_inspectors_infos);
                }
            }
        }
        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));
        $this->set(compact('values_approved', 'approvalType', 'isAdminApproval'));

        if ($this->request->is('post')) {
            try {
                if (!$isAdminApproval && !empty($approvalStates) && $approvalStates[1] == 1) {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'For Final Administrative Approval Cancellation, Please Login as EVC User Group !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
                $newData = $this->request->data['SupervisionModuleAssignedInspectorApprovalDetailAll'];

                if (!empty($newData)) {
                    $supervision_basic_ids = Hash::extract($newData, '{n}[is_approved=1].supervision_basic_id');
                    if (empty($supervision_basic_ids)) {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => "Select at least one organization's information to cancel approval !"
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                    foreach ($newData as $data) {
                        $supervision_basic_id = $data['supervision_basic_id'];
                        if (in_array($supervision_basic_id, $supervision_basic_ids)) {
                            $tier_wise_comments = $this->replace_escape_chars($data['tier_wise_comments']);
                            $tier_wise_comments = "'" . $tier_wise_comments . "'";
                            $data_to_update = array(
                                'is_approved' => 0
                            );
                            $conditions = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                                'OR' => array('SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 0, 'SupervisionModuleAssignedInspectorApprovalDetail.is_approved <=' => $approvalStates[1]),
                                'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
                            switch ($approvalStates[1]) {
                                case 1:
                                    $data_to_update['comments_of_evc'] = $tier_wise_comments;
                                    break;
                                case 2:
                                    $data_to_update['comments_of_director'] = $tier_wise_comments;
                                    break;
                                case 3:
                                    $data_to_update['comments_of_sdd'] = $tier_wise_comments;
                                    break;
                                case 4:
                                    $data_to_update['comments_of_dd'] = $tier_wise_comments;
                                    break;
                                case 5:
                                    $data_to_update['comments_of_sad'] = $tier_wise_comments;
                                    break;
                                case 6:
                                    $data_to_update['comments_of_ad'] = $tier_wise_comments;
                                    break;
                                default:
                                    break;
                            }
                            $done = $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll($data_to_update, $conditions);
                        }
                        if ($done) {
                            if ($isAdminApproval) {
                                $this->loadModel('SupervisionModuleBasicInformation');
                                $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_ids));

                                $all_org_state_history = array();
                                foreach ($supervision_basic_ids as $supervision_basic_id) {
                                    if (!empty($supervision_basic_id)) {
                                        $org_state_history = array(
                                            'supervision_basic_id' => $supervision_basic_id,
                                            'supervision_state_id' => $thisStateIds[0],
                                            'date_of_state_update' => date('Y-m-d'),
                                            'date_of_starting' => date('Y-m-d'),
                                            'user_name' => $this->Session->read('User.Name'));

                                        $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                                    }
                                }

                                if (!empty($all_org_state_history)) {
                                    $this->loadModel('SupervisionModuleStateHistory');
                                    $this->SupervisionModuleStateHistory->create();
                                    $this->SupervisionModuleStateHistory->saveAll($all_org_state_history);
                                }
                            }

                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view');
                            $this->redirect($redirect_url);
                            return;
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));
            }
        }
    }

    public function approve_edit_all() {
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
//        $user_group_id = $this->Session->read('User.GroupId');
//        if (empty($user_group_id) || !($user_group_id == 1 || $user_group_id == $committee_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $isAdminApproval = in_array(10, $user_group_ids);

        $this->loadModel('AdminModuleUserGroup');
        $approvalType = $this->AdminModuleUserGroup->field('group_name', array('id' => $user_group_id));

        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 3) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_states = $this->request->query('approval_states');
        if (empty($approval_states))
            $approval_states = $this->Session->read('Current.ApprovalStates');

        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
            if (count($approvalStates) < 2) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid approval states Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid approval states Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('approvalType', 'isAdminApproval'));

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['SupervisionModuleAssignedInspectorApprovalDetailAll'];

                if (!empty($newData)) {
                    $supervision_basic_ids = Hash::extract($newData, '{n}[is_approved=0].supervision_basic_id');

                    if (!empty($supervision_basic_ids)) {

                        $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');

                        $conditions = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_ids,
                            'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => $approvalStates[1],
                            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);

                        $done = $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll(array('OR' => array('SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 0, 'SupervisionModuleAssignedInspectorApprovalDetail.is_approved >=' => $approvalStates[0])), $conditions);
                        if ($done) {
                            if ($isAdminApproval) {
                                $this->loadModel('SupervisionModuleBasicInformation');
                                $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_ids));

                                $all_org_state_history = array();
                                foreach ($supervision_basic_ids as $supervision_basic_id) {
                                    if (!empty($supervision_basic_id)) {
                                        $org_state_history = array(
                                            'supervision_basic_id' => $supervision_basic_id,
                                            'supervision_state_id' => $thisStateIds[1],
                                            'date_of_state_update' => date('Y-m-d'),
                                            'date_of_starting' => date('Y-m-d'),
                                            'user_name' => $this->Session->read('User.Name'));

                                        $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                                    }
                                }
                                if (!empty($all_org_state_history)) {
                                    $this->loadModel('SupervisionModuleStateHistory');
                                    $this->SupervisionModuleStateHistory->create();
                                    $this->SupervisionModuleStateHistory->saveAll($all_org_state_history);
                                }
                            }

                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view');
                            $this->redirect($redirect_url);
                            return;
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_edit_all'));
            }
        }

        $condition_approved = array('SupervisionModuleBasicInformation.supervision_state_id' => $isAdminApproval ? $thisStateIds[2] : $thisStateIds[1],
            'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => $approvalStates[1],
            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id', 'SupervisionModuleAssignedInspectorApprovalDetail.inspection_date',
            'LookupLicenseAdminApprovalStatus.approval_admin_level',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
        $values_approved = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('all', array('fields' => $fields, 'conditions' => $condition_approved, 'group' => 'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id'));


        $this->set(compact('values_approved'));
    }

    public function re_approve($supervision_basic_id = null) {
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {
            $newData = $this->request->data['SupervisionModuleAssignedInspectorApprovalDetail'];
            if (!empty($newData)) {
                $this->SupervisionModuleAssignedInspectorApprovalDetail->id = $approvalDetails['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
                if ($this->SupervisionModuleAssignedInspectorApprovalDetail->save($newData)) {
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                    if (empty($redirect_url))
                        $redirect_url = array('action' => 'view');
                    $this->redirect($redirect_url);
                }
            }
            return;
        }

        $approvalDetails = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->SupervisionModuleAssignedInspectorApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

    public function delete($supervision_basic_id = null) {

        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 3) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (!empty($supervision_basic_id)) {
            if ($this->SupervisionModuleAssignedInspectorApprovalDetail->deleteAll(array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id), false)) {
                $condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[2]);

                $this->loadModel('SupervisionModuleBasicInformation');
                $this->SupervisionModuleBasicInformation->updateAll(array('licensing_state_id' => $thisStateIds[1]), $condition);

                $org_state_history = array(
                    'supervision_basic_id' => $supervision_basic_id,
                    'supervision_state_id' => $thisStateIds[1],
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('SupervisionModuleStateHistory');
                $this->SupervisionModuleStateHistory->create();
                $this->SupervisionModuleStateHistory->save($org_state_history);

                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view');
                $this->redirect($redirect_url);
            }
        }
    }

}
