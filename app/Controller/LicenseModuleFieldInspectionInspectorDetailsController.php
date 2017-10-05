<?php

App::uses('AppController', 'Controller');

class LicenseModuleFieldInspectionInspectorDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');

    public function view($inspection_type_id = null, $licensed_mfi = null) {

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->request->query('inspection_type_id');
            if (empty($inspection_type_id))
                $inspection_type_id = $this->Session->read('Current.InspectionTypeId');
        }

        if (empty($inspection_type_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Field Inspection Type Id !'
            );
            $this->set(compact('msg'));
            return;
        } else
            $this->Session->write('Current.InspectionTypeId', $inspection_type_id);

        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
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

        $licensed_mfi = $this->request->query('licensed_mfi');

        if (empty($licensed_mfi)) {
            $licensed_mfi = 0;
        } elseif (!empty($licensed_mfi)) {
            $this->Session->write('Current.LicensedMFI', $licensed_mfi);
        }

        $parameter = array('inspection_type_id' => $inspection_type_id, 'this_state_ids' => $this_state_ids, 'inspector_group_id' => $inspector_group_id, 'office_type_id' => $office_type_id, 'licensed_mfi' => $licensed_mfi);
        $redirect_url = array('controller' => 'LicenseModuleFieldInspectionInspectorDetails', 'action' => 'view', '?' => $parameter);
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $inspection_type_detail = $this->LicenseModuleFieldInspectionInspectorDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

        $condition_assign = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'LicenseModuleFieldInspectionInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT_WS(", ", CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>"), AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');
        //'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $values_assign = $this->LicenseModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_assign, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));

        if ($licensed_mfi == 1) {
            if (!empty($office_type_id)) {
                $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleBranchInfo.office_type_id' => $office_type_id);
            } else {
                $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
            }
            $fields = array('BasicModuleBranchInfo.id', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no', 'LookupAdminBoundaryDistrict.district_name');
            $this->loadModel('BasicModuleBranchInfo');
            $address_exists = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleBranchInfo.office_type_id' => $office_type_id)));
            $this->Paginator->settings = array('fields' => $fields, 'conditions' => $condition, 'limit' => 10, 'group' => array('BasicModuleBasicInformation.id'), 'order' => array('form_serial_no' => 'asc'), 'recursive' => 0);
            $values_pending = $this->Paginator->paginate('BasicModuleBranchInfo');
        } else {
            if (!empty($office_type_id)) {
                $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleProposedAddress.address_type_id' => $office_type_id);
            } else {
                $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
            }
            $fields = array('BasicModuleProposedAddress.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'LookupAdminBoundaryDistrict.district_name');
            $this->loadModel('BasicModuleProposedAddress');
            $address_exists = $this->BasicModuleProposedAddress->find('first', array('conditions' => array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleProposedAddress.address_type_id' => $office_type_id)));
            $this->Paginator->settings = array('fields' => $fields, 'conditions' => $condition, 'limit' => 10, 'group' => array('BasicModuleBasicInformation.id'), 'order' => array('form_serial_no' => 'asc'), 'recursive' => 0);
            $values_pending = $this->Paginator->paginate('BasicModuleProposedAddress');
        }
        $this->set(compact('inspection_type_id', 'inspection_type_detail', 'licensed_mfi', 'values_assign', 'values_pending', 'address_exists'));
    }

    public function assign($inspection_type_id = null, $licensed_mfi = null) {

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->request->query('inspection_type_id');
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
        }

        $inspector_group_id = $this->Session->read('Inspector.GroupId');
        $office_type_id = $this->Session->read('Office.TypeId');

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
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

        if ($this->request->is('post')) {

            $posted_data = $this->request->data['LicenseModuleFieldInspectionInspectorDetail'];
            if (!empty($posted_data)) {

//                debug($posted_data);
                $all_is_ok = true;
                $all_org_ids = array();
                $new_data_to_save = array();
                $all_org_state_history = array();

                $this->loadModel('LicenseModuleFieldInspectionDetail');
                foreach ($posted_data as $data) {
                    if (empty($data))
                        continue;

                    $org_ids = $data['org_ids'];
                    $district_id = $data['district_id'];
                    $inspector_user_ids = $data['inspector_user_ids'];
                    $team_leader_user_id = isset($data['team_leader_user_id']) ? $data['team_leader_user_id'] : null;
                    $inspection_dates = $data['inspection_dates'];
//                    debug($inspection_dates);
                    if (!empty($org_ids) && !empty($district_id) && !empty($inspector_user_ids) && !empty($inspection_dates)) {

//                        debug($inspection_dates);
                        foreach ($org_ids as $org_id) {
                            if (!empty($org_id) && !empty($inspection_dates[$org_id])) {

                                $condition = array('org_id' => $org_id, 'inspection_type_id' => $inspection_type_id);
                                $max_inspection_slno = $this->LicenseModuleFieldInspectionDetail->find('first', array('fields' => 'MAX(inspection_slno) as max_inspection_slno', 'conditions' => $condition, 'recursive' => -1));
                                $inspection_slno = $max_inspection_slno[0]['max_inspection_slno'] + 1;

                                $inspection_date = $inspection_dates[$org_id];

                                foreach ($inspector_user_ids as $inspector_user_id) {
                                    if (!empty($inspector_user_id)) {
                                        $is_team_leader = (!empty($team_leader_user_id) && ($team_leader_user_id == $inspector_user_id)) ? '1' : '0';

                                        $new_data = array(
                                            'district_id' => $district_id,
                                            'org_id' => $org_id,
                                            'inspection_type_id' => $inspection_type_id,
                                            'inspection_slno' => $inspection_slno,
                                            'inspector_user_id' => $inspector_user_id,
                                            'is_team_leader' => $is_team_leader,
                                            'inspection_date' => $inspection_date,
                                            'is_approved' => '0',
                                            'is_completed' => '0');

                                        $new_data_to_save = array_merge($new_data_to_save, array($new_data));
                                    } else
                                        $all_is_ok = false;
                                }

                                $all_org_ids = array_merge($all_org_ids, array($org_id));
                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $thisStateIds[1],
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    'user_name' => $this->Session->read('User.Name'));
                                $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                            } else
                                $all_is_ok = false;
                        }
                    }
                }

                if (!empty($new_data_to_save)) {
                    $condition = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $all_org_ids, 'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id, 'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
                    $this->LicenseModuleFieldInspectionInspectorDetail->recursive = -1;
                    $this->LicenseModuleFieldInspectionInspectorDetail->deleteAll($condition, false);

                    $this->LicenseModuleFieldInspectionInspectorDetail->create();
                    $done = $this->LicenseModuleFieldInspectionInspectorDetail->saveAll($new_data_to_save);
                    if ($done) {
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $all_org_ids));

                        if (!empty($all_org_state_history)) {
                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        }

                        if ($all_is_ok) {
                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view', $inspection_type_id, $licensed_mfi);
                            $this->redirect($redirect_url);
                            return;
                        } else {
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'Some error in inspector assign !'
                            );
                            $this->set(compact('msg'));
                        }
                    }
                } else {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'Inspector not assign !'
                    );
                    $this->set(compact('msg'));
                }
            }
        }

        $inspection_type_detail = $this->LicenseModuleFieldInspectionInspectorDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id)));

        $this->loadModel('AdminModuleUser');
        $fields = array('AdminModuleUser.id', 'AdminModuleUser.name_with_designation_and_dept');
        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $inspector_group_id, 'AdminModuleUser.activation_status_id' => 1);

        //$this->AdminModuleUser->virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS("<br />", CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>"), CONCAT_WS(", ", AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office))');
        $this->AdminModuleUser->virtualFields['name_with_designation_and_dept'] = $this->AdminModuleUser->AdminModuleUserProfile->virtualFields['name_with_designation_and_dept'];
        $inspector_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0));
//        debug($inspector_list);
        if ($licensed_mfi == 1) {
            $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleBranchInfo.office_type_id' => $office_type_id);
            $this->loadModel('BasicModuleBranchInfo');

            $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
            $dist_list = $this->BasicModuleBranchInfo->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'group' => 'BasicModuleBranchInfo.district_id', 'order' => 'BasicModuleBranchInfo.district_id'));

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.district_id');
            $orgDetailsAll = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'order' => array('BasicModuleBranchInfo.district_id' => 'asc')));
        } else {
            $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleProposedAddress.address_type_id' => $office_type_id);
            $this->loadModel('BasicModuleProposedAddress');

            $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
            $dist_list = $this->BasicModuleProposedAddress->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'group' => 'BasicModuleProposedAddress.district_id', 'order' => 'BasicModuleProposedAddress.district_id'));

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no', 'BasicModuleProposedAddress.district_id');
            $orgDetailsAll = $this->BasicModuleProposedAddress->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'order' => array('BasicModuleProposedAddress.district_id' => 'asc')));
        }

        $this->set(compact('inspection_type_id', 'inspection_type_detail', 'licensed_mfi', 'dist_list', 'inspector_list', 'orgDetailsAll'));
    }

    public function re_assign($inspection_type_id = null, $licensed_mfi = null) {

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->request->query('inspection_type_id');
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
        }

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
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

        $inspection_type_detail = $this->LicenseModuleFieldInspectionInspectorDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id)));

        $condition_assign = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'LicenseModuleFieldInspectionInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT_WS(", ", CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>"), AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');
        //'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $values_assign = $this->LicenseModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_assign, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));

        $this->set(compact('inspection_type_id', 'inspection_type_detail', 'licensed_mfi', 'values_assign'));
    }

    public function cancel($org_id = null, $inspection_type_id = null) {

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->request->query('inspection_type_id');
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

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
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

        $condition_assign = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
            'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $done = $this->LicenseModuleFieldInspectionInspectorDetail->deleteAll($condition_assign, false);
        if ($done) {
            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id));

            $org_state_history = array(
                'org_id' => $org_id,
                'state_id' => $thisStateIds[0],
                'licensing_year' => $this->Session->read('Current.LicensingYear'),
                'date_of_state_update' => date('Y-m-d'),
                'date_of_starting' => date('Y-m-d'),
                'user_name' => $this->Session->read('User.Name'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->create();
            $this->LicenseModuleStateHistory->save($org_state_history);

            $redirect_url = $this->Session->read('Current.RedirectUrl');
            if (empty($redirect_url))
                $redirect_url = array('action' => 'view', $inspection_type_id);
            $this->redirect($redirect_url);
            return;
        }
    }

    public function cancel_all($inspection_type_id = null) {

        if (empty($inspection_type_id)) {
            $inspection_type_id = $this->request->query('inspection_type_id');
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
        }

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
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

        $condition_assign = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $org_ids = $this->LicenseModuleFieldInspectionInspectorDetail->find('list', array('fields' => array('org_id'), 'conditions' => $condition_assign, 'recursive' => 0, 'group' => 'org_id'));

        if (!empty($org_ids)) {
            $done = $this->LicenseModuleFieldInspectionInspectorDetail->deleteAll($condition_assign, false);

            if ($done) {
                $this->loadModel('BasicModuleBasicInformation');
                $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_ids));
                if ($done) {
                    $all_org_state_history = array();
                    foreach ($org_ids as $org_id) {
                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[0],
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                    }

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                }

                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view', $inspection_type_id);
                $this->redirect($redirect_url);
                return;
            }
        }
    }

}
