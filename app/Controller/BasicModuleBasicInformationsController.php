<?php

App::uses('AppController', 'Controller');

class BasicModuleBasicInformationsController extends AppController {

    public $helpers = array('Js');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($opt = null) {
        $this->Session->write('Form.Mode', null);
        $this->Session->write('Form.IsEditable',true);
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_ids = $this->Session->read('User.GroupIds');

        if (empty($IsValidUser) || empty($user_group_ids)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $opt_all = false;
        if (!empty($user_group_ids) && in_array(1, $user_group_ids)) {
            $this->Session->write('Form.IsEditable',false);
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $total_values = $this->executeQuery("select count(id) as total from basic_module_basic_informations");            
        $total=$total_values[0][0]['total'];
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array('BasicModuleBasicInformation.id' => $org_id);
        } else {            
            $condition = array('BasicModuleBasicInformation.id BETWEEN ? AND ?' => array(($total-10),$total));        
        }
        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleBasicInformation']['search_option'];
            $keyword = $this->request->data['BasicModuleBasicInformation']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array("AND" => array("$option LIKE '%$keyword%'", $condition));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }

        $this->set(compact('org_id', 'user_group_ids', 'opt_all','total'));
        
        $this->paginate = array('conditions' => $condition, 'recursive' => -1, 'limit' => 8);
        if (!empty($user_group_ids) && in_array(5, $user_group_ids)) {
            $this->paginate['group'] = array('form_serial_no');
            $this->paginate['order'] = array('form_serial_no' => 'asc');
        }
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleBasicInformation');        
        $this->set(compact('values'));
    }

    public function add() {
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg', 'IsValidUser'));
            return;
        }
        $this->set(compact('IsValidUser'));
        $primary_reg_act_options = $this->BasicModuleBasicInformation->LookupBasicPrimaryRegistrationAct->find('list', array('fields' => array('LookupBasicPrimaryRegistrationAct.id', 'LookupBasicPrimaryRegistrationAct.mra_act')));
        $regauthorityoptions = $this->BasicModuleBasicInformation->LookupBasicRegistrationAuthority->find('list', array('fields' => array('LookupBasicRegistrationAuthority.id', 'LookupBasicRegistrationAuthority.registration_authority')));
        $this->set(compact('typeoforganizationoptions', 'primary_reg_act_options', 'regauthorityoptions'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {

            if (!empty($this->request->data)) {
                $reqData = $this->request->data;
                $data_mode = $this->Session->read('Data.Mode');

                if (!$data_mode || $data_mode == 'insert') {
                    $this->BasicModuleBasicInformation->create();
                    $newData = $this->BasicModuleBasicInformation->save($reqData);
                    if ($newData) {
                        $org_id = $newData['BasicModuleBasicInformation']['id'];
                        $this->Session->write('Org.Id', $org_id);
                        $this->Session->write('Data.Mode', 'update');
                        $this->Session->write('Form.Mode', 'add');
                    } else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => 'Data insertion failed !'
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                } else {
                    $org_id = $this->Session->read('Org.Id');
                    $title = 'Name and Registration';
                    $this->BasicModuleBasicInformation->id = $org_id;
                    if ($this->BasicModuleBasicInformation->save($reqData)) {
                        $this->redirect(array('action' => 'preview', $org_id, $title));
                    } else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => 'Data update failed !'
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                }
            }
        }
    }
    
    public function edit($org_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
        }           
                
        $this->BasicModuleBasicInformation->recursive = -1;
        $orgDetails = $this->BasicModuleBasicInformation->findById($org_id);
        if (empty($orgDetails)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid information !'
            );

            $this->set(compact('msg'));
            return;
        } 
        
        $licensing_state_id = $orgDetails['BasicModuleBasicInformation']['licensing_state_id'];
        if(!empty($licensing_state_id)&&$licensing_state_id=='0') {
            $title = 'Name and Registration';
        }
        if(!empty($licensing_state_id)&&$licensing_state_id=='30') {
            $title = 'Primary Information';
        }
        $this->loadModel('BasicModulePrimaryRegActDetail');
        $primary_reg_act_details = $this->BasicModulePrimaryRegActDetail->find('all', array('conditions' => array('BasicModulePrimaryRegActDetail.org_id' => $org_id))); 
        $selected_reg_act_values = Hash::extract($primary_reg_act_details, '{n}.BasicModulePrimaryRegActDetail.primary_reg_act_id');
        $this->loadModel('LookupBasicPrimaryRegistrationAct');
        $primary_reg_act_options = $this->LookupBasicPrimaryRegistrationAct->find('list', array('fields' => array('LookupBasicPrimaryRegistrationAct.id', 'LookupBasicPrimaryRegistrationAct.primary_registration_act')));        
        $this->set(compact('IsValidUser', 'org_id', 'is_selected_id','licensing_state_id','orgDetails','primary_reg_act_details', 'primary_reg_act_options', 'selected_reg_act_values'));
        if (empty($this->request->data)) {            
            $this->request->data = $orgDetails;            
        }
        if ($this->request->is(array('post', 'put'))) {
            $flag=0;            
            $this->BasicModuleBasicInformation->id = $org_id;           
            if ($this->BasicModuleBasicInformation->save($this->request->data)) {                
                $this->BasicModulePrimaryRegActDetail->deleteAll(array('BasicModulePrimaryRegActDetail.org_id' => $org_id), false);
                if(!empty($this->request->data['BasicModuleBasicInformation']['primary_reg_act_id'])) {
                    $primary_reg_act_ids = $this->request->data['BasicModuleBasicInformation']['primary_reg_act_id'];                    
                    foreach($primary_reg_act_ids as $primary_reg_act_id) {
                        $reg_act_data = array();
                        $reg_act_data['org_id'] =  $org_id;
                        $reg_act_data['primary_reg_act_id'] =  $primary_reg_act_id;                    
                        $this->BasicModulePrimaryRegActDetail->create();
                        $this->BasicModulePrimaryRegActDetail->save($reg_act_data);
                    }
                } 
                $flag=1;
            }
            if($flag==1) {
                $this->redirect(array('action' => 'view', 'all'));
            }
            else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Unable to update the information of organization !'
                );
                $this->set(compact('msg'));
                return;
            }            
        }                
    }

    public function details($org_id = null) {

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

        $filename = 'MostBasicInformation';

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
        $licensing_state_id = $basicInfoDetails['BasicModuleBasicInformation']['licensing_state_id'];
        
        $this->loadModel('BasicModulePrimaryRegActDetail');
        $primary_reg_act_details = $this->BasicModulePrimaryRegActDetail->find('all', array('conditions' => array('BasicModulePrimaryRegActDetail.org_id' => $org_id))); 
                        
        $this->set(compact('basicInfoDetails', 'filename','licensing_state_id','primary_reg_act_details'));
    }

    public function preview($org_id = null) {           
        $this->set(compact('org_id'));
    }

    public function export_pdf($org_id = null) {

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');

            if (empty($org_id)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid organization information !'
                );

                $this->set(compact('msg'));
                return;
            }
        }

        $this->BasicModuleBasicInformation->id = $org_id;
        if (!$this->BasicModuleBasicInformation->exists()) {
            throw new NotFoundException(__('Invalid Information'));
        }
        // increase memory limit in PHP
        ini_set('memory_limit', '1024M');
        $basicInfoDetails = $this->BasicModuleBasicInformation->read(null, $org_id);
        $filename = 'MostBasicInformation';
        $this->set(compact('basicInfoDetails', 'filename'));
        $this->response->download($filename . '.pdf');
    }

    public function application_preview_before_submit($org_id = null) {
        try {
            if (empty($org_id)) {
                $org_id = $this->Session->read('Org.Id');
                if (empty($org_id)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Invalid organization information !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
            }
            $this->BasicModuleBasicInformation->recursive = 2;
            $allDetails = $this->BasicModuleBasicInformation->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
            $mfiDetails = $allDetails['BasicModuleBasicInformation'];
            $this->loadModel('BasicModulePrimaryRegActDetail');
            $primaryRegActDetails = $this->BasicModulePrimaryRegActDetail->find('all', array('conditions' => array('BasicModulePrimaryRegActDetail.org_id' => $org_id))); 
            $this->loadModel('LookupBasicProposedAddressType');
            $proposed_address_types = $this->LookupBasicProposedAddressType->find('all');
            $allProposedAddressDetails = $allDetails['BasicModuleProposedAddress'];     
            $allRegistrationDetails = $allDetails['BasicModuleRegistrationDetail'];            
            $allProposedBranchDetails = $allDetails['BasicModuleProposedBranchInfo'];
            $allBankInfoForTransactionDetails = $allDetails['BasicModuleBankInfoForTransaction'];
            $allRevolvingLoanFundDetails = $allDetails['BasicModuleRevolvingLoanFund'];
            $allProposedSavingDetails = $allDetails['BasicModuleProposedSavingsOrDepositInfo'];
            $allProposedLoanDetails = $allDetails['BasicModuleProposedLoanInfo'];
            $this->loadModel('LookupBasicStatementYear');
            $total_income_exp_balance_sheet_years = $this->LookupBasicStatementYear->find('all');
            $allIncomeExpenditureDetails = $allDetails['BasicModuleEstimatedIncomeExpenditureStatement'];        
            $allBalanceSheetDetails = $allDetails['BasicModuleEstimatedBalanceSheet'];
            $commencementDateDetails =$allDetails['BasicModuleProposedDateOfCommencementMcOperation'];        
            $this->loadModel('LookupBasicPlanForMcYear');
            $total_years = $this->LookupBasicPlanForMcYear->find('all');
            $allMcActivitiDetails = $allDetails['BasicModulePlanForMicroCreditActivity'];
            $this->loadModel('LookupBasicOfficeUsageType');
            $usage_types = $this->LookupBasicOfficeUsageType->find('all');
            $allOfficeSpaceUsageDetails = $allDetails['BasicModuleOfficeSpaceUsage'];
            $allImmovablePropertyDetails = $allDetails['BasicModuleOtherImmovableProperty'];
            $allGeneralBodyMemberDetails = $allDetails['BasicModuleGeneralBodyMemberInfo'];
            $allGBMemberEducationDetails = $allDetails['BasicModuleGeneralBodyMembersEducationDetail'];
            $allGBMemberFinancialInvolvmentDetails = $allDetails['BasicModuleGeneralBodyMembersFinancialInvolvement'];
            $allGBMemberCaseOrSuitDetails = $allDetails['BasicModulGeneralBodyMembersCaseOrSuitInfo'];
            $allGBMemberOtherBusinessInvolvmentDetails = $allDetails['BasicModulGeneralBodyMembersOtherBusinessInvolvment'];
            $allMembersOfCouncilDirectorDetails = $allDetails['BasicModulMembersOfCouncilDirectorsInformation'];
            $allProposedOrActiveCeoDetails = $allDetails['BasicModuleProposedOrActiveCeoInformation'];
            $allEmployeeDetails = $allDetails['BasicModuleEmployeeInformation'];            
            $allSisterOrganizationDetails = $allDetails['BasicModuleSisterOrganizationInformation'];
            $allOtherProgramDetails = $allDetails['BasicModuleOtherProgramsInformation'];
            $allAuditDetails = $allDetails['BasicModuleAuditInformation'];
            $allRejectionDetails = $allDetails['BasicModuleRejectionInformation'];
            $allAttachmentDetails = $allDetails['BasicModuleFileAttachment'];            
            $this->set(compact('org_id','mfiDetails','primaryRegActDetails','proposed_address_types','allProposedAddressDetails','allRegistrationDetails','allProposedBranchDetails','allBankInfoForTransactionDetails','allRevolvingLoanFundDetails','allProposedSavingDetails','allProposedLoanDetails','total_income_exp_balance_sheet_years','allIncomeExpenditureDetails', 'allBalanceSheetDetails','commencementDateDetails','total_years','allMcActivitiDetails','usage_types','allOfficeSpaceUsageDetails','allImmovablePropertyDetails','allGeneralBodyMemberDetails','allGBMemberEducationDetails','allGBMemberFinancialInvolvmentDetails','allGBMemberCaseOrSuitDetails','allGBMemberOtherBusinessInvolvmentDetails','allMembersOfCouncilDirectorDetails','allProposedOrActiveCeoDetails','allEmployeeDetails','allSisterOrganizationDetails','allOtherProgramDetails','allAuditDetails','allRejectionDetails','allAttachmentDetails'));
        }
        catch(Exception $ex) {
            debug($ex->getMessage());
        }
    }

    public function final_submit($org_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
            if (empty($org_id)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid organization information !'
                );
                $this->set(compact('msg'));
                return;
            }
        }
        $this->loadModel('LookupCurrentLicensingYear');
        $current_year = $this->LookupCurrentLicensingYear->field('licensing_year', array('LookupCurrentLicensingYear.is_current_year' => 1));
        $max_serial_no = $this->serial_generator_by_max('BasicModuleBasicInformation', 'form_serial_no', array('1 = 1'));
        if (empty($max_serial_no)||($max_serial_no==1)) {
            $max_serial_no = (int) $current_year * 1000;
        }
        $form_serial_no = $max_serial_no + 1;
        $date_of_application = "'" . date('Y-m-d') . "'";
        $newData = array('BasicModuleBasicInformation.form_serial_no' => $form_serial_no, 'BasicModuleBasicInformation.date_of_application' => $date_of_application, 'BasicModuleBasicInformation.licensing_year' => "'".$current_year."'", 'BasicModuleBasicInformation.licensing_state_id' => 1, 'BasicModuleBasicInformation.is_submit' => 1);
        $done = $this->BasicModuleBasicInformation->updateAll($newData, array('BasicModuleBasicInformation.id' => $org_id));    
        $this->redirect(array('action' => 'application_preview_before_submit'));
    }
}
