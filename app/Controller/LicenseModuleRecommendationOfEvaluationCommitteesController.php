<?php

App::uses('AppController', 'Controller');

class LicenseModuleRecommendationOfEvaluationCommitteesController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator');
    public $components = array('Paginator');

    public function view($opt = 'all', $mode = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(7,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $this_state_id = $this->request->query('this_state_id');
        if (!empty($this_state_id))
            $this->Session->write('Current.StateId', $this_state_id);
        else 
            $this_state_id = $this->Session->read('Current.StateId');
        
        $current_year =  $this->Session->read('Current.LicensingYear');
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleRecommendationOfEvaluationCommittee.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleRecommendationOfEvaluationCommittee']['search_option'];
            $keyword = $this->request->data['LicenseModuleRecommendationOfEvaluationCommittee']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }
        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all'));
        
        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id - 1);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
        
        if (!empty($condition)) { 
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
        }
        
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');
        $values_not_approved = $this->LicenseModuleRecommendationOfEvaluationCommittee->BasicModuleBasicInformation->find('all', array('fields' => $all_fields, 'conditions' => $condition1));

        $this->paginate = array('conditions' => $condition2, 'group' => array('org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleRecommendationOfEvaluationCommittee->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_approved = $this->Paginator->paginate('LicenseModuleRecommendationOfEvaluationCommittee');
        
        $this->set(compact('values_approved', 'values_not_approved'));
    }

    public function add() {
        $this->loadModel('LookupInitialEvaluationPassMark');
        $minimum_pass_mark = $this->LookupInitialEvaluationPassMark->find('first', array('fields' => array('LookupInitialEvaluationPassMark.marks'), 'conditions' => array('LookupInitialEvaluationPassMark.initial_evaluation_pass_mark_type_id' => 1)));
        $existing_org_ids = $this->LicenseModuleRecommendationOfEvaluationCommittee->find('list', array('fields' => 'org_id', 'group' => 'org_id'));
        $this->loadModel('BasicModuleGeneralInfoMaintenance');
        $passed_org_id_values = $this->BasicModuleGeneralInfoMaintenance->find('all', array('fields' => 'org_id', 'group' => 'org_id', 'conditions' => array("BasicModuleGeneralInfoMaintenance.total_obtained_mark_percentage >= " => $minimum_pass_mark['LookupInitialEvaluationPassMark']['marks'])));
        $org_ids = hash::extract($passed_org_id_values, '{n}.BasicModuleGeneralInfoMaintenance.org_id');
        $this->loadModel('BasicModuleBasicInformation');
        $orgIdNames = $this->BasicModuleBasicInformation->find('all', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_ids)));
        $orgNameOptions = Hash::combine($orgIdNames, '{n}.BasicModuleBasicInformation.id', '{n}.BasicModuleBasicInformation.full_name_of_org');
        $approval_status_options = $this->LicenseModuleRecommendationOfEvaluationCommittee->LookupLicenseRecommendationStatus->find('list', array('fields' => array('LookupLicenseRecommendationStatus.id', 'LookupLicenseRecommendationStatus.recommendation_status')));
        $this->set(compact('orgNameOptions', 'approval_status_options'));

        if ($this->request->is('post')) {
            $this->LicenseModuleRecommendationOfEvaluationCommittee->create();
            if ($this->LicenseModuleRecommendationOfEvaluationCommittee->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function evaluate($org_id = null) {
                        
        if ($this->request->is(array('post', 'put'))){
            $posted_data = $this->request->data['LicenseModuleRecommendationOfEvaluationCommittee'];
            $this->LicenseModuleRecommendationOfEvaluationCommittee->create();
            if ($this->LicenseModuleRecommendationOfEvaluationCommittee->save($posted_data)) {
                
                if (empty($org_id))
                    $org_id = $posted_data['org_id'];
                
                $this_state_id = $this->Session->read('Current.StateId');
                $current_year = $this->Session->read('Current.LicensingYear');

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $this_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                //$org_state_history = array(
                //    'org_id' => $org_id,
                //    'state_id' => $this_state_id - 1,
                //    'licensing_year' => $current_year,
                //    'date_of_state_update' => date('Y-m-d'),
                //    'user_name' => $this->Session->read('User.Name'));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $this_state_id,
                    'licensing_year' => $current_year,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

                $this->redirect(array('action' => 'view'));
                return;                
            }
        }
        
        $orgFullName = $this->LicenseModuleRecommendationOfEvaluationCommittee->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];            
        $this->set(compact('orgName', 'org_id'));
    }

    public function re_evaluate($org_id=null) {
        
        if ($this->request->is(array('post', 'put'))){
            $this->LicenseModuleRecommendationOfEvaluationCommittee->id =  $this->request->data['LicenseModuleRecommendationOfEvaluationCommittee']['id'];
            if ($this->LicenseModuleRecommendationOfEvaluationCommittee->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
        
        $orgFullName = $this->LicenseModuleRecommendationOfEvaluationCommittee->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];
        
        $approval_status_options = $this->LicenseModuleRecommendationOfEvaluationCommittee->LookupLicenseRecommendationStatus->find('list', array('fields' => array('LookupLicenseRecommendationStatus.id', 'LookupLicenseRecommendationStatus.recommendation_status')));
        $this->set(compact('org_id','orgName','approval_status_options'));
        
        //$post = $this->LicenseModuleRecommendationOfEvaluationCommittee->findById($id);
        $post = $this->LicenseModuleRecommendationOfEvaluationCommittee->find('first', array('conditions' => array('LicenseModuleRecommendationOfEvaluationCommittee.org_id' => $org_id)));
        if (!$post){
            throw new NotFoundException('Invalid Information');
        }
        if (!$this->request->data){
            $this->request->data = $post;
        }
    }
    
    public function recommend_details($org_id=null){        
        $licApprovalDetails = $this->LicenseModuleRecommendationOfEvaluationCommittee->find('first', array('conditions' => array('LicenseModuleRecommendationOfEvaluationCommittee.org_id' => $org_id)));
        $this->set(compact('licApprovalDetails'));
    }
    
    public function preview($org_id=null) {  
        $this->set(compact('org_id'));
    }
    
    public function details($id = null){
        if (!$id){
            throw new NotFoundException('Invalid Information');
        }
        $licApprovalDetails = $this->LicenseModuleRecommendationOfEvaluationCommittee->findById($id);
        if ($licApprovalDetails){
            if (!empty($licApprovalDetails['BasicModuleBasicInformation']['id'])){
                $org_id = $licApprovalDetails['BasicModuleBasicInformation']['id'];                
                $this->loadModel('LicenseModuleFieldInspection');
                $licFieldInspectionDetails = $this->LicenseModuleFieldInspection->find('first', array('conditions' => array('LicenseModuleFieldInspection.org_id' => $org_id)));
                $this->loadModel('LicenseModuleEvaluationDetailInfo');
                $selectedFields = array('LicenseModuleInitialAssessmentMark.total_assessment_marks');
                $condition_basic_option = array('LicenseModuleEvaluationDetailInfo.org_id' => $org_id, 'LookupLicenseInitialAssessmentParameter.is_published' => '1');
                $basicEvaluationOptions = $this->LicenseModuleEvaluationDetailInfo->find('all', array('fields' => $selectedFields, 'conditions' => $condition_basic_option));
                $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1')));
                $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
                if (!empty($basicEvaluationOptions)) {
                    $basicEvaluationOptions = Hash::insert($basicEvaluationOptions, '{n}.LicenseModuleEvaluationDetailInfo.total_marks', array_sum($parameterOptionMaxList));
                }
                $selectedFields = array('LookupLicenseInitialAssessmentParameter.parameter',
                    'LookupLicenseInitialAssessmentParameterOption.parameter_option', 'LookupLicenseInitialAssessmentParameterOption.assessment_marks');
                $condition_mandatory = array('LicenseModuleEvaluationDetailInfo.org_id' => $org_id, 'LookupLicenseInitialAssessmentParameter.is_mandatory' => 1, 'LookupLicenseInitialAssessmentParameter.is_published' => '1');
                $licenseEvaluationMandatory = $this->LicenseModuleEvaluationDetailInfo->find('all', array('fields' => $selectedFields, 'conditions' => $condition_mandatory));
            }            
            $this->set(compact('org_id', 'licApprovalDetails', 'licFieldInspectionDetails', 'basicEvaluationOptions', 'licenseEvaluationMandatory'));
        }
    }    
}