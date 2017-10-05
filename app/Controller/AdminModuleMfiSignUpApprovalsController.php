<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AdminModuleMfiSignUpApprovalsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 5, 'order' => array('AdminModuleMfiSignUpDetail.id' => 'ASC'));

    public function view() {
        $this->loadModel('AdminModuleMfiSignUpDetail');
        $org_type_id = $this->request->query('org_type_id');
        if (!$this->request->is('post')) {
            if (!empty($org_type_id)) {
                $this->Session->write('SignUpDetail.OrgTypeId', $org_type_id);
            } else {
                $org_type_id = $this->Session->read('SignUpDetail.OrgTypeId');
            }
        }        

        if (!empty($org_type_id)) {
            $this->AdminModuleMfiSignUpDetail->recursive = -1;
            $this->Paginator->settings = array('conditions' => array('AdminModuleMfiSignUpDetail.approval_status' => '0', 'AdminModuleMfiSignUpDetail.org_type_id' => $org_type_id), 'limit' => 10);
            $pending_values = $this->Paginator->paginate('AdminModuleMfiSignUpDetail');

            $this->Paginator->settings = array('conditions' => array('AdminModuleMfiSignUpDetail.approval_status' => '1', 'AdminModuleMfiSignUpDetail.org_type_id' => $org_type_id), 'limit' => 10);
            $completed_values = $this->Paginator->paginate('AdminModuleMfiSignUpDetail');
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['AdminModuleMfiSignUpApprovalCompleted'])) {
                $completed_option = $this->request->data['AdminModuleMfiSignUpApprovalCompleted']['completed_search_option'];
                $completed_keyword = $this->request->data['AdminModuleMfiSignUpApprovalCompleted']['completed_search_keyword'];
                $completed_condition = array('AdminModuleMfiSignUpDetail.approval_status' => '0', "$completed_option LIKE '%$completed_keyword%'");

                $this->paginate = array(
                    'order' => array('AdminModuleMfiSignUpDetail.id' => 'ASC'),
                    'limit' => 10,
                    'conditions' => $completed_condition);
                $this->Paginator->settings = $this->paginate;
                $completed_values = $this->Paginator->paginate('AdminModuleMfiSignUpDetail');
            }
            if (!empty($this->request->data['AdminModuleMfiSignUpApprovalPending'])) {
                $pending_option = $this->request->data['AdminModuleMfiSignUpApprovalPending']['pending_search_option'];
                $pending_keyword = $this->request->data['AdminModuleMfiSignUpApprovalPending']['pending_search_keyword'];
                $pending_condition = array('AdminModuleMfiSignUpDetail.approval_status' => '0', "$pending_option LIKE '%$pending_keyword%'");

                $this->paginate = array(
                    'order' => array('AdminModuleMfiSignUpDetail.id' => 'ASC'),
                    'limit' => 10,
                    'conditions' => $pending_condition);
                $this->Paginator->settings = $this->paginate;
                $pending_values = $this->Paginator->paginate('AdminModuleMfiSignUpDetail');
            }
        }
        $this->set(compact('completed_values', 'pending_values'));
    }

    public function initial_approval($org_id = null) {
        try {
            $this->loadModel('AdminModuleMfiSignUpDetail');
            $this->loadModel('AdminModuleUser');
            $this->loadModel('AdminModuleUserProfile');
            $this->loadModel('AdminModuleUserGroupDistribution');
            $this->loadModel('BasicModuleBasicInformation');
            $this->loadModel('BasicModulePrimaryRegActDetail');
            $this->loadModel('LookupThisWebSiteUrl');
            $this->loadModel('LookupCurrentLicensingYear');

            $org_type_id = $this->Session->read('SignUpDetail.OrgTypeId');
            $current_year = null;
            $licensing_state_id = null;
            $user_group_id = null;
            $subject = '';
            $flag = 0;
            if (!empty($org_type_id) && $org_type_id == '1') {
                $licensing_state_id = 0;
                $user_group_id = 5;
                $subject = 'Login Information of MFI-DBMS Web Application of MRA for Submitting New License Application';
                $licensing_years = $this->LookupCurrentLicensingYear->find('first', array('conditions' => array('LookupCurrentLicensingYear.is_current_year' => 1)));
                $current_year = $licensing_years['LookupCurrentLicensingYear']['licensing_year'];
            } elseif (!empty($org_type_id) && $org_type_id == '2') {
                $licensing_state_id = 30;
                $user_group_id = 2;
                $subject = 'Login Information of MFI-DBMS Web Application of MRA for Existing Licensed MFI in MFI-DBMS of MRA';
            } elseif (!empty($org_type_id) && $org_type_id == '3') {
                $user_group_id = 4;
                $subject = 'Login Information of MFI-DBMS Web Application of MRA for Non-MFI(NGO, Banks, Govt. Organization, etc) in MFI-DBMS of MRA';
            }

            $sign_up_values = $this->AdminModuleMfiSignUpDetail->find('first', array('conditions' => array('AdminModuleMfiSignUpDetail.id' => $org_id)));
            $userid_or_email = $sign_up_values['AdminModuleMfiSignUpDetail']['email'];
            $password = $this->randPassword(8);
            $new_password_hash = Security::hash($password, 'sha256', true);
            $mobile_no = $sign_up_values['AdminModuleMfiSignUpDetail']['mobile_no'];
            $full_name_of_org = $sign_up_values['AdminModuleMfiSignUpDetail']['full_name_of_org'];
            $address_of_org = $sign_up_values['AdminModuleMfiSignUpDetail']['address_of_org'];
            $name_of_authorized_person = $sign_up_values['AdminModuleMfiSignUpDetail']['name_of_authorized_person'];
            $designation_of_authorized_person = $sign_up_values['AdminModuleMfiSignUpDetail']['designation_of_authorized_person'];
            $web_url = $this->LookupThisWebSiteUrl->find('first', array('fields' => array('LookupThisWebSiteUrl.web_url')));
            $created_by = $this->Session->read('User.Name');           
            
            $dataSource = $this->dtSource();
            $dataSource->begin(); /* Begin Transaction */             
            
            if (!empty($org_type_id) && ($org_type_id == '1' || $org_type_id == '2')) {
                
                $data_to_save_in_basic_info = array(
                    'full_name_of_org' => $full_name_of_org,
                    'address_of_org' => $address_of_org,
                    'licensing_year' => $current_year,
                    'name_of_authorized_person' => $name_of_authorized_person,
                    'designation_of_authorized_person' => $designation_of_authorized_person,
                    'licensing_state_id' => $licensing_state_id
                );
                
                $this->BasicModuleBasicInformation->create();
                $data_saved_in_basic_info = $this->BasicModuleBasicInformation->save($data_to_save_in_basic_info);
                $new_org_id = $data_saved_in_basic_info['BasicModuleBasicInformation']['id'];
                if ($data_saved_in_basic_info) {
                    $this->BasicModulePrimaryRegActDetail->updateAll(array('BasicModulePrimaryRegActDetail.org_id' => $new_org_id), array('BasicModulePrimaryRegActDetail.sign_up_org_id' => $org_id));
                }
                else {
                    $dataSource->rollback();
                }
            } elseif (!empty($org_type_id) && ($org_type_id == '3')) {
                $this->loadModel('CDBNonMfiBasicInfo');                
                
                $data_to_save_in_cdb_basic_info = array(
                    'name_of_org' => $full_name_of_org,
                    'name_of_officer' => $name_of_authorized_person,
                    'designation_of_officer' => $designation_of_authorized_person
                );
                $this->CDBNonMfiBasicInfo->create();
                $data_saved_in_cdb_basic_info = $this->CDBNonMfiBasicInfo->save($data_to_save_in_cdb_basic_info);
                if($data_saved_in_cdb_basic_info){
                    
                }
                else {                    
                    $dataSource->rollback();                
                }
                $new_org_id = $data_saved_in_cdb_basic_info['CDBNonMfiBasicInfo']['id'];
            }

            if (($data_saved_in_basic_info) || ($data_saved_in_cdb_basic_info)) {
                $data_to_save_in_user = array(
                    'user_name' => $userid_or_email,
                    'user_passwrd' => $new_password_hash,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $created_by,
                    'activation_status_id' => 1
                );

                $this->AdminModuleUser->create();
                $saved_data_in_user = $this->AdminModuleUser->save($data_to_save_in_user);
                if ($saved_data_in_user) {
                    $user_id = $saved_data_in_user['AdminModuleUser']['id'];

                    $data_to_save_in_group_distribution = array(
                        'user_id' => $user_id,
                        'user_group_id' => $user_group_id
                    );

                    $this->AdminModuleUserGroupDistribution->create();
                    $data_saved_in_group_distribution = $this->AdminModuleUserGroupDistribution->save($data_to_save_in_group_distribution);
                    
                    if ($data_saved_in_group_distribution) {
                        $data_to_save_in_user_profile = array(
                            'user_id' => $user_id,
                            'org_id' => $new_org_id,
                            'branch_id' => '',
                            'full_name_of_user' => $name_of_authorized_person,
                            'designation_of_user' => $designation_of_authorized_person,
                            'div_name_in_office' => '',
                            'org_name' => $full_name_of_org,
                            'email' => $userid_or_email,
                            'mobile_no' => $mobile_no,
                            'captcha' => ''
                        );                        
                        $this->AdminModuleUserProfile->create();
                        $data_saved_in_user_profile = $this->AdminModuleUserProfile->save($data_to_save_in_user_profile);
                        
                        if ($data_saved_in_user_profile) {
                            $this->AdminModuleMfiSignUpDetail->updateAll(array('approval_status' => 1), array('AdminModuleMfiSignUpDetail.id' => $org_id));
                            $flag = 1;
                            $dataSource->commit(); /* End Transaction */
                        }
                        else {                    
                            $dataSource->rollback();                
                        }
                    }
                    else {                    
                        $dataSource->rollback();                
                    }
                }
                else {                    
                    $dataSource->rollback();                
                }
            }            
            $message_body = 'Dear Applicant,' . "\r\n" . "\r\n" . 'Your ' . $full_name_of_org .
                ' has been shortlisted to submit license application using the MFI-DBMS web application. Please visit ' .
                $web_url['LookupThisWebSiteUrl']['web_url'] . ' and login to the system using' . "\r\n" .
                "User Id: $userid_or_email " . "\r\n" . "Password: $password " . "\r\n" . "\r\n" . 'Thanks' .
                "\r\n" . 'Microcredit Regulatory Authority';
            $is_sent_mail = false;
            if($flag == 1) {
                $is_sent_mail = $this->send_mail($subject, $message_body, $userid_or_email);                
            }
                                
            if ($is_sent_mail) {
                $this->redirect(array('action' => 'view?org_type_id=' . $org_type_id));
            }
            else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Message Sending Failed!'
                );
                $this->set(compact('msg'));
            }
        } catch (Exception $e) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Error Detected, ' . $e->getMessage()
            );
            $this->set(compact('msg'));
        }
    }

    public function details($org_id = null) {
        $this->loadModel('AdminModuleMfiSignUpDetail');
        $mfi_sign_up_details = $this->AdminModuleMfiSignUpDetail->find('first', array('conditions' => array('AdminModuleMfiSignUpDetail.id' => $org_id)));
        $this->loadModel('BasicModulePrimaryRegActDetail');
        $primary_reg_act_details = $this->BasicModulePrimaryRegActDetail->find('all', array('conditions' => array('BasicModulePrimaryRegActDetail.sign_up_org_id' => $org_id)));
        $this->set(compact('mfi_sign_up_details', 'primary_reg_act_details'));
    }
    
    public function delete($org_id = null) {
        $this->loadModel('AdminModuleMfiSignUpDetail');   
        $org_type_id = $this->Session->read('SignUpDetail.OrgTypeId');
        
        if ($this->AdminModuleMfiSignUpDetail->delete($org_id)) {
            if (!empty($org_type_id)) {
                $this->redirect(array('action' => 'view?org_type_id=' . $org_type_id));
            }
        }
    }

    public function preview($org_id = null, $title = null) {
        $this->set(compact('org_id', 'title'));
    }
}
