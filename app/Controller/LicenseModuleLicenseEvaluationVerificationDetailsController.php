<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleLicenseEvaluationVerificationDetailsController extends AppController {

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

        $redirect_url = array('controller' => 'LicenseModuleInitialEvaluationVerificationDetails', 'action' => 'view', '?' => array('committee_group_id' => $committee_group_id, 'this_state_ids' => $this_state_ids));
        $this->Session->write('Current.RedirectUrl', $redirect_url);

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
            $condition = array_merge(array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id), $condition);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        $user_is_committee_member = (in_array($committee_group_id,$user_group_id));
        $this->set(compact('org_id', 'user_is_committee_member', 'opt_all'));
        
        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $condition2 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        $condition4 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[2]);

        if (!empty($user_is_committee_member)) {
            $user_id = $this->Session->read('User.Id');

            $this->loadModel('LicenseModuleLicenseEvaluationVerificationApprovalDetail');
            $approved_org_id_list = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->find('list', array('fields' => 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id', 'conditions' => array('committee_user_id' => $user_id, 'approval_status_id' => 1), 'group' => 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id'));

            if (!empty($approved_org_id_list)) {
                $condition3 = array_merge($condition2, array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $approved_org_id_list));
                $condition2 = array_merge($condition2, array('NOT' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $approved_org_id_list)));
            }
        }

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);

            if (!empty($condition3))
                $condition3 = array_merge($condition3, $condition);

            $condition4 = array_merge($condition4, $condition);
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

        $this->loadModel('LicenseModuleInitialAssessmentDetail');
        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $values_pending = $this->LicenseModuleInitialAssessmentDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('form_serial_no' => 'asc')));

        $all_fields_with_marks = array_merge($all_fields_with_marks, array('LicenseModuleLicenseEvaluationVerificationDetail.org_id', 'LookupLicenseApprovalStatus.verification_status', 'LicenseModuleLicenseEvaluationVerificationDetail.verification_date'));
        //$all_fields = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleLicenseEvaluationVerificationDetail.org_id', 'LookupLicenseApprovalStatus.verification_status', 'LicenseModuleLicenseEvaluationVerificationDetail.verification_date');
        $values_not_verified = $this->LicenseModuleLicenseEvaluationVerificationDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition2, 'group' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id'), 'order' => array('form_serial_no' => 'asc')));

        if (!empty($condition3))
            $values_not_verified_by_all = $this->LicenseModuleLicenseEvaluationVerificationDetail->find('all', array('fields' => $all_fields_with_marks, 'conditions' => $condition3, 'group' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id'), 'order' => array('form_serial_no' => 'asc')));
        else
            $values_not_verified_by_all = null;

        $this->paginate = array('fields' => $all_fields_with_marks, 'conditions' => $condition4, 'group' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id'), 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->LicenseModuleLicenseEvaluationVerificationDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_verified = $this->Paginator->paginate('LicenseModuleLicenseEvaluationVerificationDetail');

        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'total_marks', 'pass_min_marks', 'values_verified', 'values_not_verified', 'values_not_verified_by_all', 'values_pending'));
    }

    public function verification($org_id = null) {

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
                $posted_data = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetail'];
//                if (!empty($posted_data)) {
                if (!empty($posted_data) && !empty($posted_data['verification_status_id']) && $posted_data['verification_status_id'] == 1) {
                    if (empty($org_id))
                        $org_id = $posted_data['org_id'];

                    $posted_data['is_approved'] = 0;
                    $posted_data['verification_committee_user_id'] = $this->Session->read('User.Id');

                    $this->LicenseModuleLicenseEvaluationVerificationDetail->deleteAll(array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id), false);
                    $this->LicenseModuleLicenseEvaluationVerificationDetail->create();
                    $done = $this->LicenseModuleLicenseEvaluationVerificationDetail->save($posted_data);
                    if ($done) {
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

                        $approval_data = array(
                            'org_id' => $org_id,
                            'approval_status_id' => 1,
                            'approval_date' => date('Y-m-d'),
                            'approval_comment' => "",
                            'committee_user_id' => $this->Session->read('User.Id'));

                        $this->loadModel('LicenseModuleLicenseEvaluationVerificationApprovalDetail');
                        $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->updateAll(array('approval_status_id' => -1), array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id' => $org_id, 'approval_status_id' => 1));
                        $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->create();
                        $done = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->save($approval_data);
                        if ($done) {
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

                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view');

                            $this->redirect($redirect_url);
                            return;
                        } else {
                            if (!empty($done['LicenseModuleLicenseEvaluationVerificationApprovalDetail']['id']))
                                $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->delete($done['LicenseModuleLicenseEvaluationVerificationApprovalDetail']['id']);
                        }
                    }
                }
            } catch (Exception $ex) {
                //debug($ex);
            }
        }

        $orgDetail = $this->LicenseModuleLicenseEvaluationVerificationDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $verification_status_options = $this->LicenseModuleLicenseEvaluationVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('org_id', 'orgName', 'verification_status_options'));
    }

    public function re_verification($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleLicenseEvaluationVerificationDetail->find('first', array('conditions' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id)));

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
            $posted_data = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetail'];
            if (!empty($posted_data)) {
                $this->LicenseModuleLicenseEvaluationVerificationDetail->id = $approvalDetails['LicenseModuleLicenseEvaluationVerificationDetail']['id'];
                if ($this->LicenseModuleLicenseEvaluationVerificationDetail->save($posted_data)) {
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                    if (empty($redirect_url))
                        $redirect_url = array('action' => 'view');

                    $this->redirect($redirect_url);
                }
            }
            return;
        }

//        if (!$this->request->data) {
//            $this->request->data = $approvalDetails['LicenseModuleLicenseEvaluationVerificationDetail'];
//        }

        if (!empty($approvalDetails['BasicModuleBasicInformation'])) {
            $orgDetail = $approvalDetails['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $approvalDetails = $approvalDetails['LicenseModuleLicenseEvaluationVerificationDetail'];
        $verification_status_options = $this->LicenseModuleLicenseEvaluationVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('org_id', 'orgName', 'approvalDetails', 'verification_status_options'));
    }

    public function verification_approval($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

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

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data['LicenseModuleLicenseEvaluationVerificationApprovalDetail'];

            if (empty($org_id))
                $org_id = $posted_data['org_id'];

            $option = $posted_data['approval_status_id'];

            if (!empty($option)) {

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
                $user_id = $this->Session->read('User.Id');
                $posted_data['committee_user_id'] = $user_id;

                $this->loadModel('LicenseModuleLicenseEvaluationVerificationApprovalDetail');

                if ($option == 1) {

                    $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->create();
                    $done = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->save($posted_data);

                    if ($done) {
                        $approve_id = $done['LicenseModuleLicenseEvaluationVerificationApprovalDetail']['id'];

                        $committee_member_id_list = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUser.id', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id, 'AdminModuleUser.activation_status_id' => 1), 'recursive' => 0));
                        $approve_count = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->find('count', array('fields' => 'committee_user_id', 'conditions' => array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id' => $org_id, 'approval_status_id' => 1, 'committee_user_id' => $committee_member_id_list), 'group' => 'committee_user_id'));

                        if ((!empty($approve_count) && $approve_count == count($committee_member_id_list))) {
                            $done = $this->LicenseModuleLicenseEvaluationVerificationDetail->updateAll(array('is_approved' => 1), array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id));
                            if ($done) {
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

                                $redirect_url = $this->Session->read('Current.RedirectUrl');
                                if (empty($redirect_url))
                                    $redirect_url = array('action' => 'view');

                                $this->redirect($redirect_url);
                                return;
                            } else {
                                $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->delete($approve_id);
                            }
                        }
                    }
                } else if ($option == 2) {

                    $done = $this->LicenseModuleLicenseEvaluationVerificationDetail->deleteAll(array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id), false);
                    if ($done) {
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id));

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

                        $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->updateAll(array('approval_status_id' => -1), array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id' => $org_id, 'approval_status_id' => 1));
                        $done = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->save($posted_data);
                        if ($done) {
                            $committee_member_email_list = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUserProfile.email', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id), 'recursive' => 0));
                            if (!empty($committee_member_email_list)) {
                                try {
                                    $msg = $posted_data['approval_comment'];

                                    $committee_member_name = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->AdminModuleUserProfile->field('AdminModuleUserProfile.full_name_of_user', array('AdminModuleUserProfile.user_id' => $user_id));

                                    $orgDetails = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('form_serial_no', 'short_name_of_org', 'full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
                                    if (!empty($orgDetails)) {
                                        $org_info = $orgDetails['BasicModuleBasicInformation'];
                                        $org_name = $org_info['full_name_of_org'] . (!empty($org_info['short_name_of_org']) ? ' (' . $org_info['short_name_of_org'] . ')' : '');
                                        $org_form_serial_no = $org_info['form_serial_no'];
                                    }

                                    $message_body = 'Dear User,'
                                            . "\r\n" . "\r\n"
                                            . "License Evaluation Committee Member Name: $committee_member_name"
                                            . " did not approve the License Evaluation of \"$org_name\" with Form No.:$org_form_serial_no."
                                            . " As a result the approval status of all members has been reset."
                                            . " Due to the \"$msg\" isue." . "\r\n \r\n"
                                            . "Please re-approve the License Evaluation."
                                            . "\r\n \r\n"
                                            . "Thanks" . "\r\n"
                                            . "Microcredit Regulatory Authority";

                                    $mail_details = array();
                                    $mail_details['org_id'] = $org_id;
                                    $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
                                    $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
                                    $mail_details['mail_to'] = implode(';', $committee_member_email_list);
                                    $mail_details['mail_cc'] = '';
                                    $mail_details['mail_subject'] = 'License Evaluation not Approve !';
                                    $mail_details['mail_message'] = $message_body;
                                    $mail_details['mail_is_sent'] = 0;
									$mail_details['mail_creation_date'] = date('Y-m-d');
									$mail_details['mail_creator'] = $this->Session->read('User.Id');

                                    $this->loadModel('AdminModuleMessageSendingDetail');
                                    $this->AdminModuleMessageSendingDetail->create();
                                    $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

                                    if ($done && !empty($done['AdminModuleMessageSendingDetail']['id']))
                                        $this->redirect(array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $done['AdminModuleMessageSendingDetail']['id']));
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
            }
        }

        $this->loadModel('LicenseModuleLicenseEvaluationVerificationApprovalDetail');
        $orgDetail = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $approval_status_options = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
    }

    public function verification_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LookupLicenseApprovalStatus.verification_status', 'LicenseModuleLicenseEvaluationVerificationDetail.verification_date', 'LicenseModuleLicenseEvaluationVerificationDetail.verification_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationDetails = $this->LicenseModuleLicenseEvaluationVerificationDetail->find('first', array('fields' => $fields, 'conditions' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id), 'recursive' => 0, 'group' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id'), 'order' => array('LicenseModuleLicenseEvaluationVerificationDetail.verification_date' => 'desc')));

        $this->loadModel('LicenseModuleLicenseEvaluationVerificationApprovalDetail');
        $fields = array('LookupLicenseApprovalStatus.approval_status', 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_date', 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationApprovalDetails = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->find('all', array('fields' => $fields, 'conditions' => array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id' => $org_id), 'recursive' => 0, 'order' => array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_date' => 'desc', 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.id' => 'desc')));

        $this->set(compact('vrificationDetails', 'vrificationApprovalDetails'));
    }

    public function verification_approval_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('LookupLicenseApprovalStatus.approval_status', 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_date', 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationApprovalDetails = $this->LicenseModuleLicenseEvaluationVerificationApprovalDetail->find('all', array('fields' => $fields, 'conditions' => array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.org_id' => $org_id), 'recursive' => 0, 'order' => array('LicenseModuleLicenseEvaluationVerificationApprovalDetail.approval_date' => 'desc', 'LicenseModuleLicenseEvaluationVerificationApprovalDetail.id' => 'desc')));

        $this->set(compact('vrificationApprovalDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    ///////////////*ok*////////////////

    public function verification_all() {

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
                $posted_data = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetailAll'];
                if (!empty($posted_data)) {
                    $this->LicenseModuleLicenseEvaluationVerificationDetail->create();
                    if ($this->LicenseModuleLicenseEvaluationVerificationDetail->saveAll($posted_data)) {

                        $all_org_state_history = array();
                        $this->loadModel('BasicModuleBasicInformation');

                        foreach ($posted_data as $new_data) {
                            $org_id = $new_data['org_id'];
                            if (!empty($org_id)) {

                                //$condition = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                                // $org_state_history = array(
                                // 'org_id' => $org_id,
                                // 'state_id' => $thisStateIds[0],
                                // 'licensing_year' => $current_year,
                                // 'date_of_state_update' => date('Y-m-d'),
                                // 'user_name' => $this->Session->read('User.Name'));

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
                            $this->LicenseModuleStateHistory->create(); //set($all_org_state_history);
                            $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        }

                        $redirect_url = $this->Session->read('Current.RedirectUrl');
                        if (empty($redirect_url))
                            $redirect_url = array('action' => 'view');

                        $this->redirect($redirect_url);
                        //$this->redirect(array('action' => 'view', '?' => array('this_state_id' => $thisStateIds[1])));
                        return;
                    }
                }
            } catch (Exception $ex) {
                $this->redirect(array('action' => 'verification_all'));
            }
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);
        $this->loadModel('LicenseModuleInitialAssessmentDetail');
        $parameterOptionList = $this->LicenseModuleInitialAssessmentDetail->LookupLicenseInitialAssessmentParameter->find('all', array('conditions' => array('LookupLicenseInitialAssessmentParameter.is_published' => '1', 'LookupLicenseInitialAssessmentParameter.declaration_year' => $current_year)));
        $parameterOptionMaxList = Hash::extract($parameterOptionList, '{n}.LookupLicenseInitialAssessmentParameter.max_marks');
        $total_marks = array_sum($parameterOptionMaxList);

        $this->loadModel('LookupLicenseInitialAssessmentPassMark');
        $evaluation_marks_details = $this->LookupLicenseInitialAssessmentPassMark->find('list', array('fields' => array('initial_evaluation_pass_mark_type_id', 'min_marks')));

        $pass_min_marks = $total_marks * 0.60;
        if (!empty($evaluation_marks_details) && count($evaluation_marks_details) >= 2) {
            $pass_min_marks = ($evaluation_marks_details[1] * $total_marks) / 100;
        }

        $all_fields_with_marks = array('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $this->paginate = array('fields' => $all_fields_with_marks, 'conditions' => $condition1, 'group' => 'BasicModuleBasicInformation.id', 'order' => array('LicenseModuleInitialAssessmentMark.total_assessment_marks' => 'desc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('LicenseModuleInitialAssessmentDetail');
        $verification_status_options = $this->LicenseModuleLicenseEvaluationVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));

        $this->set(compact('orgDetails', 'verification_status_options', 'total_marks', 'pass_min_marks'));
    }

    public function verification_edit_all() {

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
                $posted_data = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetailAll'];
//                foreach ($posted_data as $nData) {
//                    //$posted_data = $this->request->data['LicenseModuleLicenseEvaluationVerificationDetail'];
//                    if (!empty($nData)) {
//                        $id = $nData['id'];
//                        if (!empty($id)) {
//                            $this->LicenseModuleLicenseEvaluationVerificationDetail->id = $id;
//                            $nData = Hash::remove($nData, 'id');
//                            $this->LicenseModuleLicenseEvaluationVerificationDetail->save($nData);
//                        }
//                    }
//                }

                if (!empty($posted_data)) {
                    $this->LicenseModuleLicenseEvaluationVerificationDetail->set($posted_data);
                    if ($this->LicenseModuleLicenseEvaluationVerificationDetail->saveAll($posted_data)) {
                        $redirect_url = $this->Session->read('Current.RedirectUrl');
                        if (empty($redirect_url))
                            $redirect_url = array('action' => 'view');

                        $this->redirect($redirect_url);
                    }
                }
            } catch (Exception $ex) {
                
            }
        }

        $condition1 = array('licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.form_serial_no');

        $this->paginate = array('fields' => $all_fields, 'conditions' => $condition1, 'limit' => 10, 'order' => array('form_serial_no' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $orgDetails = $this->Paginator->paginate('BasicModuleBasicInformation');

        $verification_status_options = $this->LicenseModuleLicenseEvaluationVerificationDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
//        
//        $this->paginate = array(
//            'limit' => 10,
//            'order' => array('form_serial_no' => 'asc'));
//        
//        $this->Paginator->settings = $this->paginate;
//        $orgDetails = $this->Paginator->paginate('LicenseModuleLicenseEvaluationVerificationDetail');
        $this->set(compact('orgDetails', 'verification_status_options'));
    }

    /*
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

      $licVerificationDetails = $this->LicenseModuleLicenseEvaluationVerificationDetail->find('first', array('conditions' => array('LicenseModuleLicenseEvaluationVerificationDetail.org_id' => $org_id)));
      if (!$licVerificationDetails) {
      throw new NotFoundException('Invalid Information !');
      }
      $this->set(compact('licVerificationDetails'));
      }
     */
}
