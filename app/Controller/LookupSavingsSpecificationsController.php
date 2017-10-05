<?php

App::uses('AppController', 'Controller');

class LookupSavingsSpecificationsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 5,
        'order' => array('LookupSavingsSpecification.serial_no' => 'ASC')
    );

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupSavingsSpecification']['search_option'];
            $keyword = $this->request->data['LookupSavingsSpecification']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array('order' => array('LookupSavingsSpecification.serial_no' => 'ASC'),
                'limit' => 10, 'conditions' => $condition);
        }

        $this->LookupSavingsSpecification->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupSavingsSpecification');
        $this->set(compact('values'));
    }

    public function add() {
        $type_of_installment_options = $this->LookupSavingsSpecification->LookupTypeOfSavingsInstallment->find('list', array('fields' => array('LookupTypeOfSavingsInstallment.id', 'LookupTypeOfSavingsInstallment.type_of_installment')));
        $this->set(compact('type_of_installment_options'));

        if (!empty($this->request->data)) {
            if ($this->LookupSavingsSpecification->save($this->request->data)) {
                $this->LookupSavingsSpecification->save($this->request->data);
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Savings Specification');
        }
        
        $type_of_installment_options = $this->LookupSavingsSpecification->LookupTypeOfSavingsInstallment->find('list', array('fields' => array('LookupTypeOfSavingsInstallment.id', 'LookupTypeOfSavingsInstallment.type_of_installment')));
        $this->set(compact('type_of_installment_options'));
        
        $post = $this->LookupSavingsSpecification->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Savings Specification');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupSavingsSpecification->id = $id;
            if ($this->LookupSavingsSpecification->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupSavingsSpecification->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
