<?php

App::uses('AppController', 'Controller');

class BasicModuleRejectionInformationsController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($opt = null, $mode = null) {

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $opt_all = false;
        if ($user_group_id && in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');
        
        if (!empty($org_id)) {
            $condition = array('BasicModuleRejectionInformation.org_id' => $org_id);                
        } else {
            $condition = array();
        }
        
        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleRejectionInformation']['search_option'];
            $keyword = $this->request->data['BasicModuleRejectionInformation']['search_keyword'];
            
            if(!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array("AND" => array("$option LIKE '%$keyword%'", $condition));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }
        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all'));

        $this->paginate = array(
            'limit' => 10,
            'order' => array('BasicModuleBasicInformation.full_name_of_org' => 'asc'),
            'conditions' => $condition);

        $this->BasicModuleRejectionInformation->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleRejectionInformation');

        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['BasicModuleRejectionInformation']['id'];

            if ($mode && $mode == 'edit') {
                $this->redirect(array('action' => 'edit', $data_id, $org_id));
            } else {
                $this->redirect(array('action' => 'details', $data_id));
            }
            return;
        }

        $this->set(compact('values'));
    }
    
    public function add() {

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');

        if ((empty($org_id) && !empty($user_group_id) && !in_array(1,$user_group_id)) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $orgNameOptions = $this->BasicModuleRejectionInformation->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $options = $this->BasicModuleRejectionInformation->QuestionOnRejectionStatus->find('list', array('fields' => array('QuestionOnRejectionStatus.id', 'QuestionOnRejectionStatus.yes_no_status')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions','options'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['BasicModuleRejectionInformation']) {
                if(empty($reqData['BasicModuleRejectionInformation']['org_id']) && !empty($org_id))
                    $reqData['BasicModuleRejectionInformation']['org_id'] = $org_id;
                                        
                $existing_values = $this->BasicModuleRejectionInformation->find('first', array('recursive' => -1, 'conditions' => array('org_id' => $org_id)));
                if(!empty($existing_values)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Information already exists!'
                    );
                    $this->set(compact('msg'));
                    return;
                }
                else {                        
                    $this->BasicModuleRejectionInformation->create();
                    $newData = $this->BasicModuleRejectionInformation->save($reqData);

                    if ($newData) {
                        $data_id = $newData['BasicModuleRejectionInformation']['id'];
                        $this->Session->write('Data.Id', $data_id);
                        $this->Session->write('Data.Mode', 'update');
                        //$this->redirect(array('action' => 'preview', $data_id));
                        $this->redirect(array('action' => 'view'));
                    }
                }
            }        
        }
    }

    public function edit($id = null, $org_id = null, $back_opt = null) {

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');            
        }
        
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($id) || empty($org_id) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($id)) {
                $msg['msg'] = 'Invalid Branch data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->BasicModuleRejectionInformation->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $options = $this->BasicModuleRejectionInformation->QuestionOnRejectionStatus->find('list', array('fields' => array('QuestionOnRejectionStatus.id', 'QuestionOnRejectionStatus.yes_no_status')));
            $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions','options'));

            $post = $this->BasicModuleRejectionInformation->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid BranchInfo Information');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid branchInfo information !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->BasicModuleRejectionInformation->id = $id;
            if ($this->BasicModuleRejectionInformation->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }
    
    public function individual_preview() {
        try {
            if (empty($org_id)) {
                $org_id = $this->Session->read('Org.Id');
                if (empty($org_id)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Invalid organization information !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
            }
            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->recursive = 2;
            $allDetails = $this->BasicModuleBasicInformation->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
            $mfiDetails = $allDetails['BasicModuleBasicInformation'];
            $allRejectionDetails = $allDetails['BasicModuleRejectionInformation'];             
            $this->set(compact('org_id','mfiDetails','allRejectionDetails'));
        }
        catch(Exception $ex) {
            debug($ex->getMessage());
        }
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function details($id = null) { 
        $this->BasicModuleRejectionInformation->recursive = 
        $allDetails = $this->BasicModuleRejectionInformation->findById($id);        
        $this->set(compact('allDetails'));
    }
}