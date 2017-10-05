<?php

App::uses('AppController', 'Controller');

class LicenseModuleSuspensionReliefDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

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
            $thisStateIds = split('_', $this_state_ids);
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
        $org_id = $this->Session->read('Org.Id');
        $basic_condition = array();
        if (!empty($org_id)) {
            $basic_condition = array('LicenseModuleSuspensionReliefDetail.org_id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleSuspensionReliefDetailCompleted'])) {
                $reqData = $this->request->data['LicenseModuleSuspensionReliefDetailCompleted'];
                $option = $reqData['search_option_completed'];
                $keyword = $reqData['search_keyword_completed'];
            } 
            elseif (!empty($this->request->data['LicenseModuleSuspensionReliefDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleSuspensionReliefDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
            }            
            if (!empty($option) && !empty($keyword))
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
        }
        
        $completed_value_state_ids = explode('^', $thisStateIds[1]);
        $pending_value_condition = array_merge($basic_condition,array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($basic_condition,array('BasicModuleBasicInformation.licensing_state_id' => $completed_value_state_ids));

        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8 );
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleSuspensionReliefDetail->recursive = 0;        
        $values_approved = $this->Paginator->paginate('LicenseModuleSuspensionReliefDetail');        

        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all', 'values_approved', 'values_not_approved'));
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
            $thisStateIds = split('_', $this_state_ids);
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
        $approval_status_options = array('0'=>'Relief from Suspension','1'=>'Continue Suspension');
        
        $this->loadModel('BasicModuleBasicInformation');
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.license_issue_date'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'];
        $orgName = $orgName. (!empty($org_infos['BasicModuleBasicInformation']['short_name_of_org'])?' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')':'');
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
        
        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleSuspensionReliefDetail'];

                if (!empty($newData)) {
                    $state_id='';
                    $updateable_state_ids = explode('^', $thisStateIds[1]);
                    if($this->request->data['LicenseModuleSuspensionReliefDetail']['status_id']=='0'){
                        $state_id = $updateable_state_ids[0];
                    }
                    else if($this->request->data['LicenseModuleSuspensionReliefDetail']['status_id']=='1'){
                        $state_id = $updateable_state_ids[1];
                    }        
                   
                    $existingData = $this->LicenseModuleSuspensionReliefDetail->find('first', array('fields' => array('LicenseModuleSuspensionReliefDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionReliefDetail.org_id' => $newData['org_id'])));
                    if ($existingData) {                                                
                        $this->LicenseModuleSuspensionReliefDetail->id = $existingData['LicenseModuleSuspensionReliefDetail']['id'];
                        $done = $this->LicenseModuleSuspensionReliefDetail->save($newData);
                    } 
                    else {
                        $this->LicenseModuleSuspensionReliefDetail->create();
                        $done = $this->LicenseModuleSuspensionReliefDetail->save($newData);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $newData['org_id'];                              
                        
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $state_id), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $state_id,                            
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

        $approvalDetails = $this->LicenseModuleSuspensionReliefDetail->find('first', array('conditions' => array('LicenseModuleSuspensionReliefDetail.org_id' => $org_id)));
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
            $thisStateIds = split('_', $this_state_ids);
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
            $newData = $this->request->data['LicenseModuleSuspensionReliefDetail'];
            if (!empty($newData)) {                
                $state_id='';
                if($this->request->data['LicenseModuleSuspensionReliefDetail']['status_id']=='0'){
                    $state_id = $thisStateIds[1];
                }
                else if($this->request->data['LicenseModuleSuspensionReliefDetail']['status_id']=='1'){
                    $state_id = $thisStateIds[2];
                }
                
                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $state_id), array('BasicModuleBasicInformation.id' => $org_id));
                                
                $this->LicenseModuleSuspensionReliefDetail->id = $approvalDetails['LicenseModuleSuspensionReliefDetail']['id'];
                if ($this->LicenseModuleSuspensionReliefDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleSuspensionReliefDetail->find('first', array('conditions' => array('LicenseModuleSuspensionReliefDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = array('0'=>'Continue Suspension','1'=>'Relief from Suspension');
        $this->set(compact('approvalDetails', 'approval_status_options'));
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
        $allDetails = $this->LicenseModuleSuspensionReliefDetail->find('first', array('conditions' => array('org_id'=>$org_id)));
        $this->set(compact('allDetails'));        
    }
}

