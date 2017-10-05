<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AdminModuleMfiBranchActivateDeactivateApprovalsController extends AppController {
    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 5, 'order' => array('BasicModuleBranchInfo.id' => 'ASC'));

    public function view() {
        $this->loadModel('BasicModuleBranchInfo');
        $this->BasicModuleBranchInfo->recursive = 0;
        $this->Paginator->settings = array('conditions' => array('BasicModuleBranchInfo.is_approved' => 0), 'limit' => 10);
        $pending_values = $this->Paginator->paginate('BasicModuleBranchInfo');        
        $this->Paginator->settings = array('conditions' => array('BasicModuleBranchInfo.is_approved' => 1), 'limit' => 10);
        $completed_values = $this->Paginator->paginate('BasicModuleBranchInfo');        
        if ($this->request->is('post')) {
            if(!empty($this->request->data['AdminModuleMfiBranchActivateDeactivateApprovalCompleted'])) {
                $completed_option = $this->request->data['AdminModuleMfiBranchActivateDeactivateApprovalCompleted']['completed_search_option'];  
                $completed_keyword = $this->request->data['AdminModuleMfiBranchActivateDeactivateApprovalCompleted']['completed_search_keyword'];
                $completed_condition = array('BasicModuleBranchInfo.is_approved' => '0',"$completed_option LIKE '%$completed_keyword%'"); 
            
                $this->paginate = array(
                'order' => array('BasicModuleBranchInfo.id' => 'ASC'),
                'limit' => 10,
                'conditions' => $completed_condition);            
                $this->Paginator->settings = $this->paginate;
                $completed_values = $this->Paginator->paginate('BasicModuleBranchInfo'); 
            }                 
            if(!empty($this->request->data['AdminModuleMfiBranchActivateDeactivateApprovalPending'])) {
                $pending_option = $this->request->data['AdminModuleMfiBranchActivateDeactivateApprovalPending']['pending_search_option'];  
                $pending_keyword = $this->request->data['AdminModuleMfiBranchActivateDeactivateApprovalPending']['pending_search_keyword'];
                $pending_condition = array('BasicModuleBranchInfo.is_approved' => '0',"$pending_option LIKE '%$pending_keyword%'"); 

                $this->paginate = array(
                'order' => array('BasicModuleBranchInfo.id' => 'ASC'),
                'limit' => 10,
                'conditions' => $pending_condition);
                $this->Paginator->settings = $this->paginate;
                $pending_values = $this->Paginator->paginate('BasicModuleBranchInfo');
            }
        }
        $this->set(compact('completed_values', 'pending_values'));
    }

    public function approval($branch_id = null, $org_id = null, $approval_status = null, $activation_status = null) {
        //$this->autoRender = false;
        $this->loadModel('BasicModuleBranchInfo');  
        $this->loadModel('AdminModuleUserProfile');
              
        if($approval_status=='0'&&$activation_status=='0') { 
            $activation_status = 'Activation';
            $data = array('is_approved' => 1, 'approval_date' => "'".date('Y-m-d')."'", 'is_active' => 1);
        }
        elseif($approval_status=='0'&&$activation_status=='1') {
            $activation_status = 'Deactivation';
            $data = array('is_approved' => 1, 'approval_date' => "'".date('Y-m-d')."'", 'is_active' => 0);
        }
        $condition = array('BasicModuleBranchInfo.org_id' => $org_id,'BasicModuleBranchInfo.id' => $branch_id,'BasicModuleBranchInfo.is_approved' => $approval_status);
        $this->BasicModuleBranchInfo->updateAll($data,$condition);
        
        $userid_or_email_values = $this->AdminModuleUserProfile->find('first', array('conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
        //$userid_or_email = $userid_or_email_values['AdminModuleUserProfile']['email'];
        $userid_or_email = '';
        $message_subject = "Approval of MFI's Branch $activation_status in MFI-DBMS of MRA";
        $message_body = 'Dear Applicant,' . "\r\n" . "\r\n" . 'Your request  for branch '. $activation_status .' has been approved '
                        . 'to MFI-DBMS of MRA'. "\r\n" . "\r\n" . 'Thanks' .
                        "\r\n" . 'Microcredit Regulatory Authority';

        $Email = new CakeEmail('gmail');
        $Email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))
                ->to($userid_or_email)
                ->subject($message_subject);
        if(!$Email->send($message_body)){                  
            $msg = array(
                'type' => 'error',
                'title' => 'Error... !',
                'msg' => 'Notification for Approval of MFI Branch '. $activation_status .' Failed!'
                //'msg' => "Notification for Approval of MFI's Branch $activation_status Failed!"
            );
            $this->set(compact('msg')); 
            //return;
        }
        $this->redirect(array('action' => 'view'));
    }

    public function cancel_approval($branch_id = null, $org_id = null, $approval_status = null, $activation_status = null) {        
        $this->autoRender = false;
        $this->loadModel('BasicModuleBranchInfo');        
        $conditions = array('BasicModuleBranchInfo.org_id'=>(int)$org_id,'BasicModuleBranchInfo.id'=>(int)$branch_id);

        if($approval_status=='1'&&$activation_status=='0') {
            $data = array('is_approved' => 0, 'approval_date' => "'".date('Y-m-d')."'", 'is_active' => 1);
        }
        elseif($approval_status=='1'&&$activation_status=='1') {
            $data = array('is_approved' => 0, 'approval_date' => "'".date('Y-m-d')."'", 'is_active' => 0);                       
        }
        $data_updated = $this->BasicModuleBranchInfo->updateAll($data,$conditions);
        if($data_updated) {
            return $this->redirect(array('action' => 'view'));        
        }
    }
}