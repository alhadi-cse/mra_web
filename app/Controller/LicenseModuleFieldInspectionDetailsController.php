<?php

App::uses('AppController', 'Controller');

class LicenseModuleFieldInspectionDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($opt = 'all', $mode = null) {

        $inspection_type_id = $this->request->query('inspection_type_id');
        
        $licensed_mfi = $this->request->query('licensed_mfi');
        if (empty($licensed_mfi)) {
            $licensed_mfi = $this->Session->read('Current.LicensedMFI');            
        } else {
            $licensed_mfi = empty($this->request->query('licensed_mfi')) ? 0 : 1;
            $this->Session->write('Current.LicensedMFI', $licensed_mfi);
        }

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->Session->read('Current.InspectionTypeId');
        }
        else {
            $this->Session->write('Current.InspectionTypeId', $inspection_type_id);
        }

        $user_group_id = $this->Session->read('User.GroupIds');
        $inspector_group_id = $this->request->query('inspector_group_id');
        if (empty($inspector_group_id))
            $inspector_group_id = $this->Session->read('Current.GroupId');
        else
            $this->Session->write('Current.GroupId', $inspector_group_id);

        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($inspector_group_id, $user_group_id))) {
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

        $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'view', '?' => array('inspection_type_id' => $inspection_type_id, 'inspector_group_id' => $inspector_group_id, 'this_state_ids' => $this_state_ids, 'licensed_mfi' => $licensed_mfi));
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $condition = array();

        $opt_all = false;
        $user_is_editor = false;
        if (in_array(1, $user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        else {
            $user_committe_member_type_id = $this->Session->read('User.CommitteMemberTypeId');
            $user_is_editor = (!empty($user_committe_member_type_id) && $user_committe_member_type_id == 2);
            $org_id = $this->Session->read('Org.Id');

            if (!empty($org_id)) {
                $condition = array_merge(array('LicenseModuleFieldInspectionDetail.org_id' => $org_id), $condition);
            }
        }        

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleFieldInspectionDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleFieldInspectionDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));
        
        $user_is_inspector = in_array($inspector_group_id, $user_group_id);
        $this->set(compact('org_id', 'inspection_type_id', 'inspection_type_detail', 'user_is_inspector', 'user_is_editor', 'opt_all'));

        $condition_inspector_assigned = array('LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id, 'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1, 'LicenseModuleFieldInspectionInspectorDetail.is_completed' => array(0, 2));
        $condition_inspection_done = array('LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id, 'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1, 'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 1);

        $user_id = $this->Session->read('User.Id');
        if ((!empty($user_id)&&$user_is_inspector)) {
            $condition_inspector_assigned = array_merge($condition_inspector_assigned, array('LicenseModuleFieldInspectionInspectorDetail.inspector_user_id' => $user_id));
            $condition_inspection_done = array_merge($condition_inspection_done, array('LicenseModuleFieldInspectionInspectorDetail.inspector_user_id' => $user_id));
        }

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        $inspector_assigned_org_id_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'LicenseModuleFieldInspectionInspectorDetail.org_id', 'recursive' => -1, 'conditions' => $condition_inspector_assigned, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));
        $inspection_done_org_id_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'LicenseModuleFieldInspectionInspectorDetail.org_id', 'recursive' => -1, 'conditions' => $condition_inspection_done, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));
        
        if (empty($inspector_assigned_org_id_list) && empty($inspection_done_org_id_list)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'No organization has been assigned for Inspection !'
            );
            $this->set(compact('msg'));
            return;
        }

        $condition1 = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.id' => $inspector_assigned_org_id_list);
        $condition2 = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleFieldInspectionDetail.org_id' => $inspector_assigned_org_id_list, 'LicenseModuleFieldInspectionDetail.is_approved' => -1);
        $condition3 = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleFieldInspectionDetail.org_id' => $inspector_assigned_org_id_list, 'LicenseModuleFieldInspectionDetail.is_approved' => 0, 'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id);
        $condition4 = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModuleFieldInspectionDetail.org_id' => $inspection_done_org_id_list, 'LicenseModuleFieldInspectionDetail.is_approved' => 1, 'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id);
        
        if ($user_is_inspector) {
            $condition_approved_org = array('LicenseModuleFieldInspectionApprovalDetail.inspection_type_id' => $inspection_type_id,
                'LicenseModuleFieldInspectionApprovalDetail.inspector_user_id' => $user_id,
                'LicenseModuleFieldInspectionApprovalDetail.inspection_approval_id' => 1);

            $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');
            $approved_org_id_list = $this->LicenseModuleFieldInspectionApprovalDetail->find('list', array('fields' => 'org_id', 'conditions' => $condition_approved_org, 'recursive' => -1, 'group' => 'org_id'));
            
            if (!empty($approved_org_id_list)) {
                $condition3 = array_merge($condition3, array('NOT' => array('LicenseModuleFieldInspectionDetail.org_id' => $approved_org_id_list)));
            }
        }

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
            $condition3 = array_merge($condition3, $condition);
            $condition4 = array_merge($condition4, $condition);
        }        
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.license_no');
        try{
            $values_not_inspect = $this->LicenseModuleFieldInspectionDetail->BasicModuleBasicInformation->find('all', array('fields' => $all_fields, 'conditions' => $condition1, 'recursive' =>-1, 'group' => 'BasicModuleBasicInformation.id'));            
        }
        catch (Exception $ex) {
            debug($ex);
        }
        $values_inspected_not_submit = $this->LicenseModuleFieldInspectionDetail->find('all', array('conditions' => $condition2, 'group' => array('org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc')));
        $values_inspected_not_approved = $this->LicenseModuleFieldInspectionDetail->find('all', array('conditions' => $condition3, 'group' => array('org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc')));

        $this->paginate = array('conditions' => $condition4, 'group' => array('org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $values_inspected = $this->Paginator->paginate('LicenseModuleFieldInspectionDetail');
        $this->set(compact('licensed_mfi', 'values_inspected', 'values_inspected_not_submit', 'values_inspected_not_approved', 'values_not_inspect'));
    }

    public function inspection($org_id = null, $inspection_type_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->Session->read('Current.InspectionTypeId');

            if (empty($inspection_type_id)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Field Inspection Type Id !'
                );
                $this->set(compact('msg'));
                return;
            }
        }
        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($inspector_group_id, $user_group_id))) {
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

        if ($this->request->is('post')) {

            $newData = $this->request->data;

            if (!empty($newData) && !empty($newData['basic'])) {
                try {
                    $basicData = $newData['basic'];
                    $basicData['is_approved'] = -1;

                    $this->LicenseModuleFieldInspectionDetail->create();
                    $done = $this->LicenseModuleFieldInspectionDetail->save($basicData);

                    if ($done) {
                        $inspection_slno = $done['LicenseModuleFieldInspectionDetail']['inspection_slno'];

                        if (!empty($newData['dynamic'])) {
                            $inspection_id = $done['LicenseModuleFieldInspectionDetail']['id'];
                            $dynamicData = $newData['dynamic'];
                            $dynamicData = Hash::insert($dynamicData, '{n}.inspection_id', $inspection_id);

                            $this->loadModel('LicenseModuleFieldInspectionFieldDetail');
                            $this->LicenseModuleFieldInspectionFieldDetail->create();
                            $this->LicenseModuleFieldInspectionFieldDetail->saveAll($dynamicData);
                        }

                        $this->loadModel('BasicModuleBasicInformation');
                        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                        if ($done) {
                            $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                                'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                                'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                                'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
                            $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                            $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2), $conditions);

                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $thisStateIds[1],
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->save($org_state_history);

                            $this->redirect(array('action' => 're_inspection', $org_id, $inspection_type_id, $inspection_slno));
                        }
                        return;
                    }
                } catch (Exception $ex) {
                    
                }
            }
        }

        if ($this->request->query('licensed_mfi') != null)
            $licensed_mfi = empty($this->request->query('licensed_mfi')) ? 0 : 1;
        else
            $licensed_mfi = empty($this->Session->read('Current.LicensedMFI')) ? 0 : 1;

        $option_values = array('1' => 'Yes', '0' => 'No');

        $orgDetail = $this->LicenseModuleFieldInspectionDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        } else {
            $orgName = '';
        }

        $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

        $this->loadModel('LookupLicenseInspectionParameter');
        $fields = array('LookupLicenseInspectionParameter.parameter_group_id', 'LookupLicenseInspectionParameter.id', 'LookupLicenseInspectionParameter.parameter_name', 'LookupLicenseInspectionParameter.parameter_type');
        $conditions = array('LookupLicenseInspectionParameter.inspection_type_id' => $inspection_type_id, 'LookupLicenseInspectionParameter.licensing_year' => $current_year);
        $parameterList = $this->LookupLicenseInspectionParameter->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LookupLicenseInspectionParameter.parameter_group_id' => 'ASC', 'LookupLicenseInspectionParameter.serial_no' => 'ASC')));

        //$this->loadModel('LookupLicenseInspectionParameterGroup');
        $fields = array('LookupLicenseInspectionParameterGroup.id', 'LookupLicenseInspectionParameterGroup.inspection_parameter_group');
        $conditions = array('LookupLicenseInspectionParameterGroup.inspection_type_id' => $inspection_type_id, 'LookupLicenseInspectionParameterGroup.licensing_year' => $current_year);
        $parameterGroupList = $this->LookupLicenseInspectionParameter->LookupLicenseInspectionParameterGroup->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LookupLicenseInspectionParameterGroup.serial_no' => 'ASC')));

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id, 'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id, 'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1, 'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $inspector_name_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => $conditions, 'group' => 'inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);

        $inspection_slno_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspection_slno', 'recursive' => 0, 'conditions' => $conditions, 'group' => 'inspection_slno'));
        if (!empty($inspection_slno_list)) {
            foreach ($inspection_slno_list as $key => $inspection_slno) {
                if (isset($inspection_slno))
                    break;
            }
        }
        if (empty($inspection_slno))
            $inspection_slno = 1;

        $this->loadModel('LookupLicenseApprovalStatus');
        $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

        $this->set(compact('org_id', 'orgName', 'licensed_mfi', 'inspection_type_id', 'inspection_type_detail', 'inspection_slno', 'parameterGroupList', 'parameterList', 'option_values', 'recommendation_status_options', 'inspector_names'));
    }

    public function re_inspection($org_id = null, $inspection_type_id = null, $inspection_slno = null, $option = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->Session->read('Current.InspectionTypeId');

            if (empty($inspection_type_id)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Field Inspection Type Id !'
                );
                $this->set(compact('msg'));
                return;
            }
        }

        if (empty($inspection_slno)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Field Inspection Serial No. !'
            );
            $this->set(compact('msg'));
            return;
        }

        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($inspector_group_id, $user_group_id))) {
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

        if ($this->request->is(array('post', 'put'))) {

            $newData = $this->request->data;

            if (!empty($newData) && !empty($newData['basic'])) {
                try {
                    $basicData = $newData['basic'];
                    if (!empty($option) && $option == 1)
                        $basicData['is_approved'] = 0;

                    $this->LicenseModuleFieldInspectionDetail->id = $basicData['id'];
                    $done = $this->LicenseModuleFieldInspectionDetail->save($basicData);

                    if ($done) {

                        if (!empty($newData['dynamic'])) {
                            $inspection_id = $done['LicenseModuleFieldInspectionDetail']['id'];

                            $dynamicData = $newData['dynamic'];
                            $dynamicData = Hash::insert($dynamicData, '{n}.inspection_id', $inspection_id);

                            $this->loadModel('LicenseModuleFieldInspectionFieldDetail');
                            $this->LicenseModuleFieldInspectionFieldDetail->deleteAll(array('LicenseModuleFieldInspectionFieldDetail.inspection_id' => $inspection_id), false);

                            $this->LicenseModuleFieldInspectionFieldDetail->create();
                            $this->LicenseModuleFieldInspectionFieldDetail->saveAll($dynamicData);
                        }

                        if (!empty($option) && $option == 1) {
                            $approval_data = array(
                                'org_id' => $org_id,
                                'submission_date' => date('Y-m-d'),
                                'inspection_type_id' => $inspection_type_id,
                                'inspection_slno' => $inspection_slno,
                                'inspection_approval_id' => 1,
                                'inspection_comment' => "approved",
                                'inspector_user_id' => $this->Session->read('User.Id'),
                                'is_editor_user' => 1);

                            $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');
                            $this->LicenseModuleFieldInspectionApprovalDetail->updateAll(array('inspection_approval_id' => -1), array('LicenseModuleFieldInspectionApprovalDetail.org_id' => $org_id, 'inspection_approval_id' => 1));
                            $this->LicenseModuleFieldInspectionApprovalDetail->create();
                            $done = $this->LicenseModuleFieldInspectionApprovalDetail->save($approval_data);

                            if ($done) {
//                                $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
//                                $inspector_id_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id, 'is_approved' => 1), 'recursive' => 0, 'group' => 'inspector_user_id'));
//                                $approve_count = $this->LicenseModuleFieldInspectionApprovalDetail->find('count', array('fields' => 'inspector_user_id', 'conditions' => array('LicenseModuleFieldInspectionApprovalDetail.org_id' => $org_id, 'inspection_approval_id' => 1, 'inspector_user_id' => $inspector_id_list), 'group' => 'inspector_user_id'));
//                                $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
//                                'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
//                                'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
//                                'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

                                $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                                    'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                                    'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                                    'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                                    'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);

                                $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                                $inspector_id_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => $conditions, 'recursive' => -1, 'group' => 'inspector_user_id'));

                                $conditions = array('LicenseModuleFieldInspectionApprovalDetail.org_id' => $org_id,
                                    'LicenseModuleFieldInspectionApprovalDetail.inspection_type_id' => $inspection_type_id,
                                    'LicenseModuleFieldInspectionApprovalDetail.inspection_slno' => $inspection_slno,
                                    'LicenseModuleFieldInspectionApprovalDetail.inspection_approval_id' => 1,
                                    'LicenseModuleFieldInspectionApprovalDetail.inspector_user_id' => $inspector_id_list);

                                $approve_count = $this->LicenseModuleFieldInspectionApprovalDetail->find('count', array('fields' => 'inspector_user_id', 'conditions' => $conditions, 'recursive' => -1, 'group' => 'inspector_user_id'));

                                if ((!empty($approve_count) && $approve_count == count($inspector_id_list))) {

                                    $this->loadModel('BasicModuleBasicInformation');
                                    $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id));

                                    if ($done) {
                                        $conditions = array('LicenseModuleFieldInspectionDetail.org_id' => $org_id,
                                            'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id,
                                            'LicenseModuleFieldInspectionDetail.inspection_slno' => $inspection_slno,
                                            'LicenseModuleFieldInspectionDetail.is_approved' => 0);

                                        $this->LicenseModuleFieldInspectionDetail->updateAll(array('is_approved' => 1), $conditions);


                                        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                                            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                                            'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                                            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                                            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);
                                        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                                        $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_completed' => 1), $conditions);

                                        $org_state_history = array(
                                            'org_id' => $org_id,
                                            'state_id' => $thisStateIds[2],
                                            //'licensing_year' => $current_year,
                                            'date_of_state_update' => date('Y-m-d'),
                                            'date_of_starting' => date('Y-m-d'),
                                            'user_name' => $this->Session->read('User.Name'));

                                        $this->loadModel('LicenseModuleStateHistory');
                                        $this->LicenseModuleStateHistory->create();
                                        $this->LicenseModuleStateHistory->save($org_state_history);
                                    }
                                }
                                //$this->redirect(array('action' => 'view'));
                                $redirect_url = $this->Session->read('Current.RedirectUrl');
                                if (empty($redirect_url))
                                    $redirect_url = array('action' => 'view');
                                $this->redirect($redirect_url);
                                return;
                            }
                        }
                    }
                } catch (Exception $ex) {
                    
                }
            }
        }

        if ($this->request->query('licensed_mfi') != null)
            $licensed_mfi = empty($this->request->query('licensed_mfi')) ? 0 : 1;
        else
            $licensed_mfi = empty($this->Session->read('Current.LicensedMFI')) ? 0 : 1;

        $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));
        $this->set(compact('org_id', 'licensed_mfi', 'inspection_type_id', 'inspection_type_detail', 'inspection_slno'));

        $conditions = array('LicenseModuleFieldInspectionDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionDetail.inspection_slno' => $inspection_slno,
            'LicenseModuleFieldInspectionDetail.is_approved' => -1);

        $inspectionBasicDetails = $this->LicenseModuleFieldInspectionDetail->find('first', array('conditions' => $conditions, 'order' => array('submission_date' => 'desc')));

        if (!empty($inspectionBasicDetails) && !empty($inspectionBasicDetails['LicenseModuleFieldInspectionDetail']['id'])) {

            $inspection_id = $inspectionBasicDetails['LicenseModuleFieldInspectionDetail']['id'];

            $this->loadModel('LicenseModuleFieldInspectionFieldDetail');
            $fields = array('LicenseModuleFieldInspectionFieldDetail.parameter_id', 'LicenseModuleFieldInspectionFieldDetail.parameter_value');
            $inspectionDynamicDetails = $this->LicenseModuleFieldInspectionFieldDetail->find('list', array('fields' => $fields, 'conditions' => array('LicenseModuleFieldInspectionFieldDetail.inspection_id' => $inspection_id), 'recursive' => 0, 'order' => array('LicenseModuleFieldInspectionFieldDetail.parameter_id')));

            $inspectionDetails['basic'] = $inspectionBasicDetails['LicenseModuleFieldInspectionDetail'];
            $inspectionDetails['dynamic'] = $inspectionDynamicDetails;

            if (!$this->request->data) {
                $this->request->data = $inspectionDetails;
            }

            //$inspection_slno = $inspectionDetails['basic']['inspection_slno'];

            $fields = array('LookupLicenseInspectionParameter.parameter_group_id', 'LookupLicenseInspectionParameter.id', 'LookupLicenseInspectionParameter.parameter_name', 'LookupLicenseInspectionParameter.parameter_type');
            $conditions = array('LookupLicenseInspectionParameter.inspection_type_id' => $inspection_type_id, 'LookupLicenseInspectionParameter.licensing_year' => $current_year);
            $parameterList = $this->LicenseModuleFieldInspectionFieldDetail->LookupLicenseInspectionParameter->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LookupLicenseInspectionParameter.parameter_group_id' => 'ASC', 'LookupLicenseInspectionParameter.serial_no' => 'ASC')));

            $fields = array('LookupLicenseInspectionParameterGroup.id', 'LookupLicenseInspectionParameterGroup.inspection_parameter_group');
            $conditions = array('LookupLicenseInspectionParameterGroup.inspection_type_id' => $inspection_type_id, 'LookupLicenseInspectionParameterGroup.licensing_year' => $current_year);
            $parameterGroupList = $this->LicenseModuleFieldInspectionFieldDetail->LookupLicenseInspectionParameter->LookupLicenseInspectionParameterGroup->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LookupLicenseInspectionParameterGroup.serial_no' => 'ASC')));


            if (!empty($inspectionBasicDetails['BasicModuleBasicInformation'])) {
                $orgDetail = $inspectionBasicDetails['BasicModuleBasicInformation'];
                $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
            } else {
                $orgName = '';
            }

            $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
            $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);
            $inspector_name_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => $conditions, 'group' => 'inspector_user_id'));
            $inspector_names = implode('<br />', $inspector_name_list);

            $this->loadModel('LookupLicenseApprovalStatus');
            $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

            $option_values = array('1' => 'Yes', '0' => 'No');

            $this->set(compact('orgName', 'parameterGroupList', 'parameterList', 'option_values', 'recommendation_status_options', 'inspector_names'));
        }
    }

    public function inspection_approval($org_id = null, $inspection_type_id = null, $inspection_slno = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->Session->read('Current.InspectionTypeId');

            if (empty($inspection_type_id)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Field Inspection Type Id !'
                );
                $this->set(compact('msg'));
                return;
            }
        }

        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($inspector_group_id, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data['LicenseModuleFieldInspectionApprovalDetail'];
            if (empty($org_id))
                $org_id = $posted_data['org_id'];

            $option = $posted_data['inspection_approval_id'];

            if (!empty($option)) {

                $posted_data['inspection_type_id'] = $inspection_type_id;
                $posted_data['inspection_slno'] = $inspection_slno;

                $posted_data['inspector_user_id'] = $this->Session->read('User.Id');
                $posted_data['is_editor_user'] = 0;

                $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');

                if ($option == 1) {

                    $this->LicenseModuleFieldInspectionApprovalDetail->create();
                    $done = $this->LicenseModuleFieldInspectionApprovalDetail->save($posted_data);

                    if ($done) {
                        $approve_id = $done['LicenseModuleFieldInspectionApprovalDetail']['id'];

                        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                            'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);

                        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                        $inspector_id_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => $conditions, 'recursive' => -1, 'group' => 'inspector_user_id'));

                        $conditions = array('LicenseModuleFieldInspectionApprovalDetail.org_id' => $org_id,
                            'LicenseModuleFieldInspectionApprovalDetail.inspection_type_id' => $inspection_type_id,
                            'LicenseModuleFieldInspectionApprovalDetail.inspection_slno' => $inspection_slno,
                            'LicenseModuleFieldInspectionApprovalDetail.inspection_approval_id' => 1,
                            'LicenseModuleFieldInspectionApprovalDetail.inspector_user_id' => $inspector_id_list);

                        $approve_count = $this->LicenseModuleFieldInspectionApprovalDetail->find('count', array('fields' => 'inspector_user_id', 'conditions' => $conditions, 'recursive' => -1, 'group' => 'inspector_user_id'));

                        if ((!empty($approve_count) && $approve_count == count($inspector_id_list))) {

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
                                    $this->LicenseModuleFieldInspectionApprovalDetail->delete($approve_id);
                                    return;
                                }
                            } else {
                                $msg = array(
                                    'type' => 'warning',
                                    'title' => 'Warning... . . !',
                                    'msg' => 'Invalid State Information !'
                                );
                                $this->set(compact('msg'));
                                $this->LicenseModuleFieldInspectionApprovalDetail->delete($approve_id);
                                return;
                            }

                            $conditions = array('LicenseModuleFieldInspectionDetail.org_id' => $org_id,
                                'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id,
                                'LicenseModuleFieldInspectionDetail.inspection_slno' => $inspection_slno,
                                'LicenseModuleFieldInspectionDetail.is_approved' => 0);

                            $this->LicenseModuleFieldInspectionDetail->updateAll(array('is_approved' => 1), $conditions);

                            $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                                'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                                'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                                'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                                'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);
                            $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                            $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_completed' => 1), $conditions);

                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id));

                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $thisStateIds[2],
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->save($org_state_history);
                        }
                    }
                } else if ($option == 2) {
                    $conditions = array('LicenseModuleFieldInspectionDetail.org_id' => $org_id,
                        'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id,
                        'LicenseModuleFieldInspectionDetail.inspection_slno' => $inspection_slno,
                        'LicenseModuleFieldInspectionDetail.is_approved' => 0);

                    $done = $this->LicenseModuleFieldInspectionDetail->updateAll(array('is_approved' => -1), $conditions);
                    if ($done) {
                        ///$done = $this->LicenseModuleFieldInspectionApprovalDetail->deleteAll(array('org_id' => $org_id), false);

                        $conditions = array('LicenseModuleFieldInspectionApprovalDetail.org_id' => $org_id,
                            'LicenseModuleFieldInspectionApprovalDetail.inspection_type_id' => $inspection_type_id,
                            'LicenseModuleFieldInspectionApprovalDetail.inspection_slno' => $inspection_slno,
                            'LicenseModuleFieldInspectionApprovalDetail.inspection_approval_id' => 1);

                        $this->LicenseModuleFieldInspectionApprovalDetail->updateAll(array('inspection_approval_id' => -1), $conditions);
                        $done = $this->LicenseModuleFieldInspectionApprovalDetail->save($posted_data);
                        if ($done) {

                            $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                                'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                                'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                                'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                                'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);

                            $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                            $inspector_email_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'AdminModuleUserProfile.email', 'conditions' => $conditions, 'recursive' => 0, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.inspector_user_id'));

                            if (!empty($inspector_email_list)) {

                                try {
                                    $msg = $posted_data['inspection_comment'];

                                    $inspector_name = $this->LicenseModuleFieldInspectionInspectorDetail->AdminModuleUserProfile->field('AdminModuleUserProfile.full_name_of_user', array('AdminModuleUserProfile.user_id' => $this->Session->read('User.Id')));

                                    $orgDetails = $this->LicenseModuleFieldInspectionDetail->BasicModuleBasicInformation->find('first', array('fields' => array('form_serial_no', 'short_name_of_org', 'full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
                                    if (!empty($orgDetails)) {
                                        $org_info = $orgDetails['BasicModuleBasicInformation'];
                                        $org_name = $org_info['full_name_of_org'] . (!empty($org_info['short_name_of_org']) ? ' (' . $org_info['short_name_of_org'] . ')' : '');
                                        $org_form_serial_no = $org_info['form_serial_no'];
                                    }

                                    $message_body = 'Dear Inspector,'
                                            . "\r\n" . "\r\n"
                                            . "Inspector Name: $inspector_name"
                                            . " did not approve the Initial Field Inspection of \"$org_name\" with Form No.:$org_form_serial_no."
                                            . " As a result the approval status of all members has been reset."
                                            . " Due to the \"$msg\" issue." . "\r\n \r\n"
                                            . "Please re-approve the Initial Field Inspection."
                                            . "\r\n \r\n"
                                            . "Thanks" . "\r\n"
                                            . "Microcredit Regulatory Authority (MRA)";



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
                                    $mail_details['mail_to'] = implode(';', $inspector_email_list);
                                    $mail_details['mail_cc'] = '';
                                    $mail_details['mail_subject'] = 'License Initial Field Inspection not Approve !';
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

                                    $msg = array(
                                        'type' => 'error',
                                        'title' => 'Error... . . !',
                                        'msg' => 'Error in mail sending !\r\n' . $ex
                                    );
                                    $this->set(compact('msg'));
                                }
                            }
                        }
                    }
                }

                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view');
                $this->redirect($redirect_url);
                return;
            } else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Approval Status must be selected !'
                );
                $this->set(compact('msg'));
            }
        }

        $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 2);

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        $inspector_name_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => $conditions, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);

        $this->loadModel('LookupLicenseApprovalStatus');
        $approval_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));
        $conditions = array('LicenseModuleFieldInspectionDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionDetail.inspection_slno' => $inspection_slno,
            'LicenseModuleFieldInspectionDetail.is_approved' => 0);

        $inspectionDetails = $this->LicenseModuleFieldInspectionDetail->find('first', array('conditions' => $conditions, 'order' => array('submission_date' => 'desc')));
        $orgName = '';
        if (!empty($inspectionDetails)) {
            $this->request->data = $inspectionDetails;

            if (!empty($inspectionDetails['BasicModuleBasicInformation'])) {
                $orgDetail = $inspectionDetails['BasicModuleBasicInformation'];
                $orgName = $orgDetail['full_name_of_org'];
                $orgName = $orgName . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
            }
        }

        $option_values = array('1' => 'Yes', '0' => 'No');

        $this->set(compact('org_id', 'inspection_type_id', 'inspection_type_detail', 'inspection_slno', 'orgName', 'option_values', 'approval_status_options', 'recommendation_status_options', 'inspector_names'));

        return;
    }

    public function inspection_details($org_id = null, $inspection_type_id = null, $inspection_slno = null) {

        if (!empty($org_id)) {

            if (empty($inspection_type_id)) {
                $inspection_type_id = $this->Session->read('Current.InspectionTypeId');

                if (empty($inspection_type_id)) {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'Invalid Field Inspection Type Id !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
            }

            $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

            $conditions = array('LicenseModuleFieldInspectionDetail.org_id' => $org_id,
                'LicenseModuleFieldInspectionDetail.inspection_type_id' => $inspection_type_id);

            if (!empty($inspection_slno))
                $conditions = array_merge($conditions, array('LicenseModuleFieldInspectionDetail.inspection_slno' => $inspection_slno));

            $inspectionBasicDetails = $this->LicenseModuleFieldInspectionDetail->find('first', array('conditions' => $conditions, 'order' => array('inspection_slno' => 'desc')));

            if (!empty($inspectionBasicDetails) && !empty($inspectionBasicDetails['LicenseModuleFieldInspectionDetail']['id'])) {

                $inspection_id = $inspectionBasicDetails['LicenseModuleFieldInspectionDetail']['id'];
                if (empty($inspection_slno))
                    $inspection_slno = $inspectionBasicDetails['LicenseModuleFieldInspectionDetail']['inspection_slno'];

                $this->loadModel('LicenseModuleFieldInspectionFieldDetail');
                $fields = array('LicenseModuleFieldInspectionFieldDetail.parameter_id', 'LicenseModuleFieldInspectionFieldDetail.parameter_value');
                $inspectionDynamicDetails = $this->LicenseModuleFieldInspectionFieldDetail->find('list', array('fields' => $fields, 'conditions' => array('LicenseModuleFieldInspectionFieldDetail.inspection_id' => $inspection_id), 'recursive' => 0, 'order' => array('LicenseModuleFieldInspectionFieldDetail.parameter_id')));

                $inspectionDetails['basic'] = $inspectionBasicDetails['LicenseModuleFieldInspectionDetail'];
                $inspectionDetails['dynamic'] = $inspectionDynamicDetails;

                if (!$this->request->data) {
                    $this->request->data = $inspectionDetails;
                }

                $current_year = $this->Session->read('Current.LicensingYear');
                $fields = array('LookupLicenseInspectionParameter.parameter_group_id', 'LookupLicenseInspectionParameter.id', 'LookupLicenseInspectionParameter.parameter_name', 'LookupLicenseInspectionParameter.parameter_type');
                $conditions = array('LookupLicenseInspectionParameter.inspection_type_id' => $inspection_type_id, 'LookupLicenseInspectionParameter.licensing_year' => $current_year);
                $parameterList = $this->LicenseModuleFieldInspectionFieldDetail->LookupLicenseInspectionParameter->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LookupLicenseInspectionParameter.parameter_group_id' => 'ASC', 'LookupLicenseInspectionParameter.serial_no' => 'ASC')));

                $fields = array('LookupLicenseInspectionParameterGroup.id', 'LookupLicenseInspectionParameterGroup.inspection_parameter_group');
                $conditions = array('LookupLicenseInspectionParameterGroup.inspection_type_id' => $inspection_type_id, 'LookupLicenseInspectionParameterGroup.licensing_year' => $current_year);
                $parameterGroupList = $this->LicenseModuleFieldInspectionFieldDetail->LookupLicenseInspectionParameter->LookupLicenseInspectionParameterGroup->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LookupLicenseInspectionParameterGroup.serial_no' => 'ASC')));

                if (!empty($inspectionBasicDetails['BasicModuleBasicInformation'])) {
                    $orgDetail = $inspectionBasicDetails['BasicModuleBasicInformation'];
                    $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
                } else {
                    $orgName = '';
                }

                $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
                    'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                    'LicenseModuleFieldInspectionInspectorDetail.inspection_slno' => $inspection_slno,
                    'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1);

                $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                $inspector_name_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => $conditions, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.inspector_user_id'));
                $inspector_names = implode('<br />', $inspector_name_list);

                $this->loadModel('LookupLicenseApprovalStatus');
                $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

                $option_values = array('1' => 'Yes', '0' => 'No');

                if ($this->request->query('licensed_mfi') != null)
                    $licensed_mfi = empty($this->request->query('licensed_mfi')) ? 0 : 1;
                else
                    $licensed_mfi = empty($this->Session->read('Current.LicensedMFI')) ? 0 : 1;

                $this->set(compact('org_id', 'licensed_mfi', 'inspection_type_id', 'inspection_type_detail', 'inspection_slno', 'parameterGroupList', 'parameterList', 'option_values', 'recommendation_status_options', 'inspector_names'));
            }
        }
    }

    public function preview($org_id = null, $inspection_type_id = null, $inspection_slno = null) {
        $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

        if ($this->request->query('licensed_mfi') != null)
            $licensed_mfi = empty($this->request->query('licensed_mfi')) ? 0 : 1;
        else
            $licensed_mfi = empty($this->Session->read('Current.LicensedMFI')) ? 0 : 1;

        $this->set(compact('org_id', 'licensed_mfi', 'inspection_type_id', 'inspection_type_detail', 'inspection_slno'));
    }

    function inspector_select() {

        $this->layout = 'ajax';
        $org_id = $this->request->data['LicenseModuleFieldInspectionDetail']['org_id'];

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        //$inspector_name_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id)));
        $inspector_name_list = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id, 'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleFieldInspectionInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);

        $this->set(compact('inspector_names'));
    }

    public function inspection_approval_details($org_id = null, $inspection_type_id = null, $inspection_slno = null) {

        if (empty($org_id)) {
            $approvalDetails = null;
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg', 'approvalDetails'));
            return;
        }

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->Session->read('Current.InspectionTypeId');

            if (empty($inspection_type_id)) {
                $approvalDetails = null;
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Field Inspection Type Id !'
                );
                $this->set(compact('msg', 'approvalDetails'));
                return;
            }
        }

        $inspection_type_detail = $this->LicenseModuleFieldInspectionDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

        $fields = array('LicenseModuleFieldInspectionApprovalDetail.submission_date', 'LicenseModuleFieldInspectionApprovalDetail.inspection_comment', 'LookupLicenseApprovalStatus.approval_status', 'AdminModuleUserProfile.full_name_of_user');

        $conditions = array('LicenseModuleFieldInspectionApprovalDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionApprovalDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionApprovalDetail.inspection_slno' => $inspection_slno);

        $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');
        $approvalDetails = $this->LicenseModuleFieldInspectionApprovalDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => array('submission_date' => 'desc', 'LicenseModuleFieldInspectionApprovalDetail.id' => 'desc')));
        $this->set(compact('inspection_type_id', 'inspection_type_detail', 'inspection_slno', 'approvalDetails'));
    }

}
