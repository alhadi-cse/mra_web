<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleRejectionDetailInfosController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    
    
    public function save_rejection_history($org_id = null, $licensing_state_id = null, $reject_suspend_cancel_history_type_id = null, $reject_suspend_cancel_category_id = null, $reject_suspend_cancel_reason_id = null, $comment = null) {

//        `org_id`
//        `licensing_state_id`
//        `reject_suspend_cancel_history_type_id`
//        `reject_suspend_cancel_category_id`
//        `reject_suspend_cancel_reason_id`
//        `reject_suspend_cancel_date`
//        `comment`
        //$this->save_rejection_history($org_id, $licensing_state_id, $reject_suspend_cancel_history_type_id, $reject_suspend_cancel_category_id, $reject_suspend_cancel_reason_id, $comment);

        $org_rejection_history = array(
                'org_id' => $org_id,
                'licensing_state_id' => $licensing_state_id,
                'reject_suspend_cancel_history_type_id' => $reject_suspend_cancel_history_type_id,
                'reject_suspend_cancel_category_id' => $reject_suspend_cancel_category_id,
                'reject_suspend_cancel_reason_id' => $reject_suspend_cancel_reason_id,
                'reject_suspend_cancel_date' => date('Y-m-d'),
                'comment' => $comment);

        if (!empty($org_rejection_history)) {

            $this->loadModel('LicenseModuleRejectSuspendCancelHistory');
            $this->LicenseModuleRejectSuspendCancelHistory->create();
            $this->LicenseModuleRejectSuspendCancelHistory->save($org_rejection_history);

//            $newData = $this->LicenseModuleRejectSuspendCancelHistory->save($org_rejection_history);
//            if ($newData) {
//                $id = $newData['LicenseModuleRejectSuspendCancelHistory']['id'];
////                $this->redirect(array('action' => 'preview', $id));
//            }
        }

        return;
    }

    public function view($previous_state_id = null, $initial_rejection_state_id = null, $final_rejection_state_id = null) {

        if (empty($initial_rejection_state_id))
            $initial_rejection_state_id = $this->request->query('initial_rejection_state_id');

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($final_rejection_state_id))
            $final_rejection_state_id = $this->request->query('final_rejection_state_id');

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($previous_state_id))
            $previous_state_id = $this->request->query('previous_state_id');

        if (empty($previous_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid previous state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !in_array(1,$user_group_id)) {
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

        //if(!empty($final_rejection_state_id))
        //$this->Session->write('Current.NextStateId', $final_rejection_state_id);
        //$this_state_id = $this->Session->read('Current.StateId');
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

        $rejection_type_id = 1;
        $rejection_category_id = 1;
        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options_initial = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $rejection_category_id = 4;
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options_final = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $this->loadModel('LicenseModuleEvaluationDetailInfo');
        $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupInitialEvaluationPassMark');
        $evaluation_marks_details = $this->LookupInitialEvaluationPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {
                $this->loadModel('LicenseModuleStateHistory');
                if (!empty($org_id))
                    $condition0 = array('org_id' => $org_id, 'licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                else
                    $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                $already_appealed_ids = $this->LicenseModuleStateHistory->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

                if (!empty($org_id))
                    $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
                else
                    $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleEvaluationDetailInfo');

                if (!empty($org_id))
                    $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);
                else
                    $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleEvaluationDetailInfo');
            }
        }
        
        $admin_approval_state_id = 9;
        $not_approval_status_id = 2;
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no', 'LookupLicenseApprovalStatus.approval_status');
        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $admin_approval_state_id, 'LicenseModuleAdministrativeApproval.approval_status_id' => $not_approval_status_id);
        else
            $conditions = array('licensing_year' => $current_year, 'licensing_state_id' => $admin_approval_state_id, 'LicenseModuleAdministrativeApproval.approval_status_id' => $not_approval_status_id);

        $this->loadModel('LicenseModuleAdministrativeApproval');
        $this->LicenseModuleAdministrativeApproval->recursive = 0;
        $values_not_approved_in_admin_approval = $this->LicenseModuleAdministrativeApproval->find('all', array('fields' => $all_fields, 'conditions' => $conditions, 'group' => 'BasicModuleBasicInformation.id'));

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id,  'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline >=' => date('Y-m-d'));
        else
            $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleStateHistory');
        $values_waiting_for_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleStateHistory.org_id')));


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id,  'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));
        else
            $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));

        $this->loadModel('LicenseModuleStateHistory');
        $values_not_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleStateHistory.org_id')));
        
        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'values_not_appealed', 'values_appeal_but_failed', 'values_waiting_for_appealed', 'values_evaluated_failed', 'values_not_approved_in_admin_approval'));
    }
    
    
    public function initial_rejection($org_id = null, $previous_state_id = null, $initial_rejection_state_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !in_array(1,$user_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        //if(!empty($initial_rejection_state_id))
        //$this->Session->write('Current.NextStateId', $initial_rejection_state_id);
        //$previous_state_id = $this->Session->read('Current.StateId');
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

        $rejection_type_id = 1;
        $rejection_category_id = 1;
//        if (empty($previous_state_id) || $previous_state_id <= 4)
//            $rejection_category_id = 1;
//        else
//            $rejection_category_id = 1;
        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        if ($this->request->is(array('post', 'put'))) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationReject'];
                if (!empty($newData)) {
                    $this->loadModel('LicenseModuleStateName');
                    $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $initial_rejection_state_id)));

                    if (!empty($stateDetails) && !empty($stateDetails[$initial_rejection_state_id]))
                        $days = $stateDetails[$initial_rejection_state_id];
                    else
                        $days = 0;

                    $rejection_reason_id = $newData['rejection_option_id'];
                    if (!empty($rejection_reason_id))
                        $reason = $rejection_options[$rejection_reason_id];
                    else
                        $reason = 'undefined reason';

                    $done = $this->initial_reject_and_send_notification($org_id, $initial_rejection_state_id, $reason, $days);
                    
                    if($done) {
                        $comment = 'Initial rejection in licensing process';
                        $this->save_rejection_history($org_id, $initial_rejection_state_id, $rejection_type_id, $rejection_category_id, $rejection_reason_id, $comment);
                        
                        $this->redirect(array('action' => 'view', 4, 21, 50));                        
                    } else {
                        $msg = array(
                            'type' => 'Error',
                            'title' => 'Error... . . !',
                            'msg' => 'Initial rejection failed !'
                        );
                        $this->set(compact('msg'));    
                    }
                    return false;
                }
            } catch (Exception $ex) {
                //$this->redirect(array('action' => 'view', 4, 21, 50));
            }
        }

        $this->loadModel('LicenseModuleEvaluationDetailInfo');
        $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $orgDetail = $this->LicenseModuleEvaluationDetailInfo->find('first', array('fields' => $all_fields_with_marks, 'conditions' => array('LicenseModuleEvaluationDetailInfo.org_id' => $org_id), 'group' => array('LicenseModuleEvaluationDetailInfo.org_id')));

        $this->set(compact('org_id', 'orgDetail', 'rejection_options', 'total_marks'));
    }

    public function final_rejection($org_id = null, $previous_state_id = null, $initial_rejection_state_id = null, $final_rejection_state_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        //if(!empty($final_rejection_state_id))
        //$this->Session->write('Current.NextStateId', $final_rejection_state_id);
        //$this_state_id = $this->Session->read('Current.StateId');
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

        $rejection_type_id = 1;
        $rejection_category_id = 4;
        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

//        $rejection_category_id = 3;
//        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
//        $rejection_options1 = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));
        
        if ($this->request->is(array('post', 'put'))) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationFinalReject'];
                if (!empty($newData)) {                    
                    $rejection_reason_id = $newData['rejection_option_id'];
                    if (!empty($rejection_reason_id))
                        $reason = $rejection_options[$rejection_reason_id];
                    else
                        $reason = 'undefined reason';
                    
                    $done = $this->final_reject_and_send_notification($org_id, $final_rejection_state_id, $reason);
                    
                    if($done) {
                        $comment = 'Final rejection in licensing process';
                        $this->save_rejection_history($org_id, $final_rejection_state_id, $rejection_type_id, $rejection_category_id, $rejection_reason_id, $comment);

                        $this->redirect(array('action' => 'view', 4, 21, 50));
                    } else {
                        $msg = array(
                            'type' => 'Error',
                            'title' => 'Error... . . !',
                            'msg' => 'Final rejection failed !'
                        );
                        $this->set(compact('msg'));    
                    }
                }
            } catch (Exception $ex) {
                //$this->redirect(array('action' => 'view', 4, 21, 50));
            }
        }

        $this->loadModel('LicenseModuleEvaluationDetailInfo');        
        $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);
        
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $orgDetail = $this->LicenseModuleEvaluationDetailInfo->find('first', array('fields' => $all_fields_with_marks, 'conditions' => array('LicenseModuleEvaluationDetailInfo.org_id' => $org_id), 'group' => array('LicenseModuleEvaluationDetailInfo.org_id')));

        $this->set(compact('org_id', 'orgDetail', 'rejection_options', 'total_marks'));
    }
    
    
    public function reject_all($previous_state_id = null, $initial_rejection_state_id = null, $final_rejection_state_id = null) {

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        //if(!empty($final_rejection_state_id))
        //$this->Session->write('Current.NextStateId', $final_rejection_state_id);
        //$this_state_id = $this->Session->read('Current.StateId');
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

        $rejection_type_id = 1;
        $rejection_category_id = 1;
        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options_initial = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $rejection_category_id = 4;
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options_final = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        if ($this->request->is(array('post', 'put'))) {
            try {
                $newData = $this->request->data;
                debug($newData);
//                $newData = $this->request->data['LicenseModuleApplicationRejectAll'];
//                debug($newData);
                return;
                
                if (!empty($newData['LicenseModuleApplicationRejectAll'])) {
                    $newData = $newData['LicenseModuleApplicationRejectAll'];
                    $this->loadModel('LicenseModuleStateName');
                    $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $initial_rejection_state_id)));

                    if (!empty($stateDetails) && !empty($stateDetails[$initial_rejection_state_id]))
                        $days = $stateDetails[$initial_rejection_state_id];
                    else
                        $days = 0;

                    foreach ($newData as $new_data) {
                        $org_id = $new_data['org_id'];
                        if (!empty($org_id) && $org_id != 0) {
                            if (!empty($new_data['rejection_option_id']))
                                $reason = $rejection_options_initial[$new_data['rejection_option_id']];
                            else
                                $reason = 'undefined Reason';

                            $this->initial_reject_and_send_notification($org_id, $initial_rejection_state_id, $reason, $days);
                        }
                    }
                }
                
                if (!empty($newData['LicenseModuleApplicationFinalRejectAll'])) {
                    $newData = $newData['LicenseModuleApplicationFinalRejectAll'];
                    foreach ($newData as $new_data) {
                        $org_id = $new_data['org_id'];
                        if (!empty($org_id) && $org_id != 0) {
                            if (!empty($new_data['rejection_option_id']))
                                $reason = $rejection_options_final[$new_data['rejection_option_id']];
                            else
                                $reason = 'undefined Reason';

                            $this->final_reject_and_send_notification($org_id, $final_rejection_state_id, $reason);
                        }
                    }
                }
                
            } catch (Exception $ex) {
                
            }
        }

        $this->loadModel('LicenseModuleEvaluationDetailInfo');
        $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupInitialEvaluationPassMark');
        $evaluation_marks_details = $this->LookupInitialEvaluationPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {

                $this->loadModel('LicenseModuleStateHistory');
                $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                $already_appealed_ids = $this->LicenseModuleStateHistory->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleEvaluationDetailInfo');

                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleEvaluationDetailInfo');
            }
        }

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');
        $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleStateHistory');
        $values_waiting_for_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleStateHistory.org_id')));



        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');
        $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));
        $this->loadModel('LicenseModuleStateHistory');
        $values_not_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleStateHistory.org_id')));
        //debug($values_not_appealed);

        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'rejection_options_initial', 'rejection_options_final', 'values_not_appealed', 'values_appeal_but_failed', 'values_waiting_for_appealed', 'values_evaluated_failed'));
    }

    
    public function initial_rejection_all($previous_state_id = null, $initial_rejection_state_id = null) {

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
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

        $rejection_type_id = 1;
        $rejection_category_id = 1;
        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        if ($this->request->is(array('post', 'put'))) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationRejectAll'];
                if (!empty($newData)) {

                    $this->loadModel('LicenseModuleStateName');
                    $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $initial_rejection_state_id)));

                    if (!empty($stateDetails) && !empty($stateDetails[$initial_rejection_state_id]))
                        $days = $stateDetails[$initial_rejection_state_id];
                    else
                        $days = 0;

                    foreach ($newData as $new_data) {
                        $org_id = $new_data['org_id'];
                        if (!empty($org_id) && $org_id != 0) {
                            
                            $rejection_reason_id = $new_data['rejection_option_id'];
                            if (!empty($rejection_reason_id))
                                $reason = $rejection_options[$rejection_reason_id];
                            else
                                $reason = 'undefined reason';
                            
                            $done = $this->initial_reject_and_send_notification($org_id, $initial_rejection_state_id, $reason, $days);
                    
                            if($done) {
                                $comment = 'Initial rejection in licensing process';
                                $this->save_rejection_history($org_id, $initial_rejection_state_id, $rejection_type_id, $rejection_category_id, $rejection_reason_id, $comment);                                
                            }                            
                        }
                    }
                    //$this->redirect(array('action' => 'reject_all'));
                    $this->redirect(array('action' => 'view', 4, 21, 50));
                    return;
                }
            } catch (Exception $ex) {
                //$this->redirect(array('action' => 'view', 4, 21, 50));
            }
        }
        
        $this->loadModel('LicenseModuleEvaluationDetailInfo');
        $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupInitialEvaluationPassMark');
        $evaluation_marks_details = $this->LookupInitialEvaluationPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {
                
                $this->loadModel('LicenseModuleStateHistory');
                $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);                
                $already_appealed_ids = $this->LicenseModuleStateHistory->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);
                
                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleEvaluationDetailInfo');
            }
        }
        
//        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');
//        $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline >=' => date('Y-m-d'));
//        $this->loadModel('LicenseModuleStateHistory');
//        $values_waiting_for_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleStateHistory.org_id')));
        
        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'rejection_options', 'values_evaluated_failed'));
    }

    public function final_rejection_all($previous_state_id = null, $initial_rejection_state_id = null, $final_rejection_state_id = null) {

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        //if(!empty($final_rejection_state_id))
        //$this->Session->write('Current.NextStateId', $final_rejection_state_id);
        //$this_state_id = $this->Session->read('Current.StateId');
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

        $rejection_type_id = 1;
        $rejection_category_id = 4;
        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

//        $rejection_category_id = 3;
//        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
//        $rejection_options1 = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        
        if ($this->request->is(array('post', 'put'))) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationFinalRejectAll'];
                if (!empty($newData)) {
                    foreach ($newData as $new_data) {
                        $org_id = $new_data['org_id'];
                        if (!empty($org_id) && $org_id != 0) {
//                            if (!empty($new_data['rejection_option_id']))
//                                $reason = $rejection_options[$new_data['rejection_option_id']];
//                            else
//                                $reason = 'undefined Reason';
//
//                            $this->final_reject_and_send_notification($org_id, $final_rejection_state_id, $reason);
                            
                    
                            $rejection_reason_id = $new_data['rejection_option_id'];
                            if (!empty($rejection_reason_id))
                                $reason = $rejection_options[$rejection_reason_id];
                            else
                                $reason = 'undefined reason';

                            $done = $this->final_reject_and_send_notification($org_id, $final_rejection_state_id, $reason);
                    
                            if($done) {
                                $comment = 'Final rejection in licensing process';
                                $this->save_rejection_history($org_id, $final_rejection_state_id, $rejection_type_id, $rejection_category_id, $rejection_reason_id, $comment);
                            }
                        }
                    }
                    $this->redirect(array('action' => 'view', 4, 21, 50));
                }
            } catch (Exception $ex) {
                //$this->redirect(array('action' => 'view', 4, 21, 50));
            }
        }

        $this->loadModel('LicenseModuleEvaluationDetailInfo');        
        $parameterOptionList = $this->LicenseModuleEvaluationDetailInfo->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupInitialEvaluationPassMark');
        $evaluation_marks_details = $this->LookupInitialEvaluationPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {
                
                $this->loadModel('LicenseModuleStateHistory');
                $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);                
                $already_appealed_ids = $this->LicenseModuleStateHistory->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);
                
                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);

//                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id);

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleEvaluationDetailInfo');
                //debug($values_appeal_but_failed);
            }
        }

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');
        $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));
        $this->loadModel('LicenseModuleStateHistory');
        $values_not_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleStateHistory.org_id')));
        //debug($values_not_appealed);

        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'rejection_options', 'values_not_appealed', 'values_appeal_but_failed'));
    }

    public function initial_reject_and_send_notification($org_id = null, $initial_rejection_state_id = null, $reason = null, $days = 0) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return false;
        }
        $current_year = $this->Session->read('Current.LicensingYear');
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
            );
            $this->set(compact('msg'));
            return false;
        }

//        $this->loadModel('AdminModuleUser');
//        $email_list = $this->AdminModuleUser->find('first', array('fields' => array('AdminModuleUser.email'), 'conditions' => array('AdminModuleUser.org_id' => $org_id)));
//
//        if (!empty($email_list)) {
//            $to_mail = $email_list['AdminModuleUser']['email'];
        
        $this->loadModel('AdminModuleUserProfile');
        $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
        
        if (!empty($email_list)) {
            $to_mail = $email_list[$org_id];
            $email = new CakeEmail('gmail');
            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Application Rejected !');

            if (empty($reason)) {
                $reason = 'undefined Reason';
            }
            
            $message_body = "Dear Applicant, " . "\r\n \r\n"
                    . "Your license application has been Rejected. Due to \"$reason\"."
                    . ($days > 0 ? " You can appeal within $days days." : "\r\n")
                    . "\r\n  \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority (MRA)";

            if ($email->send($message_body)) {

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $initial_rejection_state_id,
                    'licensing_year' => $current_year,
                    'date_of_starting' => date('Y-m-d'),
                    'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);
                return true;
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Message Sending Failed !'
                );
                $this->set(compact('msg'));
                return false;
            }
        }
    }

    public function final_reject_and_send_notification($org_id = null, $final_rejection_state_id = null, $reason = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $this->set(compact('msg'));
            return false;
        }
        $current_year = $this->Session->read('Current.LicensingYear');
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
            );
            $this->set(compact('msg'));
            return false;
        }

//        $this->loadModel('AdminModuleUser');
//        $email_list = $this->AdminModuleUser->find('first', array('fields' => array('AdminModuleUser.email'), 'conditions' => array('AdminModuleUser.org_id' => $org_id)));
//
//        if (!empty($email_list)) {
//            $to_mail = $email_list['AdminModuleUser']['email'];
        
        $this->loadModel('AdminModuleUserProfile');
        $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
        
        if (!empty($email_list)) {
            $to_mail = $email_list[$org_id];
            $email = new CakeEmail('gmail');
            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Application Rejected !');

            if (empty($reason))
                $reason = 'undefined Reason';

            $message_body = 'Dear Applicant, ' . "\r\n" . "\r\n" . 'Your license application has been Rejected. Due to "' . $reason . '".' . "\r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority (MRA)';

            if ($email->send($message_body)) {

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $final_rejection_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $final_rejection_state_id,
                    'licensing_year' => $current_year,
                    'date_of_starting' => date('Y-m-d'),
                    //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);
                return true;
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Message Sending Failed !'
                );
                $this->set(compact('msg'));
                return false;
            }
        }
    }

}
