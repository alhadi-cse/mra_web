<?php

App::uses('AppController', 'Controller');

class LicenseModuleInitialAssessmentAdminApprovalDetailsController extends AppController {

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
            if (count($thisStateIds) < 2) {
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

        $redirect_url = array('controller' => 'LicenseModuleInitialAssessmentAdminApprovalDetails', 'action' => 'view', '?' => array('this_state_id' => $this_state_ids));
        $this->Session->write('Current.RedirectUrl', $redirect_url);

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
        $condition = array();
        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all') {
                $this->Session->write('Org.Id', null);
            }                
            else {
                $opt_all = true;
            }
            $org_id = $this->Session->read('Org.Id');
            if (!empty($org_id)) {
                $condition = array_merge(array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id), $condition);
            }
        }        

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }
        //$this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all'));

        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
        }

        $this->loadModel('LicenseModuleInitialAssessmentDetail');
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $values_not_approved = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('LicenseModuleInitialAssessmentMark.total_assessment_marks' => 'desc')));
        //debug($values_not_approved);

        $all_fields_with_marks = array_merge($all_fields_with_marks, array('LicenseModuleInitialAssessmentAdminApprovalDetail.approval_date', 'LookupLicenseApprovalStatus.approval_status'));
        $this->paginate = array('fields' => $all_fields_with_marks, 'conditions' => $condition2, 'group' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleInitialAssessmentAdminApprovalDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_approved = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
        //debug($values_approved);

        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'total_marks', 'pass_min_marks', 'values_approved', 'values_not_approved'));

//        
//        $values = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('all');//,array('group' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id')));
//        debug($values);
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

        $licApprovalDetails = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id)));
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
            if (count($thisStateIds) < 2) {
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
        //$next_state_id = $thisStateIds[1];

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
                $newData = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetailAll'];
                if (!empty($newData)) {

                    $all_org_state_history = array();
                    $this->loadModel('BasicModuleBasicInformation');

                    foreach ($newData as $new_data) {

                        if (!empty($new_data) && !empty($new_data['org_id']) && !empty($new_data['approval_status_id'])) {

                            $org_id = $new_data['org_id'];
                            $approval_status_id = $new_data['approval_status_id'];

                            if ($approval_status_id == 1) {
                                $existingData = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('fields' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id)));
                                if ($existingData) {
                                    $this->LicenseModuleInitialAssessmentAdminApprovalDetail->id = $existingData['LicenseModuleInitialAssessmentAdminApprovalDetail']['id'];
                                    $done = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->save($new_data);
                                } else {
                                    $this->LicenseModuleInitialAssessmentAdminApprovalDetail->create();
                                    $done = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->save($new_data);
                                }

                                if ($done) {
                                    $next_state_id = $thisStateIds[1];
                                }
                            } else if (!empty($new_data['back_state_id'])) {
                                $next_state_id = $new_data['back_state_id'];
                            }

                            if (!empty($next_state_id)) {
                                $current_year = $this->Session->read('Current.LicensingYear');

                                $this->loadModel('BasicModuleBasicInformation');
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $next_state_id,
                                    'licensing_year' => $current_year,
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    'user_name' => $this->Session->read('User.Name'));

                                $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                            } else {
                                $msg = array(
                                    'type' => 'warning',
                                    'title' => 'Warning... . . !',
                                    'msg' => 'Invalid Back/Next State Information !'
                                );
                                $this->set(compact('msg'));
                            }
                        }
                    }

                    if (!empty($all_org_state_history)) {
                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                    }

                    $this->redirect(array('action' => 'view'));
                    return;
                }
            } catch (Exception $ex) {
                //$this->redirect(array('action' => 'approve_all'));
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
        $approval_status_options = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->loadModel('LicenseModuleStateNames');
        $back_states = $this->LicenseModuleStateNames->find('list', array('fields' => array('id', 'state_title'), 'conditions' => array('LicenseModuleStateNames.id <' => $thisStateIds[0], 'LicenseModuleStateNames.id >' => 1)));

        $this->set(compact('orgDetails', 'approval_status_options', 'back_states', 'total_marks', 'pass_min_marks'));
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
            if (count($thisStateIds) < 2) {
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
                $newData = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetailAll'];
//                foreach ($newData as $nData) {
//                    //$newData = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetail'];
//                    if (!empty($nData)) {
//                        $id = $nData['id'];
//                        if (!empty($id)) {
//                            $this->LicenseModuleInitialAssessmentAdminApprovalDetail->id = $id;
//                            $nData = Hash::remove($nData, 'id');
//                            $this->LicenseModuleInitialAssessmentAdminApprovalDetail->save($nData);
//                        }
//                    }
//                }

                if (!empty($newData)) {
                    $this->LicenseModuleInitialAssessmentAdminApprovalDetail->set($newData);
                    if ($this->LicenseModuleInitialAssessmentAdminApprovalDetail->saveAll($newData)) {
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

        $approval_status_options = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
//        
//        $this->paginate = array(
//            'limit' => 10,
//            'order' => array('form_serial_no' => 'asc'));
//        
//        $this->Paginator->settings = $this->paginate;
//        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialAssessmentAdminApprovalDetail');
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
            if (count($thisStateIds) < 2) {
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetail'];

                if (!empty($newData) && !empty($newData['approval_status_id'])) {

                    $approval_status_id = $newData['approval_status_id'];

                    if ($approval_status_id == 1) {
                        $existingData = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('fields' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $newData['org_id'])));
                        if ($existingData) {
                            $this->LicenseModuleInitialAssessmentAdminApprovalDetail->id = $existingData['LicenseModuleInitialAssessmentAdminApprovalDetail']['id'];
                            $done = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->save($newData);
                        } else {
                            $this->LicenseModuleInitialAssessmentAdminApprovalDetail->create();
                            $done = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->save($newData);
                        }

                        if ($done) {
                            $next_state_id = $thisStateIds[1];
                        }
                    } else if (!empty($newData['back_state_id'])) {
                        $next_state_id = $newData['back_state_id'];
                    }

                    if (!empty($next_state_id)) {
                        $current_year = $this->Session->read('Current.LicensingYear');

                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $next_state_id,
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $this->redirect(array('action' => 'view'));
                        //return;
                    } else {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => 'Invalid Back/Next State Information !'
                        );
                        $this->set(compact('msg'));
                        //return;
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $orgName = '';
        $orgDetails = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0));
        if (!empty($orgDetails)) {
            $orgName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
            $short_name_of_org = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
            $orgName = $orgName . ((!empty($orgName) && !empty($short_name_of_org)) ? " ($short_name_of_org)" : $short_name_of_org);
        }

        $this->loadModel('LicenseModuleStateNames');
        $back_states = $this->LicenseModuleStateNames->find('list', array('fields' => array('id', 'state_title'), 'conditions' => array('LicenseModuleStateNames.id <' => $thisStateIds[0], 'LicenseModuleStateNames.id >' => 1)));

        $approval_status_options = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'back_states', 'approval_status_options'));
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
            if (count($thisStateIds) < 2) {
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

        $this->LicenseModuleInitialAssessmentAdminApprovalDetail->unbindModel(array('belongsTo' => array('LookupLicenseApprovalStatus', 'LicenseModuleInitialAssessmentMark')), true);
        $approvalDetails = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Initial Assessment Administrative Approval Information !'
            );
            $this->set(compact('msg'));
        }

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialAssessmentAdminApprovalDetail'];
                //debug($newData);
                if (!empty($newData) && !empty($newData['approval_status_id'])) {

                    $approval_status_id = $newData['approval_status_id'];

                    if ($approval_status_id == 1) {
                        $this->LicenseModuleInitialAssessmentAdminApprovalDetail->id = $approvalDetails['LicenseModuleInitialAssessmentAdminApprovalDetail']['id'];
                        if ($this->LicenseModuleInitialAssessmentAdminApprovalDetail->save($newData)) {
                            $next_state_id = $thisStateIds[1];
                        }
                    } else if (!empty($newData['back_state_id'])) {
                        $next_state_id = $newData['back_state_id'];
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));
                    }

                    if (!empty($next_state_id)) {
                        $current_year = $this->Session->read('Current.LicensingYear');
                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $next_state_id,
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $this->redirect(array('action' => 'view'));
                        return;
                    } else {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => 'Invalid Back/Next State Information !'
                        );
                        $this->set(compact('msg'));
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $orgName = '';
        $orgDetails = $approvalDetails['BasicModuleBasicInformation']; //$this->LicenseModuleInitialAssessmentAdminApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0));
        if (!empty($orgDetails)) {
            $orgName = $orgDetails['full_name_of_org'];
            $short_name_of_org = $orgDetails['short_name_of_org'];
            $orgName = $orgName . ((!empty($orgName) && !empty($short_name_of_org)) ? " ($short_name_of_org)" : $short_name_of_org);

            unset($approvalDetails['BasicModuleBasicInformation']);
        }


        if (!$this->request->data) {
            $this->request->data = $approvalDetails;
        }

        $this->loadModel('LicenseModuleStateNames');
        $back_states = $this->LicenseModuleStateNames->find('list', array('fields' => array('id', 'state_title'), 'conditions' => array('LicenseModuleStateNames.id <' => $thisStateIds[0], 'LicenseModuleStateNames.id >' => 1)));

        $approval_status_options = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'back_states', 'approval_status_options'));
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

        $licApprovalDetails = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id' => $org_id)));
        if (!$licApprovalDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('licApprovalDetails'));
    }

}
