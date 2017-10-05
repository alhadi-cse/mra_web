<?php

App::uses('AppController', 'Controller');

class LicenseModuleCancelByMraActivityClosingNotifyDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($opt = 'all') {
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
        $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            $next_state_ids = explode('_', $thisStateIds[1]);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $basic_condition = array();
        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleCancelByMraActivityClosingNotifyDetailSent'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMraActivityClosingNotifyDetailSent'];
                $option = $reqData['search_option_sent'];
                $keyword = $reqData['search_keyword_sent'];
            }
            if (!empty($this->request->data['LicenseModuleCancelByMraActivityClosingNotifyDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMraActivityClosingNotifyDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
            }
            if (!empty($option) && !empty($keyword)) {
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
        }

        $pending_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($basic_condition, array('BasicModuleBasicInformation.licensing_state_id' => $next_state_ids));

        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings = array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleCancelByMraActivityClosingNotifyDetail->recursive = 0;
        $completed_values = $this->Paginator->paginate('LicenseModuleCancelByMraActivityClosingNotifyDetail');
        $this->set(compact('org_id', 'user_group_id', 'thisStateIds', 'pending_values', 'completed_values'));
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
        $allDetails = $this->LicenseModuleCancelByMraActivityClosingNotifyDetail->find('first', array('conditions' => array('LicenseModuleCancelByMraActivityClosingNotifyDetail.org_id' => $org_id)));
        $this->set(compact('allDetails'));
    }

    public function notify_about_activity_closing($org_id = null) {
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
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
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
            $notification_details = $this->request->data['LicenseModuleCancelByMraActivityClosingNotifyDetail']['notification_details'];
            $data_to_save = array(
                'org_id' => $org_id,
                'notification_date' => date('Y-m-d'),
                'notification_details' => $notification_details
            );

            $existing_value_condition = array('LicenseModuleCancelByMraActivityClosingNotifyDetail.org_id' => $org_id);
            $existing_values = $this->LicenseModuleCancelByMraActivityClosingNotifyDetail->find('first', array('conditions' => $existing_value_condition));
            if (!empty($existing_values)) {
                $this->LicenseModuleCancelByMraActivityClosingNotifyDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleCancelByMraActivityClosingNotifyDetail->create();
            $saved = $this->LicenseModuleCancelByMraActivityClosingNotifyDetail->save($data_to_save);

            if ($saved) {
                $updatable_state_id = '';
                $this->loadModel('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail');
                $approval_value_condition = array('LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail.org_id' => $org_id);
                $approval_values = $this->LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail->find('first', array('conditions' => $approval_value_condition));

                if (!empty($approval_values)) {
                    if ($approval_values['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id'] == '0') {
                        $updatable_state_id = $next_updatable_states[0];
                    } else if ($approval_values['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id'] == '1') {
                        $updatable_state_id = $next_updatable_states[1];
                    }
                    $basic_info_condition = array('BasicModuleBasicInformation.id' => $org_id);
                    $data_to_save_in_basic_info = array('licensing_state_id' => $updatable_state_id);
                    $this->BasicModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
                    $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
                } else {
                    $message = 'This organization has not yet approved for final cacellation!';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                }
            } else {
                $message = 'Activity closing notification sending failed!';
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
