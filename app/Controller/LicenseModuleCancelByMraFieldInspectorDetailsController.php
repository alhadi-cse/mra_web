<?php

App::uses('AppController', 'Controller');

class LicenseModuleCancelByMraFieldInspectorDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');

    public function view() {        
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

        $inspector_group_id = $this->request->query('inspector_group_id');
        if (empty($inspector_group_id))
            $inspector_group_id = $this->Session->read('Inspector.GroupId');
        else
            $this->Session->write('Inspector.GroupId', $inspector_group_id);

        $office_type_id = $this->request->query('office_type_id');
        if (!empty($office_type_id))
            $this->Session->write('Office.TypeId', $office_type_id);
        else
            $office_type_id = $this->Session->read('Office.TypeId');

        $condition_assign = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]);
        $this->loadModel('BasicModuleBasicInformation');
        $orgIds_assign = $this->BasicModuleBasicInformation->find('list', array('fields' => array('id'), 'conditions' => $condition_assign));

        $allRows = null;
        if (!empty($orgIds_assign)) {
            $allDistIds = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('list', array('fields' => 'district_id', 'conditions' => array('org_id' => $orgIds_assign), 'group' => array('org_id'))); //, 'conditions' => array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => 'LookupAdminBoundaryDistrict.id')));

            if (!empty($allDistIds)) {

                $allDist = $this->LicenseModuleCancelByMraFieldInspectorDetail->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name'), 'conditions' => array('LookupAdminBoundaryDistrict.id' => $allDistIds))); //, 'conditions' => array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => 'LookupAdminBoundaryDistrict.id')));
                foreach ($allDist as $dist_id => $dist_name) {

                    $allOrgIds = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('list', array('fields' => 'org_id', 'conditions' => array('district_id' => $dist_id), 'group' => 'org_id'));
                    if (empty($allOrgIds))
                        continue;

                    $row = '';
                    $rowspan = 0;
                    foreach ($allOrgIds as $orgId) {
                        $allInspector = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleCancelByMraFieldInspectorDetail.org_id' => $orgId)));
                        $condition_assign = array('BasicModuleBasicInformation.id' => $orgId,
                            
                            'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]);
                        
                        $fields = array('LicenseModuleCancelByMraFieldInspectorDetail.inspection_date', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org');
                        $orgDetails = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('first', array('fields' => $fields, 'recursive' => 0, 'conditions' => $condition_assign, 'group' => 'LicenseModuleCancelByMraFieldInspectorDetail.org_id'));

                        if (empty($orgDetails))
                            continue;
                        $orgName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                        $orgFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                        $orgName = (!empty($orgName) ? "<strong>" . $orgName . (!empty($orgFullName) ? ":</strong> " : "</strong>"):"") . $orgFullName;
                        $ins_date = date("d-m-Y", strtotime($orgDetails['LicenseModuleCancelByMraFieldInspectorDetail']['inspection_date']));

                        ++$rowspan;
                        $row = $row . (empty($row) ? '' : '<tr>');
                        $row = $row . '<td style="text-align:center;">' . $orgDetails['BasicModuleBasicInformation']['license_no'] . '</td>'
                                . '<td>' . $orgName . '</td>'
                                . '<td>' . implode('<br />', $allInspector) . '</td>'
                                . '<td style="text-align:center;">' . $ins_date . '</td>';
                    }
                    $row = $row . "</tr> ";
                    $allRows = $allRows . '<tr><td rowspan="' . $rowspan . '">' . $dist_name . '</td>' . $row;
                }

                if (!empty($allRows)) {
                    $headerRows = '<tr><th style="width:100px;">District</th>'
                                    . '<th style="width:70px;">License No.</th>'
                                    . '<th style="min-width:175px;">Name of Organization</th>'
                                    . '<th style="width:150px;">Inspectors Name & Designation</th>'
                                    . '<th style="width:75px;">Inspection Date</th>'
                                . '</tr>';
                    $allRows = $headerRows . $allRows;
                }
            }
        }

        $condition = array( 'BasicModuleBasicInformation.licensing_state_id' => array($thisStateIds[0], $thisStateIds[1]), 'BasicModuleBranchInfo.office_type_id' => $office_type_id);
        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'LookupAdminBoundaryDistrict.district_name');

        $this->loadModel('BasicModuleBranchInfo');
        $this->paginate = array('fields' => $fields, 'conditions' => $condition, 'limit' => 10, 'order' => array('license_no' => 'asc'));
        $this->BasicModuleBranchInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_pending = $this->Paginator->paginate('BasicModuleBranchInfo');           
       
        $this->set(compact('allRows', 'values_pending'));
    }

    public function getArrayElement($array, $indexs, $justvalsplease = false) {
        $newarray = false;
        //verificamos el array
        if (is_array($array) && count($array) > 0) {

            //verify indexs and get # of indexs
            if (is_array($indexs) && count($indexs) > 0)
                $ninds = count($indexs);
            else
                return false;

            //search for coincidences
            foreach (array_keys($array) as $key) {

                //index value coincidence counter.
                $count = 0;

                //for each index we search           
                foreach ($indexs as $indx => $val) {

                    //if index value is equal then counts
                    if ($array[$key][$indx] == $val) {
                        $count++;
                    }
                }
                //if indexes match, we get the array elements :)
                if ($count == $ninds) {

                    //if you only need the vals of the first coincidence
                    //witch was my case by the way...
                    if ($justvalsplease)
                        return $array[$key];
                    else
                        $newarray[$key] = $array[$key];
                }
            }
        }
        return $newarray;
    }

    public function assign() {
        $inspector_group_id = $this->Session->read('Inspector.GroupId');
        $office_type_id = $this->Session->read('Office.TypeId');

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
        
        $this->loadModel('AdminModuleUser');
        $fields = array('AdminModuleUser.id', 'AdminModuleUser.name_with_designation_and_dept');
        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $inspector_group_id, 'AdminModuleUser.activation_status_id' => 1);
        $this->AdminModuleUser->virtualFields['name_with_designation_and_dept'] = 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office)';
        $inspector_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'recursive' => 0, 'conditions' => $conditions));
        
        $dist_list = null;
        $orgDetailsAll = null;
        //$condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $condition = array( 'BasicModuleBasicInformation.licensing_state_id' => array($thisStateIds[0], $thisStateIds[1]), 'BasicModuleBranchInfo.office_type_id' => $office_type_id);

        $this->loadModel('BasicModuleBranchInfo');
        $dist_ids = $this->BasicModuleBranchInfo->find('list', array('fields' => array('BasicModuleBranchInfo.district_id'), 'recursive' => 0, 'conditions' => $condition, 'group' => 'BasicModuleBranchInfo.district_id', 'order' => 'BasicModuleBranchInfo.district_id'));
        if (!empty($dist_ids)) {
            $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
            $condition1 = array('LookupAdminBoundaryDistrict.id' => $dist_ids);
            $dist_list = $this->LicenseModuleCancelByMraFieldInspectorDetail->LookupAdminBoundaryDistrict->find('list', array('fields' => $fields, 'conditions' => $condition1));

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBranchInfo.district_id');
            $orgDetailsAll = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $condition, 'order' => array('BasicModuleBranchInfo.district_id' => 'asc')));
        }


//        $condition = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        
//        if (!empty($evaluation_pass_marks_details) && !empty($evaluation_pass_marks_details[1])) {
//
//            $this->loadModel('LicenseModuleInitialAssessmentDetail');
//            $parameterOptionMaxList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
//            $total_marks = array_sum($parameterOptionMaxList);        
//            $pass_min_marks = ($evaluation_pass_marks_details[1] * $total_marks) / 100;
//            
//            //$passed_org_ids = $this->LicenseModuleInitialAssessmentDetail->find('list', array('fields' => array('LicenseModuleInitialAssessmentDetail.org_id'), 'recursive' => 0, 'conditions' => array_merge($condition, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks)), 'group' => 'LicenseModuleInitialAssessmentDetail.org_id'));
//            $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
//            $passed_org_ids = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'), 'recursive' => 0, 'conditions' => array_merge($condition, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks, 'LicenseModuleInitialAssessmentAdminApprovalDetail.approval_status_id' => 1)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
//            if (!empty($passed_org_ids)) {
//                $this->loadModel('BasicModuleBranchInfo');
//                $fields = array('BasicModuleBranchInfo.district_id');
//                $condition = array('BasicModuleBasicInformation.id' => $passed_org_ids, 'BasicModuleBranchInfo.office_type_id' => $office_type_id);
//                $dist_ids = $this->BasicModuleBranchInfo->find('list', array('fields' => $fields, 'recursive' => 0, 'conditions' => $condition, 'group' => 'BasicModuleBranchInfo.district_id', 'order' => 'BasicModuleBranchInfo.district_id'));
//                if (!empty($dist_ids)) {
//                    $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
//                    $condition1 = array('LookupAdminBoundaryDistrict.id' => $dist_ids);
//                    $dist_list = $this->LicenseModuleCancelByMraFieldInspectorDetail->LookupAdminBoundaryDistrict->find('list', array('fields' => $fields, 'conditions' => $condition1));
//
//                    $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBranchInfo.district_id');
//                    $orgDetailsAll = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $condition, 'order' => array('BasicModuleBranchInfo.district_id' => 'asc')));
//                }
//            }
//        }
        
        $this->set(compact('dist_list', 'inspector_list', 'orgDetailsAll'));
        
        if ($this->request->is('post')) {

            $posted_data = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail'];
            
            debug($posted_data);
            if (!empty($posted_data)) {

                $all_org_ids = array();
                $new_data_to_save = array();
                $all_org_state_history = array();
                foreach ($posted_data as $data) {                    
                    if(empty($data))continue;
                    
                    $org_ids = $data['org_ids'];
                    $district_id = $data['district_id'];
                    $inspector_user_ids = $data['inspector_user_ids'];                    
                    $inspection_dates = $data['inspection_dates'];

                    if (!empty($org_ids) && !empty($district_id) && !empty($inspector_user_ids) && !empty($inspection_dates)) {
                        foreach ($org_ids as $org_id) {
                            if (!empty($org_id) && $org_id > 0) {
                                $inspection_date = $inspection_dates[$org_id];
                                if (!empty($inspection_date)) {
                                    foreach ($inspector_user_ids as $inspector_user_id) {
                                        if (!empty($inspector_user_id) && $inspector_user_id > 0) {
                                            $new_data_to_save = array_merge($new_data_to_save, array(array('district_id' => $district_id, 'org_id' => $org_id, 'inspector_user_id' => $inspector_user_id, 'inspection_date' => $inspection_date, 'licensing_year' => $current_year, 'is_approved' => '0')));
                                        }
                                    }
                                    
                                    $all_org_ids = array_merge($all_org_ids, array($org_id));
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
                        }
                    }
                }
                debug($org_state_history);
                if (!empty($new_data_to_save)) {
                    $this->LicenseModuleCancelByMraFieldInspectorDetail->deleteAll(array('LicenseModuleCancelByMraFieldInspectorDetail.org_id' => $all_org_ids), false);
                    
                    $this->LicenseModuleCancelByMraFieldInspectorDetail->create();
                    $done = $this->LicenseModuleCancelByMraFieldInspectorDetail->saveAll($new_data_to_save);
                    debug($done);
                    if ($done) {
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $all_org_ids));

                        if (!empty($all_org_state_history)) {
                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        }

                        $this->redirect(array('action' => 'view'));
                    }
                } else {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'Inspector Assign Invalid !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
            }
        }
    }

    function organization_select_add() {
        $office_type_id = $this->Session->read('Office.TypeId');
        
        $district_id = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail']['district_id'];
        $this->loadModel('BasicModuleBranchInfo');
        $org_ids = $this->BasicModuleBranchInfo->find('all', array('fields' => array('BasicModuleBranchInfo.org_id'), 'conditions' => array('BasicModuleBranchInfo.district_id' => $district_id, 'BasicModuleBranchInfo.office_type_id' => $office_type_id)));
        $this->loadModel('BasicModuleBasicInformation');
        $orgLists = array();
        foreach ($org_ids as $org_id) {
            $orgId = $org_id['BasicModuleBranchInfo']['org_id'];
            $orgNames = $this->BasicModuleBasicInformation->find('all', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no'), 'conditions' => array('BasicModuleBasicInformation.id' => $orgId)));
            foreach ($orgNames as $value) {
                $orgName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                $license_no = $value['BasicModuleBasicInformation']['license_no'];
            }
            $orgDetailsMerged = Hash::merge(array('org_id' => $orgId), array('org_name_serial' => $orgName . ' (Form Form No.: ' . $license_no . ')'));
            $orgLists = array_merge($orgLists, array($orgDetailsMerged));
        }
        $organization_list = Hash::combine($orgLists, '{n}.org_id', '{n}.org_name_serial');
        $selected_org_list = Hash::extract($orgLists, '{n}.org_id');
        $this->set(compact('organization_list', 'selected_org_list'));
        $this->layout = 'ajax';
    }

    function organization_select_edit() {

        $district_id = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail']['district_id'];

        $organization_list = null;
        $selected_org_list = null;

        if (!empty($district_id)) {
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
        
            $office_type_id = $this->Session->read('Office.TypeId');
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

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org');
            $condition = array( 'BasicModuleBasicInformation.licensing_state_id' => array($thisStateIds[0], $thisStateIds[1]));

            $this->loadModel('LookupLicenseInitialAssessmentPassMark');
            $evaluation_pass_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));
            if (!empty($evaluation_pass_marks_details) && !empty($evaluation_pass_marks_details[1])) {

                $this->loadModel('LicenseModuleInitialAssessmentDetail');
                $parameterOptionMaxList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
                $total_marks = array_sum($parameterOptionMaxList);        
                $pass_min_marks = ($evaluation_pass_marks_details[1] * $total_marks) / 100;

//                $passed_org_ids = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => array_merge($condition, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks)), 'group' => 'LicenseModuleInitialAssessmentDetail.org_id'));// $condition, 'group' => array("org_id HAVING SUM(LookupLicenseInitialAssessmentParameterOption.assessment_marks)>=$pass_min_marks")));
//                $passed_org_ids = Hash::extract($passed_org_ids, '{n}.BasicModuleBasicInformation.id');
                
//                $passed_org_ids = $this->LicenseModuleInitialAssessmentDetail->find('list', array('fields' => array('LicenseModuleInitialAssessmentDetail.org_id'), 'recursive' => 0, 'conditions' => array_merge($condition, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks)), 'group' => 'LicenseModuleInitialAssessmentDetail.org_id'));
                $this->loadModel('LicenseModuleInitialAssessmentAdminApprovalDetail');
                $passed_org_ids = $this->LicenseModuleInitialAssessmentAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'), 'recursive' => 0, 'conditions' => array_merge($condition, array('LicenseModuleInitialAssessmentMark.total_assessment_marks >= ' => $pass_min_marks, 'LicenseModuleInitialAssessmentAdminApprovalDetail.approval_status_id' => 1)), 'group' => 'LicenseModuleInitialAssessmentAdminApprovalDetail.org_id'));
                if (!empty($passed_org_ids)) {
                    $condition = array('BasicModuleBasicInformation.id' => $passed_org_ids, 'BasicModuleBranchInfo.office_type_id' => $office_type_id, 'BasicModuleBranchInfo.district_id' => $district_id);
                    $this->loadModel('BasicModuleBranchInfo');
                    $this->BasicModuleBranchInfo->recursive = 0;
                    $organization_details = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $condition, 'order' => array('license_no' => 'asc')));
                    $organization_list = Hash::combine($organization_details, '{n}.BasicModuleBasicInformation.id', array('<strong>%s</strong>: %s (Form No.: %s)', '{n}.BasicModuleBasicInformation.short_name_of_org', '{n}.BasicModuleBasicInformation.full_name_of_org', '{n}.BasicModuleBasicInformation.license_no'));
                }
            }

            $selected_org_list = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('list', array('fields' => array('org_id'), 'conditions' => array('district_id' => $district_id), 'group' => array('org_id')));
        }
        
        $this->set(compact('organization_list', 'selected_org_list'));
        $this->layout = 'ajax';
    }

    function inspector_select_edit() {
        $district_id = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail']['district_id'];
        $inspector_list = null;
        $selected_inspector_list = null;

        if (!empty($district_id)) {
            $inspector_group_id = $this->Session->read('Inspector.GroupId');

//            $fields = array('AdminModuleUser.id');
//            $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $inspector_group_id);
//            $this->loadModel('AdminModuleUser');
//            $this->AdminModuleUser->recursive = 0;
//            $inspector_user_ids = $this->AdminModuleUser->find('list', array('fields' => $fields, 'conditions' => $conditions));
//            $fields = array('AdminModuleUserProfile.user_id', 'AdminModuleUserProfile.name_with_designation_and_division');
//            $conditions = array('AdminModuleUserProfile.user_id' => $inspector_user_ids);
//            $this->loadModel('AdminModuleUserProfile');
//            $this->AdminModuleUserProfile->recursive = 0;
//            $inspector_list = $this->AdminModuleUserProfile->find('list', array('fields' => $fields, 'conditions' => $conditions));
            
            $fields = array('AdminModuleUserProfile.user_id', 'AdminModuleUserProfile.full_name_of_user', 'AdminModuleUserProfile.designation_of_user', 'AdminModuleUserProfile.div_name_in_office');
            $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $inspector_group_id, 'AdminModuleUser.activation_status_id' => 1);
            $this->loadModel('AdminModuleUser');
            $this->AdminModuleUser->recursive = 0;
            $inspector_users = $this->AdminModuleUser->find('all', array('fields' => $fields, 'conditions' => $conditions));
            $inspector_list = Hash::combine($inspector_users, '{n}.AdminModuleUserProfile.user_id', array('%s, %s, %s', '{n}.AdminModuleUserProfile.full_name_of_user', '{n}.AdminModuleUserProfile.designation_of_user', '{n}.AdminModuleUserProfile.div_name_in_office'));
        

            $selected_inspector_list = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('list', array('fields' => array('LicenseModuleCancelByMraFieldInspectorDetail.inspector_user_id'), 'conditions' => array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => $district_id)));
        }
        $this->set(compact('inspector_list', 'selected_inspector_list'));
        $this->layout = 'ajax';
    }

    function grid_view_select() {
        $district_id = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail']['district_id'];
        $condition = array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => $district_id);
        $this->LicenseModuleCancelByMraFieldInspectorDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LicenseModuleCancelByMraFieldInspectorDetail');
        $this->paginate = array(
            'order' => array('LicenseModuleCancelByMraFieldInspectorDetail.id' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        $field_inspection_values = $this->LicenseModuleCancelByMraFieldInspectorDetail->find('all', array('conditions' => array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => $district_id)));

        $this->loadModel('BasicModuleBasicInformation');
        $org_list_values = array();
        $inspector_list_values = array();
        foreach ($field_inspection_values as $value) {
            $org_id = $value['LicenseModuleCancelByMraFieldInspectorDetail']['org_id'];
            $inspector_name = $value['LookupLicenseInspectorList']['inspector_name'];
            $org_names = $this->BasicModuleBasicInformation->find('all', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));

            foreach ($org_names as $org_name) {
                $orgName = $org_name['BasicModuleBasicInformation']['full_name_of_org'];
                $license_no = $org_name['BasicModuleBasicInformation']['license_no'];
                $orgDetailsMerged = Hash::merge(array('org_id' => $org_id), array('org_name' => $orgName), array('license_no' => $license_no));
            }
            $org_list_values = array_merge($org_list_values, array($orgDetailsMerged));
            $inspector_list_values = array_merge($inspector_list_values, array($inspector_name));
        }
        $org_list_values = array_values(array_unique($org_list_values, SORT_REGULAR));
        $inspector_list_values = array_values(array_unique($inspector_list_values, SORT_REGULAR));
        $inspector_names = implode(" & ", $inspector_list_values);

        $this->set(compact('org_list_values', 'inspector_names'));
        $this->layout = 'ajax';
    }

    //not completed
    public function re_assign($id = null, $inspector_user_id = null) {
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
        
        $inspector_group_id = $this->Session->read('Inspector.GroupId');
        $office_type_id = $this->Session->read('Office.TypeId');
        
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
        
        $fields = array('DISTINCT BasicModuleBranchInfo.district_id');
        $condition = array(
            'BasicModuleBasicInformation.licensing_state_id' => array($thisStateIds[0], $thisStateIds[1]),
            'BasicModuleBranchInfo.office_type_id' => $office_type_id);

        $this->loadModel('BasicModuleBranchInfo');
        $dist_ids = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $condition, 'order' => array('BasicModuleBranchInfo.district_id' => 'asc')));
        if (!empty($dist_ids))
            $dist_ids = Hash::extract($dist_ids, '{n}.BasicModuleBranchInfo.district_id');
        
        $dist_list = null;
        if (!empty($dist_ids)) {
            $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
            $condition1 = array('LookupAdminBoundaryDistrict.id' => $dist_ids);
            $dist_list = $this->LicenseModuleCancelByMraFieldInspectorDetail->LookupAdminBoundaryDistrict->find('list', array('fields' => $fields, 'conditions' => $condition1));
        }
        
        //$dist_list = $this->LicenseModuleCancelByMraFieldInspectorDetail->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $this->set(compact('dist_list', 'inspector_group_id'));

        if ($this->request->is('post')) {
            try {
                $flag = false;
                $posted_data = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail'];

                $district_id = Hash::extract($posted_data, 'district_id');
                $this->LicenseModuleCancelByMraFieldInspectorDetail->deleteAll(array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => $district_id), false);
                
                $org_ids = Hash::extract($posted_data, 'org_id');
                $inspector_user_ids = Hash::extract($posted_data, 'inspector_user_id');

                foreach ($org_ids as $org_id) {
                    foreach ($inspector_user_ids as $inspector_user_id) {
                        if ($inspector_user_id != "") {
                            $this->LicenseModuleCancelByMraFieldInspectorDetail->create();
                            $data_to_save = Hash::merge(array('district_id' => $district_id[0]), array('org_id' => $org_id), array('inspector_user_id' => $inspector_user_id));
                            $this->LicenseModuleCancelByMraFieldInspectorDetail->save($data_to_save);
                            $flag = true;
                        } else {
                            $flag = false;
                            break;
                        }
                    }
                }
                if ($flag) {
                    $this->redirect(array('action' => 'view', '?' => array('inspector_group_id' => $inspector_group_id)));
                } else {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'There no pending inspector to assign in this district'
                    );
                    $this->set(compact('msg'));
                }
            } catch (Exception $ex) {
                
            }
        }
    }

    public function deleteX($org_id) {
        $district_id = $this->request->data['LicenseModuleCancelByMraFieldInspectorDetail']['district_id'];
        $inspector_group_id = $this->Session->read('Inspector.GroupId');        
        $this->LicenseModuleCancelByMraFieldInspectorDetail->deleteAll(array('LicenseModuleCancelByMraFieldInspectorDetail.district_id' => $district_id));
        return $this->redirect(array('action'=>'view','?' => array('inspector_group_id' => $inspector_group_id)));
    }
    
    
    public function delete($org_id) {

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
        
        if (!empty($org_id)) {
            if ($this->LicenseModuleCancelByMraFieldInspectorDetail->deleteAll(array('LicenseModuleCancelByMraFieldInspectorDetail.org_id' => $org_id), false)) {
                $current_year = $this->Session->read('Current.LicensingYear');
                $condition = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]);

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('licensing_state_id' => $thisStateIds[0]), $condition);

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $thisStateIds[0],
                    'licensing_year' => $current_year,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

                return $this->redirect(array('action' => 'view'));
            }
        }
    }
}