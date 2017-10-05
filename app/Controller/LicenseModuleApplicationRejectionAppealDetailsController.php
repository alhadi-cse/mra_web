<?php

App::uses('AppController', 'Controller');

class LicenseModuleApplicationRejectionAppealDetailsController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($rejection_state_ids = null) {

        if (empty($rejection_state_ids)) {
            $rejection_state_ids = $this->request->query('rejection_state_ids');
        }

        if (empty($rejection_state_ids)) {
            $rejection_state_ids = $this->Session->read('Rejection.StateIds');
        } else
            $this->Session->write('Rejection.StateIds', $rejection_state_ids);

        if (!empty($rejection_state_ids)) {
            $thisStateIds = explode('_', $rejection_state_ids);
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
        $initial_rejection_state_id = $thisStateIds[0];
        $appeal_state_id = $thisStateIds[1];

        $redirect_url = array('controller' => 'LicenseModuleApplicationRejectionAppealDetails', 'action' => 'view', '?' => array('rejection_state_ids' => $rejection_state_ids));
        $this->Session->write('Current.RedirectUrl', $redirect_url);


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

        //['LicenseModuleApplicationRejectionHistoryDetail'];
//                array(
//                    'org_id' => '12',
//                    'rejection_type_id' => '1',
//                    'rejection_category_id' => '1',
//                    'rejection_reason_id' => '13',
//                    'rejection_date' => '2016-01-06',
//                    'appeal_deadline_days' => '30',
//                    'deadline_date' => '2016-01-21',
//                    'rejection_msg' => 'mmmm'
//                );
//                debug($newData);
//                $newData['previous_licensing_state_id'] = $previous_licensing_state_id;
//                debug($newData);
//                return;
//        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date');
//
//        if (!empty($org_id))
//            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
//        else
//            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
//
//        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
//        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));
//
//        if (!empty($org_id))
//            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
//        else
//            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
//
//        $values_appeal_deadline_over = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));
//
//        
//        if (!empty($org_id))
//            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $rejection_state_id);
//        else
//            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $appeal_state_id);
//        
//        $values_waiting_for_appealed_review = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));


        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));
        else
            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date >=' => date('Y-m-d'));

        $this->loadModel('LicenseModuleApplicationRejectionHistoryDetail');
        $values_waiting_for_appealed = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('conditions' => $conditions, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));

        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));
        else
            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $initial_rejection_state_id, 'LicenseModuleApplicationRejectionHistoryDetail.deadline_date <' => date('Y-m-d'));

        $values_appeal_deadline_over = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('conditions' => $conditions, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));


        if (!empty($org_id))
            $conditions = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $appeal_state_id);
        else
            $conditions = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $appeal_state_id);

        $values_waiting_for_appealed_review = $this->LicenseModuleApplicationRejectionHistoryDetail->find('all', array('conditions' => $conditions, 'group' => array('LicenseModuleApplicationRejectionHistoryDetail.org_id')));

        $this->set(compact('isAdmin', 'rejection_state_ids', 'appeal_state_id', 'values_waiting_for_appealed', 'values_appeal_deadline_over', 'values_waiting_for_appealed_review'));
    }

    public function appeal($org_id = null, $appeal_state_id = null) {

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
        if ($this->request->is('post') && !empty($this->request->data['LicenseModuleApplicationRejectionAppealDetail'])) {

            $appeal_data_to_save = array(
                'org_id' => $org_id,
                'appeal_date' => date('Y-m-d'),
                'application_of_appeal' => $this->request->data['LicenseModuleApplicationRejectionAppealDetail']['application'],
                'approval_status_id' => 0  //Not Approved
            );

            $this->LicenseModuleApplicationRejectionAppealDetail->create();
            $done = $this->LicenseModuleApplicationRejectionAppealDetail->save($appeal_data_to_save);
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

                    $this->redirect(array('action' => 'view'));
                    return;
                } catch (Exception $ex) {
                    
                }
            }
        }

        $all_fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $condition = array('BasicModuleBasicInformation.id' => $org_id);
        $this->BasicModuleBasicInformation->recursive = 0;
        $orgDetail = $this->BasicModuleBasicInformation->find('first', array('fields' => $all_fields, 'conditions' => $condition));

        $this->set(compact('org_id', 'orgDetail'));
    }

    public function appeal_review($org_id = null, $rejection_state_ids = null, $appeal_previous_state_id = null) {

        if (!empty($rejection_state_ids)) {
            $thisStateIds = explode('_', $rejection_state_ids);
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
        $initial_rejection_state_id = $thisStateIds[0];
        $final_rejection_state_id = $thisStateIds[2];

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


        $rejection_type_id = 2;

//        $this->loadModel('LookupRejectSuspendCancelStepwiseReason');
//        $fields = array('id', 'reject_suspend_cancel_reason');
//        $conditions = array('reject_suspend_cancel_history_type_id' => $rejection_type_id, 'reject_suspend_cancel_category_id' => $rejection_category_id);
//        $rejection_options = $this->LookupRejectSuspendCancelStepwiseReason->find('list', array('fields' => $fields, 'conditions' => $conditions));

        $this->loadModel('LookupLicenseApplicationRejectionReason');
        $rejection_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => array('id', 'rejection_reason'), 'conditions' => array('rejection_type_id' => $rejection_type_id), 'recursive' => -1));

        $this->loadModel('BasicModuleBasicInformation');
        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleApplicationRejectionAppealDetailReview'];

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
                                'licensing_state_id' => $rejection_state_id,
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

                        $this->LicenseModuleApplicationRejectionAppealDetail->id = $newData['id'];
                        $done = $this->LicenseModuleApplicationRejectionAppealDetail->save($appeal_data_to_update);
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

                            $this->redirect(array('action' => 'view', $rejection_state_ids));
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

        $this->LicenseModuleApplicationRejectionAppealDetail->recursive = 0;
        $orgDetail = $this->LicenseModuleApplicationRejectionAppealDetail->find('first', array('conditions' => array('LicenseModuleApplicationRejectionAppealDetail.org_id' => $org_id), 'order' => array('LicenseModuleApplicationRejectionAppealDetail.appeal_date' => 'desc')));

        $approval_status_options = array(0 => 'Not Accepted', 1 => 'Accepted');

        $this->loadModel('LicenseModuleStateName');
        $fields = array('id', 'state_title');
        $conditions = array('id <' => $appeal_previous_state_id);
        $state_list = $this->LicenseModuleStateName->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0));

        $this->set(compact('isAdmin', 'org_id', 'orgDetail', 'approval_status_options', 'rejection_options', 'state_list'));
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

        $current_year = $this->Session->read('Current.LicensingYear');
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
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
                    //'licensing_year' => $current_year,
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
                    . "\r\n" . "\r\n" . 'Your license application has been Rejected. Due to "'
                    . $reason . '".' . "\r\n" . "\r\n" . 'Thanks' . "\r\n"
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
            //$this->redirect($redirect_url);
        }
    }

}
