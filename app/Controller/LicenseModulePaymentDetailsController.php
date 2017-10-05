<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModulePaymentDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    public function view($payment_type_id = null, $this_state_ids = null, $licensed_mfi = null) {

        if (empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

        if (empty($this_state_ids))
            $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!isset($licensed_mfi)) {
            $licensed_mfi = $this->request->query('licensed_mfi');
        }

        $redirect_url = array('controller' => 'LicenseModulePaymentDetails', 'action' => 'view', '?' => array('payment_type_id' => $payment_type_id, 'this_state_ids' => $this_state_ids, 'licensed_mfi' => $licensed_mfi));
        $this->Session->write('Current.RedirectUrl', $redirect_url);


        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array(5, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $is_admin = (in_array(1, $user_group_id));
        if (!$is_admin) {
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
        }

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

        if (!empty($payment_type_id))
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
        else
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

        $this->set(compact('org_id', 'is_admin', 'thisStateIds', 'payment_type_id', 'payment_types', 'licensed_mfi'));


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no');
        $fields_with_payment_details = array_merge($fields, array('LicenseModulePaymentDetail.id', 'LicenseModulePaymentDetail.org_id', 'LicenseModulePaymentDetail.payment_type_id', 'LicenseModulePaymentDetail.payment_amount', 'LicenseModulePaymentDetail.payment_delay_fine', 'LicenseModulePaymentDetail.payment_reason', 'LicenseModulePaymentDetail.payment_fiscal_year', 'LicenseModulePaymentDetail.payment_notify_date', 'LicenseModulePaymentDetail.payment_deadline_date', 'LicenseModulePaymentDetail.payment_date', 'LicenseModulePaymentDetail.payment_document_no'));
        $fields_with_reminder_details = array_merge($fields_with_payment_details, array('LicenseModulePaymentReminderDetail.reminder_notify_date', 'LicenseModulePaymentReminderDetail.reminder_deadline_date'));

        $preStateIds = $thisStateIds[0];
        if (strpos($preStateIds, '^')) {
            $preStateIds = explode('^', $preStateIds);
        }

        $condition_selected = array('BasicModuleBasicInformation.licensing_state_id' => $preStateIds);
        $condition_pending = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => -1);
        $condition_requested = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 0);
        $condition_done = array('LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 1);
        $condition_reminder_send = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[4], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved !=' => 1, 'LicenseModulePaymentReminderDetail.reminder_is_active' => 1);

        if (!$is_admin) {
            $condition_selected = array_merge($condition_selected, array('BasicModuleBasicInformation.id' => $org_id));
            $condition_pending = array_merge($condition_pending, array('LicenseModulePaymentDetail.org_id' => $org_id));
            $condition_requested = array_merge($condition_requested, array('LicenseModulePaymentDetail.org_id' => $org_id));
            $condition_done = array_merge($condition_done, array('LicenseModulePaymentDetail.org_id' => $org_id));
            $condition_reminder_send = array_merge($condition_reminder_send, array('LicenseModulePaymentDetail.org_id' => $org_id));
        } elseif ($this->request->is('post')) {
            $option = $this->request->data['LicenseModulePaymentDetail']['search_option'];
            $keyword = $this->request->data['LicenseModulePaymentDetail']['search_keyword'];

            if (strpos($option, "full_name_of_org") !== false)
                $condition_selected = array_merge($condition_selected, array("$option LIKE '%$keyword%'"));
            $condition_pending = array_merge($condition_pending, array("$option LIKE '%$keyword%'"));
            $condition_requested = array_merge($condition_requested, array("$option LIKE '%$keyword%'"));
            $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            $condition_reminder_send = array_merge($condition_reminder_send, array("$option LIKE '%$keyword%'"));
        }

        $values_payment_selected = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => -1));
        $values_payment_pending = $this->LicenseModulePaymentDetail->find('all', array('fields' => $fields_with_payment_details, 'conditions' => $condition_pending, 'group' => 'org_id'));
        $values_payment_requested = $this->LicenseModulePaymentDetail->find('all', array('fields' => $fields_with_payment_details, 'conditions' => $condition_requested));

        $this->paginate = array('fields' => $fields_with_payment_details, 'conditions' => $condition_done, 'recursive' => 0, 'limit' => 10, 'order' => array('form_serial_no' => 'ASC'));
        $this->Paginator->settings = $this->paginate;
        $values_payment_done = $this->Paginator->paginate('LicenseModulePaymentDetail');

        $values_payment_reminder_sent = $this->LicenseModulePaymentDetail->find('all', array('fields' => $fields_with_reminder_details, 'conditions' => $condition_reminder_send, 'recursive' => 0));


        $this->set(compact('values_payment_selected', 'values_payment_pending', 'values_payment_reminder_sent', 'values_payment_requested', 'values_payment_done'));
        //$this->set(compact('org_id', 'user_group_id', 'thisStateIds', 'payment_type_id', 'payment_types', 'licensed_mfi', 'values_payment_selected', 'values_payment_pending', 'values_payment_reminder_sent', 'values_payment_requested', 'values_payment_done'));
    }

    public function payment_notification_send($org_id = null, $payment_type_id = null, $next_state_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licensed_mfi = !isset($licensed_mfi) || $licensed_mfi == 1;

        if (empty($payment_type_id))
            $payment_type_id = $this->Session->read('Payment.TypeId');

        if (empty($payment_type_id))
            $payment_type_id = '';

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        $this->loadModel('BasicModuleBasicInformation');
        $this->BasicModuleBasicInformation->recursive = -1;
        $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('form_serial_no', 'short_name_of_org', 'full_name_of_org', 'license_no', 'licensing_state_id'));
        $orgDetails = $orgDetails['BasicModuleBasicInformation'];

        if ($this->request->is('post') && $this->request->data['LicenseModulePaymentDetail']) {
            $req_data = $this->request->data['LicenseModulePaymentDetail'];

            unset($req_data['payment_message']);
            $requested_state_id = $orgDetails['licensing_state_id'];
            $req_data = Hash::insert($req_data, 'requested_licensing_state_id', $requested_state_id);
            $req_data = Hash::insert($req_data, 'payment_approved', -1);

            $conditions = array('org_id' => $org_id, 'payment_type_id' => $payment_type_id, 'payment_approved' => -1);
            $this->LicenseModulePaymentDetail->deleteAll($conditions, false);

            $this->LicenseModulePaymentDetail->create();
            $done = $this->LicenseModulePaymentDetail->save($req_data);
            if ($done && !empty($next_state_id)) {
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $date_of_deadline = $done['LicenseModulePaymentDetail']['payment_deadline_date'];
                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $next_state_id,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'date_of_deadline' => $date_of_deadline,
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

                $this->redirect($redirect_url);
            }
        }

        $formSlNo = $orgDetails['form_serial_no'];
        $licenseNo = $orgDetails['license_no'];

        $orgName = $orgDetails['short_name_of_org'];
        $orgFullName = $orgDetails['full_name_of_org'];
        $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");

        if (!empty($payment_type_id))
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
        else
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

        $this->set(compact('org_id', 'orgName', 'payment_type_id', 'payment_types', 'redirect_url'));
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

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        $conditions = array('org_id' => $org_id, 'payment_type_id' => $payment_type_id, 'payment_approved' => -1);
        $payment_id = $this->LicenseModulePaymentDetail->field('id', $conditions);
        if ($payment_id) {
            $this->redirect(array('action' => 'payment_modify', $payment_id));
            return;
        }

        $this->loadModel('BasicModuleBasicInformation');
        $this->BasicModuleBasicInformation->recursive = -1;
        $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('form_serial_no', 'short_name_of_org', 'full_name_of_org', 'license_no', 'licensing_state_id'));
        $orgDetails = $orgDetails['BasicModuleBasicInformation'];

        if ($this->request->is('post') && $this->request->data['LicenseModulePaymentDetail']) {
            $req_data = $this->request->data['LicenseModulePaymentDetail'];
            if (!empty($req_data)) {
                $req_data = Hash::insert($req_data, 'payment_approved', 0);

                if ($payment_id)
                    $this->LicenseModulePaymentDetail->id = $payment_id;
                else {
                    $requested_state_id = $orgDetails['licensing_state_id'];
                    $req_data = Hash::insert($req_data, 'requested_licensing_state_id', $requested_state_id);
                    $this->LicenseModulePaymentDetail->create();
                }
                $done = $this->LicenseModulePaymentDetail->save($req_data);
                if ($done) {
                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                    $date_of_deadline = isset($done['LicenseModulePaymentDetail']['payment_deadline_date']) ? $done['LicenseModulePaymentDetail']['payment_deadline_date'] : date('Y-m-d');
                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $next_state_id,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'date_of_deadline' => $date_of_deadline,
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);

                    $this->redirect($redirect_url);
                    return;
                }
            }
        }

        $formSlNo = $orgDetails['form_serial_no'];
        $licenseNo = $orgDetails['license_no'];

        $orgName = $orgDetails['short_name_of_org'];
        $orgFullName = $orgDetails['full_name_of_org'];
        $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");

        if (!empty($payment_type_id))
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
        else
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

        $this->set(compact('org_id', 'payment_type_id', 'orgName', 'payment_types', 'redirect_url'));
    }

    public function payment_modify($id = null) {

        if (empty($redirect_url))
            $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        if ($this->request->is(array('post', 'put'))) {
            $posted_data = $this->request->data;
            if (!empty($posted_data)) {
                $this->LicenseModulePaymentDetail->id = $id;
                if ($this->LicenseModulePaymentDetail->save($posted_data)) {
                    $this->redirect($redirect_url);
                }
            }
        }

        $existing_data = $this->LicenseModulePaymentDetail->findById($id);

        if (!empty($existing_data)) {

            if (!$this->request->data)
                $this->request->data = $existing_data;

            $org_id = $existing_data['LicenseModulePaymentDetail']['org_id'];
            $payment_type_id = $existing_data['LicenseModulePaymentDetail']['payment_type_id'];

            if (!empty($payment_type_id))
                $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
            else
                $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

            $this->set(compact('org_id', 'payment_type_id', 'payment_types', 'redirect_url'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );

            $this->set(compact('msg'));
        }
    }

    public function payment_submit($id = null, $next_state_id = null) {

        if (empty($redirect_url))
            $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        if ($this->request->is(array('post', 'put'))) {
            $posted_data = $this->request->data;
            if (!empty($posted_data)) {
                $posted_data = Hash::insert($posted_data, 'LicenseModulePaymentDetail.payment_approved', 0);
                $this->LicenseModulePaymentDetail->id = $id;
                $done = $this->LicenseModulePaymentDetail->save($posted_data);

                if ($done && $done['LicenseModulePaymentDetail']['org_id'] && !empty($next_state_id)) {
                    $org_id = $done['LicenseModulePaymentDetail']['org_id'];
                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $next_state_id,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);


                    $this->redirect($redirect_url);
                }
            }
        }

        $existing_data = $this->LicenseModulePaymentDetail->findById($id);

        if (!empty($existing_data)) {

            if (!$this->request->data)
                $this->request->data = $existing_data;

            $org_id = $existing_data['LicenseModulePaymentDetail']['org_id'];
            $payment_type_id = $existing_data['LicenseModulePaymentDetail']['payment_type_id'];

            if (!empty($payment_type_id))
                $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
            else
                $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

            $this->set(compact('org_id', 'payment_type_id', 'payment_types', 'redirect_url'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );

            $this->set(compact('msg'));
        }
    }

    public function payment_approved($id = null) {

        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $payment_details = $this->LicenseModulePaymentDetail->findById($id);

        if (!empty($payment_details)) {
            $user_name = $this->Session->read('User.Name');
            $done = $this->LicenseModulePaymentDetail->updateAll(array('LicenseModulePaymentDetail.payment_approved' => 1, 'LicenseModulePaymentDetail.payment_approved_by' => "'$user_name'"), array('LicenseModulePaymentDetail.id' => $id));

            if ($done) {
                $org_id = $payment_details['LicenseModulePaymentDetail']['org_id'];
                if (!empty($org_id)) {

                    $this_state_ids = $this->Session->read('Current.StateIds');
                    if (empty($this_state_ids))
                        $this_state_ids = $this->request->query('this_state_ids');

                    if (!empty($this_state_ids)) {
                        $thisStateIds = explode('_', $this_state_ids);
                        if (!empty($thisStateIds[7]))
                            $next_state_id = $thisStateIds[7];
                    }

                    if (empty($next_state_id) && !empty($payment_details['LicenseModulePaymentDetail']['requested_licensing_state_id']))
                        $next_state_id = $payment_details['LicenseModulePaymentDetail']['requested_licensing_state_id'];

                    if (!empty($next_state_id)) {
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $next_state_id,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $user_name);

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);
                    }
                }

                if (empty($redirect_url))
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view', $payment_details['LicenseModulePaymentDetail']['payment_type_id']);

                $this->redirect($redirect_url);
            } else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Payment information approved failed !'
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

        $paymentDetails = $this->LicenseModulePaymentDetail->find('first', array('conditions' => array('LicenseModulePaymentDetail.org_id' => $org_id, 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id)));
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Payment Information !');
        }
        $this->set(compact('payment_type_id', 'paymentDetails'));
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Payment Information !');
        }

        $paymentDetails = $this->LicenseModulePaymentDetail->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Payment Information !');
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
        //$this->loadModel('LicenseModulePaymentDetail');
        $paymentDetails = $this->LicenseModulePaymentDetail->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('paymentDetails'));
    }

    public function view_reminder($payment_type_id = null, $this_state_ids = null, $licensed_mfi = null) {

        if (empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

        if (empty($this_state_ids))
            $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!isset($licensed_mfi)) {
            $licensed_mfi = $this->request->query('licensed_mfi');
        }

        $redirect_url = array('controller' => 'LicenseModulePaymentDetails', 'action' => 'view', '?' => array('payment_type_id' => $payment_type_id, 'this_state_ids' => $this_state_ids, 'licensed_mfi' => $licensed_mfi));
        $this->Session->write('Current.RedirectUrl', $redirect_url);


        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array(2, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $is_admin = (in_array(1, $user_group_id));
        if (!$is_admin) {
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
        }

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

        if (!empty($payment_type_id))
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
        else
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

        $this->set(compact('org_id', 'is_admin', 'thisStateIds', 'payment_type_id', 'payment_types', 'licensed_mfi', 'redirect_url'));


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no');
        $fields_with_payment_details = array_merge($fields, array('LicenseModulePaymentDetail.id', 'LicenseModulePaymentDetail.org_id', 'LicenseModulePaymentDetail.payment_type_id', 'LicenseModulePaymentDetail.payment_amount', 'LicenseModulePaymentDetail.payment_delay_fine', 'LicenseModulePaymentDetail.payment_reason', 'LicenseModulePaymentDetail.payment_fiscal_year', 'LicenseModulePaymentDetail.payment_notify_date', 'LicenseModulePaymentDetail.payment_deadline_date', 'LicenseModulePaymentDetail.payment_date', 'LicenseModulePaymentDetail.payment_document_no'));
        $fields_with_reminder_details = array_merge($fields_with_payment_details, array('LicenseModulePaymentReminderDetail.reminder_notify_date', 'LicenseModulePaymentReminderDetail.reminder_deadline_date'));


        $condition_deadline_over = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => -1, 'LicenseModulePaymentDetail.payment_deadline_date <' => date('Y-m-d'));
        $condition_reminder_send = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[4], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved !=' => 1, 'LicenseModulePaymentReminderDetail.reminder_is_active' => 1);

        if (!$is_admin) {
            $condition_deadline_over = array_merge($condition_deadline_over, array('BasicModuleBasicInformation.id' => $org_id));
            $condition_reminder_send = array_merge($condition_reminder_send, array('LicenseModulePaymentDetail.org_id' => $org_id));
        } elseif ($this->request->is('post')) {
            $option = $this->request->data['LicenseModulePaymentDetail']['search_option'];
            $keyword = $this->request->data['LicenseModulePaymentDetail']['search_keyword'];

            $condition_deadline_over = array_merge($condition_deadline_over, array("$option LIKE '%$keyword%'"));
            $condition_reminder_send = array_merge($condition_reminder_send, array("$option LIKE '%$keyword%'"));
        }

        $this->paginate = array('fields' => $fields_with_payment_details, 'conditions' => $condition_deadline_over, 'recursive' => 0, 'limit' => 10, 'order' => array('form_serial_no' => 'ASC'));
        $this->Paginator->settings = $this->paginate;
        $values_payment_deadline_over = $this->Paginator->paginate('LicenseModulePaymentDetail');

        $values_payment_reminder_sent = $this->LicenseModulePaymentDetail->find('all', array('fields' => $fields_with_reminder_details, 'conditions' => $condition_reminder_send, 'recursive' => 0));


        $this->set(compact('values_payment_deadline_over', 'values_payment_reminder_sent'));
    }

    public function reminder_send($payment_id = null, $payment_type_id = null, $next_state_id = null) {

        if (empty($payment_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Request information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($next_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid state information !'
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

            $payment_type = "Fee";
            if (!empty($payment_type_id)) {
                $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => -1));
                if (!empty($payment_types))
                    $payment_type = $payment_types[$payment_type_id];
            }

            $message_body = "Dear Applicant, " . "\r\n" . "\r\n"
                    . "Your did not pay the $payment_type on time (within deadline). This is the Reminder for you to pay the $payment_type"
                    . ($days > 0 ? " within next $days days." : ".")
                    . "\r\n \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority";

            if (!$email->send($message_body)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Message Sending Failed!'
                );
                $this->set(compact('msg'));
            }
        }


        $days = 30;
        $payment_reminder_detail = array(
            'payment_id' => $payment_id,
            'payment_type_id' => $payment_type_id,
            'reminder_notify_date' => date('Y-m-d'),
            'reminder_deadline_date' => date('Y-m-d', strtotime("+$days days")),
            'reminder_sender_name' => $this->Session->read('User.Name'),
            'reminder_is_active' => 1);

        $this->loadModel('LicenseModulePaymentReminderDetail');
        $this->LicenseModulePaymentReminderDetail->updateAll(array('reminder_is_active' => 0), array('payment_id' => $payment_id));
        $this->LicenseModulePaymentReminderDetail->create();
        $done = $this->LicenseModulePaymentReminderDetail->save($payment_reminder_detail);
        if ($done) {
            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

            $date_of_deadline = $done['LicenseModulePaymentDetail']['reminder_deadline_date'];
            $org_state_history = array(
                'org_id' => $org_id,
                'state_id' => $next_state_id,
                'date_of_state_update' => date('Y-m-d'),
                'date_of_starting' => date('Y-m-d'),
                'date_of_deadline' => $date_of_deadline,
                'user_name' => $this->Session->read('User.Name'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->create();
            $this->LicenseModuleStateHistory->save($org_state_history);

            if (empty($redirect_url))
                $redirect_url = $this->Session->read('Current.RedirectUrl');
            if (empty($redirect_url))
                $redirect_url = array('action' => 'view_reminder');

            $this->redirect($redirect_url);
        }
    }

    public function view_reminderOK($payment_type_id = null, $this_state_ids = null, $licensed_mfi = null) {

        if (empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');

        if (empty($payment_type_id))
            $payment_type_id = $this->Session->read('Payment.TypeId');
        if (empty($this_state_ids))
            $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!isset($licensed_mfi)) {
            $licensed_mfi = $this->request->query('licensed_mfi');
        }

        $redirect_url = array('controller' => 'LicenseModulePaymentDetails', 'action' => 'view_reminder', '?' => array('payment_type_id' => $payment_type_id, 'this_state_ids' => $this_state_ids, 'licensed_mfi' => $licensed_mfi));
        $this->Session->write('Current.RedirectUrl', $redirect_url);


        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array(2, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

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

        if (!empty($payment_type_id))
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => 0));
        else
            $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'recursive' => 0));

        if (in_array(2, $user_group_id)) {
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
            $is_org_exist = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => -1));

            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been notified for fee payment!'
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
        } elseif (in_array(1, $user_group_id)) {

            //$fields = array('BasicModuleBasicInformation.id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org', 'license_no');
            $fields = array('BasicModuleBasicInformation.id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org', 'license_no');
            $fields_with_state_details = array_merge($fields, array('LicenseModuleCurrentStateHistory.date_of_starting', 'LicenseModuleCurrentStateHistory.date_of_deadline'));


//            $fields_deadline_over = array_merge($fields, array('LicenseModuleStateHistory.date_of_deadline'));
//            $condition_deadline_over = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));
//
//            $this->loadModel('LicenseModuleStateHistory');
//            $this->LicenseModuleStateHistory->recursive = 0;
//            $values_payment_deadline_over = $this->LicenseModuleStateHistory->find('all', array('conditions' => $condition_deadline_over, 'group' => array('LicenseModuleStateHistory.org_id')));
//            debug($values_payment_deadline_over);

            $condition_deadline_over = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleCurrentStateHistory.date_of_deadline <' => date('Y-m-d'));

            $values_payment_deadline_over = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields_with_state_details, 'conditions' => $condition_deadline_over, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));


            //debug($values_payment_deadline_over);
            //$fields_reminder_send = array_merge($fields, array('LicenseModulePaymentReminderDetail.date_of_deadline'));
            $condition_reminder_send = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[4], 'LicenseModulePaymentReminderDetail.is_active' => 1);

            //$condition_reminder1_deadline_over = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModulePaymentReminderDetail.date_of_deadline <' => date('Y-m-d'));
            $this->loadModel('LicenseModulePaymentReminderDetail');
            $this->LicenseModulePaymentReminderDetail->recursive = 0;
            $values_payment_reminder_sent = $this->LicenseModulePaymentReminderDetail->find('all', array('conditions' => $condition_reminder_send, 'group' => array('LicenseModulePaymentReminderDetail.org_id')));
            //debug($values_payment_reminder_sent);
            //$this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            //$approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));
//            $values_payment_selected = $this->LicenseModulePaymentDetail->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_deadline_over, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
//            debug($values_payment_selected);
//            
            $condition_pending = array('licensing_state_id' => $thisStateIds[0], 'LicenseModuleStateHistory.state_id' => $thisStateIds[1]);
            $condition_requested = array('licensing_state_id' => $thisStateIds[0], 'LicenseModulePaymentDetail.payment_type_id' => $payment_type_id, 'LicenseModulePaymentDetail.payment_approved' => 0);
            $condition_done = array('licensing_state_id' => $thisStateIds[3], 'LicenseModulePaymentDetail.payment_approved' => 1);

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

        $this->set(compact('org_id', 'user_group_id', 'thisStateIds', 'payment_type_id', 'payment_types', 'licensed_mfi', 'values_payment_deadline_over', 'values_payment_reminder1_send', 'values_payment_selected', 'values_payment_reminder_sent', 'values_payment_done'));
    }

    public function reminder_sendOK($org_id = null, $payment_type_id = null, $next_state_id = null) {

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
                'msg' => 'Invalid state information !'
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

            $payment_type = "Fee";
            if (!empty($payment_type_id)) {
                $payment_types = $this->LicenseModulePaymentDetail->LookupLicensePaymentType->find('list', array('fields' => array('id', 'payment_type'), 'conditions' => array('id' => $payment_type_id), 'recursive' => -1));
                if (!empty($payment_types))
                    $payment_type = $payment_types[$payment_type_id];
            }

            $message_body = "Dear Applicant, " . "\r\n" . "\r\n"
                    . "Your did not pay the $payment_type on time (within deadline). This is the Reminder for you to pay the $payment_type"
                    . ($days > 0 ? " within next $days days." : ".")
                    . "\r\n \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority";

            if ($email->send($message_body)) {
                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $next_state_id,
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('LicenseModuleStateHistory');
                $this->LicenseModuleStateHistory->create();
                $this->LicenseModuleStateHistory->save($org_state_history);

//                `payment_type_id``date_of_sending``date_of_deadline``user_name`
                //'LicenseModulePaymentReminderDetail.is_active' => 1

                $payment_reminder_detail = array(
                    'org_id' => $org_id,
                    'payment_type_id' => $payment_type_id,
                    'date_of_sending' => date('Y-m-d'),
                    'date_of_deadline' => date('Y-m-d', strtotime("+$days days")),
                    'user_name' => $this->Session->read('User.Name'),
                    'is_active' => 1);

                $this->loadModel('LicenseModulePaymentReminderDetail');
                $this->LicenseModulePaymentReminderDetail->updateAll(array('is_active' => 0), array('org_id' => $org_id, 'payment_type_id' => $payment_type_id));
                $this->LicenseModulePaymentReminderDetail->create();
                $this->LicenseModulePaymentReminderDetail->save($payment_reminder_detail);

                if (empty($redirect_url))
                    $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view_reminder');

                $this->redirect($redirect_url);
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
