<?php

App::uses('AppController', 'Controller');

class AdminModuleUserGroupsController extends AppController {
    var $components = array('Paginator');   
    public $helpers = array('Form', 'Html', 'Js', 'Session');    
    
   public function view(){   
        $this->AdminModuleUserGroup->recursive = 0;       
        $this->paginate = array(
        'order' => array('AdminModuleUserGroup.id' => 'ASC'),
        'limit' => 10);            
                
        if ($this->request->is('post')){
            $option = $this->request->data['AdminModuleUserGroup']['search_option'];  
            $keyword = $this->request->data['AdminModuleUserGroup']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");                                 
            $this->paginate = array(
            'order' => array('AdminModuleUserGroup.id' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);                  
        }
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('AdminModuleUserGroup');
        $this->set(compact('values'));        
    }
    
     public function add() { 
        $this->loadModel('AdminModuleUserOrgType');
        $user_org_type_options = $this->AdminModuleUserOrgType->find('list', array('fields' => array('AdminModuleUserOrgType.id', 'AdminModuleUserOrgType.user_org_types')));
        $this->set(compact('user_org_type_options'));    
        
        if ($this->request->is('post')) {
            $this->AdminModuleUserGroup->create();
            $this->AdminModuleUserGroup->save($this->request->data);
            $this->redirect(array('action'=>'view'));
        }     
                
    } 

    public function edit($id = null) {
        $this->loadModel('AdminModuleUserOrgType');
        $user_org_type_options = $this->AdminModuleUserOrgType->find('list', array('fields' => array('AdminModuleUserOrgType.id', 'AdminModuleUserOrgType.user_org_types')));
        $this->set(compact('user_org_type_options'));
        if (!$id) {
            throw new NotFoundException('Invalid Group Information');
        }

        $post = $this->AdminModuleUserGroup->findById($id);
        
        if ($this->request->is(array('post', 'put'))) {
            $this->AdminModuleUserGroup->id = $id;
            if ($this->AdminModuleUserGroup->save($this->request->data)) {                
                return $this->redirect(array('action'=>'view'));            
            }            
        }
        
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }
}
