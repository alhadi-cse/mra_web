<?php

App::uses('AppController', 'Controller');

class LicenseModuleFieldInspectorAdminApprovalDetailsController extends AppController {

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
        
        if (!isset($licensed_mfi)) {
            $licensed_mfi = $this->request->query('licensed_mfi');
        }
        if (empty($licensed_mfi))
            $licensed_mfi = 0;
        $this->Session->write('Current.LicensedMFI', $licensed_mfi);
        
        $parameter = array('inspection_type_id' => $inspection_type_id, 'this_state_ids' => $this_state_ids, 'committee_group_id' => $committee_group_id, 'licensed_mfi' => $licensed_mfi);
        $redirect_url = array('controller' => 'LicenseModuleFieldInspectorAdminApprovalDetails', 'action' => 'view', '?' => $parameter);
        $this->Session->write('Current.RedirectUrl', $redirect_url);
        
        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        $inspection_type_detail = $this->LicenseModuleFieldInspectionInspectorDetail->LookupLicenseInspectionType->find('list', array('fields' => array('id', 'inspection_type'), 'conditions' => array('id' => $inspection_type_id), 'recursive' => -1));

        $condition_not_approved = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $condition_approved = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'LicenseModuleFieldInspectionInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $values_not_approved = $this->LicenseModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_not_approved, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));
        $values_approved = $this->LicenseModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_approved, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));

        $this->set(compact('inspection_type_id', 'inspection_type_detail', 'licensed_mfi', 'values_not_approved', 'values_approved'));
        return;
    }

    public function approve($org_id = null, $inspection_type_id = null) {
        
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

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        
        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
        $done = $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1), $conditions);

        if ($done) {
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

            $redirect_url = $this->Session->read('Current.RedirectUrl');
            if (empty($redirect_url))
                $redirect_url = array('action' => 'view', $inspection_type_id);
            $this->redirect($redirect_url);
            return;
        }
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

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        
        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
        $done = $this->LicenseModuleFieldInspectionInspectorDetail->deleteAll($conditions, false);
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
    
    public function approve_cancel($org_id = null, $inspection_type_id = null) {

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

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        
        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id,
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
        $done = $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0), $conditions);
        if ($done) {
            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

            $org_state_history = array(
                'org_id' => $org_id,
                'state_id' => $thisStateIds[1],
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

    public function approve_all($inspection_type_id = null, $licensed_mfi = null) {
        
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleFieldInspectorAdminApprovalDetailAll'];
                if (!empty($newData)) {
                    $org_ids = Hash::extract($newData, '{n}[is_approved = 1].org_id');

                    if (!empty($org_ids)) {
                        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
                        
                        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_ids,
                            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0,
                            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

                        $done = $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1), $conditions);
                        if ($done) {
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_ids));

                            $all_org_state_history = array();
                            foreach ($org_ids as $org_id) {
                                if (!empty($org_id)) {
                                    $org_state_history = array(
                                        'org_id' => $org_id,
                                        'state_id' => $thisStateIds[2],
                                        //'licensing_year' => $current_year,
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

                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view', $inspection_type_id, $licensed_mfi);
                            $this->redirect($redirect_url);
                            return;
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all', $inspection_type_id, $licensed_mfi));
            }
        }

        $condition_not_approved = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'LicenseModuleFieldInspectionInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        $values_not_approved = $this->LicenseModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_not_approved, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));

        $this->set(compact('values_not_approved', 'licensed_mfi'));
    }

    public function approve_edit_all($inspection_type_id = null, $licensed_mfi = null) {

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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleFieldInspectorAdminApprovalDetailAll'];
                if (!empty($newData)) {
                    $org_ids = Hash::extract($newData, '{n}[is_approved = 0].org_id');

                    if (!empty($org_ids)) {

                        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');

                        $conditions = array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_ids,
                            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
                            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
                            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
                        $done = $this->LicenseModuleFieldInspectionInspectorDetail->updateAll(array('LicenseModuleFieldInspectionInspectorDetail.is_approved' => 0), $conditions);
                        if ($done) {
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_ids));

                            $all_org_state_history = array();
                            foreach ($org_ids as $org_id) {
                                if (!empty($org_id)) {
                                    $org_state_history = array(
                                        'org_id' => $org_id,
                                        'state_id' => $thisStateIds[1],
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

                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view', $inspection_type_id, $licensed_mfi);
                            $this->redirect($redirect_url);
                            return;
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_edit_all', $inspection_type_id, $licensed_mfi));
            }
        }

        $condition_approved = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2],
            'LicenseModuleFieldInspectionInspectorDetail.inspection_type_id' => $inspection_type_id,
            'LicenseModuleFieldInspectionInspectorDetail.is_approved' => 1,
            'LicenseModuleFieldInspectionInspectorDetail.is_completed' => 0);
        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'LicenseModuleFieldInspectionInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('LicenseModuleFieldInspectionInspectorDetail');
        $values_approved = $this->LicenseModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_approved, 'group' => 'LicenseModuleFieldInspectionInspectorDetail.org_id'));

        $this->set(compact('values_approved', 'licensed_mfi'));
    }

    
    
    public function re_approve($org_id = null, $inspection_type_id = null) {

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
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleFieldInspectorAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleFieldInspectorAdminApprovalDetail.org_id' => $org_id)));
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
            $newData = $this->request->data['LicenseModuleFieldInspectorAdminApprovalDetail'];
            if (!empty($newData)) {
                $this->LicenseModuleFieldInspectorAdminApprovalDetail->id = $approvalDetails['LicenseModuleFieldInspectorAdminApprovalDetail']['id'];
                if ($this->LicenseModuleFieldInspectorAdminApprovalDetail->save($newData)) {
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                    if (empty($redirect_url))
                        $redirect_url = array('action' => 'view', $inspection_type_id);
                    $this->redirect($redirect_url);
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleFieldInspectorAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleFieldInspectorAdminApprovalDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->LicenseModuleFieldInspectorAdminApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

    public function delete($org_id) {

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

        if (!empty($org_id)) {
            if ($this->LicenseModuleFieldInspectionInspectorDetail->deleteAll(array('LicenseModuleFieldInspectionInspectorDetail.org_id' => $org_id), false)) {
                $condition = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]);

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('licensing_state_id' => $thisStateIds[1]), $condition);

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $thisStateIds[1],
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
            }
        }
    }

}
