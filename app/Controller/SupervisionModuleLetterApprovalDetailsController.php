<?php

App::uses('AppController', 'Controller');

class SupervisionModuleLetterApprovalDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    //public $paginate = array();
    var $uses = array('SupervisionModuleBasicInformation', 'SupervisionModulePrepareLetterDetail');

    public function view() {
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

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
        $approval_states = $this->request->query('approval_states');

        if (!empty($approval_states))
            $this->Session->write('Current.ApprovalStates', $approval_states);
        else
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

        $parameter = array('this_state_ids' => $this_state_ids, 'committee_group_id' => $committee_group_id, 'approval_states' => $approval_states);
        $redirect_url = array('controller' => 'SupervisionModuleLetterApprovalDetails', 'action' => 'view', '?' => $parameter);
        $this->Session->write('Current.RedirectUrl', $redirect_url);

//        $this->loadModel('SupervisionModuleOrgSelectionDetail');
//        $conditions_for_running_case = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1);
//        $case_lists = $this->SupervisionModuleOrgSelectionDetail->find('list', array('fields' => array('SupervisionModuleOrgSelectionDetail.org_id', 'LookupSupervisionCategory.case_categories'), 'conditions' => $conditions_for_running_case, 'recursive' => 0));

        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));

        $operator = !empty($approvalStates[0]) ? ">=" : "=";
        $condition_not_approved = array("SupervisionModuleBasicInformation.supervision_state_id" => (int) $thisStateIds[0],
            "OR" => array("SupervisionModulePrepareLetterDetail.is_approved" => 0,
                "SupervisionModulePrepareLetterDetail.is_approved $operator" => (int) $approvalStates[0]),
            "SupervisionModulePrepareLetterDetail.is_completed" => 0);

        $isAdminApproval = in_array(10, $user_group_ids); //($user_group_ids == 10);
        $condition_approved = array('SupervisionModuleBasicInformation.supervision_state_id' => $isAdminApproval ? (int) $thisStateIds[1] : (int) $thisStateIds[0],
            'SupervisionModulePrepareLetterDetail.is_approved' => (int) $approvalStates[1],
            'SupervisionModulePrepareLetterDetail.is_completed' => 0);

        $this->loadModel('SupervisionModulePrepareLetterDetail');
        $options['limit'] = 10;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');

        //$options['fields'] = array('SupervisionModuleOrgSelectionDetail.*', 'LookupSupervisionCategory.*', 'SupervisionModuleBasicInformation.*', 'BasicModuleBasicInformation.*', 'SupervisionModulePrepareLetterDetail.*');

        $options['fields'] = array('BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleBasicInformation.id', 'LookupSupervisionCategory.case_categories', 'SupervisionModulePrepareLetterDetail.supervision_basic_id');
        
        $options['conditions'] = $condition_not_approved;
        $options['group'] = array('SupervisionModulePrepareLetterDetail.supervision_basic_id');
        $this->Paginator->settings = $options;
        $values_not_approved = $this->Paginator->paginate('SupervisionModulePrepareLetterDetail');

        $options['conditions'] = $condition_approved;
        $this->Paginator->settings = $options;
        $values_approved = $this->Paginator->paginate('SupervisionModulePrepareLetterDetail');

        //debug($this->paginate);
//        debug($condition_approved);
//        debug($options['conditions']);
//        debug($values_approved);

//        $this->loadModel('LookupLicenseAdminApprovalStatus');
//        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));

        switch ($approvalStates[1]) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $approval_title = 'Approval';
                $btn_title = 'Approve';
                $success_msg = 'Letter has been approved successfully';
                $error_msg = 'Approval failed';
                break;
            case 7:
                $btn_title = $approval_title = 'Review';
                $success_msg = 'Letter has been reviewed successfully';
                $error_msg = 'Review failed';
                break;
            case 8:
                $approval_title = "Notes";
                $btn_title = 'Add Notes';
                $success_msg = 'Notes has been added successfully';
                $error_msg = 'Notes adding failed';
                break;
            default:
                break;
        }

        $this->set(compact('approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg', 'isAdminApproval', 'values_not_approved', 'values_approved'));
        return;
    }

    public function approve($supervision_basic_id = null) {
        //$this->autoRender = false;
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

        $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        if (!empty($org_infos['BasicModuleBasicInformation'])) {
            $orgDetail = $org_infos['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        } else {
            $orgName = '';
        }

        switch ($approvalStates[1]) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $approval_title = 'Approval';
                $btn_title = 'Approve';
                $success_msg = 'Letter has been approved successfully';
                $error_msg = 'Approval failed';
                break;
            case 7:
                $btn_title = $approval_title = 'Review';
                $success_msg = 'Letter has been reviewed successfully';
                $error_msg = 'Review failed';
                break;
            case 8:
                $approval_title = "Notes";
                $btn_title = 'Add Notes';
                $success_msg = 'Notes has been added successfully';
                $error_msg = 'Notes adding failed';
                break;
            default:
                break;
        }
        $this->set(compact('supervision_basic_id', 'orgName', 'this_state_ids', 'approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg'));

        if ($this->request->is(array('post', 'put'))) {
            $isAdminApproval = in_array(10, $user_group_ids); //($user_group_ids == 10);
//            $conditions = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => (int) $supervision_basic_id,
//                    'OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0,
//                        'SupervisionModulePrepareLetterDetail.is_approved >=' => (int) $approvalStates[0]),
//                    'SupervisionModulePrepareLetterDetail.is_completed' => 0);

            $conditions = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => (int) $supervision_basic_id,
                'OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0,
                    'SupervisionModulePrepareLetterDetail.is_approved >=' => (int) $approvalStates[0]),
                'SupervisionModulePrepareLetterDetail.is_completed' => 0);

            if (!$isAdminApproval && !empty($approvalStates) && $approvalStates[1] == 1) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'For Final Administrative Approval, Please Login as EVC User Group !'
                );
                $this->set(compact('msg'));
                return;
            }
            $data_to_update = array('SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[1]);

            $tier_wise_comments_on_letters = $this->replace_escape_chars($this->request->data['SupervisionModulePrepareLetterDetail']['tier_wise_comments_on_letters']);
            $tier_wise_comments_on_letters = "'" . $tier_wise_comments_on_letters . "'";
            switch ($approvalStates[1]) {
                case 1:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_evc'] = $tier_wise_comments_on_letters;
                    break;
                case 2:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_director'] = $tier_wise_comments_on_letters;
                    break;
                case 3:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_sdd'] = $tier_wise_comments_on_letters;
                    break;
                case 4:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_dd'] = $tier_wise_comments_on_letters;
                    break;
                case 5:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_sad'] = $tier_wise_comments_on_letters;
                    break;
                case 6:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_ad'] = $tier_wise_comments_on_letters;
                    break;
                case 7:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_section'] = $tier_wise_comments_on_letters;
                    break;
                case 8:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_inspector'] = $tier_wise_comments_on_letters;
                    break;
                default:
                    break;
            }
            $done = $this->SupervisionModulePrepareLetterDetail->updateAll($data_to_update, $conditions);
            //debug($done);

            if ($done) {
                if ($isAdminApproval) {
                    $this->loadModel('SupervisionModuleBasicInformation');
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
        }
    }

    public function cancel($supervision_basic_id = null) {
        $this->autoRender = false;
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
        //can chenge states
        //$isAdminApproval = ($user_group_ids == 10);

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


        //$this->loadModel('SupervisionModulePrepareLetterDetail');
        $conditions = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id,
            'OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0, 'SupervisionModulePrepareLetterDetail.is_approved >=' => $approvalStates[0]),
            'SupervisionModulePrepareLetterDetail.is_completed' => 0);
        $done = $this->SupervisionModulePrepareLetterDetail->deleteAll($conditions, false);
        if ($done) {
            //can chenge states
            $isAdminApproval = in_array(10, $user_group_ids);

            $this->loadModel('SupervisionModuleBasicInformation');
            $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id, 'SupervisionModuleBasicInformation.supervision_state_id' => $isAdminApproval ? $thisStateIds[2] : $thisStateIds[1]));

            $org_state_history = array(
                'supervision_basic_id' => $supervision_basic_id,
                'supervision_state_id' => $thisStateIds[0],
                'date_of_state_update' => date('Y-m-d'),
                'date_of_starting' => date('Y-m-d'),
                'user_name' => $this->Session->read('User.Name'));

            $this->loadModel('SupervisionModuleStateHistory');
            $this->SupervisionModuleStateHistory->create();
            $this->SupervisionModuleStateHistory->save($org_state_history);
//            if ($isAdminApproval) {
//            }
            $redirect_url = $this->Session->read('Current.RedirectUrl');
            if (empty($redirect_url))
                $redirect_url = array('action' => 'view');
            $this->redirect($redirect_url);
            return;
        }
    }

    public function cancel_approval($supervision_basic_id = null) {

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

        $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        if (!empty($org_infos['BasicModuleBasicInformation'])) {
            $orgDetail = $org_infos['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        } else {
            $orgName = '';
        }

        //$orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';

        switch ($approvalStates[1]) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $approval_title = 'Cancellation';
                $btn_title = 'Cancel Approval';
                $success_msg = 'Letter has been cancelled successfully';
                $error_msg = 'Cancel Approval';
                break;
            case 7:
                $approval_title = '';
                $btn_title = 'Cancel Review';
                $success_msg = 'Review of Letter has been cancelled successfully';
                $error_msg = 'Review failed';
                break;
            case 8:
                $approval_title = "Notes";
                $btn_title = 'Add Notes';
                $success_msg = 'Notes has been added successfully';
                $error_msg = 'Notes adding failed';
                break;
            default:
                break;
        }
        $this->set(compact('supervision_basic_id', 'orgName', 'this_state_ids', 'approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg'));

        if ($this->request->is(array('post', 'put'))) {
            $isAdminApproval = in_array(10, $user_group_ids); //($user_group_ids == 10);
            $conditions = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => (int) $supervision_basic_id,
                'OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0, 'SupervisionModulePrepareLetterDetail.is_approved >=' => (int) $approvalStates[1]),
                'SupervisionModulePrepareLetterDetail.is_completed' => 0);
            if (!$isAdminApproval && !empty($approvalStates) && $approvalStates[1] == 1) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'For Final Administrative Approval, Please Login as EVC User Group !'
                );
                $this->set(compact('msg'));
                return;
            }
            $data_to_update = array(
                'SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[0]
            );

            $tier_wise_comments_on_letters = $this->replace_escape_chars($this->request->data['SupervisionModulePrepareLetterDetail']['tier_wise_comments_on_letters']);
            $tier_wise_comments_on_letters = "'" . $tier_wise_comments_on_letters . "'";
            switch ($approvalStates[1]) {
                case 1:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_evc'] = $tier_wise_comments_on_letters;
                    break;
                case 2:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_director'] = $tier_wise_comments_on_letters;
                    break;
                case 3:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_sdd'] = $tier_wise_comments_on_letters;
                    break;
                case 4:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_dd'] = $tier_wise_comments_on_letters;
                    break;
                case 5:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_sad'] = $tier_wise_comments_on_letters;
                    break;
                case 6:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_ad'] = $tier_wise_comments_on_letters;
                    break;
                case 7:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_section'] = $tier_wise_comments_on_letters;
                    break;
                case 8:
                    $data_to_update['SupervisionModulePrepareLetterDetail.comments_or_notes_of_inspector'] = $tier_wise_comments_on_letters;
                    break;
                default:
                    break;
            }

            $done = $this->SupervisionModulePrepareLetterDetail->updateAll($data_to_update, $conditions);

            if ($done) {
                if ($isAdminApproval) {
                    $this->loadModel('SupervisionModuleBasicInformation');
                    $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]));

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
        }
    }

    /*
      public function cancel_approval($supervision_basic_id = null) {
      //$this->autoRender = false;
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

      //can chenge states
      $isAdminApproval = in_array(10, $user_group_ids);

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
      $this->SupervisionModulePrepareLetterDetail->bindModel(
      array('belongsTo' => array(
      'SupervisionModuleBasicInformation' => array(
      'foreignKey' => 'supervision_basic_id'
      )
      )
      )
      );
      $this->loadModel('SupervisionModulePrepareLetterDetail');
      $supervision_state = $thisStateIds[0];
      if ($isAdminApproval) {
      $supervision_state = $thisStateIds[1];
      }
      $conditions = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id,
      'SupervisionModuleBasicInformation.supervision_state_id' => $supervision_state,
      'SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[1],
      'SupervisionModulePrepareLetterDetail.is_completed' => 0);

      $done = $this->SupervisionModulePrepareLetterDetail->updateAll(array('SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[0]), $conditions);
      if ($done) {
      if ($isAdminApproval) {
      $this->loadModel('SupervisionModuleBasicInformation');
      $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]));

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
      }
     */

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

        //chenge states
        $isAdminApproval = in_array(10, $user_group_ids);

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
                $newData = $this->request->data['SupervisionModuleLetterApprovalDetailAll'];
                if (!empty($newData)) {
                    $org_ids = Hash::extract($newData, '{n}[is_approved=1].org_id');

                    if (!empty($org_ids)) {
                        $this->loadModel('SupervisionModulePrepareLetterDetail');

                        $conditions = array('SupervisionModuleBasicInformation.org_id' => $org_ids,
                            'OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0, 'SupervisionModulePrepareLetterDetail.is_approved >=' => $approvalStates[0]),
                            'SupervisionModulePrepareLetterDetail.is_completed' => 0);

                        $done = $this->SupervisionModulePrepareLetterDetail->updateAll(array('SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[1]), $conditions);
                        if ($done) {
                            if ($isAdminApproval) {
                                $this->loadModel('SupervisionModuleBasicInformation');
                                $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[2]), array('SupervisionModuleBasicInformation.org_id' => $org_ids));

                                $all_org_state_history = array();
                                foreach ($org_ids as $org_id) {
                                    if (!empty($org_id)) {
                                        $org_state_history = array(
                                            'org_id' => $org_id,
                                            'supervision_state_id' => $thisStateIds[2],
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

        $condition_not_approved = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1],
            'OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0, 'SupervisionModulePrepareLetterDetail.is_approved >=' => $approvalStates[0]),
            'SupervisionModulePrepareLetterDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'SupervisionModulePrepareLetterDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('SupervisionModulePrepareLetterDetail');
        $values_not_approved = $this->SupervisionModulePrepareLetterDetail->find('all', array('fields' => $fields, 'conditions' => $condition_not_approved, 'group' => 'SupervisionModuleBasicInformation.org_id'));

        $this->set(compact('values_not_approved'));
    }

    public function approve_edit_all() {

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

        $isAdminApproval = in_array(10, $user_group_ids);

        $this->loadModel('AdminModuleUserGroup');
        $approvalType = $this->AdminModuleUserGroup->field('group_name', array('id' => $user_group_ids));

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
                $newData = $this->request->data['SupervisionModuleLetterApprovalDetailAll'];
                debug($newData);
                return;

                if (!empty($newData)) {
                    $supervision_basic_ids = Hash::extract($newData, '{n}[is_approved=0].supervision_basic_id');

                    if (!empty($supervision_basic_ids)) {

                        $this->loadModel('SupervisionModulePrepareLetterDetail');

                        $conditions = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_ids,
                            'SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[1],
                            'SupervisionModulePrepareLetterDetail.is_completed' => 0);
                        //$done = $this->SupervisionModulePrepareLetterDetail->updateAll(array('SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[0]), $conditions);

                        $done = $this->SupervisionModulePrepareLetterDetail->updateAll(array('OR' => array('SupervisionModulePrepareLetterDetail.is_approved' => 0, 'SupervisionModulePrepareLetterDetail.is_approved >=' => $approvalStates[0])), $conditions);
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
            'SupervisionModulePrepareLetterDetail.is_approved' => $approvalStates[1],
            'SupervisionModulePrepareLetterDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'SupervisionModulePrepareLetterDetail.supervision_basic_id', 'SupervisionModulePrepareLetterDetail.inspection_date',
            'LookupLicenseAdminApprovalStatus.approval_admin_level',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('SupervisionModulePrepareLetterDetail');
        $values_approved = $this->SupervisionModulePrepareLetterDetail->find('all', array('fields' => $fields, 'conditions' => $condition_approved, 'group' => 'SupervisionModulePrepareLetterDetail.supervision_basic_id'));


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

        $approvalDetails = $this->SupervisionModuleLetterApprovalDetail->find('first', array('conditions' => array('SupervisionModuleLetterApprovalDetail.supervision_basic_id' => $supervision_basic_id)));
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
            $newData = $this->request->data['SupervisionModuleLetterApprovalDetail'];
            if (!empty($newData)) {
                $this->SupervisionModuleLetterApprovalDetail->id = $approvalDetails['SupervisionModuleLetterApprovalDetail']['id'];
                if ($this->SupervisionModuleLetterApprovalDetail->save($newData)) {
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                    if (empty($redirect_url))
                        $redirect_url = array('action' => 'view');
                    $this->redirect($redirect_url);
                }
            }
            return;
        }

        $approvalDetails = $this->SupervisionModuleLetterApprovalDetail->find('first', array('conditions' => array('SupervisionModuleLetterApprovalDetail.supervision_basic_id' => $supervision_basic_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->SupervisionModuleLetterApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
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
            if ($this->SupervisionModulePrepareLetterDetail->deleteAll(array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id), false)) {
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
