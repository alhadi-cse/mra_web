<?php

App::uses('AppController', 'Controller');

class LicenseModuleDirectSuspensionContinueOrDiscontinueDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    var $uses = array('BasicModuleBasicInformation','LicenseModuleDirectSuspensionContinueOrDiscontinueDetail');
    
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
      
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail.org_id' => $org_id);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }
        $pending_viewable_states = explode('^', $thisStateIds[0]);
        $completed_viewable_states = explode('^', $thisStateIds[1]);
        
        $pending_value_condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $pending_viewable_states);
        $completed_value_condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $completed_viewable_states);

        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8 );
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->recursive = 0;        
        $values_approved = $this->Paginator->paginate('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail');                
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
                $newData = $this->request->data['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail'];

                if (!empty($newData)) {
                    $state_id='';
                    $updateable_state_ids = explode('^', $thisStateIds[1]);
                    if($this->request->data['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['status_id']=='0'){
                        $state_id = $updateable_state_ids[0];
                    }
                    else if($this->request->data['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['status_id']=='1'){
                        $state_id = $updateable_state_ids[1];
                    }

                    $existingData = $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->find('first', array('fields' => array('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail.org_id' => $newData['org_id'])));
                    if ($existingData) {                       
                        $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->id = $existingData['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['id'];
                        $done = $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->save($newData);
                    } else {
                        $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->create();
                        $done = $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->save($newData);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $newData['org_id'];
                                                        
                        $current_year = $this->Session->read('Current.LicensingYear');

                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $state_id), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $state_id,
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

        $orgFullName = $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];

        $approval_status_options = array('0'=>'Continue Suspension','1'=>'Discontinue Suspension');
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
    }

    public function details($org_id = null) {
        if ($org_id=='') {
            $org_id = $this->Session->read('Org.Id');        
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->BasicModuleBasicInformation->recursive = -1;
        $basicInfoDetails = $this->BasicModuleBasicInformation->findById($org_id);
        if (!$basicInfoDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $suspension_status_values = $this->LicenseModuleDirectSuspensionContinueOrDiscontinueDetail->find('first', array('conditions' => array('org_id'=>$org_id)));

        if(!empty($suspension_status_values)){
            $licApprovalDetails = array_merge($basicInfoDetails,$suspension_status_values);
        }
        else{
            $licApprovalDetails = array_merge($basicInfoDetails,array('LicenseModuleDirectSuspensionContinueOrDiscontinueDetail'=>array('status_id'=>'','decision_date'=>'','comment'=>'')));
        }
        $this->set(compact('licApprovalDetails'));               
    }
}
