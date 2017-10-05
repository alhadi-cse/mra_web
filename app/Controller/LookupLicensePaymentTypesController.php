<?php

App::uses('AppController', 'Controller');

class LookupLicensePaymentTypesController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicensePaymentType']['search_option'];
            $keyword = $this->request->data['LookupLicensePaymentType']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array('conditions' => $condition, 'limit' => 10, 'order' => array('LookupLicensePaymentType.serial_no' => 'ASC'));
        }

        $this->LookupLicensePaymentType->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLicensePaymentType');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicensePaymentType->save($this->request->data)) {
                $this->LookupLicensePaymentType->save($this->request->data);
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        $post = $this->LookupLicensePaymentType->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicensePaymentType->id = $id;
            if ($this->LookupLicensePaymentType->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLicensePaymentType->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
