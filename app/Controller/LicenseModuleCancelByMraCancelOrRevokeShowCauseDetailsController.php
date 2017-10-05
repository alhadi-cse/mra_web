<?php

App::uses('AppController', 'Controller');

class LicenseModuleCancelByMraCancelOrRevokeShowCauseDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    public function view($opt = 'all', $mode = null) {
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

        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id' => $org_id);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }
        $next_viewable_states = explode('^', $thisStateIds[0]);
        $pending_value_condition = array('BasicModuleBasicInformation.licensing_state_id' => $next_viewable_states);
        $completed_value_condition = array('BasicModuleBasicInformation.licensing_state_id' => array($thisStateIds[1]));

        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings = array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->recursive = 0;
        $values_approved = $this->Paginator->paginate('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail');
        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all', 'values_approved', 'values_not_approved'));
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

        $cancelOrRevokeShowCauseDetails = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->find('first', array('conditions' => array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id' => $org_id)));
        $this->set(compact('cancelOrRevokeShowCauseDetails'));
    }

    public function preview($org_id = null, $pending_status = null) {
        $this->set(compact('org_id', 'pending_status'));
    }

    public function approve_all() {
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
                $newData = $this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetailAll'];
                if (!empty($newData)) {

                    $all_org_state_history = array();
                    $this->loadModel('BasicModuleBasicInformation');
                    foreach ($newData as $new_data) {
                        $org_id = $new_data['org_id'];
                        if (!empty($org_id)) {
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

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
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'approve_all'));
            }
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id');
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialAssessmentDetail');
        $approval_status_options = array('0' => 'Revoke Show Cause', '1' => 'Cancel License');

        $this->set(compact('orgDetails', 'approval_status_options'));
    }

    public function approve_edit_all() {

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
                $newData = $this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetailAll'];

                if (!empty($newData)) {
                    $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->set($newData);
                    if ($this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->saveAll($newData)) {
                        $this->redirect(array('action' => 'view'));
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $condition1 = array('licensing_state_id' => $thisStateIds[1]);
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->loadModel('BasicModuleBasicInformation');
        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');

        $approval_status_options = array('1' => 'Revoke Show Cause', '0' => 'Cancel License');
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
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $orgFullName = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];

        $approval_status_options = array('1' => 'Revoke Show Cause', '0' => 'Cancel License');
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail'];
                if (!empty($newData)) {
                    $existingData = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->find('first', array('fields' => array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id' => $newData['org_id'])));
                    if (!empty($existingData)) {
                        $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->id = $existingData['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['id'];
                        $done = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->save($newData);
                    } else {
                        $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->create();
                        $done = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->save($newData);
                    }

                    if ($done) {
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[1],
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
            } catch (Exception $ex) {
                
            }
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
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->find('first', array('conditions' => array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id' => $org_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {

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

        if ($this->request->is(array('post', 'put'))) {
            $newData = $this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail'];

            if (!empty($newData)) {
                $state_id = '';
                if ($this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id'] == '0') {
                    $state_id = $thisStateIds[1];
                } else if ($this->request->data['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id'] == '1') {
                    $state_id = $thisStateIds[2];
                }

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->id = $approvalDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['id'];
                if ($this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->find('first', array('conditions' => array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = array('1' => 'Revoke Show Cause', '0' => 'Cancel License');
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

}
