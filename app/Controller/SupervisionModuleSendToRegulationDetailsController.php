<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class SupervisionModuleSendToRegulationDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();
    var $uses = array('SupervisionModuleBasicInformation');

    public function view($opt = 'all') {
        $user_group_ids = $this->Session->read('User.GroupIds');
        $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (!empty($thisStateIds[1])) {
                $otherStateIds = explode('^', $thisStateIds[1]);
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

        $opt_all = false;
        if (!empty($user_groups) && count($user_groups) > 1) {
            $other_user_groups = explode('^', $user_groups[1]);
        }
        $this->loadModel('SupervisionModuleOrgSelectionDetail');
        $conditions_for_running_case = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1);
        $case_lists = $this->SupervisionModuleOrgSelectionDetail->find('list', array('fields' => array('SupervisionModuleOrgSelectionDetail.org_id', 'LookupSupervisionCategory.case_categories'), 'conditions' => $conditions_for_running_case, 'recursive' => 0));

        $options['limit'] = 10;
        $pending_condition = array();
        $saved_condition = array();
        $completed_condition = array();

        if ($this->request->is('post')) {
            if (!empty($this->request->data['SupervisionModulePrepareLetterDetailCompleted'])) {
                $req_data_completed = $this->request->data['SupervisionModulePrepareLetterDetailCompleted'];
                $option_completed = $req_data_completed['search_option_completed'];
                $keyword_completed = $req_data_completed['search_keyword_completed'];
                if (!empty($option_completed) && !empty($keyword_completed))
                    $completed_condition = array("$option_completed LIKE '%$keyword_completed%'");
            }

            if (!empty($this->request->data['SupervisionModulePrepareLetterDetailPending'])) {
                $req_data_pending = $this->request->data['SupervisionModulePrepareLetterDetailPending'];
                $option_pending = $req_data_pending['search_option_pending'];
                $keyword_pending = $req_data_pending['search_keyword_pending'];
                if (!empty($option_pending) && !empty($keyword_pending))
                    $pending_condition = array("$option_pending LIKE '%$keyword_pending%'");
            }
        }

        $pending_value_condition = array_merge($pending_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($completed_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $otherStateIds[0]));

        $this->paginate = $options;
        $this->paginate['conditions'] = $pending_value_condition;
        $this->Paginator->settings = $this->paginate;
        $pending_values = $this->Paginator->paginate('SupervisionModuleBasicInformation');
        //debug($pending_values);

        $this->paginate = $options;
        $this->paginate['conditions'] = $completed_value_condition;
        $this->Paginator->settings = $this->paginate;
        $completed_values = $this->Paginator->paginate('SupervisionModuleBasicInformation');
        //debug($completed_values);

        $this->set(compact('case_lists', 'org_id', 'user_group_ids', 'opt_all', 'thisStateIds', 'otherStateIds', 'pending_values', 'completed_values'));
    }

    public function send_to_regulation($supervision_basic_id = null, $letter_serial_no = null) {
        $this->autoRender = false;
        if (empty($supervision_basic_id)) {
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
            if (!empty($thisStateIds[1])) {
                $otherStateIds = explode('^', $thisStateIds[1]);
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
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
        $data_to_update_in_basic_info = array('SupervisionModuleBasicInformation.supervision_state_id' => $otherStateIds[0]);
        $updated = $this->SupervisionModuleBasicInformation->updateAll($data_to_update_in_basic_info, $basic_info_condition);
        if ($updated) {
            if (!empty($letter_serial_no)) {
                $letter_condition = array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no' => $letter_serial_no);
                $letter_data_to_update = array('SupervisionModuleIssueLetterToMfiDetail.is_completed' => 1);

                $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
                $this->SupervisionModuleIssueLetterToMfiDetail->recursive = -1;
                $this->SupervisionModuleIssueLetterToMfiDetail->updateAll($letter_data_to_update, $letter_condition);
            }
            $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
        }
    }

    public function back_to_previous_state($supervision_basic_id = null) {
        $this->autoRender = false;
        if (empty($supervision_basic_id)) {
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
            $thisStateIds = split('_', $this_state_ids);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
        $data_to_update_in_basic_info = array('supervision_state_id' => $thisStateIds[0]);
        $this->SupervisionModuleBasicInformation->updateAll($data_to_update_in_basic_info, $basic_info_condition);
        $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
    }

}
