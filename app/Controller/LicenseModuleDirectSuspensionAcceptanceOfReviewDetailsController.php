<?php

App::uses('AppController', 'Controller');

class LicenseModuleDirectSuspensionAcceptanceOfReviewDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    var $uses = array('BasicModuleBasicInformation',   

    'LicenseModuleDirectSuspensionAcceptanceOfReviewDetail','LicenseModuleCancelByMfiAdministrativeApprovalDetailDetail',
    'LicenseModuleCancelByMfiFieldInspectorDetail','LicenseModuleCancelByMfiFieldInspectionDetail',
    'LicenseModuleCancelByMfiAdministrativeApprovalDetail',

    'LicenseModuleCancelByMraShowCauseDetail','LicenseModuleCancelByMraMfiExplanationDetail',
    'LicenseModuleCancelByMraExplanationVerificationDetail','LicenseModuleCancelByMraFieldInspectorDetail',
    'LicenseModuleCancelByMraFieldInspectionDetail','LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail',
    'LicenseModuleCancelByMraActivityCancelNotifyDetail',

    'LicenseModuleDirectSuspensionAndAskForExplanationDetail','LicenseModuleDirectSuspensionMfiExplanationDetail',
    'LicenseModuleDirectSuspensionExplanationVerifyDetail','LicenseModuleDirectSuspensionFieldInspectorDetail', 
    'LicenseModuleDirectSuspensionFieldInspectionDetail','LicenseModuleDirectSuspensionContinueOrDiscontinueDetail',
    'LicenseModuleDirectSuspensionApplicationOfReviewDetail','LicenseModuleDirectSuspensionAcceptanceOfReviewDetail',

    'LicenseModuleSuspensionHearingNotificationDetail','LicenseModuleSuspensionHearingDetail', 
    'LicenseModuleSuspensionReliefDetail','LicenseModuleSuspensionReviewApplicationDetail',
    'LicenseModuleSuspensionFieldInspectorDetail','LicenseModuleSuspensionFieldInspectionDetail',
    'LicenseModuleSuspensionAcceptanceOfReviewDetail'
    );
    
    public function view($opt = 'all', $mode = null) {        
        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if(empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
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

        $current_year = $this->Session->read('Current.LicensingYear');
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        $pending_value_condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $completed_value_condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);

        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8 );
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->recursive = 0;        
        $values_approved = $this->Paginator->paginate('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail');        

        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all', 'values_approved', 'values_not_approved'));
    }

    public function details($org_id = null) {
        if (empty($org_id)) {            
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $licApprovalDetails = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->find('first', array('conditions' => array('org_id'=>$org_id)));
        $this->set(compact('licApprovalDetails'));             
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function approve($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
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
        
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail'];

                if (!empty($newData)) {                                       
                    $existingData = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->find('first', array('fields' => array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id' => $newData['org_id'])));
                    
                    if ($existingData) {                                                
                        $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->id = $existingData['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['id'];
                        $done = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->save($newData);
                    } else {
                        $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->create();
                        $done = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->save($newData);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $newData['org_id'];
                             
                        $current_year = $this->Session->read('Current.LicensingYear');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[1],
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $this->redirect(array('action' => 'view'));
                        return;
                    }
                }
            } catch (Exception $ex) {

            }
        }

        $orgFullName = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];

        $approval_status_options = array('1'=>'Accepted','0'=>'Rejected');
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
    }

    public function re_approve($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->find('first', array('conditions' => array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id' => $org_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
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
        
        if ($this->request->is(array('post', 'put'))) {
            $newData = $this->request->data['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail'];
            if (!empty($newData)) {                
                $state_id='';
                if($this->request->data['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='0'){
                    $state_id = $thisStateIds[1];
                }
                else if($this->request->data['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='1'){
                    $state_id = $thisStateIds[2];
                }
                
                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));
                                
                $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->id = $approvalDetails['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['id'];
                if ($this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->find('first', array('conditions' => array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = array('1'=>'Revoke Show Cause','0'=>'Cancel License');
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }    
}