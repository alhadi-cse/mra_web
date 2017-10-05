<?php

App::uses('AppController', 'Controller');

class LookupLicensePaymentReminderTypesController extends AppController {    

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 5,
        'order' => array('LookupLicensePaymentReminderType.serial_no' => 'ASC')
    );

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicensePaymentReminderType']['search_option'];
            $keyword = $this->request->data['LookupLicensePaymentReminderType']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('LookupLicensePaymentReminderType.serial_no' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LookupLicensePaymentReminderType->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLicensePaymentReminderType');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicensePaymentReminderType->save($this->request->data)) {
                $this->LookupLicensePaymentReminderType->save($this->request->data);
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid License Inspector Type');
        }

        $post = $this->LookupLicensePaymentReminderType->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid License Inspector Type');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicensePaymentReminderType->id = $id;
            if ($this->LookupLicensePaymentReminderType->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLicensePaymentReminderType->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
