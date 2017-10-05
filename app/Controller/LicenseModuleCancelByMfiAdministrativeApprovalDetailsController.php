<?php

App::uses('AppController', 'Controller');

class LicenseModuleCancelByMfiAdministrativeApprovalDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    var $uses = array('BasicModuleBasicInformation',   

    'LicenseModuleCancelByMfiCancelRequest','LicenseModuleCancelByMfiAdministrativeApprovalDetailDetail',
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
        
        if (empty($user_group_id)) {
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
        
        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }
        $next_viewable_states = explode('^', $thisStateIds[0]); 
        
        $pending_value_condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $next_viewable_states);
        $completed_value_condition = array( 'BasicModuleBasicInformation.licensing_state_id' => array($thisStateIds[1]));

        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8 );
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');
                
        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->recursive = 0;
        $values_approved = $this->Paginator->paginate('LicenseModuleCancelByMfiAdministrativeApprovalDetail');

        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all', 'values_approved', 'values_not_approved'));
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

        $licApprovalDetails = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first', array('conditions' => array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id' => $org_id)));
        $this->set(compact('licApprovalDetails'));
    }

    public function preview($org_id = null,$pending_status = null) {        
        $this->set(compact('org_id','pending_status'));
    }

    public function approve_all() {        
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
        
        $next_viewable_states = explode('^', $thisStateIds[0]);
        $condition1 = array('BasicModuleBasicInformation.licensing_state_id' => $next_viewable_states);

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id');
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');
        $approval_status_options = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->set(compact('orgDetails', 'approval_status_options'));
       
        if ($this->request->is('post')) {
            try {
            $newData = $this->request->data['LicenseModuleCancelByMfiAdministrativeApprovalDetailAll'];                
                if (!empty($newData)) {
                    $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->create();
                    $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->saveAll($newData);

                    $all_org_state_history = array();
                    $this->loadModel('BasicModuleBasicInformation'); 

                    foreach ($newData as $new_data) {
                        $org_id = $new_data['org_id'];
                        if (!empty($org_id)) {
                            $licensing_state_id = '';
                            if($new_data['approval_status_id']=='1'){
                                $licensing_state_id = $thisStateIds[1];
                            }
                            else if($new_data['approval_status_id']=='2'){
                                $licensing_state_id = $thisStateIds[2];                   
                            }
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $licensing_state_id), array('BasicModuleBasicInformation.id' => $org_id));
                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $licensing_state_id,                                
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name')
                            );

                            $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                        }
                    }

                    if (!empty($all_org_state_history)) {
                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                    }

                    $this->redirect(array('action' => 'view', '?' => array('this_state_id' => $thisStateIds[1])));
                    return;
                }
            }
            catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));
            }
        }
        
    }

    public function approve_edit_all() {
        
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleCancelByMfiAdministrativeApprovalDetailAll'];
                
                if (!empty($newData)) {
                    $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->set($newData);
                    if ($this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->saveAll($newData)) {
                        $this->redirect(array('action' => 'view'));
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $condition1 = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no');

        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('license_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');

        $approval_status_options = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('orgDetails', 'approval_status_options'));
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
        $this->loadModel('BasicModuleBasicInformation');
        $orgFullName = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id),'recursive'=>-1));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];

        $approval_status_options = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
        
        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleCancelByMfiAdministrativeApprovalDetail'];

                if (!empty($newData)) {
                    /*
                        $LicenseModuleCancelByMfiCancelRequestValues = $this->LicenseModuleCancelByMfiCancelRequest->find('first',array('conditions'=>array('LicenseModuleCancelByMfiCancelRequest.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMfiCancelRequestValues)){                
                            $this->LicenseModuleCancelByMfiCancelRequest->deleteAll(array('LicenseModuleCancelByMfiCancelRequest.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMfiAdministrativeApprovalDetailValues = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMfiAdministrativeApprovalDetailValues)){                
                            $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->deleteAll(array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMfiFieldInspectorDetailsValues = $this->LicenseModuleCancelByMfiFieldInspectorDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMfiFieldInspectorDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMfiFieldInspectorDetailFieldInspectorDetailsValues)){                
                            $this->LicenseModuleCancelByMfiFieldInspectorDetail->deleteAll(array('LicenseModuleCancelByMfiFieldInspectorDetail.org_id'=>$org_id), false);
                        }
                        
                        $LicenseModuleCancelByMfiFieldInspectionDetailValues = $this->LicenseModuleCancelByMfiFieldInspectionDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMfiFieldInspectionDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMfiFieldInspectionDetailValues)){                
                            $this->LicenseModuleCancelByMfiFieldInspectionDetail->deleteAll(array('LicenseModuleCancelByMfiFieldInspectionDetail.org_id'=>$org_id), false);
                        }
                       
                        $LicenseModuleCancelByMfiAdministrativeApprovalDetailValues = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMfiAdministrativeApprovalDetailValues)){                
                            $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->deleteAll(array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMraShowCauseDetailValues = $this->LicenseModuleCancelByMraShowCauseDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraShowCauseDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraShowCauseDetailValues)){                
                            $this->LicenseModuleCancelByMraShowCauseDetail->deleteAll(array('LicenseModuleCancelByMraShowCauseDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMraMfiExplanationDetailValues = $this->LicenseModuleCancelByMraMfiExplanationDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraMfiExplanationDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraMfiExplanationDetailValues)){                
                            $this->LicenseModuleCancelByMraMfiExplanationDetail->deleteAll(array('LicenseModuleCancelByMraMfiExplanationDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMraExplanationVerificationDetailValues = $this->LicenseModuleCancelByMraExplanationVerificationDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraExplanationVerificationDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraExplanationVerificationDetailValues)){                
                            $this->LicenseModuleCancelByMraExplanationVerificationDetail->deleteAll(array('LicenseModuleCancelByMraExplanationVerificationDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMraFieldInspectorDetailValues = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraFieldInspectorDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraFieldInspectorDetailValues)){                
                            $this->LicenseModuleCancelByMraFieldInspectorDetail->deleteAll(array('LicenseModuleCancelByMraFieldInspectorDetail.org_id'=>$org_id), false);
                        }
 
                        $LicenseModuleCancelByMraFieldInspectionDetailValues = $this->LicenseModuleCancelByMraFieldInspectionDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraFieldInspectionDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraFieldInspectionDetailValues)){                
                            $this->LicenseModuleCancelByMraFieldInspectionDetail->deleteAll(array('LicenseModuleCancelByMraFieldInspectionDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMraCancelOrRevokeShowCauseDetailValues = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraCancelOrRevokeShowCauseDetailValues)){                
                            $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->deleteAll(array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleCancelByMraActivityCancelNotifyDetailValues = $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->find('first',array('conditions'=>array('LicenseModuleCancelByMraActivityCancelNotifyDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleCancelByMraActivityCancelNotifyDetailValues)){                
                            $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->deleteAll(array('LicenseModuleCancelByMraActivityCancelNotifyDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionAndAskForExplanationDetailValues = $this->LicenseModuleDirectSuspensionAndAskForExplanationDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionAndAskForExplanationDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionAndAskForExplanationDetailValues)){                
                            $this->LicenseModuleDirectSuspensionAndAskForExplanationDetail->deleteAll(array('LicenseModuleDirectSuspensionAndAskForExplanationDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionMfiExplanationDetailValues = $this->LicenseModuleDirectSuspensionMfiExplanationDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionMfiExplanationDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionMfiExplanationDetailValues)){                
                            $this->LicenseModuleDirectSuspensionMfiExplanationDetail->deleteAll(array('LicenseModuleDirectSuspensionMfiExplanationDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionExplanationVerifyDetailValues = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionExplanationVerifyDetailValues)){                
                            $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->deleteAll(array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionFieldInspectorDetailValues = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionFieldInspectorDetailValues)){                
                            $this->LicenseModuleDirectSuspensionFieldInspectorDetail->deleteAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionFieldInspectionDetailValues = $this->LicenseModuleDirectSuspensionFieldInspectionDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionFieldInspectionDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionFieldInspectionDetailValues)){                
                            $this->LicenseModuleDirectSuspensionFieldInspectionDetail->deleteAll(array('LicenseModuleDirectSuspensionFieldInspectionDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionContinueOrDiscontinueDetailValues = $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionContinueOrDiscontinueDetailValues)){                
                            $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->deleteAll(array('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionApplicationOfReviewDetailValues = $this->LicenseModuleDirectSuspensionApplicationOfReviewDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionApplicationOfReviewDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionApplicationOfReviewDetailValues)){                
                            $this->LicenseModuleDirectSuspensionApplicationOfReviewDetail->deleteAll(array('LicenseModuleDirectSuspensionApplicationOfReviewDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleDirectSuspensionAcceptanceOfReviewDetailValues = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->find('first',array('conditions'=>array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleDirectSuspensionAcceptanceOfReviewDetailValues)){                
                            $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->deleteAll(array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleSuspensionHearingNotificationDetailValues = $this->LicenseModuleSuspensionHearingNotificationDetail->find('first',array('conditions'=>array('LicenseModuleSuspensionHearingNotificationDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleSuspensionHearingNotificationDetailValues)){                
                            $this->LicenseModuleSuspensionHearingNotificationDetail->deleteAll(array('LicenseModuleSuspensionHearingNotificationDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleSuspensionHearingDetailValues = $this->LicenseModuleSuspensionHearingDetail->find('first',array('conditions'=>array('LicenseModuleSuspensionHearingDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleSuspensionHearingDetailValues)){                
                            $this->LicenseModuleSuspensionHearingDetail->deleteAll(array('LicenseModuleSuspensionHearingDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleSuspensionReliefDetailValues = $this->LicenseModuleSuspensionReliefDetail->find('first',array('conditions'=>array('LicenseModuleSuspensionReliefDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleSuspensionReliefDetailValues)){                
                            $this->LicenseModuleSuspensionReliefDetail->deleteAll(array('LicenseModuleSuspensionReliefDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleSuspensionReviewApplicationDetailValues = $this->LicenseModuleSuspensionReviewApplicationDetail->find('first',array('conditions'=>array('LicenseModuleSuspensionReviewApplicationDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleSuspensionReviewApplicationDetailValues)){                
                            $this->LicenseModuleSuspensionReviewApplicationDetail->deleteAll(array('LicenseModuleSuspensionReviewApplicationDetail.org_id'=>$org_id), false);
                        }

                        $LicenseModuleSuspensionFieldInspectionDetailValues = $this->LicenseModuleSuspensionFieldInspectionDetail->find('first',array('conditions'=>array('LicenseModuleSuspensionFieldInspectionDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleSuspensionFieldInspectionDetailValues)){                
                            $this->LicenseModuleSuspensionFieldInspectionDetail->deleteAll(array('LicenseModuleSuspensionFieldInspectionDetail.org_id'=>$org_id), false);
                        }
 
                        $LicenseModuleSuspensionAcceptanceOfReviewDetailValues = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->find('first',array('conditions'=>array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id'=>$org_id)));
                        if(!empty($LicenseModuleSuspensionAcceptanceOfReviewDetailValues)){                
                            $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->deleteAll(array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id'=>$org_id), false);
                        }  */                     
                    }
                   
                    $existingData = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first', array('fields' => array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id' => $newData['org_id'])));
                    if ($existingData) {                                                
                        $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->id = $existingData['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['id'];
                        $done = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->save($newData);
                        
                    } else {
                        $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->create();
                        $done = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->save($newData);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $newData['org_id'];
                                                
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[1],                            
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
                catch (Exception $ex) {

            }
        }
        
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

        $approvalDetails = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first', array('conditions' => array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id' => $org_id)));
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
            $newData = $this->request->data['LicenseModuleCancelByMfiAdministrativeApprovalDetail'];
            if (!empty($newData)) {
                $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->id = $approvalDetails['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['id'];
                if ($this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first', array('conditions' => array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

    public function details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licApprovalDetails = $this->LicenseModuleCancelByMfiAdministrativeApprovalDetail->find('first', array('conditions' => array('LicenseModuleCancelByMfiAdministrativeApprovalDetail.org_id' => $org_id)));
        if (!$licApprovalDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('licApprovalDetails'));
    }
}