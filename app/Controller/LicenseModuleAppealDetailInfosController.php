<?php

App::uses('AppController', 'Controller');

class LicenseModuleAppealDetailInfosController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($previous_state_id = null, $next_state_id = null, $initial_rejection_state_id = null, $final_rejection_state_id = null) {

        if (empty($initial_rejection_state_id))
            $initial_rejection_state_id = $this->request->query('initial_rejection_state_id');

        if (empty($initial_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($final_rejection_state_id))
            $final_rejection_state_id = $this->request->query('final_rejection_state_id');

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($previous_state_id))
            $previous_state_id = $this->request->query('previous_state_id');

        $user_group_id = $this->Session->read('User.GroupIds');
        $isAdmin = (!empty($user_group_id) && in_array(1,$user_group_id));
        if (!$isAdmin) {
            $org_id = $this->Session->read('Org.Id');

            if (empty($org_id)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid organization information !'
                );
                $isAdmin = false;
                $this->set(compact('isAdmin', 'msg'));
                return;
            }
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

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleStateHistory.date_of_deadline');

        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline >=' => date('Y-m-d'));
        else
            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleStateHistory');
        $values_waiting_for_appealed = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $conditions, 'group' => array('LicenseModuleStateHistory.org_id')));

        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));
        else
            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));

        $values_appeal_deadline_over = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $conditions, 'group' => array('LicenseModuleStateHistory.org_id')));


        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $previous_state_id);
        else
            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $previous_state_id);

        $values_waiting_for_appealed_review = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $conditions, 'group' => array('LicenseModuleStateHistory.org_id')));

        $this->set(compact('isAdmin', 'values_waiting_for_appealed', 'values_appeal_deadline_over', 'values_waiting_for_appealed_review'));
    }

    public function appeal($org_id = null, $initial_rejection_state_id = null, $appeal_state_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        if (empty($appeal_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('BasicModuleBasicInformation');
        if ($this->request->is('post') && !empty($this->request->data['LicenseModuleApplicationAppeal'])) {

            $appeal_data_to_save = array(
                'org_id' => $org_id,
                'appeal_date' => date('Y-m-d'),
                'application_of_appeal' => $this->request->data['LicenseModuleApplicationAppeal']['application'],
                'approval_status_id' => 0  //Not Approved
            );

            $this->LicenseModuleAppealDetailInfo->create();
            $done = $this->LicenseModuleAppealDetailInfo->save($appeal_data_to_save);
            if ($done) {
                try {
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $appeal_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                    $current_year = $this->Session->read('Current.LicensingYear');
                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $appeal_state_id,
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);

                    $this->redirect(array('action' => 'view', 22, 23, 21, 50));
                    return;
                } catch (Exception $ex) {
                    
                }
            }
        }

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $condition = array('BasicModuleBasicInformation.id' => $org_id);
        $this->BasicModuleBasicInformation->recursive = 0;
        $orgDetail = $this->BasicModuleBasicInformation->find('first', array('fields' => $all_fields, 'conditions' => $condition));

        //$licensing_state_id = $initial_rejection_state_id;
        $rejection_type_id = 1;
        //$rejection_category_id = 1;

        $fields = array('LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_date', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category', 'LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_reason');
        $conditions = array('LicenseModuleRejectSuspendCancelHistory.org_id' => $org_id,
            'LicenseModuleRejectSuspendCancelHistory.licensing_state_id' => $initial_rejection_state_id,
            'LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_history_type_id' => $rejection_type_id);

        $this->loadModel('LicenseModuleRejectSuspendCancelHistory');
        $rejection_histories = $this->LicenseModuleRejectSuspendCancelHistory->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => array('LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_date' => 'desc')));

        $this->set(compact('org_id', 'orgDetail', 'rejection_histories'));
    }

    public function appeal_review($org_id = null, $initial_rejection_state_id = null, $appeal_review_state_id = null, $appeal_previous_state_id = null, $final_rejection_state_id = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        $isAdmin = (!empty($user_group_id) && in_array(1,$user_group_id));
        if (!$isAdmin) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid uset to access this form !'
            );
            $this->set(compact('msg', 'isAdmin'));
            return;
        }
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $isAdmin = false;
            $this->set(compact('msg', 'isAdmin'));
            return;
        }

        if (empty($appeal_review_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $isAdmin = false;
            $this->set(compact('msg', 'isAdmin'));
            return;
        }
        if (empty($appeal_previous_state_id))
            $appeal_previous_state_id = 5;

        //$approvalStatusId = 0;
        $rejection_type_id = 1;
        $rejection_category_id = 4;

        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
        $fields = array('id', 'reject_suspend_cancel_reason');
        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
        $rejection_options = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $this->loadModel('BasicModuleBasicInformation');
        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationAppealReview'];

                if (!empty($newData)) {

                    if (!empty($newData['approval_status_id']))
                        $approvalStatusId = $newData['approval_status_id'];
                    else
                        $approvalStatusId = 0;

                    $next_state_id = null;
                    if ($approvalStatusId == 1 && !empty($newData['resume_state_id'])) {
                        $next_state_id = $newData['resume_state_id'];
                    } else if ($approvalStatusId == 0 && !empty($newData['rejection_option_id'])) {
                        $rejection_option_id = $newData['rejection_option_id'];
                        if (!empty($rejection_option_id) && !empty($rejection_options[$rejection_option_id]))
                            $reason = $rejection_options[$rejection_option_id];
                        else
                            $reason = 'undefined reason';

                        $done = $this->final_reject_and_send_notification($org_id, $final_rejection_state_id, $reason);
                        if ($done) {
                            if (!empty($newData['comment']))
                                $comment = $newData['comment'];
                            else
                                $comment = 'Final rejection in licensing process';

                            $next_state_id = $final_rejection_state_id;

                            $org_rejection_history = array(
                                'org_id' => $org_id,
                                'licensing_state_id' => $initial_rejection_state_id,
                                'reject_suspend_cancel_history_type_id' => $rejection_type_id,
                                'reject_suspend_cancel_category_id' => $rejection_category_id,
                                'reject_suspend_cancel_reason_id' => $rejection_option_id,
                                'reject_suspend_cancel_date' => date('Y-m-d'),
                                'comment' => $comment);

                            $this->loadModel('LicenseModuleRejectSuspendCancelHistory');
                            $this->LicenseModuleRejectSuspendCancelHistory->create();
                            $this->LicenseModuleRejectSuspendCancelHistory->save($org_rejection_history);
                        }
                    }

                    if (!empty($next_state_id)) {
                        //`approval_status_id``approval_date``comment``approved_by`
                        $appeal_data_to_update = array(
                            'approval_status_id' => $approvalStatusId,
                            'approval_date' => date('Y-m-d'),
                            'comment' => $newData['comment'],
                            'approved_by' => $this->Session->read('User.Name')
                        );

                        $this->LicenseModuleAppealDetailInfo->id = $newData['id'];
                        $done = $this->LicenseModuleAppealDetailInfo->save($appeal_data_to_update);
                        if ($done) {
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                            $current_year = $this->Session->read('Current.LicensingYear');
                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $next_state_id,
                                'licensing_year' => $current_year,
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('LicenseModuleStateHistory');
                            $this->LicenseModuleStateHistory->create();
                            $this->LicenseModuleStateHistory->save($org_state_history);

                            $this->redirect(array('action' => 'view', 22, 23, 21, 50));
                            return;
                        } else {
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'Appeal information update failed !'
                            );
                            $this->set(compact('msg'));
                        }
                    } else if ($approvalStatusId == 1 && empty($newData['resume_state_id'])) {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => 'Application Resume State not selected !'
                        );
                        $this->set(compact('msg'));
                    } else if ($approvalStatusId == 0 && empty($newData['rejection_option_id'])) {
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => 'Final Rejection Reason not selected !'
                        );
                        $this->set(compact('msg'));
                    }
                } else {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'Appeal Review Status not selected !'
                    );
                    $this->set(compact('msg'));
                }
            } catch (Exception $ex) {
                
            }
        }

        $this->LicenseModuleAppealDetailInfo->recursive = 0;
        $orgDetail = $this->LicenseModuleAppealDetailInfo->find('first', array('conditions' => array('LicenseModuleAppealDetailInfo.org_id' => $org_id), 'order' => array('LicenseModuleAppealDetailInfo.appeal_date' => 'desc')));

        $fields = array('LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_date', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category', 'LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_reason');
        $conditions = array('LicenseModuleRejectSuspendCancelHistory.org_id' => $org_id,
            'LicenseModuleRejectSuspendCancelHistory.licensing_state_id' => $initial_rejection_state_id,
            'LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_history_type_id' => $rejection_type_id);

        $this->loadModel('LicenseModuleRejectSuspendCancelHistory');
        $rejection_histories = $this->LicenseModuleRejectSuspendCancelHistory->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => array('LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_date' => 'desc')));

        $approval_status_options = array(1 => 'Accepted', 0 => 'Not Accepted');

        $this->loadModel('LicenseModuleStateName');
        $fields = array('id', 'state_title');
        $conditions = array('id <' => $appeal_previous_state_id);
        $state_list = $this->LicenseModuleStateName->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $this->set(compact('isAdmin', 'org_id', 'orgDetail', 'rejection_histories', 'approval_status_options', 'rejection_options', 'state_list'));
    }

    public function final_reject_and_send_notification($org_id = null, $final_rejection_state_id = null, $reason = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $isAdmin = false;
            $this->set(compact('msg', 'isAdmin'));
            return false;
        }

        if (empty($final_rejection_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State information !'
            );
            $isAdmin = false;
            $this->set(compact('msg', 'isAdmin'));
            return false;
        }


        $this->loadModel('BasicModuleBasicInformation');
        $done = $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $final_rejection_state_id), array('BasicModuleBasicInformation.id' => $org_id));
        if ($done) {

            try {
                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $final_rejection_state_id,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);
            } catch (Exception $ex) {
                
            }


            $this->loadModel('AdminModuleUserProfile');
            $email_id = $this->AdminModuleUserProfile->field('AdminModuleUserProfile.email', array('AdminModuleUserProfile.org_id' => $org_id));
            //$email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

            if (empty($reason))
                $reason = 'undefined Reason';

            $message_body = 'Dear Applicant, '
                    . "\r\n" . "\r\n"
                    . 'Your license application has been Rejected. Due to "'
                    . $reason . '".' . "\r\n" . "\r\n"
                    . 'Thanks' . "\r\n"
                    . 'Microcredit Regulatory Authority (MRA)';

            $mail_details = array();
            $mail_details['org_id'] = $org_id;
            $mail_details['mail_from_email'] = 'mfi.dbms.mra@gmail.com';
            $mail_details['mail_from_details'] = 'Message From MFI DBMS of MRA';
            $mail_details['mail_to'] = $email_id;
            $mail_details['mail_cc'] = '';
            $mail_details['mail_subject'] = 'License Application Rejected !';
            $mail_details['mail_message'] = $message_body;
            $mail_details['mail_is_sent'] = 0;
			$mail_details['mail_creation_date'] = date('Y-m-d');
			$mail_details['mail_creator'] = $this->Session->read('User.Id');

            $this->loadModel('AdminModuleMessageSendingDetail');
            $this->AdminModuleMessageSendingDetail->create();
            $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

            if ($done && !empty($done['AdminModuleMessageSendingDetail']['id']))
                $this->redirect(array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $done['AdminModuleMessageSendingDetail']['id']));
        }
    }

}
