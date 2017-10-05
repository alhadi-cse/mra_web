<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleDirectSuspensionNotificationDetailsController extends AppController {
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
        $pending_state_ids = explode('^', $thisStateIds[0]);
        $comple_state_ids = explode('^', $thisStateIds[1]);
        
        $basic_condition = array();
        $opt_all = false;
        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleDirectSuspensionNotificationDetailCompleted'])) {
                $reqData = $this->request->data['LicenseModuleDirectSuspensionNotificationDetailCompleted'];
                $option = $reqData['search_option_completed'];
                $keyword = $reqData['search_keyword_completed'];
            } 
            elseif (!empty($this->request->data['LicenseModuleDirectSuspensionNotificationDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleDirectSuspensionNotificationDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
            }            
            if (!empty($option) && !empty($keyword))
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
        }
        
        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $pending_state_ids));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $comple_state_ids));
        
        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleDirectSuspensionNotificationDetail->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleDirectSuspensionNotificationDetail');       
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
        $allDetails = $this->LicenseModuleDirectSuspensionNotificationDetail->find('first', array('conditions' => array('org_id'=>$org_id)));
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
        
        $next_updatable_states = explode('^', $thisStateIds[1]);        
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
            $notification_details = $this->request->data['LicenseModuleDirectSuspensionNotificationDetail']['notification_details'];
            $data_to_save = array(
                'org_id' =>$org_id,
                'notify_date' => date('Y-m-d'),
                'notification_details' => $notification_details
            );            
            $existing_value_condition = array('LicenseModuleDirectSuspensionNotificationDetail.org_id'=>$org_id);
            $existing_values = $this->LicenseModuleDirectSuspensionNotificationDetail->find('first',array('conditions'=>$existing_value_condition));
            
            if(!empty($existing_values)) {                
                $this->LicenseModuleDirectSuspensionNotificationDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleDirectSuspensionNotificationDetail->create();
            $saved = $this->LicenseModuleDirectSuspensionNotificationDetail->save($data_to_save);

            if($saved) {
                $updatable_state_id = '';
                $approval_value_condition = array('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail.org_id'=>$org_id);
                $this->loadModel('LicenseModuleDirectSuspensionAcceptanceOfReviewDetail');
                $approval_values = $this->LicenseModuleDirectSuspensionAcceptanceOfReviewDetail->find('first',array('conditions'=>$approval_value_condition));                            
               
                if(!empty($approval_values)){
                    if($approval_values['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='0'){
                        $updatable_state_id = $next_updatable_states[0];
                    }
                    else if($approval_values['LicenseModuleDirectSuspensionAcceptanceOfReviewDetail']['status_id']=='1'){
                        $updatable_state_id = $next_updatable_states[1];
                    }
                    $basic_info_condition = array('BasicModuleBasicInformation.id'=>$org_id);    
                    $data_to_update_in_basic_info = array('licensing_state_id'=>$updatable_state_id);
                    $this->BasicModuleBasicInformation->updateAll($data_to_update_in_basic_info,$basic_info_condition);
                    
                    $subject = 'Confirmation of License Suspension';
                    $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your organization '. $orgName . ' is not fullfilling the MRA criteria. This is a showcause letter to this organization'. " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
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
                    $message = 'This organization has not been approved for suspension!';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                }
            }
            else {                
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