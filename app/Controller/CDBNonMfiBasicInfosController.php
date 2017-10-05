<?php

App::uses('AppController', 'Controller');

class CDBNonMfiBasicInfosController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');

    public function view() {
        $user_group_ids = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');
        $condition = array();
        if (!empty($org_id)) {
            $condition['CDBNonMfiBasicInfo.id'] = $org_id;
        }

        $this->set(compact('org_id', 'user_group_ids'));
        if ($this->request->is('post')) {
            $option = $this->request->data['CDBNonMfiBasicInfo']['search_option'];
            $keyword = $this->request->data['CDBNonMfiBasicInfo']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($org_id)) {
                    $condition = array("AND" => array("$option LIKE '%$keyword%'", 'CDBNonMfiBasicInfo.id' => $org_id));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            } else {
                $condition = array();
            }
        }

        $this->paginate = array('conditions' => $condition, 'recursive' => 0, 'limit' => 10, 'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC'));
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('CDBNonMfiBasicInfo');
        $this->set(compact('values'));
    }

    public function add() {
        $non_mfi_types = $this->CDBNonMfiBasicInfo->LookupCDBNonMfiType->find('list', array('fields' => array('id', 'type_name')));
        $ministry_or_authority_options = $this->CDBNonMfiBasicInfo->LookupCDBNonMfiMinistryAuthorityName->find('list', array('fields' => array('id', 'name_of_ministry_or_authority')));
        $this->set(compact('non_mfi_types', 'ministry_or_authority_options'));
        if ($this->request->is('post')) {
            $this->CDBNonMfiBasicInfo->create();
            if ($this->CDBNonMfiBasicInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        $post = $this->CDBNonMfiBasicInfo->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }

        $non_mfi_types = $this->CDBNonMfiBasicInfo->LookupCDBNonMfiType->find('list', array('fields' => array('id', 'type_name')));
        $ministry_or_authority_options = $this->CDBNonMfiBasicInfo->LookupCDBNonMfiMinistryAuthorityName->find('list', array('fields' => array('LookupCDBNonMfiMinistryAuthorityName.id', 'LookupCDBNonMfiMinistryAuthorityName.name_of_ministry_or_authority')));
        $this->set(compact('non_mfi_types', 'ministry_or_authority_options'));
        if ($this->request->is(array('post', 'put'))) {
            $this->CDBNonMfiBasicInfo->id = $id;
            if ($this->CDBNonMfiBasicInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }

        $basicInfoDetails = $this->CDBNonMfiBasicInfo->findById($id);
        if (!$basicInfoDetails) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        $this->set(compact('basicInfoDetails'));
    }

}
