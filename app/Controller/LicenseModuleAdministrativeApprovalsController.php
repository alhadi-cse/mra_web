<?php

App::uses('AppController', 'Controller');

class LicenseModuleAdministrativeApprovalsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    public function view($opt = 'all', $mode = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(8,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_id = $this->request->query('this_state_id');
        if (!empty($this_state_id))
            $this->Session->write('Current.StateId', $this_state_id);
        else
            $this_state_id = $this->Session->read('Current.StateId');

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
            $condition = array_merge(array('LicenseModuleAdministrativeApproval.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleAdministrativeApproval']['search_option'];
            $keyword = $this->request->data['LicenseModuleAdministrativeApproval']['search_keyword'];

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

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id - 1);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
        }

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');
        $values_not_approved = $this->LicenseModuleAdministrativeApproval->BasicModuleBasicInformation->find('all', array('fields' => $all_fields, 'conditions' => $condition1));

        $this->paginate = array('conditions' => $condition2, 'group' => array('org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleAdministrativeApproval->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_approved = $this->Paginator->paginate('LicenseModuleAdministrativeApproval');

        $this->set(compact('values_approved', 'values_not_approved'));
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

        $licApprovalDetails = $this->LicenseModuleAdministrativeApproval->find('first', array('conditions' => array('LicenseModuleAdministrativeApproval.org_id' => $org_id)));
        $this->set(compact('licApprovalDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function approve_all() {

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(8,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_id = $this->Session->read('Current.StateId');
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
                $newData = $this->request->data['LicenseModuleAdministrativeApprovalAll'];
                if (!empty($newData)) {
                    $this->LicenseModuleAdministrativeApproval->create();
                    if ($this->LicenseModuleAdministrativeApproval->saveAll($newData)) {

                        $all_org_state_history = array();
                        foreach ($newData as $new_data) {
                            $org_id = $new_data['org_id'];
                            if (!empty($org_id)) {
                                //$condition = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $this_state_id - 1);
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $this_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                                // $org_state_history = array(
                                // 'org_id' => $org_id,
                                // 'state_id' => $this_state_id - 1,
                                // 'licensing_year' => $current_year,
                                // 'date_of_state_update' => date('Y-m-d'),
                                // 'user_name' => $this->Session->read('User.Name'));

                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $this_state_id,
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

                        $this->redirect(array('action' => 'view'));
                        return;
                    }
                }
            } catch (Exception $ex) {
                
            }
        }


        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id - 1);
//        if (!empty($condition)) { 
//            $condition1 = array_merge($condition1, $condition);
//        }

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');

        $approval_status_options = $this->LicenseModuleAdministrativeApproval->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));

        $this->set(compact('orgDetails', 'approval_status_options'));
    }

    public function approve_edit_all() {

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(8,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_id = $this->Session->read('Current.StateId');
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
                $newData = $this->request->data['LicenseModuleAdministrativeApprovalAll'];
//                foreach ($newData as $nData) {
//                    //$newData = $this->request->data['LicenseModuleAdministrativeApproval'];
//                    if (!empty($nData)) {
//                        $id = $nData['id'];
//                        if (!empty($id)) {
//                            $this->LicenseModuleAdministrativeApproval->id = $id;
//                            $nData = Hash::remove($nData, 'id');
//                            $this->LicenseModuleAdministrativeApproval->save($nData);
//                        }
//                    }
//                }

                if (!empty($newData)) {
                    $this->LicenseModuleAdministrativeApproval->set($newData);
                    if ($this->LicenseModuleAdministrativeApproval->saveAll($newData)) {
                        $this->redirect(array('action' => 'view'));
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');

        $approval_status_options = $this->LicenseModuleAdministrativeApproval->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
//        
//        $this->paginate = array(
//            'limit' => 10,
//            'order' => array('form_serial_no' => 'asc'));
//        
//        $this->Paginator->settings = $this->paginate;
//        $orgDetails = $this->Paginator->paginate('LicenseModuleAdministrativeApproval');
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

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleAdministrativeApproval'];

                if (!empty($newData)) {
                    $existingData = $this->LicenseModuleAdministrativeApproval->find('first', array('fields' => array('LicenseModuleAdministrativeApproval.id'), 'conditions' => array('org_id' => $newData['org_id'])));

                    if ($existingData) {
                        $this->LicenseModuleAdministrativeApproval->id = $existingData['LicenseModuleAdministrativeApproval']['id'];
                        $done = $this->LicenseModuleAdministrativeApproval->save($newData);
                    } else {
                        $this->LicenseModuleAdministrativeApproval->create();
                        $done = $this->LicenseModuleAdministrativeApproval->save($newData);
                    }

                    if ($done) {

                        if (empty($org_id))
                            $org_id = $newData['org_id'];

                        $this_state_id = $this->Session->read('Current.StateId');
                        $current_year = $this->Session->read('Current.LicensingYear');

                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $this_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $this_state_id,
                            'licensing_year' => $current_year,
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

        $orgFullName = $this->LicenseModuleAdministrativeApproval->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgFullName['BasicModuleBasicInformation']['full_name_of_org'];

        $approval_status_options = $this->LicenseModuleAdministrativeApproval->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
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

        $approvalDetails = $this->LicenseModuleAdministrativeApproval->find('first', array('conditions' => array('LicenseModuleAdministrativeApproval.org_id' => $org_id)));
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
            $newData = $this->request->data['LicenseModuleAdministrativeApproval'];
            if (!empty($newData)) {
                $this->LicenseModuleAdministrativeApproval->id = $approvalDetails['LicenseModuleAdministrativeApproval']['id'];
                if ($this->LicenseModuleAdministrativeApproval->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleAdministrativeApproval->find('first', array('conditions' => array('LicenseModuleAdministrativeApproval.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = $this->LicenseModuleAdministrativeApproval->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
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

        $licApprovalDetails = $this->LicenseModuleAdministrativeApproval->find('first', array('conditions' => array('LicenseModuleAdministrativeApproval.org_id' => $org_id)));
        if (!$licApprovalDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('licApprovalDetails'));
    }

}
