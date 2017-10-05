<?php

App::uses('AppController', 'Controller');

class LookupLicenseInitialAssessmentPassMarksController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 10, 'order' => array('LookupLicenseInitialAssessmentPassMarks.id' => 'ASC'));

    public function view() {
        
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseInitialAssessmentPassMarks']['search_option'];
            $keyword = $this->request->data['LookupLicenseInitialAssessmentPassMarks']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array('conditions' => $condition, 'limit' => 10, 'order' => array('LookupLicenseInitialAssessmentPassMarks.id' => 'ASC'));
        }

        $this->LookupLicenseInitialAssessmentPassMarks->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLicenseInitialAssessmentPassMarks');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicenseInitialAssessmentPassMarks->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
        $initial_evaluation_pass_mark_type_options = $this->LookupLicenseInitialAssessmentPassMarks->LookupLicenseInitialAssessmentPassMarksType->find('list', array('fields' => array('LookupLicenseInitialAssessmentPassMarksType.id', 'LookupLicenseInitialAssessmentPassMarksType.initial_evaluation_pass_mark_type')));
        $this->set(compact('initial_evaluation_pass_mark_type_options'));
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        $initial_evaluation_pass_mark_type_options = $this->LookupLicenseInitialAssessmentPassMarks->LookupLicenseInitialAssessmentPassMarksType->find('list', array('fields' => array('LookupLicenseInitialAssessmentPassMarksType.id', 'LookupLicenseInitialAssessmentPassMarksType.initial_evaluation_pass_mark_type')));
        $this->set(compact('initial_evaluation_pass_mark_type_options'));
        $post = $this->LookupLicenseInitialAssessmentPassMarks->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseInitialAssessmentPassMarks->id = $id;
            if ($this->LookupLicenseInitialAssessmentPassMarks->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLicenseInitialAssessmentPassMarks->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
