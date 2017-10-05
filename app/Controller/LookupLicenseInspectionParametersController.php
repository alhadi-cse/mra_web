<?php

App::uses('AppController', 'Controller');

class LookupLicenseInspectionParametersController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    
    
    public function view() {
        $paging_option = array('limit' => 10, 'order' => array('LookupLicenseInspectionParameter.inspection_id', 'LookupLicenseInspectionParameter.parameter_group_id', 'LookupLicenseInspectionParameter.parameter_name'));

        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseInspectionParameter']['search_option'];
            $keyword = $this->request->data['LookupLicenseInspectionParameter']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                $paging_option = array_merge($paging_option, array('conditions' => array("$option LIKE '%$keyword%'")));
            }
        }

        $this->LookupLicenseInspectionParameter->recursive = 0;
        $this->Paginator->settings = $paging_option;
        $values = $this->Paginator->paginate('LookupLicenseInspectionParameter');
        $this->set(compact('values'));
        
        //debug($values);
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicenseInspectionParameter->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
        $inspection_type_options = $this->LookupLicenseInspectionParameter->LookupLicenseInspectionType->find('list', array('fields' => array('LookupLicenseInspectionType.id', 'LookupLicenseInspectionType.inspection_type')));
        $this->set(compact('inspection_type_options'));
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        $inspection_type_options = $this->LookupLicenseInspectionParameter->LookupLicenseInspectionType->find('list', array('fields' => array('LookupLicenseInspectionType.id', 'LookupLicenseInspectionType.inspection_type')));
        $inspection_parameter_group_options = $this->LookupLicenseInspectionParameter->LookupLicenseInspectionParameterGroup->find('list', array('fields' => array('LookupLicenseInspectionParameterGroup.id', 'LookupLicenseInspectionParameterGroup.inspection_parameter_group')));
        $this->set(compact('inspection_type_options', 'inspection_parameter_group_options'));

        $post = $this->LookupLicenseInspectionParameter->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseInspectionParameter->id = $id;
            if ($this->LookupLicenseInspectionParameter->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLicenseInspectionParameter->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

    function parameter_group_select() {
        $inspection_type_id = $this->request->data['LookupLicenseInspectionParameter']['inspection_type_id'];
        $parameter_group_options = $this->LookupLicenseInspectionParameter->LookupLicenseInspectionParameterGroup->find('list', array(
            'fields' => array('LookupLicenseInspectionParameterGroup.id', 'LookupLicenseInspectionParameterGroup.inspection_parameter_group'),
            'conditions' => array('LookupLicenseInspectionParameterGroup.inspection_type_id' => $inspection_type_id),
            'recursive' => -1
        ));
        $this->set(compact('parameter_group_options'));
        $this->layout = 'ajax';
    }

}
