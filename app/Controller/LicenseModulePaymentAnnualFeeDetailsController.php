<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModulePaymentAnnualFeeDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($payment_type_id = null, $this_state_ids = null) {

        if (empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

//        if (!empty($payment_type_id)) {
//            if ($payment_type_id == 1) {
//                $title = "License Fee Payment";
//            } else if ($payment_type_id == 2) {
//                $title = "Annual Fee Payment";
//            } else if ($payment_type_id == 3) {
//                $title = "Renewal Fee Payment";
//            } else if ($payment_type_id == 4) {
//                $title = "Others Type of Payment";
//            }
//        } else {
//            $title = "Payment Information";
//        }


        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(2,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($this_state_ids))
            $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateId', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateId');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 7) {
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

        $this->loadModel('LicenseModulePaymentDetail');
        if (in_array(2,$user_group_id)) {
            $org_id = $this->Session->read('Org.Id');

            if (empty($org_id)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Organization Information !'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_pending = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_state_id' => $thisStateIds[1]);
            $is_org_exist = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been requested for annual fee payment !'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_requested = array('LicenseModulePaymentDetail.org_id' => $org_id, 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 0);
            $values_payment_requested = $this->LicenseModulePaymentDetail->find('first', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_requested['LicenseModulePaymentDetail']['id'], $org_id));
                return;
            }

            $condition_done = array('LicenseModulePaymentDetail.org_id' => $org_id, 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 1);
            $values_payment_done = $this->LicenseModulePaymentDetail->find('first', array('conditions' => $condition_done));
            if (!empty($values_payment_done)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_done['LicenseModulePaymentDetail']['id']));
                return;
            }

            $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id));
            return;

            $values_payment_selected = null;
            $values_payment_pending = null; //$this->LicenseModulePaymentDetail->find('first', array('conditions' => $condition_pending));
        } elseif (in_array(1,$user_group_id)) {

            $fields = array('BasicModuleBasicInformation.id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org', 'license_no');
            $fields_with_state_details = array_merge($fields, array('LicenseModuleCurrentStateHistory.date_of_starting', 'LicenseModuleCurrentStateHistory.date_of_deadline'));

            $condition_selected = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
            $condition_pending = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);
            //$condition_requested = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModulePaymentDetail.payment_approved' => 0);
            //$condition_done = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[3], 'LicenseModulePaymentDetail.payment_approved' => 1);

            $condition_requested = array( 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 0);
            $condition_done = array( 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 1);


            $values_payment_selected = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => 0));
//            debug($condition_selected);
//            debug($values_payment_selected);

            $values_payment_pending = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields_with_state_details, 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            $values_payment_requested = $this->LicenseModulePaymentDetail->find('all', array('conditions' => $condition_requested));

            if ($this->request->is('post')) {
                $option = $this->request->data['LicenseModulePaymentDetail']['search_option'];
                $keyword = $this->request->data['LicenseModulePaymentDetail']['search_keyword'];
                $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            }

            $this->paginate = array('conditions' => $condition_done, 'limit' => 10, 'order' => array('full_name_of_org' => 'ASC'));
            $this->LicenseModulePaymentDetail->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values_payment_done = $this->Paginator->paginate('LicenseModulePaymentDetail');
        }

        $this->set(compact('org_id', 'user_group_id', 'thisStateIds', 'payment_type_id', 'values_payment_selected', 'values_payment_pending', 'values_payment_requested', 'values_payment_done'));
    }

    public function view_reminder($payment_type_id = null, $this_state_ids = null) {
        
        if (empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');
        
        if (empty($payment_type_id))
            $payment_type_id = $this->Session->read('Payment.TypeId');

        
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(2,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($this_state_ids))
            $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 7) {
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

        $this->loadModel('LicenseModulePaymentDetail');
        if (in_array(2,$user_group_id)) {
            $org_id = $this->Session->read('Org.Id');

            if (empty($org_id)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Organization Information !'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_pending = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[1]);
            $is_org_exist = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been requested for fee payment!'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_requested = array('LicenseModulePaymentDetail.org_id' => $org_id, 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 0);
            $values_payment_requested = $this->LicenseModulePaymentDetail->find('first', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_requested['LicenseModulePaymentDetail']['id'], $org_id));
                return;
            }

            $condition_done = array('LicenseModulePaymentDetail.org_id' => $org_id, 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 1);
            $values_payment_done = $this->LicenseModulePaymentDetail->find('first', array('conditions' => $condition_done));
            if (!empty($values_payment_done)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_done['LicenseModulePaymentDetail']['id']));
                return;
            }

            $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id));
            return;

            $values_payment_selected = null;
            $values_payment_deadline_over = null;
        } elseif (in_array(1,$user_group_id)) {

            //$fields = array('BasicModuleBasicInformation.id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org', 'license_no');
            $fields = array('BasicModuleBasicInformation.id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org', 'license_no');
            $fields_with_state_details = array_merge($fields, array('LicenseModuleCurrentStateHistory.date_of_starting', 'LicenseModuleCurrentStateHistory.date_of_deadline'));


            $fields_deadline_over = array_merge($fields, array('LicenseModuleStateHistory.date_of_deadline'));
            $condition_deadline_over = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->recursive = 0;
            $values_payment_deadline_over = $this->LicenseModuleStateHistory->find('all', array('conditions' => $condition_deadline_over, 'group' => array('LicenseModuleStateHistory.org_id')));
            debug($values_payment_deadline_over);

            $condition_deadline_over = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleCurrentStateHistory.date_of_deadline <' => date('Y-m-d'));

            $values_payment_deadline_over = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields_with_state_details, 'conditions' => $condition_deadline_over, 'recursive' => 1, 'group' => 'BasicModuleBasicInformation.id'));


            debug($values_payment_deadline_over);



            $fields_reminder_send = array_merge($fields, array('LicenseModulePaymentReminderDetail.date_of_deadline'));
            $condition_reminder_send = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);

            //$condition_reminder1_deadline_over = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModulePaymentReminderDetail.date_of_deadline <' => date('Y-m-d'));
            $this->loadModel('LicenseModulePaymentReminderDetail');
            $this->LicenseModulePaymentReminderDetail->recursive = 0;
            $values_payment_reminder_sent = $this->LicenseModulePaymentReminderDetail->find('all', array('conditions' => $condition_reminder_send, 'group' => array('LicenseModulePaymentReminderDetail.org_id')));
            debug($values_payment_reminder_sent);



            //$this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            //$approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));
//            $values_payment_selected = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_deadline_over, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
//            debug($values_payment_selected);
//            
            $condition_pending = array( 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleStateHistory.state_id' => $thisStateIds[1]);
            $condition_requested = array( 'licensing_state_id' => $thisStateIds[0], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 0);
            $condition_done = array( 'licensing_state_id' => $thisStateIds[3], 'LicenseModulePaymentDetail.payment_approved' => 1);

            $values_payment_requested = $this->LicenseModulePaymentDetail->find('all', array('conditions' => $condition_requested));


            if ($this->request->is('post')) {
                $option = $this->request->data['LicenseModulePaymentDetail']['search_option'];
                $keyword = $this->request->data['LicenseModulePaymentDetail']['search_keyword'];
                $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            }

            $this->paginate = array('conditions' => $condition_done, 'limit' => 10, 'order' => array('full_name_of_org' => 'ASC'));
            $this->LicenseModulePaymentDetail->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values_payment_done = $this->Paginator->paginate('LicenseModulePaymentDetail');
        }

        $this->set(compact('org_id', 'user_group_id', 'thisStateIds', 'payment_type_id', 'values_payment_deadline_over', 'values_payment_reminder1_send', 'values_payment_selected', 'values_payment_requested', 'values_payment_done'));
    }

    public function payment_request_send($org_id = null, $payment_type_id = null, $next_state_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($next_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State Id !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('AdminModuleUserProfile');
        $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

        if (!empty($email_list) && !empty($email_list[$org_id])) {
            $to_mail = $email_list[$org_id];

            $email = new CakeEmail('gmail');
            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('License Fee Payment Request');

            $days = 0;
            if (!empty($next_state_id)) {
                $this->loadModel('LicenseModuleStateName');
                $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $next_state_id)));

                if (!empty($stateDetails) && !empty($stateDetails[$next_state_id]))
                    $days = $stateDetails[$next_state_id];
            }

            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->recursive = 0;
            $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('short_name_of_org', 'full_name_of_org', 'license_no'));

            $licenseNo = $orgDetails['BasicModuleBasicInformation']['license_no'];
            $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
            $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
            $mfiName = ((!empty($mfiName) && !empty($mfiFullName)) ? "$mfiFullName ($mfiName)" : "$mfiFullName$mfiName");

            if (!empty($payment_type_id)) {
                if ($payment_type_id == 1) {
                    $title = "License Fee";
                } else if ($payment_type_id == 2) {
                    $title = "Annual Fee";
                } else if ($payment_type_id == 3) {
                    $title = "Renewal Fee";
                } else if ($payment_type_id == 4) {
                    $title = "Others Type of Fee";
                }
            } else {
                $title = "Fee";
            }


            $message_body = "Dear Applicant, " . "\r\n \r\n"
                    . "Your Organization Name: \"$mfiName\" and License No.: $licenseNo " 
//                    . "has been successfully completed the evaluation of annual process and selected for "
//                    . "a License."
                    //. "with some Terms and Conditions. "
                    . "\r\n"
                    . "Please pay the $title"
                    . ($days > 0 ? " within next $days days." : ".")
                    . "\r\n  \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority (MRA)";

            //$email->send($message_body);

            if (true) {
                $current_year = $this->Session->read('Current.LicensingYear');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $next_state_id,
                    'licensing_year' => $current_year,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

                $this->redirect(array('action' => 'view', $payment_type_id));
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... !',
                    'msg' => 'Message Sending Failed !'
                );
                $this->set(compact('msg'));
            }
        } else {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid e-mail information  !'
            );
            $this->set(compact('msg'));
        }
    }

    public function payment_made($org_id = null, $payment_type_id = null, $next_state_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($next_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State Id !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($payment_type_id))
            $payment_type_id = $this->Session->read('Payment.TypeId');

        if (empty($payment_type_id))
            $payment_type_id = '';

        $this->loadModel('LicenseModulePaymentDetail');

        if ($this->request->is('post')) {
            $req_data = $this->request->data;
            if (!empty($req_data)) {
                $req_data = Hash::insert($req_data, 'LicenseModulePaymentDetail.payment_approved', 0);
                $this->LicenseModulePaymentDetail->create();
                if ($this->LicenseModulePaymentDetail->save($req_data)) {

//                    if (!empty($next_state_id)) {}
                    $current_year = $this->Session->read('Current.LicensingYear');
                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $next_state_id,
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        //'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);

                    $this->redirect(array('action' => 'view', $payment_type_id));
                }
            }
        }

        $fields = array('id', 'full_name_of_org');

        $condition_selected = array('BasicModuleBasicInformation.id' => $org_id);
        $orgNameOptions = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('list', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

        $paymentTypeOptions = $this->LicenseModulePaymentDetail->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type')));

        $this->set(compact('org_id', 'payment_type_id', 'orgNameOptions', 'paymentTypeOptions'));
    }

    public function payment_modify($id = null, $payment_type_id = null) {

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data;
            if (!empty($posted_data)) {
                $this->LicenseModulePaymentDetail->id = $id;
                if ($this->LicenseModulePaymentDetail->save($posted_data)) {
                    $this->redirect(array('action' => 'view', $posted_data['LicenseModulePaymentDetail']['payment_type_id']));
                    //$this->redirect(array('action' => 'view_annual_fee', '?' => array('this_state_ids' => '9_10_11_12', 'payment_type_id' => 1)));
                }
            }
        }

        $existing_data = $this->LicenseModulePaymentDetail->findById($id);
        if (!empty($existing_data)) {

            if (!$this->request->data)
                $this->request->data = $existing_data;

            $org_id = $existing_data['LicenseModulePaymentDetail']['org_id'];
            $payment_type_id = $existing_data['LicenseModulePaymentDetail']['payment_type_id'];


//            $orgNameOptions = $this->LicenseModulePaymentDetail->->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
//            $paymentTypeOptions = $this->LicenseModulePaymentDetail->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type'), 'conditions' => array('LookupPaymentType.id' => $payment_type_id)));
//
//            $orgNameOption = $orgNameOptions[$org_id];
//            $paymentTypeOption = $paymentTypeOptions[$payment_type_id];

            $this->set(compact('org_id', 'payment_type_id'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );

            $orgNameOption = $paymentTypeOption = $payment_type_id = null;
            $this->set(compact('msg', 'org_id', 'payment_type_id', 'orgNameOption', 'paymentTypeOption'));
        }
    }

    public function payment_approved($id = null, $next_state_id = null) {

        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($next_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Next State Id !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->loadModel('LicenseModulePaymentDetail');
        $payment_details = $this->LicenseModulePaymentDetail->findById($id);

        if (!empty($payment_details)) {
            $user_Name = $this->Session->read('User.Name');
            $done = $this->LicenseModulePaymentDetail->updateAll(array('LicenseModulePaymentDetail.payment_approved' => 1, 'LicenseModulePaymentDetail.payment_approved_by' => "'$user_Name'"), array('LicenseModulePaymentDetail.id' => $id));

            if ($done) {
                $org_id = $payment_details['LicenseModulePaymentDetail']['org_id'];
                if (!empty($org_id)) {
                    $current_year = $this->Session->read('Current.LicensingYear');
                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $next_state_id,
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $user_Name);

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);
                }
                $this->redirect(array('action' => 'view', $payment_details['LicenseModulePaymentDetail']['payment_type_id']));
            } else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Payment information update failed !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment information !'
            );
            $this->set(compact('msg'));
            return;
        }
    }

    public function payment_preview($id = null) {

        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('id'));

//        $this->loadModel('LicenseModulePaymentDetail');
//        $paymentDetails = $this->LicenseModulePaymentDetail->findById($id);
//        if (!$paymentDetails) {
//            throw new NotFoundException('Invalid Information');
//        }
//        $this->set(compact('paymentDetails'));
    }

    public function payment_details($org_id = null, $payment_type_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($payment_type_id))
            $payment_type_id = $this->Session->read('Payment.TypeId');

        $this->loadModel('LicenseModulePaymentDetail');
        $paymentDetails = $this->LicenseModulePaymentDetail->find('first', array('conditions' => array('LicenseModulePaymentDetail.org_id' => $org_id, 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id)));
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('payment_type_id', 'paymentDetails'));
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $this->loadModel('LicenseModulePaymentDetail');
        $paymentDetails = $this->LicenseModulePaymentDetail->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('paymentDetails'));
    }

    public function preview($id = null) {

        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );
            $this->set(compact('msg'));
            return;
        }

//        if (empty($payment_type_id))
//            $payment_type_id = $this->Session->read('Payment.TypeId');

        $this->loadModel('LicenseModulePaymentDetail');
        $paymentDetails = $this->LicenseModulePaymentDetail->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('paymentDetails'));
    }

    public function reminder_send($org_id = null, $payment_type_id = null, $next_state_id = null) {

        if (empty($next_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid state information !'
            );
            $this->set(compact('msg'));
            return;
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

        $this->loadModel('AdminModuleUserProfile');
        $email_list = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));

        if (!empty($email_list)) {
            $to_mail = $email_list[$org_id];

            $email = new CakeEmail('gmail');
            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('Reminder for License Fee Payment');

            $this->loadModel('LicenseModuleStateName');
            $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $next_state_id)));

            if (!empty($stateDetails) && !empty($stateDetails[$next_state_id]))
                $days = $stateDetails[$next_state_id];
            else
                $days = 0;

            if (!empty($payment_type_id)) {
                if ($payment_type_id == 1) {
                    $title = "License Fee";
                } else if ($payment_type_id == 2) {
                    $title = "Annual Fee";
                } else if ($payment_type_id == 3) {
                    $title = "Renewal Fee";
                } else if ($payment_type_id == 4) {
                    $title = "Others Type of Fee";
                }
            } else {
                $title = "Fee";
            }

            $message_body = "Dear Applicant, " . "\r\n" . "\r\n"
                    . "Your did not pay the $title on time (within deadline). This is the Reminder for you to pay the $title"
                    . ($days > 0 ? " within next $days days." : ".")
                    . "\r\n \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority";

            if ($email->send($message_body)) {

                $current_year = $this->Session->read('Current.LicensingYear');

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $next_state_id,
                    'licensing_year' => $current_year,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

                $payment_reminder_history = array(
                    'org_id' => $org_id,
                    'licensing_year' => $current_year,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModulePaymentReminderDetail');
                $this->LicenseModulePaymentReminderDetail->create();
                $this->LicenseModulePaymentReminderDetail->save($payment_reminder_history);

                $this->redirect(array('action' => 'view_reminder'));
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Message Sending Failed!'
                );
                $this->set(compact('msg'));
            }
        }
    }

}
