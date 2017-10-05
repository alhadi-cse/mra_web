<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleSuspensionNotificationDetailsController extends AppController {
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
            $pendingStateIds = explode('^', $thisStateIds[0]);
            $completedStateIds = explode('^', $thisStateIds[1]);
            
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

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

        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $pendingStateIds));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $completedStateIds));
        
        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleSuspensionNotificationDetail->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleSuspensionNotificationDetail');       
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
        $allDetails = $this->LicenseModuleSuspensionNotificationDetail->find('first', array('conditions' => array('org_id'=>$org_id)));
        $this->set(compact('allDetails'));
    }

    public function send_notification ($org_id = null) {
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
            $pending_state_ids = explode('^', $thisStateIds[0]);   
            $next_updatable_states = explode('^', $thisStateIds[1]);
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
            $this->request->data['LicenseModuleSuspensionNotificationDetail']['org_id'] = $org_id;
            $data_to_save = $this->request->data;                    
            $existing_value_condition = array('LicenseModuleSuspensionNotificationDetail.org_id'=>$org_id);
            $existing_values = $this->LicenseModuleSuspensionNotificationDetail->find('first',array('conditions'=>$existing_value_condition));
            if(!empty($existing_values)){                
                $this->LicenseModuleSuspensionNotificationDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleSuspensionNotificationDetail->create();
            $saved = $this->LicenseModuleSuspensionNotificationDetail->save($data_to_save);

            if($saved){
                $updatable_state_id = '';
                $approval_value_condition = array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id'=>$org_id);
                $this->loadModel('LicenseModuleSuspensionAcceptanceOfReviewDetail');
                $approval_values = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->find('first',array('conditions'=>$approval_value_condition));                            
                
                $org_infos = $this->BasicModuleBasicInformation->find('first',array('fields'=>array('BasicModuleBasicInformation.licensing_state_id'),'conditions'=>array('BasicModuleBasicInformation.id'=>$org_id)));
                $current_state_id = $org_infos['BasicModuleBasicInformation']['licensing_state_id'];
                
                if($current_state_id==$pending_state_ids[0]){
                    $updatable_state_id = $next_updatable_states[0];
                }
                else if($current_state_id==$pending_state_ids[1]){
                    if(!empty($approval_values)){
                        if($approval_values['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='0'){
                            $updatable_state_id = $next_updatable_states[1];
                        }
                        else if($approval_values['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='1'){
                            $updatable_state_id = $next_updatable_states[0];
                        }
                    }
                    else{
                        $message = 'This organization has not been approved for suspension!';
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => $message
                        );
                        $this->set(compact('msg'));
                    }
                }
                $basic_info_condition = array('BasicModuleBasicInformation.id'=>$org_id);    
                $data_to_update_in_basic_info = array('BasicModuleBasicInformation.licensing_state_id'=>$updatable_state_id);
                $this->BasicModuleBasicInformation->updateAll($data_to_update_in_basic_info,$basic_info_condition);
                
                $notification_details = $this->request->data['LicenseModuleSuspensionNotificationDetail']['notification_details'];
                $subject = 'Suspension Notification';
                $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your organization '. $orgName . ' is not fullfilling the MRA criteria. This is a notification to this organization '.$notification_details. " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                $is_sent_mail = $this->send_mail($subject, $message_body);
                $flag = 0;
                if ($is_sent_mail) {
                    $flag = 1;
                } 
                else {
                    $message = $this->get('error_message');
                }
                if ($flag == 1)
                $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids));                   
            }
            else{
                $message = 'Suspension Notification Sending Failed!';
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
