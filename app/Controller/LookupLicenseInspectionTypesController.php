<?php

App::uses('AppController', 'Controller');

class LookupLicenseInspectionTypesController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
//    public $paginate = array('limit' => 10, 'order' => array('LookupLicenseInspectionType.serial_no' => 'ASC'));

    public function view() {
//        if ($this->request->is('post')) {
//            $option = $this->request->data['LookupLicenseInspectionType']['search_option'];
//            $keyword = $this->request->data['LookupLicenseInspectionType']['search_keyword'];
//            $condition = array("$option LIKE '%$keyword%'");
//
//            $this->paginate = array('conditions' => $condition, 'limit' => 10, 'order' => array('LookupLicenseInspectionType.serial_no' => 'ASC'));
//        }
//
//        $this->LookupLicenseInspectionType->recursive = 0;
//        $this->Paginator->settings = $this->paginate;
        
        
        $paging_option = array('limit' => 10, 'order' => array('LookupLicenseInspectionType.serial_no' => 'ASC'));

        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseInspectionType']['search_option'];
            $keyword = $this->request->data['LookupLicenseInspectionType']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                $paging_option = array_merge($paging_option, array('conditions' => array("$option LIKE '%$keyword%'")));
            }
        }
        
        $this->LookupLicenseInspectionType->recursive = 0;
        $this->Paginator->settings = $paging_option;
        $values = $this->Paginator->paginate('LookupLicenseInspectionType');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicenseInspectionType->save($this->request->data)) {
                $this->LookupLicenseInspectionType->save($this->request->data);
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        $post = $this->LookupLicenseInspectionType->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseInspectionType->id = $id;
            if ($this->LookupLicenseInspectionType->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLicenseInspectionType->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
