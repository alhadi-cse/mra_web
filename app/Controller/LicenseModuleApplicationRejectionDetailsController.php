<?php

App::uses('AppController', 'Controller');

class LicenseModuleApplicationRejectionDetailsController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    
    public function view($rejection_state_ids = null) {

        $rejection_state_ids = $this->request->query('rejection_state_ids');
        if (!empty($rejection_state_ids))
            $this->Session->write('Rejection.StateIds', $rejection_state_ids);
        else
            $rejection_state_ids = $this->Session->read('Rejection.StateIds');

        if (!empty($rejection_state_ids)) {
            $thisStateIds = explode('_', $rejection_state_ids);
            if (count($thisStateIds) < 3) {
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
        
        $redirect_url = array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'view', '?' => array('rejection_state_ids' => $rejection_state_ids));
        $this->Session->write('Current.RedirectUrl', $redirect_url);

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


        $values_evaluated_failed = null;
        //$assessment_marks = array();

        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $assessment_marks = array();
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

//            $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//
////            $values_assessed_passed = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
////            $values_assessed_watch_out = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
//            $values_assessed_failed = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));

            if (!empty($pass_min_marks)) {
                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                if (!empty($org_id))
                    $condition0 = array('org_id' => $org_id, 'previous_licensing_state_id' => $thisStateIds[1]);
                else
                    $condition0 = array('previous_licensing_state_id' => $thisStateIds[1]);
                $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

                if (!empty($org_id))
                    $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
                else
                    $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

//                $values_evaluated_failed = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');

                if (!empty($org_id))
                    $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks);
                else
                    $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks);
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
            }
        }
//        else {
//            $values_assessed_pass = $values_assessed_watch_out = $values_assessed_failed = null;
//        }


        $admin_approval_state_id = 9;
        $not_approval_status_id = 2;
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no', 'LookupLicenseApprovalStatus.approval_status');
        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_state_id' => $admin_approval_state_id, 'LicenseModuleInitialAssessmentAdminApprovalDetail.approval_status_id' => $not_approval_status_id);
        else
            $conditions = array('licensing_state_id' => $admin_approval_state_id, 'LicenseModuleInitialAssessmentAdminApprovalDetail.approval_status_id' => $not_approval_status_id);

        //$this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $this->LicenseModuleInitialAssessmentAdminApprovalDetail->recursive = 0;
        $values_not_approved_in_admin_approval = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields, 'conditions' => $conditions, 'group' => 'BasicModuleBasicInformation.id'));

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
        else
            $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
        else
            $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_not_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));

        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'values_not_appealed', 'values_appeal_but_failed', 'values_waiting_for_appealed', 'values_evaluated_failed', 'values_not_approved_in_admin_approval'));
    }    
    
    public function view_1st_step_rejection($rejection_state_ids = null, $rejection_type_id = null, $rejection_category_ids = null) {

        if (empty($rejection_state_ids))
            $rejection_state_ids = $this->request->query('rejection_state_ids');

        if (!empty($rejection_state_ids))
            $this->Session->write('Rejection.StateIds', $rejection_state_ids);
        else
            $rejection_state_ids = $this->Session->read('Rejection.StateIds');

        if (!empty($rejection_state_ids)) {
            $thisStateIds = explode('_', $rejection_state_ids);
            if (count($thisStateIds) < 4) {
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


        if (empty($rejection_type_id))
            $rejection_type_id = $this->request->query('rejection_type_id');

        if (!empty($rejection_type_id))
            $this->Session->write('Rejection.TypeId', $rejection_type_id);
        else {
            $rejection_type_id = $this->Session->read('Rejection.TypeId');

            if (empty($rejection_type_id))
                $rejection_type_id = 1;
        }

        if (empty($rejection_category_ids))
            $rejection_category_ids = $this->request->query('rejection_category_ids');

        if (!empty($rejection_category_ids))
            $this->Session->write('Rejection.CategoryIds', $rejection_category_ids);
        else
            $rejection_category_ids = $this->Session->read('Rejection.CategoryIds');

        if (!empty($rejection_category_ids)) {
            $thisCategoryIds = explode('_', $rejection_category_ids);
            if (count($thisCategoryIds) < 2) {
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


//        $this->loadModel('LookupLicenseApplicationRejectionReason');
//        $fields = array('id', 'rejection_reason');
//        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $thisCategoryIds[0]);
//        $rejection_options_initial = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));
//
//        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $thisCategoryIds[1]);
//        $rejection_options_final = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));


        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
        }

        if (!empty($pass_min_marks)) {
            if (!empty($org_id))
                $condition0 = array('org_id' => $org_id, 'LicenseModuleApplicationRejectionHistoryDetail.licensing_year' => $current_year, 'state_id' => $thisStateIds[2]);
            else
                $condition0 = array('LicenseModuleApplicationRejectionHistoryDetail.licensing_year' => $current_year, 'state_id' => $thisStateIds[2]);

            $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
            $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'recursive' => 0, 'group' => array('org_id')));


            $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

            if (!empty($org_id))
                $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
            else
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

            //$this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
            $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
            $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
            $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');


            if (!empty($org_id))
                $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks);
            else
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks);
            $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
            $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
        }

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
        else
            $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
        else
            $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_not_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));

        $this->set(compact('next_state_id', 'total_marks', 'watchOut_min_marks', 'values_not_appealed', 'values_appeal_but_failed', 'values_waiting_for_appealed', 'values_evaluated_failed'));
    }

    public function initial_rejection($org_id = null, $rejection_state_ids = null, $rejection_type_id = null, $rejection_category_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
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


//        if (empty($rejection_state_ids))
//            $rejection_state_ids = $this->Session->read('Rejection.StateIds');
//
//        if (!empty($rejection_state_ids)) {
//            $thisStateIds = explode('_', $rejection_state_ids);
//            if (count($thisStateIds) < 4) {
//                $msg = array(
//                    'type' => 'warning',
//                    'title' => 'Warning... . . !',
//                    'msg' => 'Invalid State Information !'
//                );
//                $this->set(compact('msg'));
//                return;
//            }
//        } else {
//            $msg = array(
//                'type' => 'warning',
//                'title' => 'Warning... . . !',
//                'msg' => 'Invalid State Information !'
//            );
//            $this->set(compact('msg'));
//            return;
//        }

        if (empty($rejection_type_id))
            $rejection_type_id = $this->Session->read('Rejection.TypeId');
        if (empty($rejection_type_id))
            $rejection_type_id = 1;


        if (empty($rejection_category_ids))
            $rejection_category_ids = $this->Session->read('Rejection.CategoryIds');

        if (!empty($rejection_category_ids)) {
            $thisCategoryIds = explode('_', $rejection_category_ids);
            if (count($thisCategoryIds) < 2) {
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

//
//        $this->loadModel('LookupLicenseApplicationRejectionReason');
//        $fields = array('id', 'rejection_reason');
//        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $thisCategoryIds[0]);
//        $rejection_options_initial = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));
//
//
//        $this->loadModel('LookupLicenseInitialAssessmentParameter');
//        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
//        $total_marks = array_sum($parameterOptionMaxMarksList);
//
//        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
//        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
//
//        $pass_min_marks = $total_marks * 0.60;
//        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
//            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
//            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
//        }
//
//        if (!empty($pass_min_marks)) {
//            if (!empty($org_id))
//                $condition0 = array('org_id' => $org_id, 'LicenseModuleApplicationRejectionHistoryDetail.licensing_year' => $current_year, 'state_id' => $thisStateIds[2]);
//            else
//                $condition0 = array('LicenseModuleApplicationRejectionHistoryDetail.licensing_year' => $current_year, 'state_id' => $thisStateIds[2]);
//
//            $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
//            $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'recursive' => 0, 'group' => array('org_id')));
//
//
//            $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//
//            if (!empty($org_id))
//                $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
//            else
//                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
//
//            //$this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
//            $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
//            $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
//            $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
//
//
//            if (!empty($org_id))
//                $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks);
//            else
//                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks);
//            $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
//            $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
//        }



        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_ids);
        $rejection_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

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

                    if ($done) {
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


        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $orgDetail = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('fields' => $all_fields_with_marks, 'conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id), 'group' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id')));

        $this->set(compact('org_id', 'orgDetail', 'rejection_options', 'total_marks'));
    }
    
    public function rejection($org_id = null, $committee_group_id = null, $rejection_state_ids = null, $rejection_type_id = null, $rejection_category_ids = null, $redirect_url = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');

        if (empty($committee_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid User Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
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

        if (empty($redirect_url))
            $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');


        if ($this->request->is(array('post', 'put'))) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationRejectionHistoryDetail'];

                if (!empty($newData) && !empty($newData['rejection_type_id'])) {

                    if (empty($rejection_state_ids))
                        $rejection_state_ids = $this->Session->read('Rejection.StateIds');

                    if (!empty($rejection_state_ids)) {
                        $thisStateIds = explode('_', $rejection_state_ids);
                        if (count($thisStateIds) < 3) {
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
                    $previous_licensing_state_id = $thisStateIds[0];

                    $rejection_type_id = $newData['rejection_type_id'];
                    $rejection_state_id = $thisStateIds[$rejection_type_id];
                    $rejection_reason_id = $newData['rejection_reason_id'];

                    if (!empty($rejection_reason_id)) {
                        $this->loadModel('LookupLicenseApplicationRejectionReason');
                        $rejection_reason_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => array('id', 'rejection_reason'), 'conditions' => array('id' => $rejection_reason_id)));

                        $reason = $rejection_reason_options[$rejection_reason_id];
                    }

                    $msg = $newData['rejection_msg'];

//                        $deadline_date = '';
//                        if ($rejection_type_id == 1) {
//                            $days = $newData['appeal_deadline_days'];
//                            $deadline_date = $newData['deadline_date'];
//                            if (empty($days) && empty($deadline_date)) {
//                                $this->loadModel('LicenseModuleStateName');
//                                $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $rejection_state_id)));
//
//                                if (!empty($stateDetails) && !empty($stateDetails[$rejection_state_id])) {
//                                    $days = $stateDetails[$rejection_state_id];
//                                    $deadline_date = date('Y-m-d', strtotime("+$days days"));
//                                }
//                            }
//                        }
//                        if(empty($deadline_date) && !empty($days))
//                            $deadline_date = date('Y-m-d', strtotime("+$days days"));

                    $this->loadModel('BasicModuleBasicInformation');
                    $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $rejection_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                    if ($done) {
                        try {
                            $newData['previous_licensing_state_id'] = $previous_licensing_state_id;
                            $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                            $this->LicenseModuleApplicationRejectionHistoryDetail->create();
                            $this->LicenseModuleApplicationRejectionHistoryDetail->save($newData);

                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $rejection_state_id,
                                'licensing_year' => $current_year,
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                            $this->LicenseModuleApplicationRejectionHistoryDetail->create();
                            $this->LicenseModuleApplicationRejectionHistoryDetail->save($org_state_history);
                        } catch (Exception $ex) {                            
                        }
                        
                        $this->loadModel('AdminModuleUserProfile');
                        $email_id = $this->AdminModuleUserProfile->field('AdminModuleUserProfile.email', array('AdminModuleUserProfile.org_id' => $org_id));

                        //$email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
                    

                        $message_body = "Dear Applicant, " . "\r\n \r\n"
                                . "Your license application has been Rejected. "
                                . (!empty($reason) ? "Due to \"$reason\"." : "")
                                . $msg . "\r\n  \r\n \r\n  \r\n"
                                //. (!empty($deadline_date) ? ("You can appeal within date: $deadline_date" . (!empty($days) ? " ($days days)." : "")) : "")
                                . "\r\n  \r\n"
                                . "Thanks \r\n"
                                . "Microcredit Regulatory Authority (MRA)";


                        $mail_details = array();
                        $mail_details['org_id'] = $org_id;
                        $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
                        $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
                        $mail_details['mail_to'] = $email_id;
                        $mail_details['mail_cc'] = '';
                        $mail_details['mail_subject'] = 'License Application Rejected !';
                        $mail_details['mail_message'] = $message_body;
                        $mail_details['mail_is_sent'] = 0;
						$mail_details['mail_creation_date'] = date('Y-m-d');
						$mail_details['mail_creator'] = $this->Session->read('User.Id');

                        $this->loadModel('AdminModuleMessageSendingDetail');
                        $this->AdminModuleMessageSendingDetail->create();
                        $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

                        if ($done && !empty($done['AdminModuleMessageSendingDetail']['id']))
                            $this->redirect(array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $done['AdminModuleMessageSendingDetail']['id']));
                        $this->redirect($redirect_url);
                    }
                } else {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Rejection Type not selected !'
                    );
                    $this->set(compact('msg'));
                    return false;
                }
            } catch (Exception $ex) {
                //$this->redirect(array('action' => 'view', 4, 21, 50));
            }
        }

        $this->loadModel('BasicModuleBasicInformation');
        $all_fields = array('id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org');
        $orgDetail = $this->BasicModuleBasicInformation->find('first', array('fields' => $all_fields, 'conditions' => array('id' => $org_id), 'recursive' => -1));

        $this->set(compact('org_id', 'orgDetail'));


        $this->loadModel('LookupLicenseApplicationRejectionType');
        $rejection_type_options = $this->LookupLicenseApplicationRejectionType->find('list', array('fields' => array('id', 'rejection_type')));

        if (empty($rejection_type_id))
            $rejection_type_id = $this->Session->read('Rejection.TypeId');

        if (!empty($rejection_type_id)) {
            $conditions = array('rejection_type_id' => $rejection_type_id);
        }

        if (empty($rejection_category_ids))
            $rejection_category_ids = $this->Session->read('Rejection.CategoryIds');

        if (!empty($rejection_category_ids)) {
            $rejection_category_ids = explode('_', $rejection_category_ids);
        }
//        else if (!empty($conditions)) {
//            $rejection_category_ids = $this->LookupLicenseApplicationRejectionType->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('id'), 'conditions' => $conditions, 'recursive' => 0));
//        }

        if (!empty($rejection_category_ids)) {
            if (empty($conditions)) {
                $conditions_category = array('id' => $rejection_category_ids);
                $conditions_reason = array('rejection_category_id' => $rejection_category_ids);
            } else {
                $conditions_category = array_merge($conditions, array('id' => $rejection_category_ids));
                $conditions_reason = array_merge($conditions, array('rejection_category_id' => $rejection_category_ids));
            }
        } else if (!empty($conditions)) {
            $conditions_category = $conditions_reason = $conditions;
        }

        //$rejection_type_options = array('Initial Rejection', 'Final Rejection');
        $this->loadModel('LookupLicenseApplicationRejectionReason');

        if (!empty($conditions_reason))
            $rejection_reason_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => array('id', 'rejection_reason'), 'conditions' => $conditions_reason));
        else
            $rejection_reason_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => array('id', 'rejection_reason')));

        if (!empty($conditions_category))
            $rejection_category_options = $this->LookupLicenseApplicationRejectionReason->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('id', 'rejection_category'), 'conditions' => $conditions_category));
        else
            $rejection_category_options = $this->LookupLicenseApplicationRejectionReason->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('id', 'rejection_category')));


        $this->set(compact('rejection_type_id', 'rejection_type_options', 'rejection_category_options', 'rejection_reason_options', 'redirect_url'));


//        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
//        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//        $orgDetail = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('fields' => $all_fields_with_marks, 'conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id), 'group' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id')));
    }
    
    public function initial_reject_and_send_notification($org_id = null, $rejection_state_id = null, $reason = null, $msg = null, $days = 0) {

        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $rejection_state_id), array('BasicModuleBasicInformation.id' => $org_id));
        
        if ($done) {
            try {
                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $rejection_state_id,
                    'date_of_starting' => date('Y-m-d'),
                    'deadline_date' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                $this->LicenseModuleApplicationRejectionHistoryDetail->create();
                $this->LicenseModuleApplicationRejectionHistoryDetail->save($org_state_history);
            } catch (Exception $ex) {
            }

            $this->loadModel('AdminModuleUserProfile');
            $email_id = $this->AdminModuleUserProfile->field('AdminModuleUserProfile.email', array('AdminModuleUserProfile.org_id' => $org_id));
            
            //$email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

            if (empty($reason))
                $reason = 'undefined Reason';

            $message_body = "Dear Applicant, " . "\r\n \r\n"
                    . "Your license application has been Rejected. Due to \"$reason\"."
                    . $msg . "\r\n  \r\n \r\n  \r\n"
                    //. ($days > 0 ? "You can appeal within $days days." : "\r\n")
                    . "\r\n  \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority (MRA)";

            $mail_details = array();
            $mail_details['org_id'] = $org_id;
            $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
            $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
            $mail_details['mail_to'] = $email_id;
            $mail_details['mail_cc'] = '';
            $mail_details['mail_subject'] = 'License Application Rejected !';
            $mail_details['mail_message'] = $message_body;
            $mail_details['mail_is_sent'] = 0;
			$mail_details['mail_creation_date'] = date('Y-m-d');
			$mail_details['mail_creator'] = $this->Session->read('User.Id');

            $this->loadModel('AdminModuleMessageSendingDetail');
            $this->AdminModuleMessageSendingDetail->create();
            $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

            if ($done && !empty($done['AdminModuleMessageSendingDetail']['id']))
                $this->redirect(array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $done['AdminModuleMessageSendingDetail']['id']));
            $this->redirect($redirect_url);
        }
    }

    public function final_reject_and_send_notification($org_id = null, $rejection_state_id = null, $reason = null) {

        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $rejection_state_id), array('BasicModuleBasicInformation.id' => $org_id));

        if ($done) {
            try {
                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $rejection_state_id,
                    //'licensing_year' => $current_year,
                    'date_of_starting' => date('Y-m-d'),
                    //'deadline_date' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                $this->LicenseModuleApplicationRejectionHistoryDetail->create();
                $this->LicenseModuleApplicationRejectionHistoryDetail->save($org_state_history);
            } catch (Exception $ex) {
                
            }

            $this->loadModel('AdminModuleUserProfile');
            $email_id = $this->AdminModuleUserProfile->field('AdminModuleUserProfile.email', array('AdminModuleUserProfile.org_id' => $org_id));

            //$email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

            if (empty($reason))
                $reason = 'undefined Reason';

            $message_body = 'Dear Applicant, ' . "\r\n" . "\r\n"
                    . 'Your license application has been Rejected. Due to "'
                    . $reason . '".'
                    . "\r\n" . "\r\n" . 'Thanks' . "\r\n"
                    . 'Microcredit Regulatory Authority (MRA)';
            
            $mail_details = array();
            $mail_details['org_id'] = $org_id;
            $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
            $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
            $mail_details['mail_to'] = $email_id;
            $mail_details['mail_subject'] = 'License Application Rejected !';
            $mail_details['mail_message'] = $message_body;
            $mail_details['mail_is_sent'] = 0;
			$mail_details['mail_creation_date'] = date('Y-m-d');
			$mail_details['mail_creator'] = $this->Session->read('User.Id');

            $this->loadModel('AdminModuleMessageSendingDetail');
            $this->AdminModuleMessageSendingDetail->create();
            $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

            if ($done && !empty($done['AdminModuleMessageSendingDetail']['id']))
                $this->redirect(array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $done['AdminModuleMessageSendingDetail']['id']));
            //$this->redirect($redirect_url);
        }
    }

    public function rejection_history_details($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $rejectionHistoryDetails = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('conditions' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id' => $org_id)));
        $this->set(compact('rejectionHistoryDetails'));
    }

    public function rejection_history_preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }
    
    public function save_rejection_history($org_id = null, $licensing_state_id = null, $rejection_type_id = null, $rejection_category_id = null, $rejection_reason_id = null, $comment = null) {

//        `org_id`
//        `licensing_state_id`
//        `rejection_type_id`
//        `rejection_category_id`
//        `rejection_reason_id`
//        `rejection_date`
//        `comment`
        //$this->save_rejection_history($org_id, $licensing_state_id, $rejection_type_id, $rejection_category_id, $rejection_reason_id, $comment);

        $org_rejection_history = array(
            'org_id' => $org_id,
            'licensing_state_id' => $licensing_state_id,
            'rejection_type_id' => $rejection_type_id,
            'rejection_category_id' => $rejection_category_id,
            'rejection_reason_id' => $rejection_reason_id,
            'rejection_date' => date('Y-m-d'),
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

    public function view_p($previous_state_id = null, $initial_rejection_state_id = null, $final_rejection_state_id = null) {

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
        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_category_id' => $rejection_category_id);
        $rejection_options_initial = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $rejection_category_id = 4;
        $conditions = array('rejection_category_id' => $rejection_category_id);
        $rejection_options_final = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

//        $this->loadModel('LookupLicenseInitialAssessmentParameter');
//        $parameterOptionList = $this->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
//        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
//        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();



        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $assessment_marks = array();
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

//            $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//
////            $values_assessed_passed = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
////            $values_assessed_watch_out = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
//            $values_assessed_failed = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));

            if (!empty($pass_min_marks)) {
                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                if (!empty($org_id))
                    $condition0 = array('org_id' => $org_id, 'licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                else
                    $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

                if (!empty($org_id))
                    $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
                else
                    $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

                //$this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');

                if (!empty($org_id))
                    $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks);
                else
                    $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids, 'LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks);
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id', 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
            }
        }
//        else {
//            $values_assessed_pass = $values_assessed_watch_out = $values_assessed_failed = null;
//        }

        /*
          $this->loadModel('LookupLicenseInitialAssessmentPassMark');
          $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
          if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
          $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
          $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
          $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

          if (!empty($pass_min_marks)) {
          $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
          if (!empty($org_id))
          $condition0 = array('org_id' => $org_id, 'licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
          else
          $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
          $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
          //debug($already_appealed_ids);

          $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

          if (!empty($org_id))
          $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));
          else
          $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

          $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
          $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');

          if (!empty($org_id))
          $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);
          else
          $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);
          $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
          $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
          }
          }

         */

        $admin_approval_state_id = 9;
        $not_approval_status_id = 2;
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no', 'LookupLicenseApprovalStatus.approval_status');
        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $admin_approval_state_id, 'LicenseModuleInitialAssessmentAdminApprovalDetail.approval_status_id' => $not_approval_status_id);
        else
            $conditions = array('licensing_year' => $current_year, 'licensing_state_id' => $admin_approval_state_id, 'LicenseModuleInitialAssessmentAdminApprovalDetail.approval_status_id' => $not_approval_status_id);

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $this->LicenseModuleInitialAssessmentAdminApprovalDetail->recursive = 0;
        $values_not_approved_in_admin_approval = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all', array('fields' => $all_fields, 'conditions' => $conditions, 'group' => 'BasicModuleBasicInformation.id'));

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
        else
            $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        if (!empty($org_id))
            $condition1 = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
        else
            $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_not_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));

        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'values_not_appealed', 'values_appeal_but_failed', 'values_waiting_for_appealed', 'values_evaluated_failed', 'values_not_approved_in_admin_approval'));
    }

    public function initial_rejectionX($org_id = null, $previous_state_id = null, $initial_rejection_state_id = null) {

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
        if (empty($user_group_id) || !(in_array(1,$user_group_id))) {
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
        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

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

                    if ($done) {
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

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $parameterOptionList = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $orgDetail = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('fields' => $all_fields_with_marks, 'conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id), 'group' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id')));

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
        if (empty($user_group_id) || !in_array(1,$user_group_id)) {
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
        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

//        $rejection_category_id = 3;
//        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
//        $rejection_options1 = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

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

                    if ($done) {
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

        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionList = $this->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $orgDetail = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('fields' => $all_fields_with_marks, 'conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id), 'group' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id')));

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
        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
        $rejection_options_initial = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $rejection_category_id = 4;
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
        $rejection_options_final = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

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

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $parameterOptionList = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {

                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');

                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);
                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
            }
        }

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));



        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_not_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));
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
        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

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

                            if ($done) {
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

//        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
//        $parameterOptionList = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
//        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
//        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {

                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                $condition0 = array('LicenseModuleApplicationRejectionHistoryDetail.licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id'), 'conditions' => $condition0, 'recursive' => -1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'NOT' => array('BasicModuleBasicInformation.id' => $already_appealed_ids));

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('LicenseModuleInitialAssessmentMark.org_id' => 'asc'));
                //$this->Paginator->settings->recur = ;
                $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
                $values_evaluated_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
            }
        }

//        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
//        $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
//        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
//        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));

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
        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $fields = array('id', 'rejection_reason');
        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

//        $rejection_category_id = 3;
//        $conditions = array('rejection_type_id' => $rejection_type_id, 'rejection_category_id' => $rejection_category_id);
//        $rejection_options1 = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => $fields, 'conditions' => $conditions));


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

                            if ($done) {
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

        $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
        $parameterOptionList = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $values_evaluated_failed = null;
        $assessment_marks = array();

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            if (!empty($pass_min_marks)) {

                $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
                $condition0 = array('licensing_year' => $current_year, 'state_id' => $initial_rejection_state_id);
                $already_appealed_ids = $this->LicenseModuleApplicationRejectionHistoryDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition0, 'group' => array('org_id')));
                //debug($already_appealed_ids);

                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id, 'BasicModuleBasicInformation.id' => $already_appealed_ids);

//                $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//                $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $previous_state_id);

                $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)<$pass_min_marks"), 'limit' => 10, 'order' => array('org_id' => 'asc'));
                $values_appeal_but_failed = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
                //debug($values_appeal_but_failed);
            }
        }

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
        $condition1 = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_not_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $condition1, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));
        //debug($values_not_appealed);

        $this->set(compact('next_state_id', 'assessment_marks', 'total_marks', 'rejection_options', 'values_not_appealed', 'values_appeal_but_failed'));
    }

}
