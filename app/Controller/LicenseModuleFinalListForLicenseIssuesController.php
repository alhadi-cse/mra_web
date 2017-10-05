<?php

App::uses('AppController', 'Controller');

class LicenseModuleFinalListForLicenseIssuesController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($opt = 'all', $mode = null) {

        $user_group_id = $this->Session->read('User.GroupIds');

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(2,$user_group_id))) {
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

        $this_state_id = $thisStateIds[0];
        //$final_state_id = $thisStateIds[1];

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

//        if (in_array(2,$user_group_id)) {
//            
//            $org_id = $this->Session->read('Org.Id');
//
////            if (!empty($org_id)) {
////                $condition = array_merge(array('LicenseModuleEvaluationDetailInfo.org_id' => $org_id), $condition);
////            } 
////            else {
////                $msg = array(
////                'type' => 'warning',
////                'title' => 'Warning... . . !',
////                'msg' => 'Form serial no. not assinged for this user !');
////                $this->set(compact('msg'));
////                return;
////            }
//        }

        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        } else if (in_array(2,$user_group_id)) {
            $org_id = $this->Session->read('Org.Id');
        }
        //$org_id = $this->Session->read('Org.Id');
//        if (!empty($org_id)) {
//            $condition = array_merge(array('LicenseModuleEvaluationDetailInfo.org_id' => $org_id), $condition);
//        }
//
        if (!empty($org_id))
            $condition_done = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
        else
            $condition_done = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);

        if ($this->request->is('post')) {
            $reqData = $this->request->data['LicenseModuleFindFinalListForLicenseIssue'];
            $option = $reqData['search_option'];
            $keyword = $reqData['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition_done)) {
                    $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
                } else {
                    $condition_done = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }

//        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all'));
//        
//        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
////        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
////        $condition3 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id + 1);
//        
//        if (!empty($condition)) { 
//            $condition1 = array_merge($condition1, $condition);
////            $condition2 = array_merge($condition2, $condition);
////            $condition3 = array_merge($condition3, $condition);
//        }
//        
//        
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->loadModel('BasicModuleBasicInformation');
        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition_done, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $this->BasicModuleBasicInformation->recursive = 0;
        $values_final_list = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->set(compact('this_state_ids', 'user_group_id', 'values_final_list'));
    }

    public function done($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

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

//        $currentState = $thisStateIds[0];
        $nextState = $thisStateIds[1];
        if (empty($nextState)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $nextState), array('BasicModuleBasicInformation.id' => $org_id));

        if ($done) {
            try {
                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $nextState,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);
            } catch (Exception $ex) {
                
            }

            $this->loadModel('AdminModuleUserProfile');
            $email_id = $this->AdminModuleUserProfile->field('AdminModuleUserProfile.email', array('AdminModuleUserProfile.org_id' => $org_id));
            //$email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));


            $orgDetails = $this->BasicModuleBasicInformation->findById($org_id);
            $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
            $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
            $mfiName = $mfiName . ((!empty($mfiName) && !empty($mfiFullName)) ? ": " : "") . $mfiFullName;

            $license_no = "MRA-__/_-__";

            $message_body = "Dear Applicant, " . "\r\n \r\n"
                    . "Your Organization $mfiName" 
                    . "has been successfully completed the license process"
                    . "\r\n"
                    . "Your Organization License No.: $license_no"
                    . "\r\n  \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority (MRA)";

            $mail_details = array();
            $mail_details['org_id'] = $org_id;
            $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
            $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
            $mail_details['mail_to'] = email_id;
            $mail_details['mail_cc'] = '';
            $mail_details['mail_subject'] = 'License Application Successfully Completed.';
            $mail_details['mail_message'] = $message_body;
            $mail_details['mail_is_sent'] = 0;
			$mail_details['mail_creation_date'] = date('Y-m-d');
			$mail_details['mail_creator'] = $this->Session->read('User.Id');

            $this->loadModel('AdminModuleMessageSendingDetail');
            $this->AdminModuleMessageSendingDetail->create();
            $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

            if ($done && !empty($done['AdminModuleMessageSendingDetail']['id']))
                $this->redirect(array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $done['AdminModuleMessageSendingDetail']['id']));


            //$done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $nextState), array('BasicModuleBasicInformation.id' => $org_id));
        }
    }

}
