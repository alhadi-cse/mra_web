<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleCancelByMraShowCauseDetailsController extends AppController {

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
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $basic_condition = $condition_completed = $condition_pending = array();
        $opt_all = false;
        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $basic_condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleCancelByMraShowCauseDetailCompleted'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMraShowCauseDetailCompleted'];
                $option = $reqData['search_option_completed'];
                $keyword = $reqData['search_keyword_completed'];

                if (!empty($option) && $keyword)
                    $condition_completed = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
            if (!empty($this->request->data['LicenseModuleCancelByMraShowCauseDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleCancelByMraShowCauseDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
                if (!empty($option) && $keyword)
                    $condition_pending = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
            }
        }

        $pending_value_condition = array_merge($condition_pending, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($condition_completed, array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]));
//        $completed_value_condition = array_merge($basic_condition, array('LicenseModuleCancelByMraShowCauseDetail.org_id' => 376));
        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings = array('conditions' => $pending_value_condition, 'limit' => 10);
        $this->BasicModuleBasicInformation->recursive = -1;
        $pending_values = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $completed_values = $this->Paginator->paginate('LicenseModuleCancelByMraShowCauseDetail');

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
        $allDetails = $this->LicenseModuleCancelByMraShowCauseDetail->find('first', array('conditions' => array('LicenseModuleCancelByMraShowCauseDetail.org_id' => $org_id)));
        $this->set(compact('allDetails'));
    }

    public function show_cause_for_cancellation($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
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
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
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
            $show_cause_reasons = $this->request->data['LicenseModuleCancelByMraShowCauseDetail']['show_cause_reasons'];
            $existing_value_condition = array('LicenseModuleCancelByMraShowCauseDetail.org_id' => $org_id);
            $existing_values = $this->LicenseModuleCancelByMraShowCauseDetail->find('first', array('conditions' => $existing_value_condition));

            if (!empty($existing_values)) {
                $data_to_save = array(
                    'show_cause_date' => "'" . date('Y-m-d') . "'",
                    'show_cause_reasons' => "'" . $show_cause_reasons . "'"
                );
                $saved = $this->LicenseModuleCancelByMraShowCauseDetail->updateAll($data_to_save, $existing_value_condition);
            } else {
                $data_to_save = array(
                    'org_id' => $org_id,
                    'show_cause_date' => date('Y-m-d'),
                    'show_cause_reasons' => $show_cause_reasons
                );
                $this->LicenseModuleCancelByMraShowCauseDetail->create();
                $saved = $this->LicenseModuleCancelByMraShowCauseDetail->save($data_to_save);
            }
            if ($saved) {
                $basic_info_condition = array('BasicModuleBasicInformation.id' => $org_id);
                $data_to_save_in_basic_info = array('licensing_state_id' => $thisStateIds[1]);
                $this->BasicModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);

                $subject = 'Show casue for License Cancellation';
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
            else {
                $message = 'Show cause sending failed';
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
