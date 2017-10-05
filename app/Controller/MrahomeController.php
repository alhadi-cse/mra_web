<?php

App::uses('AppController', 'Controller');

class MrahomeController extends AppController {

    public function home() {
        if (!$this->request->is('ajax')) {
            $this->layout = "mra_default";
        }
        return;
    }

    public function home_info() {
        return;
    }

    public function user_info() {
        return;
    }

    public function home_info_x() {
        $user_id = $this->Session->read('User.Id');
        $user_group_id = $this->Session->read('User.GroupId');

        $fields = array('AdminModuleUser.user_name', 'AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.full_name_of_user',
            'AdminModuleUserProfile.designation_of_user', 'AdminModuleUserProfile.div_name_in_office',
            'AdminModuleUserProfile.org_name', 'AdminModuleUserProfile.mobile_no', 'AdminModuleUserProfile.email',
            'AdminModuleUserGroup.group_name', 'LookupUserCommitteeMemberType.committee_member_type');

        $this->loadModel('AdminModuleUser');
        $user_infos = $this->AdminModuleUser->findById($user_id, $fields);

        $org_id = $user_infos['AdminModuleUserProfile']['org_id'];
        $fields = array('full_name_of_org', 'short_name_of_org', 'license_no', 'form_serial_no', 'date_of_application');

        $this->loadModel('BasicModuleBasicInformation');
        $this->BasicModuleBasicInformation->recursive = -1;
        $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, $fields);

        $this->set(compact('user_infos', 'orgDetails'));


        if ($this->request->is('post')) {
            if (!empty($this->request->data['PeriodSelection']['selected_period'])) {
                $this->Session->write('Current.DataPeriod', $this->request->data['PeriodSelection']['selected_period']);
                $msg = array(
                    'type' => 'success',
                    'title' => 'Data Period Set',
                    'msg' => 'Data period set successfully !'
                );
                $this->set(compact('msg'));
            } else {
                $this->Session->write('Current.DataPeriod', '');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Data period has not selected !'
                );
                $this->set(compact('msg'));
            }
        }

        $selected_period = $this->Session->read('Current.DataPeriod');
        $this->loadModel('AdminModulePeriodDetail');
        $period_list = $this->AdminModulePeriodDetail->find('list', array('fields' => array('id', 'period'), 'conditions' => array('user_group_id' => $user_group_id), 'group' => 'period'));

        $this->set(compact('selected_period', 'period_list'));
    }

}
