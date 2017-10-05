<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleSuspensionFieldInspectionDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();
    var $uses = array('BasicModuleBasicInformation','LicenseModuleSuspensionFieldInspectionDetail');

    public function view($opt = 'all', $mode = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        $inspector_group_id = $this->request->query('inspector_group_id');
        if (empty($inspector_group_id))
            $inspector_group_id = $this->Session->read('Current.GroupId');
        else
            $this->Session->write('Current.GroupId', $inspector_group_id);

         if (empty($user_group_id) || !(in_array(1,$user_group_id) ||  in_array($inspector_group_id,$user_group_id))) {
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
            $thisStateIds = split('_', $this_state_ids);
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

        $opt_all = false;
        $user_is_editor = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        else {
            $user_committe_member_type_id = $this->Session->read('User.CommitteMemberTypeId');
            $user_is_editor = (!empty($user_committe_member_type_id) && $user_committe_member_type_id == 2); //false;//true;//        
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleSuspensionFieldInspectionDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleSuspensionFieldInspectionDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        $this->set(compact('org_id', 'user_group_id', 'user_is_editor', 'opt_all'));


        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1], 'LicenseModuleSuspensionFieldInspectionDetail.is_approved' => -1);
        $condition3 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1], 'LicenseModuleSuspensionFieldInspectionDetail.is_approved' => 0);
        $condition4 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2], 'LicenseModuleSuspensionFieldInspectionDetail.is_approved' => 1);

        if (in_array($inspector_group_id,$user_group_id)) {

            $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');

            $user_id = $this->Session->read('User.Id');
            $this_user_org_id_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'LicenseModuleSuspensionFieldInspectorDetail.org_id', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id' => $user_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.org_id'));

            if (empty($this_user_org_id_list)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'No organization has been assigned to the user !'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition1 = array_merge($condition1, array('BasicModuleBasicInformation.id' => $this_user_org_id_list));
            $condition2 = array_merge($condition2, array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $this_user_org_id_list));
            $condition3 = array_merge($condition3, array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $this_user_org_id_list));

            $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');
            $approved_org_id_list = $this->LicenseModuleFieldInspectionApprovalDetail->find('list', array('fields' => 'org_id', 'conditions' => array('inspector_user_id' => $user_id, 'inspection_approval_id' => 1), 'group' => 'org_id'));

            if (!empty($approved_org_id_list))
                $condition3 = array_merge($condition3, array('NOT' => array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $approved_org_id_list)));
        }

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);
            $condition3 = array_merge($condition3, $condition);
            $condition4 = array_merge($condition4, $condition);
        }

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no');

        $this->LicenseModuleSuspensionFieldInspectionDetail->recursive = 0;
        $values_not_inspect = $this->LicenseModuleSuspensionFieldInspectionDetail->BasicModuleBasicInformation->find('all', array('fields' => $all_fields, 'conditions' => $condition1));

        $values_inspected_not_submit = $this->LicenseModuleSuspensionFieldInspectionDetail->find('all', array('conditions' => $condition2, 'group' => array('org_id'), 'limit' => 10, 'order' => array('license_no' => 'asc')));

        $values_inspected_not_approved = $this->LicenseModuleSuspensionFieldInspectionDetail->find('all', array('conditions' => $condition3, 'group' => array('org_id'), 'limit' => 10, 'order' => array('license_no' => 'asc')));

        $this->paginate = array('conditions' => $condition4, 'group' => array('org_id'), 'limit' => 10, 'order' => array('license_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $values_inspected = $this->Paginator->paginate('LicenseModuleSuspensionFieldInspectionDetail');

        $this->set(compact('values_inspected', 'values_inspected_not_submit', 'values_inspected_not_approved', 'values_not_inspect'));
    }

    public function inspection($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
         if (empty($user_group_id) || !(in_array(1,$user_group_id) ||  in_array($inspector_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleSuspensionFieldInspectionDetail'];
                //debug($newData);
                //return;
                //$this->LicenseModuleSuspensionFieldInspectionDetail->deleteAll(array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $org_id), false);
                $this->LicenseModuleSuspensionFieldInspectionDetail->create();
                if ($this->LicenseModuleSuspensionFieldInspectionDetail->save($newData)) {

                    if (empty($org_id))
                        $org_id = $newData['org_id'];

                    $this_state_ids = $this->Session->read('Current.StateIds');
                    if (!empty($this_state_ids)) {
                        $thisStateIds = split('_', $this_state_ids);
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

                    $this->redirect(array('action' => 're_inspection', $org_id));
                    return;
                }
            } catch (Exception $ex) {
                //debug($ex);
            }
        }

        $option_values = array('1' => 'Yes', '0' => 'No');
        $orgNameDetail = $this->LicenseModuleSuspensionFieldInspectionDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $orgNameDetail['BasicModuleBasicInformation']['full_name_of_org'];
        if (!empty($orgNameDetail['BasicModuleBasicInformation']['short_name_of_org']))
            $orgName = $orgName . ' (' . $orgNameDetail['BasicModuleBasicInformation']['short_name_of_org'] . ')';

        $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
        //$inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id)));
        $inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);

        $this->loadModel('LookupLicenseApprovalStatus');
        $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

        $this->set(compact('org_id', 'orgName', 'option_values', 'recommendation_status_options', 'inspector_names'));
    }

    public function re_inspection($org_id = null, $option = null) {

        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
         if (empty($user_group_id) || !(in_array(1,$user_group_id) ||  in_array($inspector_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data['LicenseModuleSuspensionFieldInspectionDetail'];
            if (empty($org_id))
                $org_id = $posted_data['org_id'];
            if (!empty($option) && $option == 1)
                $posted_data['is_approved'] = 0;

            $this->LicenseModuleSuspensionFieldInspectionDetail->id = $posted_data['id'];
            $done = $this->LicenseModuleSuspensionFieldInspectionDetail->save($posted_data);

            if ($done && !empty($option) && $option == 1) {
                $approval_data = array(
                    'org_id' => $org_id,
                    'submission_date' => date('Y-m-d'),
                    'inspection_approval_id' => 1,
                    'inspection_comment' => "",
                    'inspector_user_id' => $this->Session->read('User.Id'),
                    'is_editor_user' => 1);

                $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');
                $this->LicenseModuleFieldInspectionApprovalDetail->create();
                $done = $this->LicenseModuleFieldInspectionApprovalDetail->save($approval_data);

                $this->redirect(array('action' => 'view'));
                return;
            }
        }

        $option_values = array('1' => 'Yes', '0' => 'No');

        $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
        //$inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id)));
        $inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);

        $this->loadModel('LookupLicenseApprovalStatus');
        $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

        $this->set(compact('org_id', 'option_values', 'recommendation_status_options', 'inspector_names'));

        $inspectionDetails = $this->LicenseModuleSuspensionFieldInspectionDetail->find('first', array('conditions' => array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $org_id, 'is_approved' => -1), 'order' => array('submission_date' => 'desc')));

        if (!$this->request->data) {
            $this->request->data = $inspectionDetails;
        }
    }

    public function inspection_approval($org_id = null) {
        
        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
         if (empty($user_group_id) || !(in_array(1,$user_group_id) ||  in_array($inspector_group_id,$user_group_id))) {
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

                $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');

                if ($option == 1) {

                    $posted_data['inspector_user_id'] = $this->Session->read('User.Id');
                    $posted_data['is_editor_user'] = 0;
                    $this->LicenseModuleFieldInspectionApprovalDetail->create();
                    $done = $this->LicenseModuleFieldInspectionApprovalDetail->save($posted_data);

                    if ($done) {
                        $approve_id = $done['LicenseModuleFieldInspectionApprovalDetail']['id'];

                        $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
                        $this->LicenseModuleSuspensionFieldInspectorDetail->recursive = 0;
                        $inspector_id_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => array('org_id' => $org_id, 'is_approved' => 1), 'group' => 'inspector_user_id'));
                        $approve_count = $this->LicenseModuleFieldInspectionApprovalDetail->find('count', array('fields' => 'inspector_user_id', 'conditions' => array('org_id' => $org_id, 'inspection_approval_id' => 1, 'inspector_user_id' => $inspector_id_list), 'group' => 'inspector_user_id'));

                        if ((!empty($approve_count) && $approve_count == count($inspector_id_list))) {

                            $this_state_ids = $this->Session->read('Current.StateIds');
                            if (!empty($this_state_ids)) {
                                $thisStateIds = split('_', $this_state_ids);
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

                            $this->LicenseModuleSuspensionFieldInspectionDetail->updateAll(array('is_approved' => 1), array('org_id' => $org_id, 'is_approved' => 0));

                            $current_year = $this->Session->read('Current.LicensingYear');
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id));

                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $thisStateIds[2],
                                'licensing_year' => $current_year,
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->save($org_state_history);
                        }
                    }
                } else if ($option == 2) {

                    $done = $this->LicenseModuleSuspensionFieldInspectionDetail->updateAll(array('is_approved' => -1), array('org_id' => $org_id, 'is_approved' => 0));

                    if ($done) {
                        $done = $this->LicenseModuleFieldInspectionApprovalDetail->deleteAll(array('org_id' => $org_id), false);

                        if ($done) {
                            $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
                            $inspector_email_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'AdminModuleUserProfile.email', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id'));

                            if (!empty($inspector_email_list)) {

                                try {
                                    $msg = $posted_data['inspection_comment'];

                                    /* $inspector_details = $this->LicenseModuleSuspensionFieldInspectorDetail->AdminModuleUserProfile->find('list', array('fields' => 'AdminModuleUserProfile.full_name_of_user', 'recursive' => 0, 'conditions' => array('AdminModuleUserProfile.user_id' => $this->Session->read('User.Id'))));
                                    if (!empty($inspector_details) && is_array($inspector_details)) {
                                        $keys = array_keys($inspector_details);
                                        if (!empty($keys[0]) && is_array($keys))
                                            $inspector_name = $inspector_details[$keys[0]];
                                    } */
									
									$inspector_name = $this->LicenseModuleSuspensionFieldInspectorDetail->AdminModuleUserProfile->field('AdminModuleUserProfile.full_name_of_user', array('AdminModuleUserProfile.user_id' => $this->Session->read('User.Id')));

                                    $orgDetails = $this->LicenseModuleSuspensionFieldInspectionDetail->BasicModuleBasicInformation->find('first', array('fields' => array('license_no', 'short_name_of_org', 'full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
                                    if (!empty($orgDetails)) {
                                        $org_info = $orgDetails['BasicModuleBasicInformation'];
                                        $org_name = $org_info['full_name_of_org'] . (!empty($org_info['short_name_of_org']) ? ' (' . $org_info['short_name_of_org'] . ')' : '');
                                        $org_license_no = $org_info['license_no'];
                                    }

                                    $email = new CakeEmail('gmail');
                                    $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->subject('License Field Inspection not Approve !');
                                    $email->to($inspector_email_list);
                                    $message_body = 'Dear User,'
                                            . "\r\n" . "\r\n"
                                            . "Inspector Name: $inspector_name"
                                            . " did not approve the Field Inspection of \"$org_name\" with License No.:$org_license_no."
                                            . " As a result the approval status of all members has been reset."
                                            . " Due to the \"$msg\" isue." . "\r\n \r\n"
                                            . "Please re-approve the Field Inspection."
                                            . "\r\n \r\n"
                                            . "Thanks" . "\r\n"
                                            . "Microcredit Regulatory Authority";

                                    //$email->send($message_body);
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

                $this->redirect(array('action' => 'view'));
                return;
            }
        }


        $option_values = array('1' => 'Yes', '0' => 'No');

        $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
        $inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);

        $this->loadModel('LookupLicenseApprovalStatus');
        $approval_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

        $this->set(compact('org_id', 'option_values', 'approval_status_options', 'recommendation_status_options', 'inspector_names'));

        $inspectionDetails = $this->LicenseModuleSuspensionFieldInspectionDetail->find('first', array('conditions' => array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $org_id, 'is_approved' => 0), 'order' => array('submission_date' => 'desc')));

        if (!$this->request->data) {
            $this->request->data = $inspectionDetails;
        }
    }

    public function inspection_details($org_id = null) {

        if (!empty($org_id)) {
            $option_values = array('1' => 'Yes', '0' => 'No');

            $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
            $inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id'));
            $inspector_names = implode('<br />', $inspector_name_list);

            $this->loadModel('LookupLicenseApprovalStatus');
            $recommendation_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.recommendation_status')));

            $this->set(compact('option_values', 'recommendation_status_options', 'inspector_names'));

            $post = $this->LicenseModuleSuspensionFieldInspectionDetail->find('first', array('conditions' => array('LicenseModuleSuspensionFieldInspectionDetail.org_id' => $org_id), 'order' => array('submission_date' => 'desc')));
            if (!$this->request->data) {
                $this->request->data = $post;
            }
        }
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    function inspector_select() {
        $this->layout = 'ajax';
        $org_id = $this->request->data['LicenseModuleSuspensionFieldInspectionDetail']['org_id'];
        $this->loadModel('LicenseModuleSuspensionFieldInspectorDetail');
        //$inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id)));
        $inspector_name_list = $this->LicenseModuleSuspensionFieldInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionFieldInspectorDetail.org_id' => $org_id, 'LicenseModuleSuspensionFieldInspectorDetail.is_approved' => 1), 'group' => 'LicenseModuleSuspensionFieldInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);
        $this->set(compact('inspector_names'));
    }

    public function inspection_approval_details($org_id = null) {
        $this->loadModel('LicenseModuleFieldInspectionApprovalDetail');
        $approvalDetails = $this->LicenseModuleFieldInspectionApprovalDetail->find('all');
        $approvalDetails = $this->LicenseModuleFieldInspectionApprovalDetail->find('all', array('conditions' => array('org_id' => $org_id), 'group' => 'inspector_user_id', 'order' => array('submission_date' => 'desc', 'LicenseModuleFieldInspectionApprovalDetail.id' => 'desc')));
        $this->set(compact('approvalDetails'));
    }
}

