<?php

App::uses('AppController', 'Controller');

class LicenseModuleInitialEvaluationDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    
    public function view($opt = 'all', $mode = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if(empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);
        
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
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

        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleInitialEvaluationDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleInitialEvaluationDetail']['search_keyword'];

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

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        $condition3 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]);

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
            $condition3 = array_merge($condition3, $condition);
        }
        
        $this->loadModel('LicenseModuleInitialAssessmentDetail');
        $parameterOptionList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $values_not_approved = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('LicenseModuleInitialAssessmentMark.total_assessment_marks'=>'desc')));
        
        $this->paginate = array('conditions' => $condition2, 'group' => array('LicenseModuleInitialEvaluationDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleInitialEvaluationDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_in_progress = $this->Paginator->paginate('LicenseModuleInitialEvaluationDetail');
        
        $this->paginate = array('conditions' => $condition3, 'group' => array('LicenseModuleInitialEvaluationDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleInitialEvaluationDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_approved = $this->Paginator->paginate('LicenseModuleInitialEvaluationDetail');

        $this->set(compact('values_approved', 'values_in_progress', 'values_not_approved', 'total_marks', 'pass_min_marks'));
    }

    public function approve_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licApprovalDetails = $this->LicenseModuleInitialEvaluationDetail->find('first', array('conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id)));
        $this->set(compact('licApprovalDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function approve_all() {

        $committee_group_id = $this->Session->read('Committee.GroupId');
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

        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialEvaluationDetailAll'];
                if (!empty($newData)) {
                    $this->LicenseModuleInitialEvaluationDetail->create();
                    if ($this->LicenseModuleInitialEvaluationDetail->saveAll($newData)) {

                        $all_org_state_history = array();
                        $this->loadModel('BasicModuleBasicInformation');
        
                        foreach ($newData as $new_data) {
                            $org_id = $new_data['org_id'];
                            if (!empty($org_id)) {
                                
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));

                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $thisStateIds[1],
                                    'licensing_year' => $current_year,
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    'user_name' => $this->Session->read('User.Name'));

                                $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                            }
                        }
                        if (!empty($all_org_state_history)) {
                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        }

                        $this->redirect(array('action' => 'view', '?' => array('this_state_id' => $thisStateIds[1])));
                        return;
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));                        
            }
        }


        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $this->paginate = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('LicenseModuleInitialAssessmentMark.total_assessment_marks'=>'desc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialAssessmentDetail');
        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->set(compact('orgDetails', 'approval_status_options', 'total_marks', 'pass_min_marks'));
    }

    public function approve_edit_all() {

        $committee_group_id = $this->Session->read('Committee.GroupId');
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

        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialEvaluationDetailAll'];
                if (!empty($newData)) {
                    $this->LicenseModuleInitialEvaluationDetail->set($newData);
                    if ($this->LicenseModuleInitialEvaluationDetail->saveAll($newData)) {

                        $all_org_state_history = array();
                        $this->loadModel('BasicModuleBasicInformation');
        
                        foreach ($newData as $new_data) {
                            $org_id = $new_data['org_id'];
                            if (!empty($org_id)) {
                                
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));

                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $thisStateIds[2],
                                    'licensing_year' => $current_year,
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    'user_name' => $this->Session->read('User.Name'));

                                $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                            }
                        }
                        if (!empty($all_org_state_history)) {
                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        }

                        $this->redirect(array('action' => 'view'));
                    }
                }
            } catch (Exception $ex) {
            }
        }

//        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
//        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');
//
//        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
//        $this->Paginator->settings = $this->paginate;
//        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');
        
        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        //$all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->paginate = array('conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialEvaluationDetail');

        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }
        
        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->set(compact('orgDetails', 'approval_status_options', 'total_marks', 'pass_min_marks'));
    }

    public function approve_edit_allX() {

        $committee_group_id = $this->Session->read('Committee.GroupId');
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

        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialEvaluationDetailAll'];
                if (!empty($newData)) {
                    $this->LicenseModuleInitialEvaluationDetail->set($newData);
                    if ($this->LicenseModuleInitialEvaluationDetail->saveAll($newData)) {
                        $this->redirect(array('action' => 'view'));
                    }
                }
            } catch (Exception $ex) {
            }
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');

        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->set(compact('orgDetails', 'approval_status_options'));
    }

    public function approve($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $current_year = $this->Session->read('Current.LicensingYear');
                        
        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialEvaluationDetail'];

                if (!empty($newData)) {

                    $existingData = $this->LicenseModuleInitialEvaluationDetail->find('first', array('fields' => array('LicenseModuleInitialEvaluationDetail.id'), 'conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $newData['org_id'])));

                    if ($existingData) {
                        $this->LicenseModuleInitialEvaluationDetail->id = $existingData['LicenseModuleInitialEvaluationDetail']['id'];
                        $done = $this->LicenseModuleInitialEvaluationDetail->save($newData);
                    } else {
                        $this->LicenseModuleInitialEvaluationDetail->create();
                        $done = $this->LicenseModuleInitialEvaluationDetail->save($newData);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $newData['org_id'];

                        $this_state_ids = $this->Session->read('Current.StateIds');
                        if (!empty($this_state_ids)) {
                            $thisStateIds = explode('_', $this_state_ids);
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

                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[1],
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        debug($org_state_history);

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $this->redirect(array('action' => 'view'));
                        return;
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $orgDetail = $this->LicenseModuleInitialEvaluationDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }
        
        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
        
        
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
//        }

//        $this->set(compact('org_id', 'orgName', 'approval_status_options', 'total_marks', 'pass_min_marks'));
    }

    public function re_approve($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleInitialEvaluationDetail->find('first', array('conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $current_year = $this->Session->read('Current.LicensingYear');
                    
        if ($this->request->is(array('post', 'put'))) {

            $newData = $this->request->data['LicenseModuleInitialEvaluationDetail'];
            if (!empty($newData)) {
                $this->LicenseModuleInitialEvaluationDetail->id = $newData['id'];
                if ($this->LicenseModuleInitialEvaluationDetail->save($newData)) {

                    if (empty($org_id))
                        $org_id = $newData['org_id'];

                    $this_state_ids = $this->Session->read('Current.StateIds');
                    if (!empty($this_state_ids)) {
                        $thisStateIds = explode('_', $this_state_ids);
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

                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $thisStateIds[2],
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);

                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleInitialEvaluationDetail->find('first', array('conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $this->set(compact('approvalDetails', 'approval_status_options', 'total_marks', 'pass_min_marks'));
    }

    public function re_approveP($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleInitialEvaluationDetail->find('first', array('conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {
            $newData = $this->request->data['LicenseModuleInitialEvaluationDetail'];
            if (!empty($newData)) {
                $this->LicenseModuleInitialEvaluationDetail->id = $approvalDetails['LicenseModuleInitialEvaluationDetail']['id'];
                if ($this->LicenseModuleInitialEvaluationDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleInitialEvaluationDetail->find('first', array('conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->LicenseModuleInitialEvaluationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

    
    public function details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licApprovalDetails = $this->LicenseModuleInitialEvaluationDetail->find('first', array('conditions' => array('LicenseModuleInitialEvaluationDetail.org_id' => $org_id)));
        if (!$licApprovalDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('licApprovalDetails'));
    }

}
