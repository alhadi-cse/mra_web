<?php
App::uses('AppController', 'Controller');

class LicenseModuleSuspensionHearingDetailsController extends AppController {
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();
    
    public function view($opt = 'all') {
        $user_group_id = $this->Session->read('User.GroupIds');        
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
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $next_state_ids = explode('^', $thisStateIds[1]);
        $viewable_state_ids = explode('^', $thisStateIds[1]);
        array_push($viewable_state_ids, $thisStateIds[0]);
        
        $basic_condition = array();
        $opt_all = false;
        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleSuspensionNotificationDetailCompleted'])) {
                $reqData = $this->request->data['LicenseModuleSuspensionNotificationDetailCompleted'];
                $option = $reqData['search_option_completed'];
                $keyword = $reqData['search_keyword_completed'];
            } 
            elseif (!empty($this->request->data['LicenseModuleSuspensionNotificationDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleSuspensionNotificationDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
            }            
            if (!empty($option) && !empty($keyword))
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
        }
                
        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $next_state_ids));
        
        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleSuspensionHearingDetail->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleSuspensionHearingDetail');       
        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'thisStateIds', 'pending_values', 'completed_values'));
    }
    
    public function preview($org_id = null) {
        $this->set(compact('org_id'));
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
        $allDetails = $this->LicenseModuleSuspensionHearingDetail->find('first', array('conditions' => array('org_id'=>$org_id)));
        $this->set(compact('allDetails'));
    }

    public function hearing($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
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
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('BasicModuleBasicInformation');
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.license_issue_date'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'];
        $orgName = $orgName. (!empty($org_infos['BasicModuleBasicInformation']['short_name_of_org'])?' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')':'');
        $this->set(compact('orgName'));
        
        if($this->request->is('post')){
            $hearing_details = $this->request->data['LicenseModuleSuspensionHearingDetail']['hearing_details'];
            $data_to_save = array(
                'org_id' =>$org_id,
                'hearing_date' => date('Y-m-d'),
                'hearing_details' => $hearing_details
            );
                        
            $existing_value_condition = array('LicenseModuleSuspensionHearingDetail.org_id'=>$org_id);
            $existing_values = $this->LicenseModuleSuspensionHearingDetail->find('first',array('conditions'=>$existing_value_condition));
            if(!empty($existing_values)){                
                $this->LicenseModuleSuspensionHearingDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleSuspensionHearingDetail->create();
            $saved = $this->LicenseModuleSuspensionHearingDetail->save($data_to_save);

            if($saved){
                $basic_info_condition = array('BasicModuleBasicInformation.id'=>$org_id);
                $data_to_save_in_basic_info = array('BasicModuleBasicInformation.licensing_state_id'=>$thisStateIds[1]);
                $this->BasicModuleBasicInformation->updateAll($data_to_save_in_basic_info,$basic_info_condition);
                $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids));
            }
            else{               
                $message = 'Request for Cancellation Failed';
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
            }            
        }
    }
}
