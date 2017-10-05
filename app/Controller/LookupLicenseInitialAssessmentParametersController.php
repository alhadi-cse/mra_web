<?php

App::uses('AppController', 'Controller');

class LookupLicenseInitialAssessmentParametersController extends AppController {
    
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    
    public function view() {

        $paging_option = array('limit' => 10, 'order' => array('LookupLicenseInitialAssessmentParameter.sorting_order' => 'ASC', 'LookupLicenseInitialAssessmentParameter.declaration_year' => 'ASC'));

        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseInitialAssessmentParameter']['search_option'];
            $keyword = $this->request->data['LookupLicenseInitialAssessmentParameter']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                $paging_option = array_merge($paging_option, array('conditions' => array("$option LIKE '%$keyword%'")));
            }
        }

//        $this->paginate = $paging_option;
//        $this->Paginator->settings = $this->paginate;
        
        $this->LookupLicenseInitialAssessmentParameter->recursive = 0;
        $this->Paginator->settings = $paging_option;
        $values = $this->Paginator->paginate('LookupLicenseInitialAssessmentParameter');        
        $this->set(compact('values'));
    }

    public function publish_parameter(){        
        $parameterList = $this->LookupLicenseInitialAssessmentParameter->find('all', array('fields' => array('LookupLicenseInitialAssessmentParameter.id','LookupLicenseInitialAssessmentParameter.parameter','LookupLicenseInitialAssessmentParameter.sorting_order','LookupLicenseInitialAssessmentParameter.is_published')));        
        
        $this->set(compact('parameterList'));
        
        if ($this->request->is(array('post', 'put'))) 
        {                       
            $posted_data = array();                        
            $posted_data = $this->request->data['LookupLicenseInitialAssessmentParameter'];  
            
            $flag= false;
            
            foreach($posted_data as $data)
            {                 
                $sorting_order_data = $data["sorting_order"];
                $is_published_data = $data["is_published"];                
                $parameter_id = $data["id"]; 
                $data = array("sorting_order"=>$sorting_order_data,"is_published"=>$is_published_data);
                $this->LookupLicenseInitialAssessmentParameter->id = $parameter_id;
                $this->LookupLicenseInitialAssessmentParameter->save($data);                
                $flag= true;
            }
            
            if ($flag) {
                $this->redirect(array('action' => 'view'));
            }
            else {
                $this->Session->setFlash('Unable to update the information of organization');
            }            
        }
    }

    public function add() {  
        $parameterTypeOptions = $this->LookupLicenseInitialAssessmentParameter->LookupLicenseInitialAssessmentParameterType->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameterType.id', 'LookupLicenseInitialAssessmentParameterType.parameter_type')));
        $modelNameOptions = $this->LookupLicenseInitialAssessmentParameter->LookupModelDefinition->find('list', array('fields' => array('LookupModelDefinition.id', 'LookupModelDefinition.model_description')));
        $operation_type_options = $this->LookupLicenseInitialAssessmentParameter->LookupTypeOfOperationOnParameter->find('list', array('fields' => array('LookupTypeOfOperationOnParameter.id', 'LookupTypeOfOperationOnParameter.operators')));
        $this->set(compact('parameterTypeOptions','modelNameOptions','operation_type_options'));
        if ($this->request->is('post')) {
            $this->LookupLicenseInitialAssessmentParameter->create();            
            if ($this->LookupLicenseInitialAssessmentParameter->save($this->request->data)) {                 
                $this->redirect(array('action' => 'view'));
            }                                  
        }        
    }

    public function edit($id = null) {

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        $parameterTypeOptions = $this->LookupLicenseInitialAssessmentParameter->LookupLicenseInitialAssessmentParameterType->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameterType.id', 'LookupLicenseInitialAssessmentParameterType.parameter_type')));
        $parameter_type_id = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.parameter_type_id'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.id' => $id)));
        $parameter_type_id_selected = $parameter_type_id[$id];
        
        $modelNameOptions = $this->LookupLicenseInitialAssessmentParameter->LookupModelDefinition->find('list', array('fields' => array('LookupModelDefinition.id', 'LookupModelDefinition.model_description')));
        $model_id = $this->LookupLicenseInitialAssessmentParameter->find('list', array('fields' => array('LookupLicenseInitialAssessmentParameter.model_id'), 'conditions' => array('LookupLicenseInitialAssessmentParameter.id' => $id)));
        $fieldNameOptions = $this->LookupLicenseInitialAssessmentParameter->LookupModelFieldDefinition->find('list', array(
            'fields' => array('LookupModelFieldDefinition.id', 'LookupModelFieldDefinition.field_description'),
            'conditions' => array('LookupModelFieldDefinition.model_id' => $model_id),
            'recursive' => -1
        )); 
        
        $operation_type_options = $this->LookupLicenseInitialAssessmentParameter->LookupTypeOfOperationOnParameter->find('list', array('fields' => array('LookupTypeOfOperationOnParameter.id', 'LookupTypeOfOperationOnParameter.operators')));

        $this->set(compact('parameterTypeOptions','parameter_type_id_selected','modelNameOptions','fieldNameOptions','operation_type_options'));       
        $post = $this->LookupLicenseInitialAssessmentParameter->findById($id);
        
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseInitialAssessmentParameter->id = $id;
            if ($this->LookupLicenseInitialAssessmentParameter->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }
            
    function field_select() {
        $model_id = $this->request->data['LookupLicenseInitialAssessmentParameter']['model_id'];
        $field_options = $this->LookupLicenseInitialAssessmentParameter->LookupModelFieldDefinition->find('list', array(
            'fields' => array('LookupModelFieldDefinition.id', 'LookupModelFieldDefinition.field_description'),
            'conditions' => array('LookupModelFieldDefinition.model_id' => $model_id),
            'recursive' => -1
        ));

        $this->set(compact('field_options'));
        $this->layout = 'ajax';
    }
        
    public function delete($id)
    {
        if ($this->LookupLicenseInitialAssessmentParameter->delete($id))
        {
            return $this->redirect(array('action' => 'view'));            
        }
    }
}
