<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleCancelByMfiCancelRequestsController extends AppController {
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
        
    public function view_bk1() {
    }
    
    public function view($opt = 'all') {
        $user_group_id = $this->Session->read('User.GroupIds');     
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
         
        $org_id = $this->Session->read('Org.Id');
        $basic_condition = array();
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }
               
                       
        if ($this->request->is('post')) {                        
            if(!empty($this->request->data['LicenseModuleCancelByMfiCancelRequestCompleted'])) {
                $reqDataCompleted = $this->request->data['LicenseModuleCancelByMfiCancelRequestCompleted'];
                $option = $reqDataCompleted['search_option_completed'];
                $keyword = $reqDataCompleted['search_keyword_completed'];
            }
            if(!empty($this->request->data['LicenseModuleCancelByMfiCancelRequestPending'])) {
                $reqDataPending = $this->request->data['LicenseModuleCancelByMfiCancelRequestPending'];
                $option = $reqDataPending['search_option_pending'];
                $keyword = $reqDataPending['search_keyword_pending'];
            }
            if (!empty($option) && !empty($keyword)) {
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
        }

        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $next_state_ids));
                
        $this->Paginator->settings['LicenseModuleCancelByMfiCancelRequest'] = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMfiCancelRequest->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleCancelByMfiCancelRequest');       
        
        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings['BasicModuleBasicInformation'] =  array('conditions' => $pending_value_condition, 'limit' => 10);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');
        
        $this->set(compact('org_id', 'user_group_id', 'opt', 'thisStateIds', 'pending_values', 'completed_values'));
    }
    
    public function show_pending($opt = 'all') {
        $user_group_id = $this->Session->read('User.GroupIds');     
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
         
        $org_id = $this->Session->read('Org.Id');
        $basic_condition = array();
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }  
                       
        if ($this->request->is('post')) {
            if(!empty($this->request->data['LicenseModuleCancelByMfiCancelRequestPending'])) {
                $reqDataPending = $this->request->data['LicenseModuleCancelByMfiCancelRequestPending'];
                $option = $reqDataPending['search_option_pending'];
                $keyword = $reqDataPending['search_keyword_pending'];
            }
            if (!empty($option) && !empty($keyword)) {
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
        }

        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        
        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings['BasicModuleBasicInformation'] =  array('conditions' => $pending_value_condition, 'limit' => 10);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');
        
        $this->set(compact('org_id', 'user_group_id', 'opt', 'thisStateIds', 'pending_values'));
    }
    
    public function show_completed($opt = 'all') {
        $user_group_id = $this->Session->read('User.GroupIds');     
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
         
        $org_id = $this->Session->read('Org.Id');
        $basic_condition = array();
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }
               
                       
        if ($this->request->is('post')) {                        
            if(!empty($this->request->data['LicenseModuleCancelByMfiCancelRequestCompleted'])) {
                $reqDataCompleted = $this->request->data['LicenseModuleCancelByMfiCancelRequestCompleted'];
                $option = $reqDataCompleted['search_option_completed'];
                $keyword = $reqDataCompleted['search_keyword_completed'];
            }            
            if (!empty($option) && !empty($keyword)) {
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
        }

        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $next_state_ids));
                
        $this->Paginator->settings['LicenseModuleCancelByMfiCancelRequest'] = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMfiCancelRequest->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleCancelByMfiCancelRequest');       
                        
        $this->set(compact('org_id', 'user_group_id', 'opt', 'thisStateIds', 'completed_values'));
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
        $this->LicenseModuleCancelByMfiCancelRequest->recursive = 0;
        $allDetails = $this->LicenseModuleCancelByMfiCancelRequest->find('first', array('conditions' => array('org_id'=>$org_id)));
        $this->set(compact('allDetails'));
    }

    public function request_for_cancellation($org_id = null) {
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
            $cancel_reasons = $this->request->data['LicenseModuleCancelByMfiCancelRequest']['cancel_reasons'];
            $data_to_save = array(
                'org_id' =>$org_id,
                'cancel_request_date' => date('Y-m-d'),
                'cancel_reasons' => $cancel_reasons
            );

            $existing_value_condition = array('LicenseModuleCancelByMfiCancelRequest.org_id'=>$org_id);
            $existing_values = $this->LicenseModuleCancelByMfiCancelRequest->find('first',array('conditions'=>$existing_value_condition));
            if(!empty($existing_values)){                
                $this->LicenseModuleCancelByMfiCancelRequest->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleCancelByMfiCancelRequest->create();
            $saved = $this->LicenseModuleCancelByMfiCancelRequest->save($data_to_save);
            
            if($saved) {
                $basic_info_condition = array('BasicModuleBasicInformation.id'=>$org_id);
                $data_to_update_in_basic_info = array('licensing_state_id'=>$thisStateIds[1]);
                $this->BasicModuleBasicInformation->updateAll($data_to_update_in_basic_info,$basic_info_condition);
                
                $subject = 'License Cancel Request';
                $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your organization '. $orgName . ' has been requested for license cancellation successfully.'. " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                $is_sent_mail = $this->send_mail($subject, $message_body);
                $flag = 0;
                if ($is_sent_mail) {
                    $flag = 1;
                } else {
                    $message = $this->get('error_message');
                }
                if ($flag == 1)
                    $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids));
            }
            else {                
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