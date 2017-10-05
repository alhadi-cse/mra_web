<?php

App::uses('AppController', 'Controller');

class LicenseModuleParametersController extends AppController {
    //public $components = array('RequestHandler','Paginator');    
    public $components = array('Paginator');    
    public $helpers = array('Html', 'Form', 'Paginator','Js');

    public $paginate = array(
        'limit' => 10,
        'order' => array('LicenseModuleParameter.sorting_order' => 'ASC', 'LicenseModuleParameter.year' => 'ASC')
    );

    public function view() {

        if ($this->request->is('post')) {
            $keyword = $this->request->data['LicenseModuleParameter']['search_keyword'];

            $condition = array("LicenseModuleParameter.parameter LIKE '%$keyword%'");
            $this->paginate = array(
                'order' => array('LicenseModuleParameter.sorting_order' => 'ASC', 'LicenseModuleParameter.year' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LicenseModuleParameter->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LicenseModuleParameter');
        $this->set(compact('values'));
    }

    public function publish_parameter(){        
        $parameterList = $this->LicenseModuleParameter->find('all', array('fields' => array('LicenseModuleParameter.id','LicenseModuleParameter.parameter','LicenseModuleParameter.sorting_order','LicenseModuleParameter.is_published')));        
        $this->set(compact('parameterList'));
        
        if ($this->request->is(array('post', 'put'))) 
        {                       
            $posted_data = array();                        
            $posted_data = $this->request->data['LicenseModuleParameter'];  
            $flag= false;
            
            foreach($posted_data as $data)
            { 
                $sorting_order_data = $data["sorting_order"];
                $is_published_data = $data["is_published"];  
                $parameter_id = $data["id"]; 
                $this->LicenseModuleParameter->updateAll(array("sorting_order"=>$sorting_order_data,"is_published"=>$is_published_data),array("id"=>$parameter_id));                
                $flag= true;
            }
            
            if ($flag) {
                $this->redirect(array('action' => 'view'));
            }
            else {
                $this->Session->setFlash('Unable to update the parameter information');
            }            
        }
    }

    public function add() {         
        if ($this->request->is('post')) {
            $this->LicenseModuleParameter->create();            
            if ($this->LicenseModuleParameter->save($this->request->data)) {      
                
                $this->redirect(array('action' => 'view'));
            }                                  
        }        
    }

    public function edit($id = null) {

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
               
        $post = $this->LicenseModuleParameter->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LicenseModuleParameter->id = $id;
            if ($this->LicenseModuleParameter->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id)
    {
        if ($this->LicenseModuleParameter->delete($id))
        {
            return $this->redirect(array('action' => 'view'));            
        }
    }
}
