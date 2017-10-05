<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleCancelByMfiCancelNotificationDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($opt = 'all') {
        $user_group_id = $this->Session->read('User.GroupIds');
        $this->set(compact('user_group_id'));
        if (empty($user_group_id)) {
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
            $next_state_ids = explode('^', $thisStateIds[1]);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $opt_all = false;
        $basic_condition = array();
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleCancelByMfiCancelNotificationDetailCompleted'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMfiCancelNotificationDetailCompleted'];
            } elseif (!empty($this->request->data['LicenseModuleCancelByMfiCancelNotificationDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMfiCancelNotificationDetailPending'];
            }
            $option = $reqData['search_option'];
            $keyword = $reqData['search_keyword'];

            if (!empty($option) && !empty($keyword))
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
        }

        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $next_state_ids));

        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings = array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMfiCancelNotificationDetail->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleCancelByMfiCancelNotificationDetail');

        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'thisStateIds', 'pending_values', 'completed_values'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $allDetails = $this->LicenseModuleCancelByMfiCancelNotificationDetail->find('first', array('conditions' => array('LicenseModuleCancelByMfiCancelNotificationDetail.org_id' => $org_id)));
        $this->set(compact('allDetails'));
    }

    public function send_notification($org_id = null) {
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

        $next_updatable_states = explode('^', $thisStateIds[1]);
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->loadModel('BasicModuleBasicInformation');
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.license_issue_date'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'];
        $orgName = $orgName . (!empty($org_infos['BasicModuleBasicInformation']['short_name_of_org']) ? ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')' : '');
        $this->set(compact('orgName'));

        if ($this->request->is('post')) {
            $notification_details = $this->request->data['LicenseModuleCancelByMfiCancelNotificationDetail']['notification_details'];
            $data_to_save = array(
                'org_id' => $org_id,
                'notify_date' => date('Y-m-d'),
                'notification_details' => $notification_details
            );

            $existing_value_condition = array('LicenseModuleCancelByMfiCancelNotificationDetail.org_id' => $org_id);
            $existing_values = $this->LicenseModuleCancelByMfiCancelNotificationDetail->find('first', array('conditions' => $existing_value_condition));
            if (!empty($existing_values)) {
                $this->LicenseModuleCancelByMfiCancelNotificationDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleCancelByMfiCancelNotificationDetail->create();
            $saved = $this->LicenseModuleCancelByMfiCancelNotificationDetail->save($data_to_save);

            if ($saved) {
                $updatable_state_id = '';
                $approval_value_condition = array('LicenseModuleCancelByMfiApprovalDetail.org_id' => $org_id);
                $this->loadModel('LicenseModuleCancelByMfiApprovalDetail');
                $approval_values = $this->LicenseModuleCancelByMfiApprovalDetail->find('first', array('conditions' => $approval_value_condition));

                if (!empty($approval_values)) {
                    if (($approval_values['LicenseModuleCancelByMfiApprovalDetail']['is_approved'] == 1) && ($approval_values['LicenseModuleCancelByMfiApprovalDetail']['approval_status_evc'] == 1)) {
                        $updatable_state_id = $next_updatable_states[0];
                    } else if (($approval_values['LicenseModuleCancelByMfiApprovalDetail']['is_approved'] == 1) && ($approval_values['LicenseModuleCancelByMfiApprovalDetail']['approval_status_evc'] == 2)) {
                        $updatable_state_id = $next_updatable_states[1];
                    }

                    $basic_info_condition = array('BasicModuleBasicInformation.id' => $org_id);
                    $data_to_update_in_basic_info = array('licensing_state_id' => $updatable_state_id);
                    $state_updated = $this->BasicModuleBasicInformation->updateAll($data_to_update_in_basic_info, $basic_info_condition);
                    if ($state_updated) {
                        $subject = 'Confirmation of License Cancellation';
                        $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your organization ' . $orgName . ' is not fullfilling the MRA criteria. This is a showcause letter to this organization' . " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                        $is_sent_mail = $this->send_mail($subject, $message_body);
                        $flag = 0;
                        if ($is_sent_mail) {
                            $flag = 1;
                        } else {
                            $message = $this->get('error_message');
                        }
                        if ($flag == 1)
                            $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
                    }
                }
                else {
                    $message = 'This organization has not yet approved for final cacellation';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                }
            } else {
                $message = 'Cancel Notification Sending Failed';
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
            }
        }
    }

}
