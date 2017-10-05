<?php

App::uses('AppController', 'Controller');

class LicenseModuleInitialAssessmentReviewVerificationDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    public function view($opt = 'all', $mode = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
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

//        $redirect_url = array('controller' => 'LicenseModuleInitialAssessmentReviewVerificationDetails', 'action' => 'view', '?' => array('committee_group_id' => $committee_group_id, 'this_state_ids' => $this_state_ids));
//        $this->Session->write('Current.RedirectUrl', $redirect_url);

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
        $condition = array();
        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetail']['search_keyword'];

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

        if (in_array($committee_group_id,$user_group_id)) {

            $user_id = $this->Session->read('User.Id');
//            $this_user_org_id_list = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('list', array('fields' => 'LicenseModuleInitialAssessmentReviewVerificationDetail.org_id', 'recursive' => 0, 'conditions' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.verification_committee_user_id' => $user_id, 'LicenseModuleInitialAssessmentReviewVerificationDetail.is_approved' => 1), 'group' => 'LicenseModuleInitialAssessmentReviewVerificationDetail.org_id'));
//
//            if (empty($this_user_org_id_list)) {
//                $msg = array(
//                    'type' => 'warning',
//                    'title' => 'Warning... . . !',
//                    'msg' => 'No organization has been assigned to the user !'
//                );
//                $this->set(compact('msg'));
//                return;
//            }
//
//            $condition1 = array_merge($condition1, array('BasicModuleBasicInformation.id' => $this_user_org_id_list));
//            $condition2 = array_merge($condition2, array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $this_user_org_id_list));
//            $condition3 = array_merge($condition3, array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $this_user_org_id_list));
            //$this->loadModel('LicenseModuleInitialAssessmentReviewVerificationDetail');
            $approved_org_id_list = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('list', array('fields' => 'org_id', 'conditions' => array('verification_committee_user_id' => $user_id, 'verification_status_id' => 1), 'group' => 'org_id'));

            if (!empty($approved_org_id_list))
                $condition2 = array_merge($condition2, array('NOT' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $approved_org_id_list)));
        }

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
            $condition3 = array_merge($condition3, $condition);
        }


        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $this->loadModel('LicenseModuleInitialAssessmentDetail');
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $values_pending = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('form_serial_no' => 'asc')));

        $values_not_verified = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('all', array('conditions' => $condition2, 'group' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id'), 'order' => array('form_serial_no' => 'asc')));

        $this->paginate = array('conditions' => $condition3, 'group' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleInitialAssessmentReviewVerificationDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_verified = $this->Paginator->paginate('LicenseModuleInitialAssessmentReviewVerificationDetail');

        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'total_marks', 'pass_min_marks', 'values_verified', 'values_not_verified', 'values_pending'));

//        $this->loadModel('LicenseModuleInitialAssessmentDetail');
//        $parameterOptionList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
//        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
//        $total_marks = array_sum($parameterOptionMaxList);
//
//        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
//        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
//
//        $pass_min_marks = $total_marks * 0.60;
//        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
//            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
//        }
//
//        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
//        $values_not_verified = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('LicenseModuleInitialAssessmentMark.total_assessment_marks'=>'desc')));
//        
//        
//        $this->paginate = array('conditions' => $condition3, 'group' => array('org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
//        $this->LicenseModuleInitialAssessmentReviewVerificationDetail->recursive = 0;
//        $this->Paginator->settings = $this->paginate;
//        $values_verified = $this->Paginator->paginate('LicenseModuleInitialAssessmentReviewVerificationDetail');
//
//        $this->set(compact('values_verified', 'values_not_verified', 'total_marks', 'pass_min_marks'));
    }

    public function verification_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('LookupLicenseApprovalStatus.verification_status', 'LicenseModuleInitialAssessmentReviewVerificationDetail.verification_date', 'LicenseModuleInitialAssessmentReviewVerificationDetail.verification_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationDetails = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('all', array('fields' => $fields, 'conditions' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $org_id), 'recursive' => 0, 'order' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.verification_date' => 'desc')));

        $this->set(compact('vrificationDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function verification_all() {

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
                $posted_data = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetailAll'];
                if (!empty($posted_data)) {
                    $this->LicenseModuleInitialAssessmentReviewVerificationDetail->create();
                    if ($this->LicenseModuleInitialAssessmentReviewVerificationDetail->saveAll($posted_data)) {

                        $all_org_state_history = array();
                        $this->loadModel('BasicModuleBasicInformation');

                        foreach ($posted_data as $new_data) {
                            $org_id = $new_data['org_id'];
                            if (!empty($org_id)) {

                                //$condition = array('BasicModuleBasicInformation.id' => $org_id,  'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                                // $org_state_history = array(
                                // 'org_id' => $org_id,
                                // 'state_id' => $thisStateIds[0],
                                // 'licensing_year' => $current_year,
                                // 'date_of_state_update' => date('Y-m-d'),
                                // 'user_name' => $this->Session->read('User.Name'));

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
                            $this->LicenseModuleStateHistory->create(); //set($all_org_state_history);
                            $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        }

                        $redirect_url = $this->Session->read('Current.RedirectUrl');
                        if (empty($redirect_url))
                            $redirect_url = array('action' => 'view');
                        $this->redirect($redirect_url);

//                        $this->redirect(array('action' => 'view', '?' => array('this_state_id' => $thisStateIds[1])));
                        return;
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'verification_all'));
            }
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
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
        $this->paginate = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('LicenseModuleInitialAssessmentMark.total_assessment_marks' => 'desc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialAssessmentDetail');
        $verification_status_options = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));

        $this->set(compact('orgDetails', 'verification_status_options', 'total_marks', 'pass_min_marks'));
    }

    public function verification_edit_all() {

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
                $posted_data = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetailAll'];
//                foreach ($posted_data as $nData) {
//                    //$posted_data = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetail'];
//                    if (!empty($nData)) {
//                        $id = $nData['id'];
//                        if (!empty($id)) {
//                            $this->LicenseModuleInitialAssessmentReviewVerificationDetail->id = $id;
//                            $nData = Hash::remove($nData, 'id');
//                            $this->LicenseModuleInitialAssessmentReviewVerificationDetail->save($nData);
//                        }
//                    }
//                }

                if (!empty($posted_data)) {
                    $this->LicenseModuleInitialAssessmentReviewVerificationDetail->set($posted_data);
                    if ($this->LicenseModuleInitialAssessmentReviewVerificationDetail->saveAll($posted_data)) {
                        $redirect_url = $this->Session->read('Current.RedirectUrl');
                        if (empty($redirect_url))
                            $redirect_url = array('action' => 'view');
                        $this->redirect($redirect_url);
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

        $verification_status_options = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
//        
//        $this->paginate = array(
//            'limit' => 10,
//            'order' => array('form_serial_no' => 'asc'));
//        
//        $this->Paginator->settings = $this->paginate;
//        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialAssessmentReviewVerificationDetail');
        $this->set(compact('orgDetails', 'verification_status_options'));
    }

    public function verification($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is('post')) {
            try {
                $posted_data = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetail'];
//                debug($posted_data);
//                $posted_data['verification_committee_user_id'] = $this->Session->read('User.Id');
//                debug($posted_data);
//                return;
                if (!empty($posted_data)) {
                    $posted_data['verification_committee_user_id'] = $this->Session->read('User.Id');
                    $existingData = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('first', array('fields' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.id'), 'conditions' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $posted_data['org_id'])));

                    if ($existingData) {
                        $this->LicenseModuleInitialAssessmentReviewVerificationDetail->id = $existingData['LicenseModuleInitialAssessmentReviewVerificationDetail']['id'];
                        $done = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->save($posted_data);
                    } else {
                        $this->LicenseModuleInitialAssessmentReviewVerificationDetail->create();
                        $done = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->save($posted_data);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $posted_data['org_id'];

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

                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[1],
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $redirect_url = $this->Session->read('Current.RedirectUrl');
                        if (empty($redirect_url))
                            $redirect_url = array('action' => 'view');
                        $this->redirect($redirect_url);
                        return;
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $orgDetail = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $verification_status_options = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('org_id', 'orgName', 'verification_status_options'));
    }

    public function verification_approval($org_id = null) {

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
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
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

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetail'];
            if (empty($org_id))
                $org_id = $posted_data['org_id'];

            $option = $posted_data['verification_status_id'];

            if (!empty($option)) {

                $user_id = $this->Session->read('User.Id');

                $this->loadModel('LicenseModuleInitialAssessmentReviewVerificationDetail');

                if ($option == 1) {

                    $posted_data['verification_committee_user_id'] = $user_id;

                    $this->LicenseModuleInitialAssessmentReviewVerificationDetail->create();
                    $done = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->save($posted_data);
                    if ($done) {
                        $approve_id = $done['LicenseModuleInitialAssessmentReviewVerificationDetail']['id'];

                        $committee_member_id_list = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUser.id', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id, 'AdminModuleUser.activation_status_id' => 1), 'recursive' => 0));
                        $approve_count = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('count', array('fields' => 'verification_committee_user_id', 'conditions' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $org_id, 'verification_status_id' => 1, 'verification_committee_user_id' => $committee_member_id_list), 'group' => 'verification_committee_user_id'));

                        if ((!empty($approve_count) && $approve_count == count($committee_member_id_list))) {

                            $this_state_ids = $this->Session->read('Current.StateIds');
                            debug($this_state_ids);
                            if (!empty($this_state_ids)) {
                                $thisStateIds = explode('_', $this_state_ids);
                                if (count($thisStateIds) < 3) {
                                    $msg = array(
                                        'type' => 'warning',
                                        'title' => 'Warning... . . !',
                                        'msg' => 'Invalid State Information !'
                                    );
                                    $this->set(compact('msg'));
                                    $this->LicenseModuleInitialAssessmentReviewVerificationDetail->delete($approve_id);
                                    return;
                                }
                            } else {
                                $msg = array(
                                    'type' => 'warning',
                                    'title' => 'Warning... . . !',
                                    'msg' => 'Invalid State Information !'
                                );
                                $this->set(compact('msg'));
                                $this->LicenseModuleInitialAssessmentReviewVerificationDetail->delete($approve_id);
                                return;
                            }

                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id));

                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $thisStateIds[2],
                                'licensing_year' => $this->Session->read('Current.LicensingYear'),
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->save($org_state_history);
                        }
                    }
                } else if ($option == 2) {

                    $done = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->deleteAll(array('org_id' => $org_id), false);
                    if ($done) {
                        $committee_member_email_list = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUserProfile.email', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id), 'recursive' => 0));

                        if (!empty($committee_member_email_list)) {

                            try {
                                $msg = $posted_data['approval_comment'];

                                $committee_member_name = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->AdminModuleUserProfile->field('AdminModuleUserProfile.full_name_of_user', array('AdminModuleUserProfile.user_id' => $user_id));

                                $orgDetails = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->BasicModuleBasicInformation->find('first', array('fields' => array('form_serial_no', 'short_name_of_org', 'full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
                                if (!empty($orgDetails)) {
                                    $org_info = $orgDetails['BasicModuleBasicInformation'];
                                    $org_name = $org_info['full_name_of_org'] . (!empty($org_info['short_name_of_org']) ? ' (' . $org_info['short_name_of_org'] . ')' : '');
                                    $org_form_serial_no = $org_info['form_serial_no'];
                                }

                                $message_body = 'Dear User,'
                                        . "\r\n" . "\r\n"
                                        . " Initial Assessment Verification Committee Member Name: $committee_member_name"
                                        . " did not approve the Initial Assessment Verification of \"$org_name\" with Form No.:$org_form_serial_no."
                                        . " As a result the approval status of all members has been reset."
                                        . " Due to the \"$msg\" isue." . "\r\n \r\n"
                                        . "Please re-approve the Initial Assessment Verification."
                                        . "\r\n \r\n"
                                        . "Thanks" . "\r\n"
                                        . "Microcredit Regulatory Authority";



//            $mail_attachments = array(
//                'example.txt' => array(
//                    'file' => 'full/path/to/example.txt',
//                    'mimetype' => 'text/plain'
//                ),
//                'my_image.jpef' => array(
//                    'file' => '/full/path/to/my_image.jpeg',
//                    'mimetype' => 'image/jpeg'
//                )
//            );

                                $mail_details = array();
                                $mail_details['org_id'] = $org_id;
                                $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
                                $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
                                $mail_details['mail_to'] = implode(';', $committee_member_email_list);
                                $mail_details['mail_cc'] = '';
                                $mail_details['mail_subject'] = 'Initial Assessment Verification not Approve !';
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
                            } catch (Exception $ex) {
                                
                            }
                        }
                    }
                }
                
                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view');
                $this->redirect($redirect_url);
                return;
            }
        }

        $orgDetail = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $verification_status_options = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('org_id', 'orgName', 'verification_status_options'));
    }

    public function re_verification($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('first', array('conditions' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $org_id)));
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
            $posted_data = $this->request->data['LicenseModuleInitialAssessmentReviewVerificationDetail'];
            if (!empty($posted_data)) {
                $this->LicenseModuleInitialAssessmentReviewVerificationDetail->id = $approvalDetails['LicenseModuleInitialAssessmentReviewVerificationDetail']['id'];
                if ($this->LicenseModuleInitialAssessmentReviewVerificationDetail->save($posted_data)) {
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                    if (empty($redirect_url))
                        $redirect_url = array('action' => 'view');
                    $this->redirect($redirect_url);
                }
            }
            return;
        }

        $verification_status_options = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('approvalDetails', 'verification_status_options'));
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

        $licVerificationDetails = $this->LicenseModuleInitialAssessmentReviewVerificationDetail->find('first', array('conditions' => array('LicenseModuleInitialAssessmentReviewVerificationDetail.org_id' => $org_id)));
        if (!$licVerificationDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('licVerificationDetails'));
    }

}
