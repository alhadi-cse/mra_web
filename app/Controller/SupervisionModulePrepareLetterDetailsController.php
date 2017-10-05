<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class SupervisionModulePrepareLetterDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    //public $paginate = array();
    var $uses = array('SupervisionModuleBasicInformation', 'SupervisionModulePrepareLetterDetail', 'AdminModuleUserProfile');

    public function view($opt = 'all') {
        $user_group_ids = $this->Session->read('User.GroupIds');
        $this->set(compact('user_group_ids'));
        $approval_states = $this->request->query('approval_states');
        if (!empty($approval_states))
            $this->Session->write('Current.ApprovalStates', $approval_states);
        else
            $approval_states = $this->Session->read('Current.ApprovalStates');

        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
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

        $opt_all = false;
        if (!empty($user_groups) && count($user_groups) > 1) {
            $other_user_groups = explode('^', $user_groups[1]);
        }

        //$this->loadModel('SupervisionModuleOrgSelectionDetail');
        //$conditions_for_running_case = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1);
        //$case_lists = $this->SupervisionModuleOrgSelectionDetail->find('list', array('fields' => array('SupervisionModuleOrgSelectionDetail.org_id', 'LookupSupervisionCategory.case_categories'), 'conditions' => $conditions_for_running_case, 'recursive' => 0));

        $options['limit'] = 10;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');

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
            if (!empty($this->request->data['SupervisionModulePrepareLetterDetailSaved'])) {
                $req_data_saved = $this->request->data['SupervisionModulePrepareLetterDetailSaved'];
                $option_saved = $req_data_saved['search_option_saved'];
                $keyword_saved = $req_data_saved['search_keyword_saved'];
                if (!empty($option_saved) && !empty($keyword_saved))
                    $saved_condition = array("$option_saved LIKE '%$keyword_saved%'");
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
        $prepared_but_not_submitted_value_condition = array_merge($saved_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]));
        $completed_value_condition = array_merge($completed_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[2]));

        $options['conditions'] = $pending_value_condition;
        $options['group'] = array('SupervisionModuleBasicInformation.id');
        $this->Paginator->settings = $options;
        $pending_values = $this->Paginator->paginate('SupervisionModuleBasicInformation');

        $options['conditions'] = $prepared_but_not_submitted_value_condition;
        $options['group'] = array('SupervisionModulePrepareLetterDetail.supervision_basic_id');
        $this->Paginator->settings = $options;
        $prepared_but_not_submitted_values = $this->Paginator->paginate('SupervisionModulePrepareLetterDetail');

        $options['conditions'] = $completed_value_condition;
        $this->Paginator->settings = $options;
        $completed_values = $this->Paginator->paginate('SupervisionModulePrepareLetterDetail');

        $this->set(compact('org_id', 'user_group_ids', 'opt_all', 'thisStateIds', 'pending_values', 'prepared_but_not_submitted_values', 'completed_values'));
    }

    public function preview($supervision_basic_id = null) {
        $supervision_case_id = $this->SupervisionModulePrepareLetterDetail->SupervisionModuleBasicInformation->field('supervision_case_id', array('SupervisionModuleBasicInformation.id' => $supervision_basic_id));
        $this->set(compact('supervision_basic_id', 'supervision_case_id'));
    }

    public function details($supervision_basic_id = null) {
        $allDetails = $this->SupervisionModulePrepareLetterDetail->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        $this->set(compact('allDetails'));
    }

    public function findings_details($supervision_basic_id = null) {
        $this->loadModel('SupervisionModuleFindingsDetail');
        $findings_values = $this->SupervisionModuleFindingsDetail->find('all', array('conditions' => array('SupervisionModuleFindingsDetail.supervision_basic_id' => $supervision_basic_id), 'recursive' => -1));
        $this->set(compact('findings_values'));
    }

    public function prepare_letter($supervision_basic_id = null, $opt = null, $back_opt = null) {
        if (empty($supervision_basic_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_states = $this->request->query('approval_states');
        if (empty($approval_states))
            $approval_states = $this->Session->read('Current.ApprovalStates');
        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_ids = $this->request->query('this_state_ids');
        if (empty($this_state_ids))
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

        $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        if (!empty($org_infos['BasicModuleBasicInformation'])) {
            $orgDetail = $org_infos['BasicModuleBasicInformation'];
            $org_id = $orgDetail['id'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        } else {
            $org_id = $orgName = '';
        }

        $field_condition = array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id);
        $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
        $this->SupervisionModuleIssueLetterToMfiDetail->recursive = -1;
        $last_letter_serial_no = $this->SupervisionModuleIssueLetterToMfiDetail->field('letter_serial_no', $field_condition, 'letter_serial_no DESC');

        if (empty($last_letter_serial_no) || $last_letter_serial_no < 1) {
            $letter_serial_no = 1;
        } else {
            $letter_serial_no = $last_letter_serial_no + 1;
        }

        $this->set(compact('org_infos', 'supervision_basic_id', 'orgName', 'org_id', 'letter_serial_no', 'back_opt'));

        if ($this->request->is('post')) {
            if (!is_null($opt) && is_array($thisStateIds) && !empty($thisStateIds[$opt])) {
                $comments_or_notes_of_inspector = $this->request->data['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_inspector'];
                $letters = $this->request->data['SupervisionModulePrepareLetterDetail']['letters'];
                $data_to_save = array(
                    'supervision_basic_id' => $supervision_basic_id,
                    'comments_or_notes_of_inspector' => $comments_or_notes_of_inspector,
                    'letter_serial_no' => $letter_serial_no,
                    'letters' => $letters,
                    'is_approved' => $approvalStates[0],
                    'is_completed' => 0,
                );

                try {
                    $this->loadModel('SupervisionModuleFindingsDetail');
                    if (!empty($last_letter_serial_no)) {
                        $this->SupervisionModulePrepareLetterDetail->recursive = -1;
                        $this->SupervisionModulePrepareLetterDetail->updateAll(array('SupervisionModulePrepareLetterDetail.is_completed' => 1), array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModulePrepareLetterDetail.letter_serial_no' => $last_letter_serial_no));

                        //$this->SupervisionModulePrepareLetterDetail->deleteAll(array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModulePrepareLetterDetail.letter_serial_no' => $last_letter_serial_no), FALSE);
                        //$this->SupervisionModuleFindingsDetail->deleteAll(array('SupervisionModuleFindingsDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleFindingsDetail.letter_serial_no' => $last_letter_serial_no), FALSE);
                    }

                    $this->SupervisionModulePrepareLetterDetail->create();
                    $saved = $this->SupervisionModulePrepareLetterDetail->save($data_to_save);

                    if ($saved) {
                        if (!empty($this->request->data['SupervisionModuleFindingsDetail'])) {
                            $findings_values = $this->request->data['SupervisionModuleFindingsDetail'];
                            foreach ($findings_values as $findings_data) {
                                try {
                                    $findings_data['supervision_basic_id'] = $supervision_basic_id;
                                    $findings_data['letter_serial_no'] = $last_letter_serial_no;
                                    $this->SupervisionModuleFindingsDetail->create();
                                    $this->SupervisionModuleFindingsDetail->save($findings_data);
                                } catch (Exception $ex) {
                                    
                                }
                            }
                        }

                        if (!empty($last_letter_serial_no)) {
                            $letter_condition = array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no' => $last_letter_serial_no);
                            $letter_data_to_update = array('SupervisionModuleIssueLetterToMfiDetail.is_completed' => 1);

                            $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
                            $this->SupervisionModuleIssueLetterToMfiDetail->recursive = -1;
                            $this->SupervisionModuleIssueLetterToMfiDetail->updateAll($letter_data_to_update, $letter_condition);
                        }

                        $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
                        $data_to_save_in_basic_info = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[$opt]);
                        $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
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
                    
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Next State Information !'
                );
                $this->set(compact('msg'));
            }
        }
    }

    public function submit_modified_letter($supervision_basic_id = null, $no_of_letter_issued = null) {
        if (empty($supervision_basic_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_states = $this->Session->read('Current.ApprovalStates');
        if (!empty($approval_states)) {
            $approvalStates = explode('_', $approval_states);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Approval State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
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

        $this->loadModel('SupervisionModuleFindingsDetail');
        $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        if (!empty($org_infos['BasicModuleBasicInformation'])) {
            $orgDetail = $org_infos['BasicModuleBasicInformation'];
            $org_id = $orgDetail['id'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        } else {
            $org_id = $orgName = '';
        }
//        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';
//        $org_id = $org_infos['BasicModuleBasicInformation']['id'];

        $counting_condition = array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id);
        $no_of_letter_issued = $this->serial_generator_by_count('SupervisionModuleIssueLetterToMfiDetail', 'supervision_basic_id', $counting_condition);

        if (empty($no_of_letter_issued) || (!empty($no_of_letter_issued) && $no_of_letter_issued < 1)) {
            $letter_serial_no = 1;
        } else {
            $letter_serial_no = $no_of_letter_issued;
        }

        $this->set(compact('supervision_basic_id', 'orgName', 'org_id', 'letter_serial_no'));

//        $letter_captions = $this->cardinal_to_ordinal_number($letter_serial_no, 'Letter');
//        $this->set(compact('supervision_basic_id', 'orgName', 'org_id', 'letter_captions', 'letter_serial_no'));


        if ($this->request->is(array('put', 'post'))) {
            try {
                $comments_or_notes_of_inspector = $this->request->data['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_inspector'];
                $letters = $this->request->data['SupervisionModulePrepareLetterDetail']['letters'];
                $data_to_update = array(
                    'comments_or_notes_of_inspector' => "\"$comments_or_notes_of_inspector\"", //mysqli_real_escape_string($comments_or_notes_of_inspector), // 
                    'letter_serial_no' => $letter_serial_no,
                    'letters' => "\"$letters\"",
                    'is_approved' => $approvalStates[1],
                    'is_completed' => 0,
                );

                $this->SupervisionModulePrepareLetterDetail->recursive = -1;
                $updated = $this->SupervisionModulePrepareLetterDetail->updateAll($data_to_update, array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id));
                if ($updated) {
                    $findings_details = $this->SupervisionModuleFindingsDetail->find('first', array('conditions' => array('SupervisionModuleFindingsDetail.supervision_basic_id' => $supervision_basic_id), 'recursive' => -1));
                    if (!empty($findings_details)) {
                        $findings_deleted = $this->SupervisionModuleFindingsDetail->deleteAll(array('SupervisionModuleFindingsDetail.supervision_basic_id' => $supervision_basic_id), FALSE);
                    }
                    if (!empty($this->request->data['SupervisionModuleFindingsDetail'])) {
                        $findings_values = $this->request->data['SupervisionModuleFindingsDetail'];
                        foreach ($findings_values as $findings_data) {
                            $findings_data['supervision_basic_id'] = $supervision_basic_id;
                            $this->SupervisionModuleFindingsDetail->create();
                            $findings_data_saved = $this->SupervisionModuleFindingsDetail->save($findings_data);
                        }
                    }
                    $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
                    $data_to_save_in_basic_info = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[2]);
                    $basic_info_updated = $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
                    if ($basic_info_updated) {
                        $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
                    }
                } else {
                    $message = 'Update Failed';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                    return;
                }
            } catch (Exception $ex) {
                
            }
        }

        if (!$this->request->data) {
            $existing_values = $this->SupervisionModulePrepareLetterDetail->find('first', array('conditions' => array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id), 'recursive' => -1));
            $existing_findings_values = $this->SupervisionModuleFindingsDetail->find('all', array('conditions' => array('SupervisionModuleFindingsDetail.supervision_basic_id' => $supervision_basic_id), 'recursive' => -1));
            if (!empty($existing_values['SupervisionModulePrepareLetterDetail'])) {
                $this->request->data['SupervisionModulePrepareLetterDetail'] = $existing_values['SupervisionModulePrepareLetterDetail'];
            }
            if (!empty($existing_findings_values['SupervisionModuleFindingsDetail'])) {
                $existing_findings_values = $existing_findings_values['SupervisionModuleFindingsDetail'];
            }
            $this->set(compact('existing_values', 'existing_findings_values'));
        }
    }

    public function back_to_inspector($supervision_basic_id = null) {
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
        $data_to_save_in_basic_info = array('supervision_state_id' => $thisStateIds[0]);
        $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
        $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
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
        $data_to_save_in_basic_info = array('supervision_state_id' => $thisStateIds[1]);
        $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
        $this->redirect(array('action' => 'view?this_state_ids=' . $this_state_ids));
    }

    public function show_previous_letter($supervision_basic_id = null) {
        $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
        $existing_value_condition = array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id);
        $issued_letter_details = $this->SupervisionModuleIssueLetterToMfiDetail->find('first', array('conditions' => $existing_value_condition, 'order' => array('letter_serial_no' => 'DESC'), 'recursive' => -1));
        $this->set(compact('issued_letter_details'));
    }

    public function show_explanation_against_previous_letter($supervision_basic_id = null) {
        $this->loadModel('SupervisionModuleReplyOrExplanationOfMfiDetail');
        $existing_value_condition = array('SupervisionModuleReplyOrExplanationOfMfiDetail.supervision_basic_id' => $supervision_basic_id);
        $explanation_details = $this->SupervisionModuleReplyOrExplanationOfMfiDetail->find('first', array('conditions' => $existing_value_condition, 'order' => array('letter_id' => 'DESC'), 'recursive' => -1));
        $this->set(compact('explanation_details'));
    }

}
