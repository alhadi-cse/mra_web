<?php

App::uses('AppController', 'Controller');

class LicenseModuleDirectSuspensionFieldInspAdminApprovalDetailsController extends AppController {

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

        $condition_not_approved = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $condition_approved = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'LicenseModuleDirectSuspensionFieldInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
        $values_not_approved = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_not_approved, 'group' => 'LicenseModuleDirectSuspensionFieldInspectorDetail.org_id'));
        $values_approved = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_approved, 'group' => 'LicenseModuleDirectSuspensionFieldInspectorDetail.org_id'));
       
        $this->set(compact('values_not_approved', 'values_approved'));
        return;
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
                $newData = $this->request->data['LicenseModuleDirectSuspensionFieldInspAdminApprovalDetailAll'];
                if (!empty($newData)) {
                    $org_ids = Hash::extract($newData, '{n}[is_approved = 1].org_id');

                    if (!empty($org_ids)) {
//                        $this->redirect(array('action' => 'approve_all'));

                        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
                        $done = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->updateAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.is_approved' => 1), array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id' => $org_ids));
                        if ($done) {
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_ids));

                            $all_org_state_history = array();
                            foreach ($org_ids as $org_id) {
                                if (!empty($org_id)) {
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

                            $this->redirect(array('action' => 'view'));
                            return;
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));
            }
        }

        $condition_not_approved = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'LicenseModuleDirectSuspensionFieldInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
        $values_not_approved = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_not_approved, 'group' => 'LicenseModuleDirectSuspensionFieldInspectorDetail.org_id'));

        $this->set(compact('values_not_approved'));
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
                $newData = $this->request->data['LicenseModuleDirectSuspensionFieldInspAdminApprovalDetailAll'];
                if (!empty($newData)) {
                    $org_ids = Hash::extract($newData, '{n}[is_approved = 0].org_id');

                    if (!empty($org_ids)) {

                        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
                        $done = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->updateAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.is_approved' => 0), array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id' => $org_ids));
                        if ($done) {
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_ids));

                            $all_org_state_history = array();
                            foreach ($org_ids as $org_id) {
                                if (!empty($org_id)) {
                                    $org_state_history = array(
                                        'org_id' => $org_id,
                                        'state_id' => $thisStateIds[0],
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
                            return;
                        }
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));
            }
        }

        $condition_approved = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);
        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'LicenseModuleDirectSuspensionFieldInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
        $values_approved = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_approved, 'group' => 'LicenseModuleDirectSuspensionFieldInspectorDetail.org_id'));

        $this->set(compact('values_approved'));
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

        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
        $done = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->updateAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.is_approved' => 1), array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id' => $org_id));

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

            $this->redirect(array('action' => 'view'));
            return;
        }
    }

    public function approve_cancel($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
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

        $this->loadModel('LicenseModuleDirectSuspensionFieldInspectorDetail');
        $done = $this->LicenseModuleDirectSuspensionFieldInspectorDetail->updateAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.is_approved' => 0), array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id' => $org_id));
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

            $this->redirect(array('action' => 'view'));
            return;
        }
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

        $approvalDetails = $this->LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail.org_id' => $org_id)));
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
            $newData = $this->request->data['LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail'];
            if (!empty($newData)) {
                $this->LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail->id = $approvalDetails['LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail']['id'];
                if ($this->LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail->find('first', array('conditions' => array('LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->LicenseModuleDirectSuspensionFieldInspAdminApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

    public function deleteX() {
        $district_id = $this->request->data['LicenseModuleDirectSuspensionFieldInspectorDetail']['district_id'];
        $inspector_group_id = $this->Session->read('Inspector.GroupId');
        $this->LicenseModuleDirectSuspensionFieldInspectorDetail->deleteAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.district_id' => $district_id));
        return $this->redirect(array('action' => 'view', '?' => array('inspector_group_id' => $inspector_group_id)));
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
            if ($this->LicenseModuleDirectSuspensionFieldInspectorDetail->deleteAll(array('LicenseModuleDirectSuspensionFieldInspectorDetail.org_id' => $org_id), false)) {
                $current_year = $this->Session->read('Current.LicensingYear');
                $condition = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);

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
