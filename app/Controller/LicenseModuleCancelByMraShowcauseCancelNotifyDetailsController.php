<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleCancelByMraShowcauseCancelNotifyDetailsController extends AppController {
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
        $basic_condition = array();
        $org_id = $this->Session->read('Org.Id');            
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }
        
        if ($this->request->is('post')) {
            if(!empty($this->request->data['LicenseModuleCancelByMraShowcauseCancelNotifyDetailSent'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMraShowcauseCancelNotifyDetailSent'];
                $option = $reqData['search_option_sent'];
                $keyword = $reqData['search_keyword_sent'];
            }
            if(!empty($this->request->data['LicenseModuleCancelByMraShowcauseCancelNotifyDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMraShowcauseCancelNotifyDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
            }
            if (!empty($option) && !empty($keyword)) {
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
        }

        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));
        $this->Paginator->settings =  array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->loadModel('BasicModuleBasicInformation');
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMraShowcauseCancelNotifyDetail->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleCancelByMraShowcauseCancelNotifyDetail');       
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
        $allDetails = $this->LicenseModuleCancelByMraShowcauseCancelNotifyDetail->find('first', array('conditions' => array('LicenseModuleCancelByMraShowcauseCancelNotifyDetail.org_id'=>$org_id)));
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
            $notification_details = $this->request->data['LicenseModuleCancelByMraShowcauseCancelNotifyDetail']['notification_details'];
            $data_to_save = array(
                'org_id' =>$org_id,
                'notify_date' => date('Y-m-d'),
                'notification_details' => $notification_details
            );            
            $existing_value_condition = array('LicenseModuleCancelByMraShowcauseCancelNotifyDetail.org_id'=>$org_id);
            $existing_values = $this->LicenseModuleCancelByMraShowcauseCancelNotifyDetail->find('first',array('conditions'=>$existing_value_condition));
            if(!empty($existing_values)){                
                $this->LicenseModuleCancelByMraShowcauseCancelNotifyDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleCancelByMraShowcauseCancelNotifyDetail->create();
            $saved = $this->LicenseModuleCancelByMraShowcauseCancelNotifyDetail->save($data_to_save);

            if($saved){
                $basic_info_condition = array('BasicModuleBasicInformation.id'=>$org_id);    
                $data_to_update_in_basic_info = array('licensing_state_id'=>$thisStateIds[1]);
                $state_updated = $this->BasicModuleBasicInformation->updateAll($data_to_update_in_basic_info,$basic_info_condition);
                if($state_updated) {
                    $msg_subject = 'Confirmation of License Cancellation';
                    $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your organization '. $orgName . ' is not fullfilling the MRA criteria. This is a showcause letter to this organization'. " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                    $is_sent_mail = $this->send_mail($msg_subject, $message_body);
                    $flag = 0;
                    if($is_sent_mail) {
                        $flag = 1;
                    }
                    else {
                        $message = $this->get('error_message');
                    }
                    if($flag==1)
                    $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids));
                }
            }
            else{
                $message = 'Cancel Notification Sending Failed';
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