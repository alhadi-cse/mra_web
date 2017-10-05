<?php

App::uses('AppController', 'Controller');

class LicenseModuleParameterOptionsController extends AppController {
    public $components = array('RequestHandler','Paginator');    
    public $helpers = array('Html', 'Form', 'Paginator','Js');
    public $paginate = array(
        'limit' => 10,
        'order' => array('LicenseModuleParameter.serial' => 'ASC')
    );

    public function view() { 

        if ($this->request->is('post')) {
            $keyword = $this->request->data['LicenseModuleParameterOption']['search_keyword'];

            $condition = array("LicenseModuleParameter.parameter LIKE '%$keyword%'");
            $this->paginate = array(
                'order' => array('LicenseModuleParameter.serial' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LicenseModuleParameterOption->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LicenseModuleParameterOption');
        $this->set(compact('values'));  
    }

    public function add() {         
        $parameterOptions = $this->LicenseModuleParameterOption->LicenseModuleParameter->find('list', array('fields' => array('LicenseModuleParameter.id','LicenseModuleParameter.parameter')));
        $this->set(compact('parameterOptions'));
        
        if ($this->request->is('post')) {
            $this->LicenseModuleParameterOption->create();            
            if ($this->LicenseModuleParameterOption->save($this->request->data)) {                
                $this->redirect(array('action' => 'view'));
            }                                  
        }        
    }

    public function edit($id = null) {

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        
        $parameterOptions = $this->LicenseModuleParameterOption->LicenseModuleParameter->find('list', array('fields' => array('LicenseModuleParameter.id','LicenseModuleParameter.parameter')));
        $this->set(compact('parameterOptions'));
               
        $post = $this->LicenseModuleParameterOption->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LicenseModuleParameterOption->id = $id;
            if ($this->LicenseModuleParameterOption->save($this->request->data)) {
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
        if ($this->LicenseModuleParameterOption->delete($id))
        {
            return $this->redirect(array('action' => 'view'));            
        }
    }
}
