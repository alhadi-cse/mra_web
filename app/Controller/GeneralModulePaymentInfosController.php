<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class GeneralModulePaymentInfosController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($payment_type_id = null, $this_state_ids = null) {

        if (!empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

        if (empty($payment_type_id))
            return;

        if (empty($this_state_ids))
            $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');


        if ($payment_type_id == 1) {
            if (empty($this_state_ids))
                $this_state_ids = '25_26_27_28';
            $this->redirect(array('action' => 'view_license_fee', '?' => array('this_state_ids' => $this_state_ids, 'payment_type_id' => $payment_type_id)));

//            if (empty($this_state_ids))
//                $this_state_ids = '10_31_32_33';
//            //$this->redirect(array('action' => 'view_license_fee', '?' => array('this_state_ids' => $this_state_ids, 'payment_type_id' => $payment_type_id)));

            return;
        } else if ($payment_type_id == 2) {
            $this->redirect(array('action' => 'view_license', '?' => array('this_state_ids' => $this_state_ids, 'payment_type_id' => $payment_type_id)));
            //$this->redirect(array('action' => 'view_license_fee', '?' => array('this_state_ids' => $this_state_ids, 'payment_type_id' => $payment_type_id)));
            return;
        } else if ($payment_type_id == 3) {
            $this->redirect(array('action' => 'view_license', '?' => array('this_state_ids' => $this_state_ids, 'payment_type_id' => $payment_type_id)));
            //$this->redirect(array('action' => 'view_license_fee', '?' => array('this_state_ids' => $this_state_ids, 'payment_type_id' => $payment_type_id)));
            return;
        }

//        $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id));
//        return;
    }

    public function viewXP() {
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

        $this_state_id = $this->request->query('this_state_id');
        if (!empty($this_state_id))
            $this->Session->write('Current.StateId', $this_state_id);
        else
            $this_state_id = $this->Session->read('Current.StateId');

        $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

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

            $condition_pending = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
            $is_org_exist = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been requested for license fee payment!'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_requested = array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $values_payment_requested = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_requested['GeneralModulePaymentInfo']['id'], $org_id));
                return;
            }

            $condition_done = array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 1);
            $values_payment_done = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_done));
            if (!empty($values_payment_done)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_done['GeneralModulePaymentInfo']['id']));
                return;
            }

            $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id));
            return;

            $values_payment_selected = null;
            $values_payment_pending = null; //$this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_pending));
        } elseif (in_array(1,$user_group_id)) {

            $fields = array('BasicModuleBasicInformation.id', 'form_serial_no', 'full_name_of_org', 'short_name_of_org');
            $condition_pending = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
            $condition_requested = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $condition_done = array('licensing_year' => $current_year, 'licensing_state_id' => $this_state_id + 1, 'GeneralModulePaymentInfo.payment_approved' => 1);

            $this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            $approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));

            if (!empty($approved_org_ids)) {
                $condition_selected = array('BasicModuleBasicInformation.id' => $approved_org_ids, 'licensing_year' => $current_year, 'licensing_state_id' => $this_state_id - 1);

                $values_payment_selected = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
            } else
                $values_payment_selected = null;

            $values_payment_requested = $this->GeneralModulePaymentInfo->find('all', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested))
                $requested_org_ids = Hash::extract($values_payment_requested, '{n}.BasicModuleBasicInformation.id');

            //$this->GeneralModulePaymentInfo->find('list', array('conditions' => $condition_requested));

            if (!empty($requested_org_ids))
                $condition_pending = array_merge($condition_pending, array('NOT' => array('BasicModuleBasicInformation.id' => $requested_org_ids)));
            $values_payment_pending = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            if ($this->request->is('post')) {
                $option = $this->request->data['GeneralModulePaymentInfo']['search_option'];
                $keyword = $this->request->data['GeneralModulePaymentInfo']['search_keyword'];
                $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            }

            $this->paginate = array('conditions' => $condition_done, 'limit' => 10, 'order' => array('full_name_of_org' => 'ASC'));
            $this->GeneralModulePaymentInfo->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values_payment_done = $this->Paginator->paginate('GeneralModulePaymentInfo');
        }

        $this->set(compact('org_id', 'user_group_id', 'payment_type_id', 'values_payment_selected', 'values_payment_pending', 'values_payment_requested', 'values_payment_done'));
    }

    public function view_all() {
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

        $this_state_id = $this->request->query('this_state_id');
        if (!empty($this_state_id))
            $this->Session->write('Current.StateId', $this_state_id);
        else
            $this_state_id = $this->Session->read('Current.StateId');

        $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

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

            $condition_pending = array('BasicModuleBasicInformation.id' => $org_id, 'licensing_year' => $current_year, 'licensing_state_id' => $this_state_id);
            $is_org_exist = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been requested for license fee payment_made!'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_requested = array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $values_payment_requested = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_requested['GeneralModulePaymentInfo']['id'], $org_id));
                return;
            }

            $condition_done = array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 1);
            $values_payment_done = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_done));
            if (!empty($values_payment_done)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_done['GeneralModulePaymentInfo']['id']));
                return;
            }

            $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id));
            return;

            $values_payment_selected = null;
            $values_payment_pending = null;
        } elseif (in_array(1,$user_group_id)) {

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
            $condition_pending = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'licensing_state_id' => $this_state_id, 'LicenseModuleStateHistory.state_id' => $this_state_id);
            $condition_requested = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'licensing_state_id' => $this_state_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $condition_done = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'licensing_state_id' => $this_state_id + 1, 'GeneralModulePaymentInfo.payment_approved' => 1);

            $this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            $approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));

            if (!empty($approved_org_ids)) {
                $condition_selected = array('BasicModuleBasicInformation.id' => $approved_org_ids, 'licensing_year' => $current_year, 'licensing_state_id' => $this_state_id - 1);

                $values_payment_selected = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
            } else
                $values_payment_selected = null;

            $values_payment_requested = $this->GeneralModulePaymentInfo->find('all', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested))
                $requested_org_ids = Hash::extract($values_payment_requested, '{n}.BasicModuleBasicInformation.id');

            if (!empty($requested_org_ids))
                $condition_pending = array_merge($condition_pending, array('NOT' => array('BasicModuleBasicInformation.id' => $requested_org_ids)));
            //$values_payment_pending = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            $fields = array_merge($fields, array('LicenseModuleStateHistory.date_of_deadline'));
            $this->loadModel('LicenseModuleStateHistory');
            $values_payment_pending = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition_pending, 'group' => array('LicenseModuleStateHistory.org_id')));

            if ($this->request->is('post')) {
                $option = $this->request->data['GeneralModulePaymentInfo']['search_option'];
                $keyword = $this->request->data['GeneralModulePaymentInfo']['search_keyword'];
                $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            }

            $this->paginate = array('conditions' => $condition_done, 'limit' => 10, 'order' => array('full_name_of_org' => 'ASC'));
            $this->GeneralModulePaymentInfo->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values_payment_done = $this->Paginator->paginate('GeneralModulePaymentInfo');
        }

        $this->set(compact('org_id', 'user_group_id', 'payment_type_id', 'values_payment_selected', 'values_payment_pending', 'values_payment_requested', 'values_payment_done'));
    }

    public function view_license_fee($payment_type_id = null, $this_state_ids = null) {
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
            if (count($thisStateIds) < 4) {
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

        if (empty($payment_type_id))
            $payment_type_id = $this->request->query('payment_type_id');

        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

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
            $is_org_exist = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            //debug($is_org_exist);
            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been requested for license fee payment !'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_requested = array('GeneralModulePaymentInfo.org_id' => $org_id, 'licensing_state_id' => $thisStateIds[2], 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $values_payment_requested = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_requested, 'order' => array('GeneralModulePaymentInfo.date_of_payment' => 'desc')));
            if (!empty($values_payment_requested)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_requested['GeneralModulePaymentInfo']['id'], $payment_type_id));
                return;
            }

            $condition_done = array('GeneralModulePaymentInfo.org_id' => $org_id, 'licensing_state_id' => $thisStateIds[3], 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 1);
            $values_payment_done = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_done, 'order' => array('GeneralModulePaymentInfo.date_of_payment' => 'desc')));
            if (!empty($values_payment_done)) {
                $this->redirect(array('action' => 'payment_preview', $values_payment_done['GeneralModulePaymentInfo']['id']));
                return;
            }

            $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id, $thisStateIds[2]));
            return;

            $values_payment_selected = null;
            $values_payment_pending = null;
        } elseif (in_array(1,$user_group_id)) {

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

            $this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            $approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));

            if (!empty($approved_org_ids)) {
                $condition_selected = array('BasicModuleBasicInformation.id' => $approved_org_ids, 'licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0]);

                $values_payment_selected = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
            } else
                $values_payment_selected = null;

            $condition_pending = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModuleStateHistory.state_id' => $thisStateIds[1]);
            $condition_requested = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2], 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $condition_done = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[3], 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 1);

            $values_payment_requested = $this->GeneralModulePaymentInfo->find('all', array('conditions' => $condition_requested));

            $fields = array_merge($fields, array('LicenseModuleStateHistory.date_of_deadline'));
            $this->loadModel('LicenseModuleStateHistory');
            $values_payment_pending = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields, 'conditions' => $condition_pending, 'group' => array('LicenseModuleStateHistory.org_id')));

            if ($this->request->is('post')) {
                $option = $this->request->data['GeneralModulePaymentInfo']['search_option'];
                $keyword = $this->request->data['GeneralModulePaymentInfo']['search_keyword'];
                $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            }

            $this->paginate = array('conditions' => $condition_done, 'limit' => 10, 'order' => array('full_name_of_org' => 'ASC'));
            $this->GeneralModulePaymentInfo->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values_payment_done = $this->Paginator->paginate('GeneralModulePaymentInfo');
        }

        $this->set(compact('org_id', 'user_group_id', 'thisStateIds', 'payment_type_id', 'values_payment_selected', 'values_payment_pending', 'values_payment_requested', 'values_payment_done'));
    }

    public function view_reminder() {
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

        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 4) {
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

        $payment_type_id = $this->request->query('payment_type_id');
        if (!empty($payment_type_id))
            $this->Session->write('Payment.TypeId', $payment_type_id);
        else
            $payment_type_id = $this->Session->read('Payment.TypeId');

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
            $is_org_exist = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $condition_pending, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

            if (empty($is_org_exist)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'This Organization has not yet been requested for license fee payment!'
                );
                $this->set(compact('msg'));
                return;
            }

            $condition_requested = array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $values_payment_requested = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_requested));
            if (!empty($values_payment_requested)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_requested['GeneralModulePaymentInfo']['id'], $org_id));
                return;
            }

            $condition_done = array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 1);
            $values_payment_done = $this->GeneralModulePaymentInfo->find('first', array('conditions' => $condition_done));
            if (!empty($values_payment_done)) {
                $this->redirect(array('action' => 'payment_modify', $values_payment_done['GeneralModulePaymentInfo']['id']));
                return;
            }

            $this->redirect(array('action' => 'payment_made', $org_id, $payment_type_id));
            return;

            $values_payment_selected = null;
            $values_payment_deadline_over = null;
        } elseif (in_array(1,$user_group_id)) {

            $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');

            $fields_deadline_over = array_merge($fields, array('LicenseModuleStateHistory.date_of_deadline'));
            $condition_deadline_over = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0], 'LicenseModuleStateHistory.date_of_deadline <' => date('Y-m-d'));

            $this->loadModel('LicenseModuleStateHistory');
            $this->LicenseModuleStateHistory->recursive = 0;
            $values_payment_deadline_over = $this->LicenseModuleStateHistory->find('all', array('fields' => $fields_deadline_over, 'conditions' => $condition_deadline_over, 'group' => array('LicenseModuleStateHistory.org_id')));
            debug($values_payment_deadline_over);



            $fields_reminder1_send = array_merge($fields, array('LicenseModulePaymentReminderDetail.date_of_deadline'));
            $condition_reminder1_send = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);

            //$condition_reminder1_deadline_over = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1], 'LicenseModulePaymentReminderDetail.date_of_deadline <' => date('Y-m-d'));
            $this->loadModel('LicenseModulePaymentReminderDetail');
            $this->LicenseModulePaymentReminderDetail->recursive = 0;
            $values_payment_reminder1_sent = $this->LicenseModulePaymentReminderDetail->find('all', array('fields' => $fields_reminder1_send, 'conditions' => $condition_reminder1_send, 'group' => array('LicenseModulePaymentReminderDetail.org_id')));
            debug($values_payment_reminder1_sent);



            //$this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            //$approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));
//            $values_payment_selected = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('all', array('fields' => $fields, 'conditions' => $condition_deadline_over, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));
//            debug($values_payment_selected);
//            
            $condition_pending = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'LicenseModuleStateHistory.state_id' => $thisStateIds[1]);
            $condition_requested = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[0], 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id, 'GeneralModulePaymentInfo.payment_approved' => 0);
            $condition_done = array('BasicModuleBasicInformation.licensing_year' => $current_year, 'licensing_state_id' => $thisStateIds[3], 'GeneralModulePaymentInfo.payment_approved' => 1);


            $values_payment_requested = $this->GeneralModulePaymentInfo->find('all', array('conditions' => $condition_requested));


            if ($this->request->is('post')) {
                $option = $this->request->data['GeneralModulePaymentInfo']['search_option'];
                $keyword = $this->request->data['GeneralModulePaymentInfo']['search_keyword'];
                $condition_done = array_merge($condition_done, array("$option LIKE '%$keyword%'"));
            }

            $this->paginate = array('conditions' => $condition_done, 'limit' => 10, 'order' => array('full_name_of_org' => 'ASC'));
            $this->GeneralModulePaymentInfo->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values_payment_done = $this->Paginator->paginate('GeneralModulePaymentInfo');
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
            $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, array('BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org'));

            $frmSerialNo = $orgDetails['BasicModuleBasicInformation']['form_serial_no'];
            $mfiName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
            $mfiFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
            $mfiName = ((!empty($mfiName) && !empty($mfiFullName)) ? "$mfiFullName ($mfiName)" : "$mfiFullName$mfiName");

//            $message_body = "Dear Applicant,  \r\n \r\n"
//                    . "Your license application has been approved successfully. Please pay your license fee"
//                    . ($days > 0 ? " within next $days days." : ".")
//                    . "\r\n \r\n"
//                    . "Thanks \r\n"
//                    . "Microcredit Regulatory Authority";

            $message_body = "Dear Applicant, " . "\r\n \r\n"
                    . "Your Organization Name: \"$mfiName\" and Form Serial No.: $frmSerialNo " 
                    . "has been successfully completed the evaluation of license process and selected for "
                    . "a License."
                    //. "with some Terms and Conditions. "
                    . "\r\n"
                    . "Please pay the license fee"
                    . ($days > 0 ? " within next $days days." : ".")
                    . "\r\n  \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority (MRA)";


            if ($email->send($message_body)) {
                if (!empty($next_state_id)) {
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
                }

                $this->redirect(array('action' => 'view', $payment_type_id));
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Message Sending Failed !'
                );
                $this->set(compact('msg'));
            }
        } else {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
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

        if (empty($payment_type_id))
            $payment_type_id = $this->Session->read('Payment.TypeId');

        if (empty($payment_type_id))
            $payment_type_id = '';

        if ($this->request->is('post')) {
            $req_data = $this->request->data;
            if (!empty($req_data)) {
                $req_data = Hash::insert($req_data, 'GeneralModulePaymentInfo.payment_approved', 0);
                $this->GeneralModulePaymentInfo->create();
                if ($this->GeneralModulePaymentInfo->save($req_data)) {

                    if (!empty($next_state_id)) {
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
                    }
                    $this->redirect(array('action' => 'view', $payment_type_id));
                }
            }
        }

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org');
        if (empty($org_id)) {
            $this->loadModel('LicenseModuleLicenseEvaluationAdminApprovalDetail');
            $approved_org_ids = $this->LicenseModuleLicenseEvaluationAdminApprovalDetail->find('list', array('fields' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id'), 'conditions' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.approval_status_id' => 1), 'group' => array('LicenseModuleLicenseEvaluationAdminApprovalDetail.org_id')));
            if (!empty($approved_org_ids)) {
                $condition_selected = array('BasicModuleBasicInformation.id' => $approved_org_ids);
            }
        } else {
            $condition_selected = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if (empty($condition_selected)) {
            $orgNameOptions = array();
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
        } else
            $orgNameOptions = $this->GeneralModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => $fields, 'conditions' => $condition_selected, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id'));

        $paymentTypeOptions = $this->GeneralModulePaymentInfo->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type')));

        $this->set(compact('org_id', 'payment_type_id', 'orgNameOptions', 'paymentTypeOptions'));
    }

    public function payment_modify($id = null, $payment_type_id = null) {

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data;
            if (!empty($posted_data)) {
                $this->GeneralModulePaymentInfo->id = $id;
                if ($this->GeneralModulePaymentInfo->save($posted_data)) {
                    $this->redirect(array('action' => 'view', $posted_data['GeneralModulePaymentInfo']['payment_type_id']));
                    //$this->redirect(array('action' => 'view_license_fee', '?' => array('this_state_ids' => '9_10_11_12', 'payment_type_id' => 1)));
                }
            }
        }

        $existing_data = $this->GeneralModulePaymentInfo->findById($id);
        if (!empty($existing_data)) {

            if (!$this->request->data)
                $this->request->data = $existing_data;

            $org_id = $existing_data['GeneralModulePaymentInfo']['org_id'];
            $payment_type_id = $existing_data['GeneralModulePaymentInfo']['payment_type_id'];


//            $orgNameOptions = $this->GeneralModulePaymentInfo->->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
//            $paymentTypeOptions = $this->GeneralModulePaymentInfo->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type'), 'conditions' => array('LookupPaymentType.id' => $payment_type_id)));
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

        $payment_details = $this->GeneralModulePaymentInfo->findById($id);

        if (!empty($payment_details)) {
            $user_Name = $this->Session->read('User.Name');
            $done = $this->GeneralModulePaymentInfo->updateAll(array('GeneralModulePaymentInfo.payment_approved' => 1, 'GeneralModulePaymentInfo.payment_approved_by' => "'$user_Name'"), array('GeneralModulePaymentInfo.id' => $id));

            if ($done) {
                $org_id = $payment_details['GeneralModulePaymentInfo']['org_id'];
                if (!empty($next_state_id) && !empty($org_id)) {
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
                $this->redirect(array('action' => 'view', $payment_details['GeneralModulePaymentInfo']['payment_type_id']));
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

        $paymentDetails = $this->GeneralModulePaymentInfo->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('paymentDetails'));
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

        $paymentDetails = $this->GeneralModulePaymentInfo->find('first', array('conditions' => array('GeneralModulePaymentInfo.org_id' => $org_id, 'GeneralModulePaymentInfo.payment_type_id' => $payment_type_id)));
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('payment_type_id', 'paymentDetails'));
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $paymentDetails = $this->GeneralModulePaymentInfo->findById($id);
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

        $paymentDetails = $this->GeneralModulePaymentInfo->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('paymentDetails'));
    }

    public function reminder_send($org_id = null, $this_state_id = null, $payment_reminder_type_id = null) {

        if (empty($this_state_id)) {
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
            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->to($to_mail)->subject('1st Reminder for License Fee Payment');

            $this->loadModel('LicenseModuleStateName');
            $stateDetails = $this->LicenseModuleStateName->find('list', array('fields' => array('id', 'deadline_in_days'), 'conditions' => array('id' => $this_state_id)));

            if (!empty($stateDetails) && !empty($stateDetails[$this_state_id]))
                $days = $stateDetails[$this_state_id];
            else
                $days = 0;

            $message_body = "Dear Applicant, " . "\r\n" . "\r\n"
                    . "Your license application has been approved successfully. This is the "
                    . ( $payment_reminder_type_id == 1 ? "1st Reminder for you to pay the license fee" : "Final Reminder for you to pay the license fee with ")
                    . ($days > 0 ? " within next $days days." : ".")
                    . "\r\n \r\n"
                    . "Thanks \r\n"
                    . "Microcredit Regulatory Authority";

            if ($email->send($message_body)) {

                $current_year = $this->Session->read('Current.LicensingYear');

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $this_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                $org_state_history = array(
                    'org_id' => $org_id,
                    'state_id' => $this_state_id,
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
                    'payment_reminder_type_id' => $payment_reminder_type_id,
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
                // Failure, without any exceptions
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
