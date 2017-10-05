<?php

App::uses('AppController', 'Controller');

class LookupRejectSuspendCancelStepCategoriesController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 10,
        'order' => array('LookupRejectSuspendCancelStepCategory.serial_no' => 'ASC')
   );

   public function view()
   {            
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupRejectSuspendCancelStepCategory']['search_option'];  
            $keyword = $this->request->data['LookupRejectSuspendCancelStepCategory']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupRejectSuspendCancelStepCategory.serial_no' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupRejectSuspendCancelStepCategory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupRejectSuspendCancelStepCategory');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupRejectSuspendCancelStepCategory->save($this->request->data))
            {
                $this->LookupRejectSuspendCancelStepCategory->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }
        $reject_suspend_cancel_history_type_options = $this->LookupRejectSuspendCancelStepCategory->LookupRejectSuspendCancelHistoryType->find('list', array('fields' => array('LookupRejectSuspendCancelHistoryType.id', 'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type')));
        $this->set(compact('reject_suspend_cancel_history_type_options'));
   }

   public function edit($id = null)
   {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }
        $reject_suspend_cancel_history_type_options = $this->LookupRejectSuspendCancelStepCategory->LookupRejectSuspendCancelHistoryType->find('list', array('fields' => array('LookupRejectSuspendCancelHistoryType.id', 'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type')));
        $this->set(compact('reject_suspend_cancel_history_type_options'));
        
        $post = $this->LookupRejectSuspendCancelStepCategory->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupRejectSuspendCancelStepCategory->id = $id;
            if ($this->LookupRejectSuspendCancelStepCategory->save($this->request->data)) {                
                return $this->redirect(array('action'=>'view'));            
            }            
        }
        
        if (!$this->request->data)
        {
            $this->request->data = $post;
        }
   }

    public function delete($id)
    {      
        if ($this->LookupRejectSuspendCancelStepCategory->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    } 
    
}


