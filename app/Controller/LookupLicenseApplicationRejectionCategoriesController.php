<?php

App::uses('AppController', 'Controller');

class LookupLicenseApplicationRejectionCategoriesController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 10, 'order' => array('LookupLicenseApplicationRejectionCategory.serial_no' => 'ASC'));

    
    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseApplicationRejectionCategory']['search_option'];
            $keyword = $this->request->data['LookupLicenseApplicationRejectionCategory']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('LookupLicenseApplicationRejectionCategory.serial_no' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LookupLicenseApplicationRejectionCategory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLicenseApplicationRejectionCategory');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicenseApplicationRejectionCategory->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }

        $rejection_type_options = $this->LookupLicenseApplicationRejectionCategory->LookupLicenseApplicationRejectionType->find('list', array('fields' => array('id', 'rejection_type')));
        $this->set(compact('rejection_type_options'));
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        $post = $this->LookupLicenseApplicationRejectionCategory->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseApplicationRejectionCategory->id = $id;
            if ($this->LookupLicenseApplicationRejectionCategory->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }

        $rejection_type_options = $this->LookupLicenseApplicationRejectionCategory->LookupLicenseApplicationRejectionType->find('list', array('fields' => array('id', 'rejection_type')));
        $this->set(compact('rejection_type_options'));
    }

    public function selected_categories() {

        $rejection_type_id = '';
        if (!empty($this->request->data)) {
            foreach ($this->request->data as $reqData) {
                if (isset($reqData['rejection_type_id'])) {
                    $rejection_type_id = $reqData['rejection_type_id'];
                    break;
                }
            }
        }

        if (!empty($rejection_type_id))
            $category_options = $this->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('id', 'rejection_category'), 'conditions' => array('rejection_type_id' => $rejection_type_id), 'recursive' => -1));
        else
            $category_options = $this->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('id', 'rejection_category'), 'recursive' => -1));

        $this->set(compact('category_options'));
        $this->layout = 'ajax';
    }

    public function delete($id) {
        if ($this->LookupLicenseApplicationRejectionCategory->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
