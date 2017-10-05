<?php

App::uses('AppController', 'Controller');

class LicenseModuleVerificationOfCancelRequestsController extends AppController {
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    var $uses = array('BasicModuleBasicInformation','LicenseModuleCancelRequest','LicenseModuleVerificationOfCancelRequest');

    public function view($opt = 'all', $mode = null) {        
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

        $user_name = $this->Session->read('User.Name');            

        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleVerificationOfCancelRequest.org_id' => $org_id), $condition);
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
        }

        $this->Paginator->settings =  array('conditions' => $condition1, 'limit' => 10 );
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');        
        $this->Paginator->settings = array('conditions' => $condition2, 'limit' => 10 );
        $completed_values = $this->Paginator->paginate('BasicModuleBasicInformation');
       if ($this->request->is('post')){
            $completed_option = $this->request->data['LicenseModuleVerificationOfCancelRequestCompleted']['completed_search_option'];  
            $completed_keyword = $this->request->data['LicenseModuleVerificationOfCancelRequestCompleted']['completed_search_keyword'];
            $completed_condition = array('LicenseModuleVerificationOfCancelRequest.verification_status_id' => '0',"$completed_option LIKE '%$completed_keyword%'"); 

            $this->paginate = array(
            'order' => array('LicenseModuleVerificationOfCancelRequest.id' => 'ASC'),
            'limit' => 10,
            'conditions' => $completed_condition);            
            $this->Paginator->settings = $this->paginate;
            $completed_values = $this->Paginator->paginate('LicenseModuleVerificationOfCancelRequest');        

            $pending_option = $this->request->data['LicenseModuleVerificationOfCancelRequestPending']['pending_search_option'];  
            $pending_keyword = $this->request->data['LicenseModuleVerificationOfCancelRequestPending']['pending_search_keyword'];
            $pending_condition = array('LicenseModuleVerificationOfCancelRequest.verification_status_id' => '0',"$pending_option LIKE '%$pending_keyword%'"); 

            $this->paginate = array(
            'order' => array('LicenseModuleVerificationOfCancelRequest.id' => 'ASC'),
            'limit' => 10,
            'conditions' => $pending_condition);
            $this->Paginator->settings = $this->paginate;
            $pending_values = $this->Paginator->paginate('LicenseModuleVerificationOfCancelRequest');
        }
        $this->set(compact('completed_values','pending_values'));              
    }
    public function preview($org_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }
        if (empty($org_id)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }

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
        $cancel_request_values = $this->LicenseModuleCancelRequest->find('first', array('conditions' => array('org_id'=>$org_id)));
        $basicInfoDetails = $this->BasicModuleBasicInformation->findById($org_id);
        $allDetails = array_merge($basicInfoDetails,$cancel_request_values);  
        
        $this->set(compact('allDetails'));
    }
}