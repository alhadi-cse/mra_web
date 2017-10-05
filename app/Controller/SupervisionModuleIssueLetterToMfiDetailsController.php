<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class SupervisionModuleIssueLetterToMfiDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    //var $uses = array('SupervisionModuleBasicInformation', 'SupervisionModuleIssueLetterToMfiDetail', 'SupervisionModulePrepareLetterDetail');
    var $uses = array('SupervisionModuleBasicInformation', 'SupervisionModuleIssueLetterToMfiDetail', 'SupervisionModulePrepareLetterDetail', 'AdminModuleUserProfile');

    public function view($opt = 'all') {
        $user_group_ids = $this->Session->read('User.GroupIds');
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

            if (!empty($this->request->data['SupervisionModulePrepareLetterDetailPending'])) {
                $req_data_pending = $this->request->data['SupervisionModulePrepareLetterDetailPending'];
                $option_pending = $req_data_pending['search_option_pending'];
                $keyword_pending = $req_data_pending['search_keyword_pending'];
                if (!empty($option_pending) && !empty($keyword_pending))
                    $pending_condition = array("$option_pending LIKE '%$keyword_pending%'");
            }
        }

        $pending_value_condition = array_merge($pending_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]));
        $completed_value_condition = array_merge($completed_condition, array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds, 'SupervisionModuleIssueLetterToMfiDetail.is_completed' => 0)); // $thisStateIds[1]

        $fields = array('BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleBasicInformation.id', 'SupervisionModuleBasicInformation.supervision_state_id',
            'LookupSupervisionCategory.case_categories', 'SupervisionModulePrepareLetterDetail.letter_serial_no'); //, 'SupervisionModulePrepareLetterDetail.supervision_basic_id');

        $options['fields'] = $fields;
        $options['conditions'] = $pending_value_condition;
        $options['group'] = array('SupervisionModuleBasicInformation.id');
        $this->Paginator->settings = $options;
        $pending_values = $this->Paginator->paginate('SupervisionModulePrepareLetterDetail');

        $fields = array('BasicModuleBasicInformation.license_no', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleBasicInformation.id', 'SupervisionModuleBasicInformation.supervision_state_id', 'LookupSupervisionCategory.case_categories',
            'SupervisionModuleIssueLetterToMfiDetail.id', 'SupervisionModuleIssueLetterToMfiDetail.issue_date', 'SupervisionModuleIssueLetterToMfiDetail.msg_subject',
            'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no', 'SupervisionModuleIssueLetterToMfiDetail.letter_details');

        $options['fields'] = $fields;
        $options['conditions'] = $completed_value_condition;
        $options['group'] = array('SupervisionModuleBasicInformation.id');
        $this->Paginator->settings = $options;
        $completed_values = $this->Paginator->paginate('SupervisionModuleIssueLetterToMfiDetail');

        $this->set(compact('user_group_ids', 'opt_all', 'thisStateIds', 'pending_values', 'completed_values'));
    }

    public function preview($supervision_basic_id = null, $letter_id = null, $issue_status = null) {
        //$this->loadModel('SupervisionModulePrepareLetterDetail');
        $allDetails = $this->SupervisionModulePrepareLetterDetail->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
        if (!empty($allDetails)) {
            $supervision_case_id = $allDetails['SupervisionModuleBasicInformation']['supervision_case_id'];
        } else {
            $this->loadModel('SupervisionModuleBasicInformation');
            $basicDetails = $this->SupervisionModuleBasicInformation->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
            $supervision_case_id = $basicDetails['SupervisionModuleBasicInformation']['supervision_case_id'];
        }
        $this->set(compact('supervision_basic_id', 'supervision_case_id', 'letter_id', 'issue_status'));
    }

    public function details($supervision_basic_id = null, $letter_id = null) {

        $fields = array('SupervisionModuleIssueLetterToMfiDetail.issue_date', 'SupervisionModuleIssueLetterToMfiDetail.msg_subject',
            'SupervisionModuleIssueLetterToMfiDetail.msg_to', 'SupervisionModuleIssueLetterToMfiDetail.to_designation',
            'SupervisionModuleIssueLetterToMfiDetail.to_organization', 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no',
            'SupervisionModuleIssueLetterToMfiDetail.letter_details');

        $conditions = array();
        if ($supervision_basic_id)
            $conditions['SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id'] = $supervision_basic_id;
        if ($letter_id)
            $conditions['SupervisionModuleIssueLetterToMfiDetail.id'] = $letter_id;

        $issued_letter_details = $this->SupervisionModuleIssueLetterToMfiDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => array('SupervisionModuleIssueLetterToMfiDetail.id' => 'desc'), 'recursive' => 0));

        $this->set(compact('issued_letter_details'));

//        if (!empty($letter_id)) {
//            $issued_letter_detail = $this->SupervisionModuleIssueLetterToMfiDetail->findById($letter_id, $fields); //, array('fields' => $fields, 'conditions' => array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id), 'order' => array('SupervisionModuleIssueLetterToMfiDetail.id' => 'desc'), 'recursive' => 0));
//            $issued_letter_details[0] = $issued_letter_detail;
//        } else
    }

    public function details_all($supervision_basic_id = null) {
        //$this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
        $fields = array('BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org',
            'SupervisionModuleIssueLetterToMfiDetail.issue_date', 'SupervisionModuleIssueLetterToMfiDetail.msg_subject',
            'SupervisionModuleIssueLetterToMfiDetail.msg_to', 'SupervisionModuleIssueLetterToMfiDetail.to_designation',
            'SupervisionModuleIssueLetterToMfiDetail.to_organization', 'SupervisionModuleIssueLetterToMfiDetail.letter_serial_no',
            'SupervisionModuleIssueLetterToMfiDetail.letter_details');

        $issued_letter_details = $this->SupervisionModuleIssueLetterToMfiDetail->find('all', array('fields' => $fields, 'conditions' => array('SupervisionModuleIssueLetterToMfiDetail.supervision_basic_id' => $supervision_basic_id), 'order' => array('SupervisionModuleIssueLetterToMfiDetail.id' => 'desc'), 'recursive' => 0));

        $this->set(compact('issued_letter_details'));
    }

    public function input_for_letter($supervision_basic_id = null) {
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
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        } else {
            $orgName = '';
        }

        $existing_value_condition = array('SupervisionModulePrepareLetterDetail.supervision_basic_id' => $supervision_basic_id);
        $letter_details = $this->SupervisionModulePrepareLetterDetail->find('first', array('fields' => array('SupervisionModulePrepareLetterDetail.letter_serial_no', 'SupervisionModulePrepareLetterDetail.letters as letter_details'), 'conditions' => $existing_value_condition));

        $this->loadModel('LookupBasicMraAuthority');
        $mra_authority_name_options = $this->LookupBasicMraAuthority->find('list', array('fields' => array('LookupBasicMraAuthority.id', 'LookupBasicMraAuthority.authority_name')));

        $this->set(compact('orgName', 'supervision_basic_id', 'mra_authority_name_options'));

        if (!$this->request->data && !empty($letter_details['SupervisionModulePrepareLetterDetail'])) {
            $this->request->data['SupervisionModuleIssueLetterToMfiDetail'] = $letter_details['SupervisionModulePrepareLetterDetail'];
        }
    }

    public function preview_letter($supervision_basic_id = null) {
        if ($this->request->is('post')) {
            $requested_data = $this->request->data;
            if (!empty($this->request->data))
                $this->Session->write('Request.Data', $requested_data);
            $authority_id = $requested_data['SupervisionModuleIssueLetterToMfiDetail']['mra_authority_id'];
            $this->loadModel('LookupBasicMraAuthority');
            $authority_info = $this->LookupBasicMraAuthority->findById($authority_id);
            $this->set(compact('requested_data', 'authority_info', 'supervision_basic_id'));
        }
    }

    public function send_letter($supervision_basic_id = null) {

        if ($this->request->is('post')) {
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

            $requested_data = $this->Session->read('Request.Data');
            $requested_data['SupervisionModuleIssueLetterToMfiDetail']['supervision_basic_id'] = $supervision_basic_id;
            $org_infos = $this->SupervisionModuleBasicInformation->find('first', array('conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 0));
            if (!empty($org_infos['BasicModuleBasicInformation'])) {
                $org_id = $org_infos['BasicModuleBasicInformation']['id'];
                $orgDetail = $org_infos['BasicModuleBasicInformation'];
                $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
            } else {
                $org_id = $orgName = '';
            }

//            $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'] . ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')';
//            $org_id = $org_infos['BasicModuleBasicInformation']['id'];
            $msg_subject = $requested_data['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];

            try {
                $this->AdminModuleUserProfile->recursive = 0;
                $user_email_infos = $this->AdminModuleUserProfile->find('first', array('fields' => array('AdminModuleUserProfile.email'), 'conditions' => array('AdminModuleUserProfile.org_id' => $org_id)));
                if (!empty($user_email_infos)) {
                    $mail_to = $user_email_infos['AdminModuleUserProfile']['email'];
                    $subject = 'Letter from MRA';
                }
                $data_to_save = $requested_data['SupervisionModuleIssueLetterToMfiDetail'];
                $this->loadModel('SupervisionModuleIssueLetterToMfiDetail');
                $this->SupervisionModuleIssueLetterToMfiDetail->create();
                $saved = $this->SupervisionModuleIssueLetterToMfiDetail->save($data_to_save);

                if ($saved) {
                    $basic_info_condition = array('SupervisionModuleBasicInformation.id' => $supervision_basic_id);
                    $data_to_save_in_basic_info = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]);
                    $this->SupervisionModuleBasicInformation->updateAll($data_to_save_in_basic_info, $basic_info_condition);
                    $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your organization ' . $orgName . ' is not fullfilling the MRA criteria. This is a showcause letter to this organization' . " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                    $is_sent_mail = $this->send_mail($mail_to, $msg_subject, $message_body);
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
                    $message = 'Letter sending failed';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                }
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }
        }
    }

}
