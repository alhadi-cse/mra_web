<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleLicensePermissionDetailsController extends AppController {

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

        $opt_all = false;
        if (in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleLicensePermissionDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleLicensePermissionDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleLicensePermissionDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $this->set(compact('org_id', 'user_group_id', 'this_state_ids', 'opt_all', 'total_marks', 'pass_min_marks'));

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1);
        //$condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        $condition3 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]);

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            //$condition2 = array_merge($condition2, $condition);
            $condition3 = array_merge($condition3, $condition);
        }

        $this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
        $this->paginate = array('conditions' => $condition1, 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_selected = $this->Paginator->paginate('LicenseModuleLicenseEvaluationAdminApprovalDetail');

        //$values_notification_sent = $this->LicenseModuleLicensePermissionDetail->find('all', array('conditions' => $condition2, 'group' => array('LicenseModuleLicensePermissionDetail.org_id')));//, 'order' => array('LicenseModuleLicensePermissionDetail.id' => 'desc')));    //, 'order' => array('LicenseModuleLicensePermissionDetail.id' => 'desc')
        $values_licensed = $this->LicenseModuleLicensePermissionDetail->find('all', array('conditions' => $condition3, 'group' => array('LicenseModuleLicensePermissionDetail.org_id')));

        $this->set(compact('values_selected', 'values_notification_sent', 'values_licensed'));
    }

    public function license_issue($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($this_state_ids))
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
            return false;
        }

        $orgDetail = $this->LicenseModuleLicensePermissionDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $this->set(compact('org_id', 'orgName'));

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleLicensePermissionDetail'];
                if (!empty($newData) && !empty($newData['license_no'])) {
                    $this->loadModel('AdminModuleUserProfile');
                    $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

                    if (!empty($email_list) && !empty($email_list[$org_id])) {
                        $to_mail = $email_list[$org_id];

                        if (empty($to_mail)) {
                            $msg = array(
                                'type' => 'error',
                                'title' => 'Error... ... !',
                                'msg' => 'Invalid/Empty E-mail Information !'
                            );
                            $this->set(compact('msg'));
                            return false;
                        }

                        $license_no = $newData['license_no'];
                        $nextStateId = $thisStateIds[2];
                        
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->recursive = 0;
                        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $nextStateId, 'BasicModuleBasicInformation.license_no' => "'" . $license_no . "'", 'BasicModuleBasicInformation.license_issue_date' => "'" . date('Y-m-d') . "'"), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));

                        if ($done) {
                            $this->loadModel('AdminModuleUserGroupDistribution');
                            $user_details = $this->AdminModuleUserGroupDistribution->find('first',array('conditions'=>array('BasicModuleBasicInformation.id'=>$org_id),'recursive'=>0));
                            $user_id = $user_details['AdminModuleUser']['id'];
                            $this->AdminModuleUserGroupDistribution->updateAll(array('AdminModuleUserGroupDistribution.user_group_id'=>2), array('AdminModuleUserGroupDistribution.user_id' => $user_id));

                            $email = new CakeEmail('gmail');
                            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Primary Evaluation Successfully completed');

                            $this->BasicModuleBasicInformation->recursive = 0;
                            $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org'));

                            $frmSerialNo = $orgDetails['BasicModuleBasicInformation']['form_serial_no'];
                            $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                            $mfiName = ((!empty($mfiName) && !empty($mfiFullName)) ? "$mfiFullName ($mfiName)" : "$mfiFullName$mfiName");

                            $msg_terms = $newData['msg_terms_conditions'];
                            $message_body = "Dear Applicant, " . "\r\n \r\n"
                                    . "Your Organization Name \"$mfiName\"\r\n and \r\nForm Serial No.: $frmSerialNo "
                                    . "has been successfully completed the evaluation of license process and got a MRA License."
                                    . "\r\n"
                                    . (!empty($license_no) ? "License No.: $license_no" : "")
                                    . "\r\n \r\n"
                                    . (!empty($msg_terms) ? "Terms and Conditions are: $msg_terms" : "")
                                    . "\r\n"
                                    . "\r\n  \r\n"
                                    . "Thanks \r\n"
                                    . "Microcredit Regulatory Authority (MRA)";

                            if ($email->send($message_body)) {

                                $this->LicenseModuleLicensePermissionDetail->deleteAll(array('LicenseModuleLicensePermissionDetail.org_id' => $org_id), false);

                                $this->LicenseModuleLicensePermissionDetail->create();
                                $this->LicenseModuleLicensePermissionDetail->save(array('org_id' => $org_id, 'notification_sent_date' => date('Y-m-d'), 'comment' => $this->Session->read('User.Name')));

                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $nextStateId,
                                    'licensing_year' => $current_year,
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                                    'user_name' => $this->Session->read('User.Name'));

                                $this->loadModel('LicenseModuleStateHistory');
                                $this->LicenseModuleStateHistory->create();
                                $this->LicenseModuleStateHistory->save($org_state_history);

                                $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
                                return true;
                            } else {
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'BasicModuleBasicInformation.license_no' => '', 'BasicModuleBasicInformation.license_issue_date' => ''), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));
                                $msg = array(
                                    'type' => 'error',
                                    'title' => 'Error... ... !',
                                    'msg' => 'Message Sending Failed !'
                                );
                                $this->set(compact('msg'));
                                return false;
                            }
                        } else {
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'Unable to update Basic Information !'
                            );
                            $this->set(compact('msg'));
                            return;
                        }
                    } else {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => 'Invalid/Empty User Information !'
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                }
            } catch (Exception $ex) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => $ex
                );
                $this->set(compact('msg'));
                return;
            }
        }
    }

    public function accept($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($this_state_ids))
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
            return false;
        }

        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));

        if ($done) {

            //$this->LicenseModuleLicensePermissionDetail->recursive = -1;
            $this->LicenseModuleLicensePermissionDetail->unbindModel(array('belongsTo' => array('BasicModuleBasicInformation', 'LicenseModuleInitialAssessmentMark')));
            $this->LicenseModuleLicensePermissionDetail->updateAll(array('condition_accept_date' => "'" . date('Y-m-d') . "'"), array('org_id' => $org_id));

            $org_state_history = array(
                'org_id' => $org_id,
                'state_id' => $thisStateIds[2],
                'licensing_year' => $current_year,
                'date_of_state_update' => date('Y-m-d'),
                'date_of_starting' => date('Y-m-d'),
                //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                'user_name' => $this->Session->read('User.Name'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->create();
            $this->LicenseModuleStateHistory->save($org_state_history);

            $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
            return true;
        }
    }

    public function permit($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($this_state_ids))
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
            return false;
        }


        $this->loadModel('AdminModuleUserProfile');
        $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

        if (!empty($email_list)) {
            $to_mail = $email_list[$org_id];

            if (empty($to_mail)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Invalid/Empty E-mail Information !'
                );
                $this->set(compact('msg'));
                return false;
            }

            $this->loadModel('BasicModuleBasicInformation');
            $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[3]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]));

            if ($done) {
                $email = new CakeEmail('gmail');
                $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Primary Evaluation Successfully completed');

                $this->BasicModuleBasicInformation->recursive = 0;
                $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org'));

                $frmSerialNo = $orgDetails['BasicModuleBasicInformation']['form_serial_no'];
                $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                $mfiName = $mfiName . ((!empty($mfiName) && !empty($mfiFullName)) ? ": " : "") . $mfiFullName;

                //$license_no = 'MRA-452/24-2015';
                $message_body = "Dear Applicant, " . "\r\n \r\n"
                        . "Your Organization $mfiName and Form Serial No.: $frmSerialNo " 
                        . "has been successfully completed the \"Temporary License Permission\" process. "
//                                    . "\r\n"
//                                    . "Your Organization has been "
                        . "So, your organization are now permited for \"Temporary License Permission\" "
                        . "\r\n"
                        //. "msg_contains"
                        . "\r\n"
                        . "N.B.: Please pay the anual license fee within 3 months(if not pay)."
                        . "\r\n  \r\n"
                        . "Thanks \r\n"
                        . "Microcredit Regulatory Authority (MRA)";

                if ($email->send($message_body)) {

                    $this->LicenseModuleLicensePermissionDetail->unbindModel(array('belongsTo' => array('BasicModuleBasicInformation', 'LicenseModuleInitialAssessmentMark')));
                    $this->LicenseModuleLicensePermissionDetail->updateAll(array('permission_issue_date' => "'" . date('Y-m-d') . "'"), array('org_id' => $org_id));

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $thisStateIds[3],
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);

                    $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
                    return true;
                } else {
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Message Sending Failed !'
                    );
                    $this->set(compact('msg'));
                    return false;
                }
            } else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Unable to update Basic Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid/Empty User Information !'
            );
            $this->set(compact('msg'));
            return;
        }
    }

    public function permit_all($this_state_ids = null) {

        if (empty($this_state_ids))
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
            return false;
        }

        if (!empty($org_id)) {
            $condition = array_merge(array('LicenseModuleLicensePermissionDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleLicensePermissionDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleLicensePermissionDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleLicensePermissionDetail'];

                if (!empty($newData)) {
                    $org_ids = Hash::extract($newData, '{n}[is_permit > 0].org_id');
                    if (!empty($org_ids)) {
                        $this->loadModel('AdminModuleUserProfile');
                        $this->loadModel('BasicModuleBasicInformation');
                        foreach ($org_ids as $org_id) {

                            $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
                            if (!empty($email_list) || !empty($email_list[$org_id])) {
                                $to_mail = $email_list[$org_id];

                                $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[3]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]));

                                if ($done) {
                                    $email = new CakeEmail('gmail');
                                    $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Primary Evaluation Successfully completed');

                                    $this->BasicModuleBasicInformation->recursive = 0;
                                    $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org'));

                                    $frmSerialNo = $orgDetails['BasicModuleBasicInformation']['form_serial_no'];
                                    $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                                    $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                                    $mfiName = $mfiName . ((!empty($mfiName) && !empty($mfiFullName)) ? ": " : "") . $mfiFullName;

                                    $message_body = "Dear Applicant, " . "\r\n \r\n"
                                            . "Your Organization $mfiName and Form Serial No.: $frmSerialNo " 
                                            . "has been successfully completed the \"Temporary License Permission\" process. "
                                            //                                    . "\r\n"
                                            //                                    . "Your Organization has been "
                                            . "So, your organization are now permited for \"Temporary License Permission\" "
                                            . "\r\n"
                                            //. "msg_contains"
                                            . "\r\n"
                                            . "N.B.: Please pay the anual license fee within 3 months(if not pay)."
                                            . "\r\n  \r\n"
                                            . "Thanks \r\n"
                                            . "Microcredit Regulatory Authority (MRA)";

                                    if ($email->send($message_body)) {

                                        $this->LicenseModuleLicensePermissionDetail->unbindModel(array('belongsTo' => array('BasicModuleBasicInformation', 'LicenseModuleInitialAssessmentMark')));
                                        $this->LicenseModuleLicensePermissionDetail->updateAll(array('permission_issue_date' => "'" . date('Y-m-d') . "'"), array('org_id' => $org_id));

                                        $org_state_history = array(
                                            'org_id' => $org_id,
                                            'state_id' => $thisStateIds[3],
                                            'licensing_year' => $current_year,
                                            'date_of_state_update' => date('Y-m-d'),
                                            'date_of_starting' => date('Y-m-d'),
                                            'user_name' => $this->Session->read('User.Name'));

                                        $this->loadModel('LicenseModuleStateHistory');
                                        $this->LicenseModuleStateHistory->create();
                                        $this->LicenseModuleStateHistory->save($org_state_history);
                                    } else {
                                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));
                                    }
                                }
                            }
                        }
                        $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
                    }
                }
            } catch (Exception $ex) {
                
            }
        }


        $this->loadModel('LookupLicenseInitialAssessmentParameter');
        $parameterOptionMaxMarksList = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.max_marks'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year))); //find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.declaration_year, SUM(LookupLicenseInitialAssessmentParameter.max_marks)'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1'), 'group'=>'LookupLicenseInitialAssessmentParameter.declaration_year'));
        $total_marks = array_sum($parameterOptionMaxMarksList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        if (!empty($condition)) {
            $condition = array_merge($condition, array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]));
        } else {
            $condition = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]);
        }

        $this->loadModel('LicenseModuleLicensePermissionDetail');
        $this->paginate = array('conditions' => $condition, 'group' => array('LicenseModuleLicensePermissionDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleLicensePermissionDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('LicenseModuleLicensePermissionDetail');

        $this->set(compact('org_id', 'user_group_id', 'this_state_ids', 'opt_all', 'total_marks', 'pass_min_marks', 'orgDetails'));

        return;
    }

    public function request_for_license($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($this_state_ids))
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
            return false;
        }

        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[4]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[3]));

        if ($done) {

            //$this->LicenseModuleLicensePermissionDetail->recursive = -1;
//            $this->LicenseModuleLicensePermissionDetail->unbindModel(array('belongsTo' => array('BasicModuleBasicInformation', 'LicenseModuleInitialAssessmentMark')));
//            $this->LicenseModuleLicensePermissionDetail->updateAll(array('condition_accept_date' => "'" . date('Y-m-d') . "'"), array('org_id' => $org_id));

            $org_state_history = array(
                'org_id' => $org_id,
                'state_id' => $thisStateIds[4],
                'licensing_year' => $current_year,
                'date_of_state_update' => date('Y-m-d'),
                'date_of_starting' => date('Y-m-d'),
                //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                'user_name' => $this->Session->read('User.Name'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->create();
            $this->LicenseModuleStateHistory->save($org_state_history);

            $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
            return true;
        }
    }

    public function approve($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

        if (empty($this_state_ids))
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
            return false;
        }
                
        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[3]), array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]));

        if ($done) {          
            
            $this->LicenseModuleLicensePermissionDetail->unbindModel(array('belongsTo' => array('BasicModuleBasicInformation', 'LicenseModuleInitialAssessmentMark')));
            $this->LicenseModuleLicensePermissionDetail->updateAll(array('permission_issue_date' => "'" . date('Y-m-d') . "'"), array('org_id' => $org_id));

            $org_state_history = array(
                'org_id' => $org_id,
                'state_id' => $thisStateIds[3],
                'licensing_year' => $current_year,
                'date_of_state_update' => date('Y-m-d'),
                'date_of_starting' => date('Y-m-d'),
                //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                'user_name' => $this->Session->read('User.Name'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->create();
            $this->LicenseModuleStateHistory->save($org_state_history);

            $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
            return true;
        }
    }

    public function done($org_id = null, $this_state_ids = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return false;
        }

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
            return false;
        }

        $this->loadModel('AdminModuleUserProfile');
        $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

        if (!empty($email_list)) {
            $to_mail = $email_list[$org_id];

            if (empty($to_mail)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Invalid/Empty E-mail Information !'
                );
                $this->set(compact('msg'));
                return false;
            }

            $this->loadModel('BasicModuleBasicInformation');
            $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $nextState), array('BasicModuleBasicInformation.id' => $org_id));

            if ($done) {
                $email = new CakeEmail('gmail');
                $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Application Successfully Completed.');

                $this->BasicModuleBasicInformation->recursive = 0;
                $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org'));
                $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                $mfiName = $mfiName . ((!empty($mfiName) && !empty($mfiFullName)) ? ": " : "") . $mfiFullName;

                $license_no = 'MRA-452/24-2015';

                $message_body = "Dear Applicant, " . "\r\n \r\n"
                        . "Your Organization $mfiName" 
                        . "has been successfully completed the license process"
                        . "\r\n"
                        . "Your Organization License No.: $license_no"
                        . "\r\n  \r\n"
                        . "Thanks \r\n"
                        . "Microcredit Regulatory Authority (MRA)";


                if ($email->send($message_body)) {
                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $nextState,
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);

                    $this->redirect(array('action' => 'view', '?' => array($this_state_ids)));
                    return true;
                } else {
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id));
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Message Sending Failed !'
                    );
                    $this->set(compact('msg'));
                    return false;
                }
            }
        }
    }

}
