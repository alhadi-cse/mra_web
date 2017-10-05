<?php

App::uses('AppController', 'Controller');

class AdminModulePeriodDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('AdminModulePeriodDetail.is_current_period' => 'DESC', 'AdminModulePeriodDetail.period_type_id' => 'ASC')
    );

    public function view($opt = null) {
        $mfi_user_groups = array(2, 3);
        if (!empty($mfi_user_groups)) {
            $this->Session->write('MfiViewable.UserGroup', $mfi_user_groups);
        }
        $mfi_viewable_user_groups = $this->Session->read('MfiViewable.UserGroup');
        $user_group_options = $this->AdminModulePeriodDetail->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $mfi_viewable_user_groups)));
        $this->set(compact('user_group_options'));

        if ($this->request->is('post')) {
            $this->paginate = array('order' => array('AdminModulePeriodDetail.is_current_period' => 'DESC'), 'limit' => 10);
            if ($opt == 'all') {
                $this->Session->write('Temp.Paginate', '');
            } elseif ($opt == 'current') {
                $conditions = array('AdminModulePeriodDetail.is_current_period' => 1);
                $this->paginate['conditions'] = $conditions;
                $this->Session->write('Temp.Paginate', $this->paginate);
            } elseif ($opt == 'custom') {
                $user_group_id = $this->request->data['AdminModulePeriodDetail']['user_group_id'];
                $period_type_id = $this->request->data['AdminModulePeriodDetail']['period_type_id'];
                $period_id = $this->request->data['AdminModulePeriodDetail']['period_id'];
                $conditions = array('AdminModulePeriodDetail.user_group_id' => (int) $user_group_id,
                    'AdminModulePeriodDetail.period_type_id' => (int) $period_type_id,
                    'AdminModulePeriodDetail.period_id' => $period_id);
                $this->paginate['conditions'] = $conditions;
                $this->Session->write('Temp.Paginate', $this->paginate);
            }
        }
        $temp_paginate = $this->Session->read('Temp.Paginate');
        if (!empty($temp_paginate)) {
            $this->paginate = $temp_paginate;
        }
        $this->AdminModulePeriodDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('AdminModulePeriodDetail');
        $this->set(compact('values'));
    }

    public function add() {
        $mfi_viewable_user_groups = $this->Session->read('MfiViewable.UserGroup');
        $user_group_options = $this->AdminModulePeriodDetail->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $mfi_viewable_user_groups)));
        $this->set(compact('user_group_options'));
        if ($this->request->is('post')) {

            $user_group_id = $this->request->data['AdminModulePeriodDetail']['user_group_id'];
            $period_type_id = $this->request->data['AdminModulePeriodDetail']['period_type_id'];
            $data_type_ids = $this->request->data['AdminModulePeriodDetail']['data_type_id'];
            $from_date = $this->request->data['AdminModulePeriodList']['from_date'];
            $is_current_period = 0;

            if ($period_type_id == 1) {
                $to_date = date('Y-m-d', strtotime("+1 years -1 days", strtotime($from_date)));
            } elseif ($period_type_id == 2) {
                $to_date = date('Y-m-d', strtotime("+6 months -1 days", strtotime($from_date)));
            } elseif ($period_type_id == 3) {
                $to_date = date('Y-m-d', strtotime("+1 months -1 days", strtotime($from_date)));
            }

            $this->request->data['AdminModulePeriodDetail']['to_date'] = $to_date;
            if (!empty($this->request->data['AdminModulePeriodDetail']['is_current_period'])) {
                $this->request->data['AdminModulePeriodDetail']['is_current_period'] = $is_current_period;
            }
            $is_saved = true;
            $this->loadModel('AdminModulePeriodList');
            $data_to_save_in_period_list = array();
            $data_to_save_in_period_list['type_id'] = $period_type_id;
            $data_to_save_in_period_list['from_date'] = $from_date;
            $data_to_save_in_period_list['to_date'] = $to_date;

            $period_id_values = $this->AdminModulePeriodDetail->find('first', array('fields' => array('AdminModulePeriodList.id'), 'conditions' => array('AdminModulePeriodList.from_date' => $from_date, 'AdminModulePeriodDetail.period_type_id' => $period_type_id)));
            if (!empty($period_id_values)) {
                $period_id = $period_id_values['AdminModulePeriodList']['id'];
            } else {
                $this->AdminModulePeriodList->create();
                $saved_period_list = $this->AdminModulePeriodList->save($data_to_save_in_period_list);

                if ($saved_period_list && !empty($saved_period_list['AdminModulePeriodList']['id'])) {
                    $period_id = $saved_period_list['AdminModulePeriodList']['id'];

                    foreach ($data_type_ids as $data_type_id) {
                        $this->request->data['AdminModulePeriodDetail']['data_type_id'] = $data_type_id;
                        $this->request->data['AdminModulePeriodDetail']['period_id'] = $period_id;
                        $exists_values = $this->AdminModulePeriodDetail->find('first', array(
                            'conditions' => array('AdminModulePeriodDetail.user_group_id' => $user_group_id,
                                'AdminModulePeriodDetail.period_type_id' => $period_type_id,
                                'AdminModulePeriodDetail.data_type_id' => $data_type_id,
                                'AdminModulePeriodDetail.period_id' => $period_id)
                                )
                        );
                        if (!empty($exists_values)) {
                            continue;
                            $is_saved = false;
//                            break;
                        } else {
                            $this->AdminModulePeriodDetail->create();
                            $this->AdminModulePeriodDetail->save($this->request->data);
                        }
                    }
                }
            }

            if (!$is_saved) {
                $message = 'Some data already exists ! ';
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
                return;
            }

            $this->redirect(array('action' => 'view'));
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Licensing Status');
        }
        $mfi_viewable_user_groups = $this->Session->read('MfiViewable.UserGroup');
        $user_group_options = $this->AdminModulePeriodDetail->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $mfi_viewable_user_groups)));
        $this->set(compact('user_group_options'));
        $post = $this->AdminModulePeriodDetail->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->AdminModulePeriodDetail->id = $id;
            if ($this->AdminModulePeriodDetail->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->AdminModulePeriodDetail->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

    public function preview($id = null) {
        $this->set(compact('id'));
    }

    public function details($id = null) {
        $this->AdminModulePeriodDetail->recursive = 0;
        $allDetails = $this->AdminModulePeriodDetail->findById($id);
        $this->set(compact('allDetails'));
    }

    function get_to_date() {
        $this->autoRender = false;
        echo '';

        $to_date = "";
        if (!empty($this->request->data['AdminModulePeriodList']['from_date']) && !empty($this->request->data['AdminModulePeriodDetail']['period_type_id'])) {
            $from_date = $this->request->data['AdminModulePeriodList']['from_date'];
            $period_type_id = $this->request->data['AdminModulePeriodDetail']['period_type_id'];

            if ($period_type_id == 1) {
                $to_date = date('d-m-Y', strtotime("+1 years -1 days", strtotime($from_date)));
            } elseif ($period_type_id == 2) {
                $to_date = date('d-m-Y', strtotime("+6 months -1 days", strtotime($from_date)));
            } elseif ($period_type_id == 3) {
                $to_date = date('d-m-Y', strtotime("+1 months -1 days", strtotime($from_date)));
            }
            echo '  to  ' . $to_date;
        }

        $this->layout = 'ajax';
        //return $to_date;
    }

    function period_type_select() {
        $period_type_options = array('2' => array('1' => 'Yearly', '2' => 'Half-Yearly', '3' => 'Monthly'),
            '3' => array('2' => 'Half-Yearly'));
        $user_group_id = $this->request->data['AdminModulePeriodDetail']['user_group_id'];
        $this->Session->write('Temp.UserGroupId', $user_group_id);
        $period_type_options = $period_type_options[$user_group_id];
        $this->set(compact('period_type_options'));
        $this->layout = 'ajax';
    }

    function show_data_type_list() {
        $this->loadModel('AdminModulePeriodDataType');
        $user_group_id = $this->Session->read('Temp.UserGroupId');
        if (!empty($this->request->data['AdminModulePeriodDetail']['period_type_id'])) {
            $period_type_id = $this->request->data['AdminModulePeriodDetail']['period_type_id'];
            $data_type_options = $this->AdminModulePeriodDataType->find('list', array('fields' => array('AdminModulePeriodDataType.id', 'AdminModulePeriodDataType.data_types'),
                'conditions' => array('AdminModulePeriodDataType.user_group_id' => $user_group_id, 'AdminModulePeriodDataType.period_type_id' => $period_type_id),
                'order' => array('AdminModulePeriodDataType.serial_no' => 'ASC')));
            $this->set(compact('data_type_options'));
        }
        $this->layout = 'ajax';
    }

    function data_type_select() {
        $this->loadModel('AdminModulePeriodDataType');
        $user_group_id = $this->Session->read('Temp.UserGroupId');
        if (!empty($this->request->data['AdminModulePeriodDetail']['period_type_id'])) {
            $period_type_id = $this->request->data['AdminModulePeriodDetail']['period_type_id'];
            $data_type_options = $this->AdminModulePeriodDataType->find('list', array('fields' => array('AdminModulePeriodDataType.id', 'AdminModulePeriodDataType.data_types'), 'conditions' => array('AdminModulePeriodDataType.user_group_id' => $user_group_id, 'AdminModulePeriodDataType.period_type_id' => $period_type_id)));
            $this->set(compact('data_type_options'));
        }
        $this->layout = 'ajax';
    }

    function period_select() {
        $user_group_id = $this->Session->read('Temp.UserGroupId');
        $period_values = array();
        if (!empty($this->request->data['AdminModulePeriodDetail']['period_type_id'])) {
            $period_type_id = $this->request->data['AdminModulePeriodDetail']['period_type_id'];
            $period_values = $this->AdminModulePeriodDetail->find('all', array(
                'conditions' => array('AdminModulePeriodDetail.user_group_id' => $user_group_id, 'AdminModulePeriodDetail.period_type_id' => $period_type_id),
                'group' => array('AdminModulePeriodDetail.period_id'),
            ));
            $period_options = Hash::combine($period_values, '{n}.AdminModulePeriodList.id', '{n}.AdminModulePeriodList.period');
            $this->set(compact('period_options'));
        }
        $this->layout = 'ajax';
    }

    public function set_current_period($id = null) {
        $this->autoRender = false;
        $post = $this->AdminModulePeriodDetail->findById($id);
        $user_group_id = $post['AdminModulePeriodDetail']['user_group_id'];
        $period_type_id = $post['AdminModulePeriodDetail']['period_type_id'];
        $data_type_id = $post['AdminModulePeriodDetail']['data_type_id'];
        $period_id = $post['AdminModulePeriodDetail']['period_id'];
        $conditions_with_status = array();
        $conditions_with_period = array();
        $conditions = array('AdminModulePeriodDetail.user_group_id' => (int) $user_group_id,
            'AdminModulePeriodDetail.period_type_id' => (int) $period_type_id,
            'AdminModulePeriodDetail.data_type_id' => (int) $data_type_id);
        $conditions_with_status = array_merge($conditions, array('is_current_period' => 1));
        $this->AdminModulePeriodDetail->updateAll(array('is_current_period' => 0), $conditions_with_status);
        $conditions_with_period = array_merge($conditions, array('period_id' => $period_id));
        $this->AdminModulePeriodDetail->updateAll(array('is_current_period' => 1), $conditions_with_period);
        return $this->redirect(array('action' => 'view', 'current'));
    }

}
