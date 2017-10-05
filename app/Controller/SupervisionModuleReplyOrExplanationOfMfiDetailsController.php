<?php

App::uses('AppController', 'Controller');

class SupervisionModuleReplyOrExplanationOfMfiDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    var $uses = array('SupervisionModuleBasicInformation', 'SupervisionModuleReplyOrExplanationOfMfiDetail');

    public function view($opt = 'all') {
        $user_group_ids = $this->Session->read('User.GroupIds');
        $this->set(compact('user_group_ids'));
        $this_state_ids = $this->request->query('this_state_ids');
        $next_letter_approval_states = $this->request->query('next_letter_approval_states');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('^', $this_state_ids);
            if (!empty($thisStateIds[1])) {
                $other_state_ids = $thisStateIds[1];
            }
            if (!empty($thisStateIds[0])) {
                $thisStateIds = explode('_', $thisStateIds[0]);
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

        $options['limit'] = 10;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC', 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no' => 'DESC');
        $options['group'] = array('SupervisionModuleBasicInformation.id');


        $pending_condition = array();
        $saved_condition = array();
        $completed_condition = array();

        if ($this->request->is('post')) {
            if (!empty($this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetailCompleted'])) {
                $req_data_completed = $this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetailCompleted'];
                $option_completed = $req_data_completed['search_option_completed'];
                $keyword_completed = $req_data_completed['search_keyword_completed'];
                if (!empty($option_completed) && !empty($keyword_completed))
                    $completed_condition = array("$option_completed LIKE '%$keyword_completed%'");
            }
            if (!empty($this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetailSaved'])) {
                $req_data_saved = $this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetailSaved'];
                $option_saved = $req_data_saved['search_option_saved'];
                $keyword_saved = $req_data_saved['search_keyword_saved'];
                if (!empty($option_saved) && !empty($keyword_saved))
                    $saved_condition = array("$option_saved LIKE '%$keyword_saved%'");
            }
            if (!empty($this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetailPending'])) {
                $req_data_pending = $this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetailPending'];
                $option_pending = $req_data_pending['search_option_pending'];
                $keyword_pending = $req_data_pending['search_keyword_pending'];
                if (!empty($option_pending) && !empty($keyword_pending))
                    $pending_condition = array("$option_pending LIKE '%$keyword_pending%'");
            }
        }

        $pending_value_condition = array_merge($pending_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0], 'SupervisionModuleIssueLetterToMfiDetail.is_completed' => 0));
        $completed_value_condition = array_merge($completed_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1], 'SupervisionModuleIssueLetterToMfiDetail.is_completed' => 2));

        $fields = array('SupervisionModuleBasicInformation.id', 'SupervisionModuleBasicInformation.supervision_state_id',
            'BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleIssueLetterToMfiDetail.id', 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no',
            'SupervisionModuleIssueLetterToMfiDetail.msg_subject', 'SupervisionModuleIssueLetterToMfiDetail.issue_date',
            'SupervisionModuleReplyOrExplanationOfMfiDetail.explanation_giving_date', 'LookupSupervisionCategory.case_categories');

        $options['fields'] = $fields;
        $options['conditions'] = $completed_value_condition;
        $this->Paginator->settings = $options;
        $completed_values = $this->Paginator->paginate('SupervisionModuleReplyOrExplanationOfMfiDetail');

//        debug($completed_values);
//        $exp_given_letter_ids = Hash::extract($completed_values, "{n}.SupervisionModuleReplyOrExplanationOfMfiDetail.letter_id");
//        $exp_given_letter_ids = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->find('list', array('fields' => array('letter_id')));
//        debug($exp_given_letter_ids);
//        $pending_value_condition = array_merge($pending_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]));
//        if (!empty($exp_given_letter_ids))
//            $pending_value_condition['NOT'] = array('SupervisionModuleIssueLetterToMfiDetail.id' => array_unique($exp_given_letter_ids));


        $fields = array('BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleBasicInformation.id', 'SupervisionModuleBasicInformation.supervision_state_id', 'LookupSupervisionCategory.case_categories',
            'SupervisionModuleIssueLetterToMfiDetail.id', 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no', 'SupervisionModuleIssueLetterToMfiDetail.issue_date',
            'SupervisionModuleIssueLetterToMfiDetail.msg_subject', 'SupervisionModuleIssueLetterToMfiDetail.letter_details');

        $options['fields'] = $fields;
        $options['conditions'] = $pending_value_condition;

        $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
        $this->Paginator->settings = $options;
        $pending_values = $this->Paginator->paginate('SupervisionModuleIssueLetterToMfiDetail');

//        debug($pending_values);

        $this->set(compact('org_id', 'user_group_ids', 'opt_all', 'thisStateIds', 'other_state_ids', 'next_letter_approval_states', 'pending_values', 'completed_values'));
    }

    public function preview($supervision_basic_id = null) {

        $allDetails = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        if (!empty($allDetails)) {
            $supervision_case_id = $allDetails['SupervisionModuleBasicInformation']['supervision_case_id'];
        } else {
            $this->loadModel('SupervisionModuleBasicInformation');
            $basicDetails = $this->SupervisionModuleBasicInformation->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
            $supervision_case_id = $basicDetails['SupervisionModuleBasicInformation']['supervision_case_id'];
        }
        $this->set(compact('supervision_basic_id', 'supervision_case_id', 'allDetails'));
    }

    public function details($supervision_basic_id = null) {
        if (empty($supervision_basic_id)) {
            $supervision_basic_id = $this->Session->read('Org.Id');
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleIssueLetterToMfiDetail.issue_date', 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no', 'SupervisionModuleIssueLetterToMfiDetail.msg_subject',
            'SupervisionModuleReplyOrExplanationOfMfiDetail.explanation_giving_date', 'SupervisionModuleReplyOrExplanationOfMfiDetail.explanation_details');

        $allDetails = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->find('all', array('fields' => $fields, 'conditions' => array('SupervisionModuleReplyOrExplanationOfMfiDetail.supervision_basic_id' => $supervision_basic_id), 'order' => array('SupervisionModuleIssueLetterToMfiDetail.letter_serial_no' => 'DESC'), 'recursive' => 0));
        $this->set(compact('allDetails'));
    }

    public function detailsX($supervision_basic_id = null) {
        if (empty($supervision_basic_id)) {
            $supervision_basic_id = $this->Session->read('Org.Id');
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no', 'SupervisionModuleIssueLetterToMfiDetail.msg_subject',
            'SupervisionModuleReplyOrExplanationOfMfiDetail.explanation_giving_date', 'SupervisionModuleReplyOrExplanationOfMfiDetail.explanation_details');
        //$fields = array();

        $allDetails = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->find('first', array('fields' => $fields, 'conditions' => array('SupervisionModuleReplyOrExplanationOfMfiDetail.supervision_basic_id' => $supervision_basic_id), 'recursive' => 0));
        $this->set(compact('allDetails'));
    }

    public function explanation_against_letter($letter_id = null) {
        if (empty($letter_id)) {
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
            $thisStateIds = explode('^', $this_state_ids);
            if (!empty($thisStateIds[0])) {
                $thisStateIds = explode('_', $thisStateIds[0]);
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

        $fields = array('BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleIssueLetterToMfiDetail.id', 'SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id',
            'SupervisionModuleIssueLetterToMfiDetail.issue_date', 'SupervisionModuleIssueLetterToMfiDetail.memo_no',
            'SupervisionModuleIssueLetterToMfiDetail.msg_subject', 'SupervisionModuleIssueLetterToMfiDetail.letter_details');

        $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
        $letter_details = $this->SupervisionModuleIssueLetterToMfiDetail->findById($letter_id, $fields);

//        debug($letter_details);

        $this->set(compact('letter_details'));

        if ($this->request->is('post')) {
            try {
                $supervision_basic_id = $letter_details['SupervisionModuleIssueLetterToMfiDetail']['supervision_basic_id'];
                $expl_delete_condition = array('SupervisionModuleReplyOrExplanationOfMfiDetail.supervision_basic_id' => $supervision_basic_id,
                    'SupervisionModuleReplyOrExplanationOfMfiDetail.letter_id' => $letter_id);

                $this->SupervisionModuleReplyOrExplanationOfMfiDetail->recursive = -1;
                $this->SupervisionModuleReplyOrExplanationOfMfiDetail->deleteAll($expl_delete_condition, false);

                $this->request->data['SupervisionModuleReplyOrExplanationOfMfiDetail']['supervision_basic_id'] = $supervision_basic_id;
                $this->SupervisionModuleReplyOrExplanationOfMfiDetail->recursive = -1;
                $this->SupervisionModuleReplyOrExplanationOfMfiDetail->create();
                $saved = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->save($this->request->data);

                if ($saved) {
                    $letter_condition = array('SupervisionModuleIssueLetterToMfiDetail.id' => $letter_id);
                    $letter_data_to_update = array('SupervisionModuleIssueLetterToMfiDetail.is_completed' => 2);

                    $this->SupervisionModuleIssueLetterToMfiDetail->recursive = -1;
                    $this->SupervisionModuleIssueLetterToMfiDetail->updateAll($letter_data_to_update, $letter_condition);

                    $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
                    $basic_info_data_to_update = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]);

                    $this->SupervisionModuleBasicInformation->recursive = -1;
                    $this->SupervisionModuleBasicInformation->updateAll($basic_info_data_to_update, $basic_info_condition);
                    $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
                } else {
                    $message = 'Saving Failed';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                }
            } catch (Exception $ex) {
                debug($ex);
            }
        }
    }

    public function back_to_previous_state($supervision_basic_id = null, $letter_id = null) {
        $this->autoRender = false;
        if (empty($supervision_basic_id) || empty($letter_id)) {
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
            $thisStateIds = explode('^', $this_state_ids);
            if (!empty($thisStateIds[0])) {
                $thisStateIds = split('_', $thisStateIds[0]);
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

        try {
            $delete_condition = array('SupervisionModuleReplyOrExplanationOfMfiDetail.supervision_basic_id' => $supervision_basic_id,
                'SupervisionModuleReplyOrExplanationOfMfiDetail.letter_id' => $letter_id);

            $this->SupervisionModuleReplyOrExplanationOfMfiDetail->recursive = -1;
            $done = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->deleteAll($delete_condition, false);

            if ($done) {
                $letter_condition = array('SupervisionModuleIssueLetterToMfiDetail.id' => $letter_id);
                $letter_data_to_update = array('SupervisionModuleIssueLetterToMfiDetail.is_completed' => 0);

                $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
                $this->SupervisionModuleIssueLetterToMfiDetail->recursive = -1;
                $this->SupervisionModuleIssueLetterToMfiDetail->updateAll($letter_data_to_update, $letter_condition);

                $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
                $basic_info_data_to_update = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]);

                $this->SupervisionModuleBasicInformation->recursive = -1;
                $this->SupervisionModuleBasicInformation->updateAll($basic_info_data_to_update, $basic_info_condition);
                $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
            }
        } catch (Exception $ex) {
            debug($ex);
        }
    }

    public function back_to_previous_stateX($supervision_basic_id = null) {
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
            $thisStateIds = explode('^', $this_state_ids);
            if (!empty($thisStateIds[0])) {
                $thisStateIds = split('_', $thisStateIds[0]);
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

        $this->SupervisionModuleReplyOrExplanationOfMfiDetail->create();
        $saved = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->save($this->request->data);

        if ($saved) {
            $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
            $data_to_save_in_basic_info = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]);

            $this->SupervisionModuleBasicInformation->recursive = -1;
            $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
            $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
        }

        $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
        $data_to_save_in_basic_info = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]);

        $this->SupervisionModuleBasicInformation->recursive = -1;
        $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
        $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
    }

}
