<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AdminModuleMfiAddressChangeApprovalsController extends AppController {
    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 5, 'order' => array('BasicModuleBranchInfo.id' => 'ASC'));

    public function view() {
        $this->loadModel('BasicModuleBranchInfo');
        $this->BasicModuleBranchInfo->recursive = 0;
        $this->Paginator->settings = array('conditions' => array('BasicModuleBranchInfo.is_approved' => 0, 'BasicModuleBranchInfo.office_type_id' => 1), 'limit' => 10);
        $pending_values = $this->Paginator->paginate('BasicModuleBranchInfo');        
        $this->Paginator->settings = array('conditions' => array('BasicModuleBranchInfo.is_approved' => 1,'BasicModuleBranchInfo.office_type_id' => 1), 'limit' => 10);
        $completed_values = $this->Paginator->paginate('BasicModuleBranchInfo');

        if ($this->request->is('post')) {
            if(!empty($this->request->data['AdminModuleMfiAddressChangeApprovalCompleted'])) {
                $completed_option = $this->request->data['AdminModuleMfiAddressChangeApprovalCompleted']['completed_search_option'];  
                $completed_keyword = $this->request->data['AdminModuleMfiAddressChangeApprovalCompleted']['completed_search_keyword'];
                $completed_condition = array('BasicModuleBranchInfo.is_approved' => '0',"$completed_option LIKE '%$completed_keyword%'"); 
            
                $this->paginate = array(
                'order' => array('BasicModuleBranchInfo.id' => 'ASC'),
                'limit' => 10,
                'conditions' => $completed_condition);            
                $this->Paginator->settings = $this->paginate;
                $completed_values = $this->Paginator->paginate('BasicModuleBranchInfo'); 
            }                 
            if(!empty($this->request->data['AdminModuleMfiAddressChangeApprovalPending'])) {
                $pending_option = $this->request->data['AdminModuleMfiAddressChangeApprovalPending']['pending_search_option'];  
                $pending_keyword = $this->request->data['AdminModuleMfiAddressChangeApprovalPending']['pending_search_keyword'];
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

    public function approval($org_id = null) {
        $this->loadModel('BasicModuleBranchInfo');  
        $this->loadModel('AdminModuleUserProfile');
                                                           
        $userid_or_email_values = $this->AdminModuleUserProfile->find('first', array('conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
        $userid_or_email = $userid_or_email_values['AdminModuleUserProfile']['email'];
        $message_subject = "Approval of MFI's Head Office Address Change in MFI-DBMS of MRA";
        $message_body = 'Dear Applicant,' . "\r\n" . "\r\n" . 'Your request  for head offfice address change has been approved '
                        . 'to MFI-DBMS of MRA'. "\r\n" . "\r\n" . 'Thanks' .
                        "\r\n" . 'Microcredit Regulatory Authority';

        $Email = new CakeEmail('gmail');
        $Email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))
                ->to($userid_or_email)
                ->subject($message_subject);
        if($Email->send($message_body)){                  
            $this->BasicModuleBranchInfo->updateAll(array('is_approved'=>null, 'is_current_address'=>null), array('BasicModuleBranchInfo.org_id' => $org_id,'BasicModuleBranchInfo.is_current_address' => '1', 'BasicModuleBranchInfo.office_type_id' => '1'));
            $this->BasicModuleBranchInfo->updateAll(array('is_approved' => 1, 'is_current_address' => 1), array('BasicModuleBranchInfo.org_id' => $org_id,'BasicModuleBranchInfo.is_approved' => '0', 'BasicModuleBranchInfo.office_type_id' => '1'));
            $this->redirect(array('action' => 'view'));
        }
    }

    public function current_address_details($org_id = null) {
        $this->loadModel('BasicModuleBranchInfo');
        $current_address_details = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id,'BasicModuleBranchInfo.office_type_id' => 1,'BasicModuleBranchInfo.is_current_address' => 1)));
        $this->set(compact('current_address_details'));
    }
    
    public function new_address_details($org_id = null) {
        $this->loadModel('BasicModuleBranchInfo');
        $new_address_details = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id,'BasicModuleBranchInfo.office_type_id' => 1,'BasicModuleBranchInfo.is_current_address' => null)));
        $this->set(compact('new_address_details'));
    }

    public function preview($org_id = null, $title = null, $status = null) {         
        $this->set(compact('org_id','title','status'));
    }
}