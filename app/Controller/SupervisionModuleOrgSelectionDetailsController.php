<?php

App::uses('AppController', 'Controller');

class SupervisionModuleOrgSelectionDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('SupervisionModuleOrgSelectionDetail.is_running_case' => 'DESC')
    );

    public function view($opt = null) {
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
        if ($this->request->is('post')) {
            $this->paginate = array('order' => array('SupervisionModuleOrgSelectionDetail.is_running_case' => 'DESC'), 'limit' => 10);
            if ($opt == 'all') {
                $this->Session->write('Temp.Paginate', '');
            } elseif ($opt == 'current') {
                $conditions = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1);
                $this->paginate['conditions'] = $conditions;
                $this->Session->write('Temp.Paginate', $this->paginate);
            } elseif ($opt == 'custom') {
                $search_keyword = $this->request->data['SupervisionModuleOrgSelectionDetail']['search_keyword'];
                $conditions = array('OR' => array('BasicModuleBasicInformation.full_name_of_org' => $search_keyword,
                        'BasicModuleBasicInformation.short_name_of_org' => $search_keyword,
                        'BasicModuleBasicInformation.license_no' => $search_keyword));
                $this->paginate['conditions'] = $conditions;
                $this->Session->write('Temp.Paginate', $this->paginate);
            }
        }
        $temp_paginate = $this->Session->read('Temp.Paginate');
        if (!empty($temp_paginate)) {
            $this->paginate = $temp_paginate;
        }
        $this->SupervisionModuleOrgSelectionDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('SupervisionModuleOrgSelectionDetail');
        $this->set(compact('values'));
    }

    public function add() {
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

        $org_id = "";
        $orgName = "";
        $org_conditions = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $org_name_options = $this->SupervisionModuleOrgSelectionDetail->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'), 'conditions' => $org_conditions, 'order' => 'BasicModuleBasicInformation.full_name_of_org'));
        $case_category_options = $this->SupervisionModuleOrgSelectionDetail->LookupSupervisionCategory->find('list', array('fields' => array('LookupSupervisionCategory.id', 'LookupSupervisionCategory.case_categories')));
        $this->set(compact('org_name_options', 'org_id', 'orgName', 'case_category_options'));

        if ($this->request->is('post')) {
            try {
                $org_id = $this->request->data['SupervisionModuleOrgSelectionDetail']['org_id'];
                $supervision_category_id = $this->request->data['SupervisionModuleOrgSelectionDetail']['supervision_category_id'];
                $from_date = $this->request->data['SupervisionModuleOrgSelectionDetail']['from_date'];
                $existing_value_condition = array('SupervisionModuleOrgSelectionDetail.org_id' => $org_id, 'supervision_category_id' => $supervision_category_id, 'from_date' => $from_date);
                $existing_values = $this->SupervisionModuleOrgSelectionDetail->find('first', array('conditions' => $existing_value_condition));

                if (empty($existing_values)) {
                    $this->request->data['SupervisionModuleOrgSelectionDetail']['is_running_case'] = 0;
                    $this->SupervisionModuleOrgSelectionDetail->create();
                    $saved = $this->SupervisionModuleOrgSelectionDetail->save($this->request->data);
                    if ($saved) {
                        $data_to_save_in_basic_info['supervision_case_id'] = $saved['SupervisionModuleOrgSelectionDetail']['id'];
                        $data_to_save_in_basic_info['org_id'] = $org_id;
                        $data_to_save_in_basic_info['supervision_state_id'] = $thisStateIds[1];
                        $this->loadModel('SupervisionModuleBasicInformation');
                        $this->SupervisionModuleBasicInformation->create();
                        $saved = $this->SupervisionModuleBasicInformation->save($data_to_save_in_basic_info);
                        $this->redirect(array('action' => 'view'));
                    }
                } else {
                    $message = 'Data already exists! ';
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                    return;
                }
            } catch (Exception $ex) {
                //debug($ex->getMessage()); 
            }
        }
    }

    public function preview($id = null) {
        $this->set(compact('id'));
    }

    public function details($id = null) {
        $fields = array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no',
            'LookupSupervisionCategory.case_categories', 'SupervisionModuleOrgSelectionDetail.supervision_reason',
            'SupervisionModuleOrgSelectionDetail.from_date', 'SupervisionModuleOrgSelectionDetail.is_running_case');

        $this->SupervisionModuleOrgSelectionDetail->recursive = 0;
        $allDetails = $this->SupervisionModuleOrgSelectionDetail->findById($id, $fields);
        $this->set(compact('allDetails'));
    }

    public function set_status($id = null, $status_id = null, $supervision_state_id = null) {
        $this->autoRender = false;
        $post = $this->SupervisionModuleOrgSelectionDetail->findById($id);
        $org_id = $post['SupervisionModuleOrgSelectionDetail']['org_id'];
        $conditions_to_update = array('SupervisionModuleOrgSelectionDetail.id' => $id);
        if (!empty($post)) {
            $done = $this->SupervisionModuleOrgSelectionDetail->updateAll(array('is_running_case' => $status_id), $conditions_to_update);
            if ($done) {
                $this->loadModel('SupervisionModuleBasicInformation');
                $conditions_to_update_basic = array('SupervisionModuleBasicInformation.org_id' => $org_id);
                if (!empty($supervision_state_id)) {
                    $this->SupervisionModuleBasicInformation->updateAll(array('supervision_state_id' => $supervision_state_id), $conditions_to_update_basic);
                }
                return $this->redirect(array('action' => 'view', 'current'));
            }
        }
    }

}
