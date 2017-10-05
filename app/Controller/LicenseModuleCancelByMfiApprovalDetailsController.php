<?php

App::uses('AppController', 'Controller');

class LicenseModuleCancelByMfiApprovalDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

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
            if(!empty($thisStateIds)) {
                $otherStateIds = explode('^', $thisStateIds[0]);
            }
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
        $redirect_url = array('controller' => 'LicenseModuleCancelByMfiApprovalDetails', 'action' => 'view', '?' => $parameter);
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));
        $operator = !empty($approvalStates[0]) ? ">=" : "=";  

        $existing_values = $this->LicenseModuleCancelByMfiApprovalDetail->find('first',array('condtions'=> array('OR'=>array("BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[0],'BasicModuleBasicInformation.licensing_state_id' => $otherStateIds[1]),"LicenseModuleCancelByMfiApprovalDetail.is_approved $operator"=>$approvalStates[0])));
        $condition_not_approved = array();
              
        if(empty($existing_values)) {        
            $condition_not_approved = array("OR"=>array("BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[0],"BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[1]));

            $this->paginate['conditions'] = $condition_not_approved;
            $this->paginate['recursive'] = -1;
            $this->Paginator->settings = $this->paginate;
            $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');            
        }        
        else {            
            $condition_not_approved = array(
                "OR"=>array("BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[0],"BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[1]),
                "OR"=>array("LicenseModuleCancelByMfiApprovalDetail.is_approved" =>0,"LicenseModuleCancelByMfiApprovalDetail.is_approved $operator" =>(int) $approvalStates[0]),                
                "LicenseModuleCancelByMfiApprovalDetail.is_completed" => 0
            );
            
            $this->paginate['conditions'] = $condition_not_approved;
            $this->Paginator->settings = $this->paginate;        
            $values_not_approved = $this->Paginator->paginate('LicenseModuleCancelByMfiApprovalDetail');            
        }
        $condition_approved = array();
        $isAdminApproval = in_array(10,$user_group_ids);
        if($isAdminApproval) {
            $condition_approved = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
                'LicenseModuleCancelByMfiApprovalDetail.is_approved' =>(int) $approvalStates[1],
                'LicenseModuleCancelByMfiApprovalDetail.is_completed' => 0
            );
        }
        else {
            $condition_approved = array("OR"=>array("BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[0],"BasicModuleBasicInformation.licensing_state_id" => $otherStateIds[1]),
                'LicenseModuleCancelByMfiApprovalDetail.is_approved' =>(int) $approvalStates[1],
                'LicenseModuleCancelByMfiApprovalDetail.is_completed' => 0
            );
        }

        $this->paginate['conditions'] = $condition_approved;
        $this->paginate['recursive'] = 0;
        $this->Paginator->settings = $this->paginate;
        $values_approved = $this->Paginator->paginate('LicenseModuleCancelByMfiApprovalDetail');

        $this->loadModel('LookupLicenseAdminApprovalStatus');
        $approvalType = $this->LookupLicenseAdminApprovalStatus->field('approval_admin_level', array('approval_status_id' => $approvalStates[1]));
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
            default:
                break;
        }  
        
        $this->set(compact('approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg', 'isAdminApproval', 'values_not_approved', 'values_approved'));
        return;
    }

    public function approve($org_id = null) {        
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
            if(!empty($thisStateIds)) {
                $otherStateIds = explode('^', $thisStateIds[0]);
            }
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
        $approval_status_options = $this->LicenseModuleCancelByMfiApprovalDetail->LookupLicenseApprovalStatus_AD->find('list', array('fields' => array('LookupLicenseApprovalStatus_AD.id', 'LookupLicenseApprovalStatus_AD.approval_status')));       

        $this->loadModel('BasicModuleBasicInformation');
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';
        
        $approval_title = 'Approval';
        $btn_title = 'Approve';
        $success_msg = 'License Cancellation has been approved successfully';
        $error_msg = 'Approval failed';
        
        switch ($approvalStates[1]) {
            case 1:
                    $tier_wise_approval_status_field = 'approval_status_evc';
                    $tier_wise_comments_field = 'comments_or_notes_of_evc';
                    break;
                case 2:
                    $tier_wise_approval_status_field = 'approval_status_director';
                    $tier_wise_comments_field = 'comments_or_notes_of_director';
                    break;
                case 3:
                    $tier_wise_approval_status_field = 'approval_status_sdd';
                    $tier_wise_comments_field = 'comments_or_notes_of_sdd';
                    break;
                case 4:
                    $tier_wise_approval_status_field = 'approval_status_dd';
                    $tier_wise_comments_field = 'comments_or_notes_of_dd';
                    break;
                case 5:
                    $tier_wise_approval_status_field = 'approval_status_sad';
                    $tier_wise_comments_field = 'comments_or_notes_of_sad';
                    break;
                case 6:
                    $tier_wise_approval_status_field = 'approval_status_ad';
                    $tier_wise_comments_field = 'comments_or_notes_of_ad';
                break;            
            default:                
                break;
        } 
        $this->set(compact('org_id', 'orgName', 'this_state_ids', 'approval_status_options', 'approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg'));
        
        if ($this->request->is('post', 'put')) {                        
            $isAdminApproval = in_array(10, $user_group_ids);
            $conditions = array('LicenseModuleCancelByMfiApprovalDetail.org_id' => (int) $org_id,
                'OR' => array('LicenseModuleCancelByMfiApprovalDetail.is_approved >=' => (int) $approvalStates[0]),
                'LicenseModuleCancelByMfiApprovalDetail.is_completed' => 0);
            if (!$isAdminApproval&&!empty($approvalStates)&&$approvalStates[1]==1) {                
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'For Final Administrative Approval, Please Login as EVC User Group !'
                );
                $this->set(compact('msg'));            
                return;
            }
            $tier_wise_comments = $this->replace_escape_chars($this->request->data['LicenseModuleCancelByMfiApprovalDetail']['tier_wise_comments']);
            $tier_wise_approval_status = $this->request->data['LicenseModuleCancelByMfiApprovalDetail']['approval_status_id'];
            $existing_values = $this->LicenseModuleCancelByMfiApprovalDetail->find('first',array('condtions'=>$conditions));
            $done = false;
            if(empty($existing_values)) {
                $data_to_save = array(
                    'org_id' => $org_id,
                    $tier_wise_approval_status_field => $tier_wise_approval_status,
                    $tier_wise_comments_field => $tier_wise_comments,
                    'is_approved' => $approvalStates[1],
                    'is_completed' => 0
                );
                $this->LicenseModuleCancelByMfiApprovalDetail->create();
                $done = $this->LicenseModuleCancelByMfiApprovalDetail->save($data_to_save);
            }
            else {            
                $data_to_update = array(
                    'LicenseModuleCancelByMfiApprovalDetail.is_approved' => $approvalStates[1]                
                );
                
                $tier_wise_comments = $this->replace_escape_chars($this->request->data['LicenseModuleCancelByMfiApprovalDetail']['tier_wise_comments']);
                $tier_wise_comments = "'".$tier_wise_comments."'";
                
                $data_to_update["LicenseModuleCancelByMfiApprovalDetail.$tier_wise_approval_status_field"] = $tier_wise_approval_status;
                $data_to_update["LicenseModuleCancelByMfiApprovalDetail.$tier_wise_comments_field"] = $tier_wise_comments;

                $done = $this->LicenseModuleCancelByMfiApprovalDetail->updateAll($data_to_update, $conditions);
            }
            if ($done) {                
                if ($isAdminApproval) { 
                    $this->loadModel('BasicModuleBasicInformation');                    
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));
                    
                    $org_state_history = array(
                        'org_id' => $org_id,
                        'licensing_state_id' => $thisStateIds[1],
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name')
                    );

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

    public function cancel_approval($org_id = null) {        
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
        
        $this->loadModel('BasicModuleBasicInformation');        
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';
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
            default:
                break;
        } 
        $this->set(compact('org_id', 'orgName', 'this_state_ids', 'approvalType', 'approval_title', 'btn_title', 'success_msg', 'error_msg'));
        
        if ($this->request->is('post', 'put')) {                        
            $isAdminApproval = in_array(10, $user_group_ids);
            $conditions = array('LicenseModuleCancelByMfiApprovalDetail.org_id' => (int) $org_id,
                'OR' => array('LicenseModuleCancelByMfiApprovalDetail.is_approved' => 0, 'LicenseModuleCancelByMfiApprovalDetail.is_approved >=' => (int) $approvalStates[1]),
                'LicenseModuleCancelByMfiApprovalDetail.is_completed' => 0);
            if (!$isAdminApproval&&!empty($approvalStates)&&$approvalStates[1]==1) {                
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'For Final Administrative Approval, Please Login as EVC User Group !'
                );
                $this->set(compact('msg'));            
                return;
            } 
            $data_to_update = array(                 
                'LicenseModuleCancelByMfiApprovalDetail.is_approved' => $approvalStates[0]               
            );
            $tier_wise_approval_status = null;
            $tier_wise_comments = $this->replace_escape_chars($this->request->data['LicenseModuleCancelByMfiApprovalDetail']['tier_wise_comments']);
            $tier_wise_comments = "'".$tier_wise_comments."'";
            switch ($approvalStates[1]) {
                case 1:
                    $tier_wise_approval_status_field = 'approval_status_evc';
                    $tier_wise_comments_field = 'comments_or_notes_of_evc';
                    break;
                case 2:
                    $tier_wise_approval_status_field = 'approval_status_director';
                    $tier_wise_comments_field = 'comments_or_notes_of_director';
                    break;
                case 3:
                    $tier_wise_approval_status_field = 'approval_status_sdd';
                    $tier_wise_comments_field = 'comments_or_notes_of_sdd';
                    break;
                case 4:
                    $tier_wise_approval_status_field = 'approval_status_dd';
                    $tier_wise_comments_field = 'comments_or_notes_of_dd';
                    break;
                case 5:
                    $tier_wise_approval_status_field = 'approval_status_sad';
                    $tier_wise_comments_field = 'comments_or_notes_of_sad';
                    break;
                case 6:
                    $tier_wise_approval_status_field = 'approval_status_ad';
                    $tier_wise_comments_field = 'comments_or_notes_of_ad';
                    break;                
                default:
                    break;
            }
            $data_to_update["LicenseModuleCancelByMfiApprovalDetail.$tier_wise_approval_status_field"] = $tier_wise_approval_status;
            $data_to_update["LicenseModuleCancelByMfiApprovalDetail.$tier_wise_comments_field"] = $tier_wise_comments;            
            $done = $this->LicenseModuleCancelByMfiApprovalDetail->updateAll($data_to_update, $conditions);
            
            if ($done) {                
                if ($isAdminApproval) {
                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'licensing_state_id' => $thisStateIds[1],
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

    public function approve_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $approvalDetails = $this->LicenseModuleCancelByMfiApprovalDetail->find('first', array('conditions' => array('LicenseModuleCancelByMfiApprovalDetail.org_id' => $org_id)));
        $this->set(compact('approvalDetails'));
    }

    public function preview($org_id = null,$pending_status = null) {        
        $this->set(compact('org_id','pending_status'));
    }
}
