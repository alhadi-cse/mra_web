<?php

App::uses('AppController', 'Controller');

class LookupLicenseInitialAssessmentParameterOptionsController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Html', 'Form', 'Paginator', 'Js');
    
    
    public function view() {
        $paging_option = array('limit' => 10, 'order' => array('LookupLicenseInitialAssessmentParameter.sorting_order' => 'ASC', 'LookupLicenseInitialAssessmentParameter.declaration_year' => 'ASC'));
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseInitialAssessmentParameterOption']['search_option'];
            $keyword = $this->request->data['LookupLicenseInitialAssessmentParameterOption']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                $paging_option = array_merge($paging_option, array('conditions' => array("$option LIKE '%$keyword%'")));
                //$this->paginate = $paging_option;
            //debug($this->paginate);
            }
        } else {
            if (empty($option))
                $option = '';
            if (empty($keyword))
                $keyword = '';
            //debug($this->paginate);
        }
        
        //$this->Paginator->settings = $this->paginate;
        
        $this->LookupLicenseInitialAssessmentParameterOption->recursive = 0;
        $this->Paginator->settings = $paging_option;
        $values = $this->Paginator->paginate('LookupLicenseInitialAssessmentParameterOption');
        $this->set(compact('values', 'option', 'keyword'));
    }

    public function add() {
        $parameterOptions = $this->LookupLicenseInitialAssessmentParameterOption->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.id', 'LookupLicenseInitialAssessmentParameter.parameter')));

        $this->set(compact('parameterOptions'));

        if ($this->request->is('post')) {
            $this->LookupLicenseInitialAssessmentParameterOption->create();
            if ($this->LookupLicenseInitialAssessmentParameterOption->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null, $parameter_id = null) {

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $parameter_type_id_array = $this->LookupLicenseInitialAssessmentParameterOption->LookupLicenseInitialAssessmentParameter->find('list', array(
            'fields' => array('LookupLicenseInitialAssessmentParameter.parameter_type_id'),
            'conditions' => array('LookupLicenseInitialAssessmentParameter.id' => $parameter_id),
            'recursive' => -1
        ));
        $parameter_type = "";
        if ($parameter_id != null) {
            $parameter_type_id = $parameter_type_id_array[$parameter_id];

            $parameter_types_array = $this->LookupLicenseInitialAssessmentParameterOption->LookupLicenseInitialAssessmentParameter->LookupLicenseInitialAssessmentParameterType->find('list', array(
                'fields' => array('LookupLicenseInitialAssessmentParameterType.parameter_type'),
                'conditions' => array('LookupLicenseInitialAssessmentParameterType.id' => $parameter_type_id),
                'recursive' => -1
            ));
            $parameter_type = $parameter_types_array[$parameter_type_id];
        }

        $parameterOptions = $this->LookupLicenseInitialAssessmentParameterOption->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.id', 'LookupLicenseInitialAssessmentParameter.parameter')));
        $this->set(compact('parameterOptions', 'parameter_type'));

        $post = $this->LookupLicenseInitialAssessmentParameterOption->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseInitialAssessmentParameterOption->id = $id;
            if ($this->LookupLicenseInitialAssessmentParameterOption->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    function parameter_type_select() {
        $parameter_id = $this->request->data['LookupLicenseInitialAssessmentParameterOption']['parameter_id'];
        $parameter_type_id_array = $this->LookupLicenseInitialAssessmentParameterOption->LookupLicenseInitialAssessmentParameter->find('list', array(
            'fields' => array('LookupLicenseInitialAssessmentParameter.parameter_type_id'),
            'conditions' => array('LookupLicenseInitialAssessmentParameter.id' => $parameter_id),
            'recursive' => -1
        ));
        $type_of_parameter = "";
        if ($parameter_id != null) {
            $parameter_type_id = $parameter_type_id_array[$parameter_id];
            $parameter_types_array = $this->LookupLicenseInitialAssessmentParameterOption->LookupLicenseInitialAssessmentParameter->LookupLicenseInitialAssessmentParameterType->find('list', array(
                'fields' => array('LookupLicenseInitialAssessmentParameterType.parameter_type'),
                'conditions' => array('LookupLicenseInitialAssessmentParameterType.id' => $parameter_type_id),
                'recursive' => -1
            ));
            $type_of_parameter = $parameter_types_array[$parameter_type_id];
        }
        $this->set(compact('type_of_parameter'));
        $this->layout = 'ajax';
    }

    public function delete($id) {
        if ($this->LookupLicenseInitialAssessmentParameterOption->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
