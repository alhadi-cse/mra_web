<?php

App::uses('AppController', 'Controller');

class LicenseModuleInitialAssessmentDetailsController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($opt = 'all', $mode = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        $assessor_group_id = $this->request->query('assessor_group_id');
        if (empty($assessor_group_id))
            $assessor_group_id = $this->Session->read('Current.GroupId');
        else
            $this->Session->write('Current.GroupId', $assessor_group_id);

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($assessor_group_id,$user_group_id))) {
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

        $condition = array();
        if (in_array($assessor_group_id,$user_group_id)) {
            $user_name = $this->Session->read('User.Name');
            $this->loadModel('LicenseModuleInitialAssessmentAssessorDetail');
            $frmSl = $this->LicenseModuleInitialAssessmentAssessorDetail->find('first', array('fields' => array('from_form_no', 'to_form_no'), 'conditions' => array('AdminModuleUser.user_name' => $user_name)));
            if (!empty($frmSl['LicenseModuleInitialAssessmentAssessorDetail'])) {
                try {
                    $frmSl = $frmSl['LicenseModuleInitialAssessmentAssessorDetail'];
                    $condition = array('BasicModuleBasicInformation.form_serial_no BETWEEN ? and ?' => array($frmSl['from_form_no'], $frmSl['to_form_no']));
                } catch (Exception $ex) {
                    
                }
            } else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Form serial no. not assinged for this user !');
                $this->set(compact('msg'));
                return;
            }
        }

        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        //if (!empty($org_id)) {
        //    $condition = array_merge(array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id), $condition);
        //}

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleInitialAssessmentDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleInitialAssessmentDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'")));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }
        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all'));

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        $condition3 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]);

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
            $condition3 = array_merge($condition3, $condition);
        }
        $values_not_evaluat = $this->LicenseModuleInitialAssessmentDetail->BasicModuleBasicInformation->find('all', array('fields' => $all_fields, 'conditions' => $condition1, 'recursive' => -1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('form_serial_no' => 'asc')));
        
        $this->Paginator->settings = array('fields' => $all_fields_with_marks, 'conditions' => $condition2, 'group' => array('LicenseModuleInitialAssessmentDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleInitialAssessmentDetail->recursive = 0;
        $values_assessed_not_submit = $this->Paginator->paginate('LicenseModuleInitialAssessmentDetail');

        $parameterOptionMaxList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $assessment_marks = array();
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {

            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;
            $assessment_marks = array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks);

            $values_assessed_passed = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks)), 'group' => 'LicenseModuleInitialAssessmentDetail.org_id'));
            $values_assessed_watch_out = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $pass_min_marks, 'LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentDetail.org_id'));
            $values_assessed_failed = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => array_merge($condition3, array('LicenseModuleInitialAssessmentMark.total_assessment_marks < ' => $watchOut_min_marks)), 'group' => 'LicenseModuleInitialAssessmentDetail.org_id'));
        } else {
            $values_assessed_pass = $values_assessed_watch_out = $values_assessed_failed = null;
        }

        $this->set(compact('assessment_marks', 'total_marks', 'values_assessed_passed', 'values_assessed_watch_out', 'values_assessed_failed', 'values_assessed_not_submit', 'values_not_evaluat'));
    }

    public function assess($org_id = null) {

        $assessor_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($assessor_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $existingOrgList = $this->LicenseModuleInitialAssessmentDetail->find('list', array('fields' => array('LicenseModuleInitialAssessmentDetail.org_id'), 'conditions' => array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id), 'group' => array('LicenseModuleInitialAssessmentDetail.org_id')));
        if (!empty($existingOrgList)) {
            $this->redirect(array('action' => 're_assess', $org_id));
            return;
        }

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleInitialAssessmentDetail'];

                if (!empty($newData) && !empty($newData['org_id'])) {
                    $org_id = $newData['org_id'];
                    $newData = Hash::remove($newData, 'org_id');
                    $newData = Hash::insert($newData, '{n}.org_id', $org_id);

                    $this->LicenseModuleInitialAssessmentDetail->create();
                    if ($this->LicenseModuleInitialAssessmentDetail->saveAll($newData)) {
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

                        $this->redirect(array('action' => 're_assess', $org_id));
                    }
                }
            } catch (Exception $ex) {
                
            }
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

        $orgDetail = $this->LicenseModuleInitialAssessmentDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $parameterNewList = array();
        $parameterList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year), 'order' => array('LookupLicenseInitialAssessmentParameter.sorting_order' => 'ASC')));

        foreach ($parameterList as $parameterDetails) {
            $parameter_id = $parameterDetails['LookupLicenseInitialAssessmentParameter']['id'];
            $parameter_title = $parameterDetails['LookupLicenseInitialAssessmentParameter']['parameter'];
            $parameter_optionList = $parameterDetails['LookupLicenseInitialAssessmentParameterOption'];

            $parameter_value = null;
            $parameter_selected_option = null;

            $parameter_is_mandatory = $parameterDetails['LookupLicenseInitialAssessmentParameter']['is_mandatory'];
            if (!empty($parameter_is_mandatory) && $parameter_is_mandatory == 1)
                $parameter_options = Hash::combine($parameter_optionList, '{n}.id', '{n}.parameter_option');
            else
                $parameter_options = Hash::combine($parameter_optionList, '{n}.id', '{n}.option_with_marks');

            $model_id = $parameterDetails['LookupModelDefinition']['id'];
            $model_name = $parameterDetails['LookupModelDefinition']['model_name'];

            if (!empty($model_id) && !empty($model_name)) {
                if (strpos($model_name, 'BasicModuleBasic') !== false || $model_id == 1)
                    $conditions = array($model_name . '.id' => $org_id);
                else
                    $conditions = array($model_name . '.org_id' => $org_id);

                $field_name = $parameterDetails['LookupModelFieldDefinition']['field_name'];

                if (!empty($field_name)) {
                    $is_aggregated = $parameterDetails['LookupTypeOfOperationOnParameter']['is_aggregated'];
                    $operator = $parameterDetails['LookupTypeOfOperationOnParameter']['operators'];

                    if (!empty($is_aggregated) && $is_aggregated == true && !empty($operator)) {
                        $options = array('conditions' => $conditions,
                            'groupby' => array($model_name . '.org_id'),
                            'fields' => array($operator . '(' . $model_name . '.' . $field_name . ')'));
                    } else
                        $options = array('fields' => array($model_name . '.' . $field_name), 'conditions' => $conditions);

                    try {
                        $this->loadModel($model_name);
                        $field_value = $this->$model_name->find('first', $options);

                        if (!empty($field_value[$model_name]))
                            $parameter_value = $field_value[$model_name][$field_name];
                    } catch (Exception $ex) {
                        
                    }

                    $parameter_type = $parameterDetails['LookupLicenseInitialAssessmentParameterType']['parameter_type'];
                    $is_dynamic = (!empty($parameter_type) && $parameter_type == 'Dynamic'); // $parameterDetails['LookupLicenseInitialAssessmentParameterType']['parameter_type'];// => 'Dynamic'

                    if ($is_dynamic && !empty($parameter_value)) {
                        foreach ($parameter_optionList as $parameter_option) {
                            if ($parameter_option['maximum_value'] >= $parameter_value && $parameter_value >= $parameter_option['minimum_value']) {
                                $parameter_selected_option = $parameter_option['id'];
                                break;
                            }
                        }
                    }
                }
            }
            $parameterNewDetails = Hash::merge(array('parameterId' => $parameter_id), array('parameterTitle' => $parameter_title), array('parameterValue' => $parameter_value), array('parameterOptions' => $parameter_options), array('parameterSelectedOption' => $parameter_selected_option));
            $parameterNewList = array_merge($parameterNewList, array($parameterNewDetails));
        }
        $this->set(compact('org_id', 'orgName', 'parameterNewList'));
    }

    public function re_assess($org_id = null, $option = null, $is_review = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $assessor_group_id = $this->Session->read('Current.GroupId');
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($assessor_group_id,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
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

        if ($this->request->is(array('post', 'put'))) {
            $posted_data = $this->request->data['LicenseModuleInitialAssessmentDetail'];

            if (!empty($is_review) && $is_review == 1) {
                foreach ($posted_data as $data) {
                    $parameter_id = $data['parameter_id'];
                    $param_option_id = $data['assess_parameter_option_id'];

                    if (empty($param_option_id))
                        $param_option_id = 'NULL';
                    if (!empty($parameter_id))
                        $this->LicenseModuleInitialAssessmentDetail->updateAll(array('LicenseModuleInitialAssessmentDetail.assess_parameter_option_id' => "$param_option_id"), array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id, 'LicenseModuleInitialAssessmentDetail.parameter_id' => $parameter_id));
                }
//                foreach ($posted_data as $data) {
//                    $parameter_id = $data['parameter_id'];
//                    $param_option_id = $data['assess_parameter_option_id'];
//                    $assessors_parameter_option_id = $data['assessors_parameter_option_id'];
//
//                    if (empty($param_option_id))
//                        $param_option_id = 'NULL';
//                    if (empty($assessors_parameter_option_id))
//                        $assessors_parameter_option_id = 'NULL';
//
//                    if (!empty($parameter_id))
//                        $this->LicenseModuleInitialAssessmentDetail->updateAll(array('LicenseModuleInitialAssessmentDetail.assess_parameter_option_id' => "$param_option_id", 'LicenseModuleInitialAssessmentDetail.assessors_parameter_option_id' => "$assessors_parameter_option_id"), array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id, 'LicenseModuleInitialAssessmentDetail.parameter_id' => $parameter_id));
//                }
            }
            else {
                foreach ($posted_data as $data) {
                    $parameter_id = $data['parameter_id'];
                    //$param_option_id = $data['assess_parameter_option_id'];
                    $param_option_id = $assessors_parameter_option_id = $data['assessors_parameter_option_id'];

                    if (empty($param_option_id))
                        $param_option_id = 'NULL';
                    if (empty($assessors_parameter_option_id))
                        $assessors_parameter_option_id = 'NULL';

                    if (!empty($parameter_id))
                        $this->LicenseModuleInitialAssessmentDetail->updateAll(array('LicenseModuleInitialAssessmentDetail.assess_parameter_option_id' => "$param_option_id", 'LicenseModuleInitialAssessmentDetail.assessors_parameter_option_id' => "$assessors_parameter_option_id"), array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id, 'LicenseModuleInitialAssessmentDetail.parameter_id' => $parameter_id));
                }

//                foreach ($posted_data as $data) {
//                    $parameter_id = $data['parameter_id'];
//                    $param_option_id = $data['assess_parameter_option_id'];
//
//                    if (empty($param_option_id))
//                        $param_option_id = 'NULL';
//                    if (!empty($parameter_id))
//                        $this->LicenseModuleInitialAssessmentDetail->updateAll(array('LicenseModuleInitialAssessmentDetail.assess_parameter_option_id' => "$param_option_id"), array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id, 'LicenseModuleInitialAssessmentDetail.parameter_id' => $parameter_id));
//                }
            }

            $this->loadModel('BasicModuleBasicInformation');

            if (empty($is_review) || $is_review != 1) {
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

                $updated_state_id = (empty($option) || $option != 1) ? $thisStateIds[1] : $thisStateIds[2];
            } else {
                $this->BasicModuleBasicInformation->recursive = 0;
                $orgDteails = $this->BasicModuleBasicInformation->findById($org_id, array('BasicModuleBasicInformation.licensing_state_id'));
                $updated_state_id = $orgDteails['BasicModuleBasicInformation']['licensing_state_id'];
            }

            $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $updated_state_id), array('BasicModuleBasicInformation.id' => $org_id));
            if ($done) {
                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $updated_state_id,
                    'licensing_year' => $current_year,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

                if (!empty($is_review) && $is_review == 1)
                    $this->redirect(array('controller' => 'LicenseModuleInitialAssessmentReviewVerificationDetails', 'action' => 'view'));
                else
                    $this->redirect(array('action' => 'view'));

                return;
            }
        }

        $parameterNewList = array();
        $orgDetail = $this->LicenseModuleInitialAssessmentDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $parameterAssessedDetails = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => array('LookupLicenseInitialAssessmentParameter.id', 'LookupLicenseInitialAssessmentParameterOption.id'), 'conditions' => array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id)));
        $parameterAssessedList = Hash::combine($parameterAssessedDetails, '{n}.LookupLicenseInitialAssessmentParameter.id', '{n}.LookupLicenseInitialAssessmentParameterOption.id');

        $parameterList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year), 'order' => array('LookupLicenseInitialAssessmentParameter.sorting_order' => 'asc')));

        foreach ($parameterList as $parameterDetails) {
            $parameter_id = $parameterDetails['LookupLicenseInitialAssessmentParameter']['id'];
            $parameter_title = $parameterDetails['LookupLicenseInitialAssessmentParameter']['parameter'];
            $parameter_optionList = $parameterDetails['LookupLicenseInitialAssessmentParameterOption'];

            $parameter_value = $parameter_selected_option = null;

            if (!empty($parameterAssessedList[$parameter_id]))
                $parameter_selected_option = $parameterAssessedList[$parameter_id];

            $parameter_is_mandatory = $parameterDetails['LookupLicenseInitialAssessmentParameter']['is_mandatory'];
            if (!empty($parameter_is_mandatory) && $parameter_is_mandatory == 1)
                $parameter_options = Hash::combine($parameter_optionList, '{n}.id', '{n}.parameter_option');
            else
                $parameter_options = Hash::combine($parameter_optionList, '{n}.id', '{n}.option_with_marks');

            $model_id = $parameterDetails['LookupModelDefinition']['id'];
            $model_name = $parameterDetails['LookupModelDefinition']['model_name'];

            if (!empty($model_id) && !empty($model_name)) {
                if (strpos($model_name, 'BasicModuleBasic') !== false || $model_id == 1)
                    $conditions = array($model_name . '.id' => $org_id);
                else
                    $conditions = array($model_name . '.org_id' => $org_id);

                $field_name = $parameterDetails['LookupModelFieldDefinition']['field_name'];

                if (!empty($field_name)) {
                    $is_aggregated = $parameterDetails['LookupTypeOfOperationOnParameter']['is_aggregated'];
                    $operator = $parameterDetails['LookupTypeOfOperationOnParameter']['operators'];

                    if (!empty($is_aggregated) && $is_aggregated == true && !empty($operator)) {
                        $options = array('conditions' => $conditions,
                            'groupby' => array($model_name . '.org_id'),
                            'fields' => array($operator . '(' . $model_name . '.' . $field_name . ')'));
                    } else {
                        $options = array('fields' => array($model_name . '.' . $field_name), 'conditions' => $conditions);
                    }

                    try {
                        $this->loadModel($model_name);
                        $field_value = $this->$model_name->find('first', $options);

                        if (!empty($field_value[$model_name])) {
                            $parameter_value = $field_value[$model_name][$field_name];
                        }
                    } catch (Exception $ex) {
                        
                    }
                }
            }

            $parameterNewDetails = Hash::merge(array('parameterId' => $parameter_id), array('parameterTitle' => $parameter_title), array('parameterValue' => $parameter_value), array('parameterOptions' => $parameter_options), array('parameterSelectedOption' => $parameter_selected_option));
            $parameterNewList = array_merge($parameterNewList, array($parameterNewDetails));
        }

        $this->set(compact('org_id', 'is_review', 'orgName', 'parameterNewList'));
    }

    public function assess_details($org_id = null) {

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
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $selectedFields = array('LookupLicenseInitialAssessmentParameter.max_marks');
        $parameterOptionMaxMarksList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => $selectedFields, 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));

        $selectedFields = array('LicenseModuleInitialAssessmentMark.total_assessment_marks, LicenseModuleInitialAssessmentMark.total_assessment_marks_assessor');
        $condition_basic_option = array('LicenseModuleInitialAssessmentMark.org_id' => $org_id);
        $assessmentMarks = $this->LicenseModuleInitialAssessmentDetail->LicenseModuleInitialAssessmentMark->find('first', array('fields' => $selectedFields, 'conditions' => $condition_basic_option));

        if (!empty($parameterOptionMaxMarksList))
            $total_marks = array_sum($parameterOptionMaxMarksList);
        else
            $total_marks = 100;

        if (!empty($assessmentMarks))
            $assessmentMarks = array('total_assessment_marks' => $assessmentMarks['LicenseModuleInitialAssessmentMark']['total_assessment_marks'],
                'total_assessment_marks_assessor' => $assessmentMarks['LicenseModuleInitialAssessmentMark']['total_assessment_marks_assessor'],
                'total_marks' => $total_marks);
        else
            $assessmentMarks = array('total_assessment_marks' => '',
                'total_assessment_marks_assessor' => '',
                'total_marks' => $total_marks);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
            $watchOut_min_marks = ($evaluation_marks_details[2] * $total_marks) / 100;

            $assessmentMarks = array_merge($assessmentMarks, array('pass_min_marks' => $pass_min_marks, 'watchOut_min_marks' => $watchOut_min_marks));
        } else
            $assessmentMarks = array_merge($assessmentMarks, array('pass_min_marks' => '', 'watchOut_min_marks' => ''));

        $selectedFields = array('LookupLicenseInitialAssessmentParameter.parameter', 'LookupLicenseInitialAssessmentParameterOption.parameter_option', 'LookupLicenseInitialAssessmentParameterOption.assessment_marks', 'LookupLicenseInitialReAssessmentParameterOption.parameter_option', 'LookupLicenseInitialReAssessmentParameterOption.assessment_marks');
        $condition_mandatory = array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id, 'LookupLicenseInitialAssessmentParameter.is_mandatory' => 1, 'LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year);
        $licenseAssessmentMandatory = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $selectedFields, 'conditions' => $condition_mandatory));
        $condition_is_not_mandatory = array('LicenseModuleInitialAssessmentDetail.org_id' => $org_id,
            'LookupLicenseInitialAssessmentParameter.is_published' => '1',
            'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year,
            'OR' => array('LookupLicenseInitialAssessmentParameter.is_mandatory' => 0,
                'LookupLicenseInitialAssessmentParameter.is_mandatory IS NULL'));
        $licenseAssessmentDetails = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $selectedFields, 'conditions' => $condition_is_not_mandatory, 'order' => array('LookupLicenseInitialAssessmentParameter.sorting_order' => 'asc')));

        $this->set(compact('org_id', 'assessmentMarks', 'licenseAssessmentMandatory', 'licenseAssessmentDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

}
