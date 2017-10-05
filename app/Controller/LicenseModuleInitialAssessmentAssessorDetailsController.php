
<?php

App::uses('AppController', 'Controller');

class LicenseModuleInitialAssessmentAssessorDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view() {

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !in_array(1, $user_group_id)) {
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

        $assessor_group_id = $this->request->query('assessor_group_id');
        if (!empty($assessor_group_id))
            $this->Session->write('Assessor.GroupId', $assessor_group_id);

        $fields = array('LicenseModuleInitialAssessmentAssessorDetail.id',
            'from_form_no', 'to_form_no', 'name_with_designation_and_dept');
        $conditions = array('LicenseModuleInitialAssessmentAssessorDetail.licensing_year' => $current_year);

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleInitialAssessmentAssessorDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleInitialAssessmentAssessorDetail']['search_keyword'];
            $conditions = array_merge($conditions, array("$option LIKE '%$keyword%'"));
        }

//        $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'limit' => 10, 'order' => array('LicenseModuleInitialAssessmentAssessorDetail.id' => 'asc'));
//        $this->Paginator->settings = $this->paginate;
//        $values_assigned = $this->Paginator->paginate('LicenseModuleInitialAssessmentAssessorDetail');
        $this->LicenseModuleInitialAssessmentAssessorDetail->virtualFields['name_with_designation_and_dept'] = $this->LicenseModuleInitialAssessmentAssessorDetail->AdminModuleUserProfile->virtualFields['name_with_designation_and_dept'];

        $values_assigned = $this->LicenseModuleInitialAssessmentAssessorDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LicenseModuleInitialAssessmentAssessorDetail.id' => 'asc')));
//        debug($values_assigned);

        $fields = array('MIN(BasicModuleBasicInformation.form_serial_no) As min_form_no', 'MAX(BasicModuleBasicInformation.form_serial_no) As max_form_no');
        $conditions = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);

        $this->loadModel('BasicModuleBasicInformation');
        $form_serial_nos = $this->BasicModuleBasicInformation->find('first', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => -1));
        $form_serial_no = null;
        if (!empty($form_serial_nos[0])) {
            $form_serial_no = $form_serial_nos[0];
        }

        $this->set(compact('values_assigned', 'form_serial_no'));
    }

    public function assign() {
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

        $fields = array('LicenseModuleInitialAssessmentAssessorDetail.id', 'from_form_no', 'to_form_no', 'name_with_designation_and_dept');
        $conditions = array('LicenseModuleInitialAssessmentAssessorDetail.licensing_year' => $current_year);

        $this->LicenseModuleInitialAssessmentAssessorDetail->virtualFields['name_with_designation_and_dept'] = $this->LicenseModuleInitialAssessmentAssessorDetail->AdminModuleUserProfile->virtualFields['name_with_designation_and_dept'];
        $values_assigned = $this->LicenseModuleInitialAssessmentAssessorDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LicenseModuleInitialAssessmentAssessorDetail.id' => 'asc')));

        $conditions = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $fields = array('MIN(BasicModuleBasicInformation.form_serial_no) As min_form_no', 'MAX(BasicModuleBasicInformation.form_serial_no) As max_form_no');
        $this->loadModel('BasicModuleBasicInformation');
        $form_serial_nos = $this->BasicModuleBasicInformation->find('first', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => -1));
//        debug($form_serial_nos);
        if (!empty($form_serial_nos[0])) {
            $min_form_serial = (int) $form_serial_nos[0]['min_form_no'];
            $max_form_serial = (int) $form_serial_nos[0]['max_form_no'];
        }

        $assessor_group_id = $this->Session->read('Assessor.GroupId');
        $existing_assessor_user_ids = $this->LicenseModuleInitialAssessmentAssessorDetail->find('list', array('fields' => array('assessor_user_id'), 'conditions' => array('licensing_year' => $current_year)));

        $fields = array('AdminModuleUser.id', 'AdminModuleUser.name_with_designation_and_dept');
        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $assessor_group_id, 'AdminModuleUser.activation_status_id' => 1);
        if (!empty($existing_assessor_user_ids))
            $conditions = array_merge($conditions, array("NOT" => array("AdminModuleUser.id" => $existing_assessor_user_ids)));

        $this->loadModel('AdminModuleUser');
        //$this->AdminModuleUser->virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS("<br />", CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>"), CONCAT_WS(", ", AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office))');
        $this->AdminModuleUser->virtualFields['name_with_designation_and_dept'] = $this->AdminModuleUser->AdminModuleUserProfile->virtualFields['name_with_designation_and_dept'];
        $assessor_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0));
//        debug($form_serial_nos);
//        debug($assessor_list);

        $this->set(compact('values_assigned', 'assessor_list', 'min_form_serial', 'max_form_serial'));

        if ($this->request->is('post')) {
            try {
                $this->LicenseModuleInitialAssessmentAssessorDetail->create();
                $newData = $this->request->data['LicenseModuleInitialAssessmentAssessorDetail'];

                $all_org_ids = array();
                $all_org_state_history = array();
                $new_data_to_save = array();
                $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);

                foreach ($newData as $new_data) {
                    $assessor_user_id = $new_data['assessor_user_id'];
                    $from_form_no = $new_data['from_form_no'];
                    $to_form_no = $new_data['to_form_no'];

                    if (!empty($from_form_no) && is_numeric($from_form_no) && !empty($to_form_no) && is_numeric($to_form_no)) {
                        $conditions = array_merge($condition, array('BasicModuleBasicInformation.form_serial_no BETWEEN ? AND ?' => array($from_form_no, $to_form_no)));
                        try {
                            $new_data_to_save = array_merge($new_data_to_save, array(array('assessor_user_id' => $assessor_user_id, 'from_form_no' => $from_form_no, 'to_form_no' => $to_form_no, 'licensing_year' => $current_year)));
                            $org_ids = $this->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id'), 'conditions' => $conditions, 'recursive' => -1));
                        } catch (Exception $ex) {
                            debug($ex);
                        }

                        if (!empty($org_ids)) {
                            $all_org_ids = array_merge($all_org_ids, $org_ids);
                            foreach ($org_ids as $org_id) {
                                if (!empty($org_id)) {
                                    $org_state_history = array(
                                        'org_id' => $org_id,
                                        'state_id' => $thisStateIds[1],
                                        'licensing_year' => $current_year,
                                        'date_of_state_update' => date('Y-m-d'),
                                        'date_of_starting' => date('Y-m-d'),
                                        'user_name' => $this->Session->read('User.Name'));

                                    $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                                }
                            }
                        }
                    }
                }

                if (!empty($new_data_to_save) && !empty($all_org_ids)) {
                    $this->LicenseModuleInitialAssessmentAssessorDetail->create();
                    $done = $this->LicenseModuleInitialAssessmentAssessorDetail->saveAll($new_data_to_save);
                    $conditions = array_merge($condition, array('BasicModuleBasicInformation.id' => $all_org_ids));
                    $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), $conditions);
                    if ($done && !empty($all_org_state_history)) {
                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->saveAll($all_org_state_history);
                        $this->redirect(array('action' => 'view'));
                    }
                } else {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Empty/Invalid Form Serial Number !'
                    );
                    $this->set(compact('msg'));
                }
            } catch (Exception $ex) {
                
            }
        }
    }

    public function re_assign() {
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

        $assessor_group_id = $this->Session->read('Assessor.GroupId');
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

        $fields = array('LicenseModuleInitialAssessmentAssessorDetail.id',
            'from_form_no', 'to_form_no', 'name_with_designation_and_dept',
            'AdminModuleUserProfile.user_id');
        $conditions = array('LicenseModuleInitialAssessmentAssessorDetail.licensing_year' => $current_year);
        $this->LicenseModuleInitialAssessmentAssessorDetail->virtualFields['name_with_designation_and_dept'] = $this->LicenseModuleInitialAssessmentAssessorDetail->AdminModuleUserProfile->virtualFields['name_with_designation_and_dept'];
        $values_assigned = $this->LicenseModuleInitialAssessmentAssessorDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'order' => array('LicenseModuleInitialAssessmentAssessorDetail.id' => 'asc')));

        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $assessor_group_id, 'AdminModuleUser.activation_status_id' => 1);
        $existing_assessor_user_ids = Hash::extract($values_assigned, '{n}.AdminModuleUserProfile.user_id');
        if (!empty($existing_assessor_user_ids))
            $conditions = array_merge($conditions, array("NOT" => array("AdminModuleUser.id" => $existing_assessor_user_ids)));

//        $fields = array('AdminModuleUserProfile.user_id', 'AdminModuleUserProfile.full_name_of_user', 'AdminModuleUserProfile.designation_of_user', 'AdminModuleUserProfile.div_name_in_office');
//        $this->loadModel('AdminModuleUser');
//        $this->AdminModuleUser->recursive = 0;
//        $assessor_list = $this->AdminModuleUser->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0));

        $fields = array('AdminModuleUser.id', 'AdminModuleUser.name_with_designation_and_dept');

        $this->loadModel('AdminModuleUser');
        $this->AdminModuleUser->virtualFields['name_with_designation_and_dept'] = $this->AdminModuleUser->AdminModuleUserProfile->virtualFields['name_with_designation_and_dept'];
        $assessor_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0));

        $fields = array('MIN(BasicModuleBasicInformation.form_serial_no) As min_form_no', 'MAX(BasicModuleBasicInformation.form_serial_no) As max_form_no');
        $conditions = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);

        $this->loadModel('BasicModuleBasicInformation');
        $form_serial_nos = $this->BasicModuleBasicInformation->find('first', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => -1));
        $form_serial_no = null;
        if (!empty($form_serial_nos)) {
            $form_serial_no = $form_serial_nos[0];
        }

        $this->set(compact('values_assigned', 'assessor_list', 'form_serial_no'));

        if ($this->request->is('post')) {
            try {
                $this->LicenseModuleInitialAssessmentAssessorDetail->create();
                $flag = false;
                $isEmpty = false;
                $posted_data = $this->request->data['LicenseModuleInitialAssessmentAssessorDetail'];

                $new_data_lists_to_save = array();
                foreach ($posted_data as $new_data_list) {
                    $assessor_user_id = $new_data_list['assessor_user_id'];
                    $from_form_no = $new_data_list['from_form_no'];
                    $to_form_no = $new_data_list['to_form_no'];

                    if (!empty($from_form_no) && is_numeric($from_form_no) && !empty($to_form_no) && is_numeric($to_form_no)) {
                        $new_data_lists_to_save = array_merge($new_data_lists_to_save, array(array('assessor_user_id' => $assessor_user_id, 'from_form_no' => $from_form_no, 'to_form_no' => $to_form_no, 'licensing_year' => $current_year)));
                    }
                }
                if (!empty($new_data_lists_to_save)) {
                    $this->LicenseModuleInitialAssessmentAssessorDetail->set($new_data_lists_to_save);
                    foreach ($posted_data as $data) {
                        $assessor_user_id = $data['assessor_user_id'];
                        $from_form_no = $data['from_form_no'];
                        $to_form_no = $data['to_form_no'];

                        if (empty($from_form_no) || empty($to_form_no)) {
                            $isEmpty = true;
                            break;
                        } else {
                            if (!empty($assessor_user_id)) {
                                $this->LicenseModuleInitialAssessmentAssessorDetail->updateAll(array('LicenseModuleInitialAssessmentAssessorDetail.from_form_no' => $from_form_no, 'LicenseModuleInitialAssessmentAssessorDetail.to_form_no' => $to_form_no), array('LicenseModuleInitialAssessmentAssessorDetail.assessor_user_id' => $assessor_user_id));
                                $flag = true;
                            }
                        }
                    }

                    if ($flag) {
                        $this->redirect(array('action' => 'view'));
                    }
                    if ($isEmpty) {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => 'Empty Form Serial Number!'
                        );
                        $this->set(compact('msg'));
                    }
                }
            } catch (Exception $ex) {
                
            }
        }
    }

    public function delete($org_id) {

        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
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

        $deleted_data = $this->LicenseModuleInitialAssessmentAssessorDetail->findById($org_id);
        $deleted_data = $deleted_data['LicenseModuleInitialAssessmentAssessorDetail'];

        if (!empty($deleted_data)) {
            $from_form_no = $deleted_data['from_form_no'];
            $to_form_no = $deleted_data['to_form_no'];

            if (!empty($from_form_no) && !empty($to_form_no)) {
                if ($this->LicenseModuleInitialAssessmentAssessorDetail->delete($org_id)) {

                    $current_year = $this->Session->read('Current.LicensingYear');

                    $condition = array(
                        'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1],
                        'BasicModuleBasicInformation.form_serial_no BETWEEN ? AND ?' => array($from_form_no, $to_form_no));

                    $this->loadModel('BasicModuleBasicInformation');
                    $this->BasicModuleBasicInformation->updateAll(array('licensing_state_id' => $thisStateIds[0]), $condition);

                    $org_state_history = array(
                        'org_id' => $org_id,
                        'state_id' => $thisStateIds[0],
                        'licensing_year' => $current_year,
                        'date_of_state_update' => date('Y-m-d'),
                        'date_of_starting' => date('Y-m-d'),
                        'user_name' => $this->Session->read('User.Name'));

                    $this->loadModel('LicenseModuleStateHistory');
                    $this->LicenseModuleStateHistory->create();
                    $this->LicenseModuleStateHistory->save($org_state_history);
                    return $this->redirect(array('action' => 'view'));
                }
            }
        }
    }

}
