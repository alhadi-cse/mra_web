
<?php

App::uses('AppController', 'Controller');

class LookupRejectSuspendCancelStepwiseReasonsController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 10,
        'order' => array('LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_reason' => 'ASC')
   );

   public function view()
   {
       if ($this->request->is('post')) {
            $option = $this->request->data['LookupRejectSuspendCancelStepwiseReason']['search_option']; 
            $keyword = $this->request->data['LookupRejectSuspendCancelStepwiseReason']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");                 

            $this->paginate = array(
            'order' => array('LookupRejectSuspendCancelStepwiseReason.serial_no' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupRejectSuspendCancelStepwiseReason->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupRejectSuspendCancelStepwiseReason');
        $this->set(compact('values'));
   }

   public function add()
   {
        if(!empty($this->request->data))
        {
            if($this->LookupRejectSuspendCancelStepwiseReason->save($this->request->data))
            {
                $this->LookupRejectSuspendCancelStepwiseReason->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }
        $reject_suspend_cancel_history_type_options = $this->LookupRejectSuspendCancelStepwiseReason->LookupRejectSuspendCancelHistoryType->find('list', array('fields' => array('LookupRejectSuspendCancelHistoryType.id', 'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type')));
        $reject_suspend_cancel_category_options = $this->LookupRejectSuspendCancelStepwiseReason->LookupRejectSuspendCancelStepCategory->find('list', array('fields' => array('LookupRejectSuspendCancelStepCategory.id', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category')));
        $this->set(compact('reject_suspend_cancel_history_type_options','reject_suspend_cancel_category_options'));
   }

   public function edit($id = null)
   {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Rejection Reason');
        }
        $reject_suspend_cancel_history_type_options = $this->LookupRejectSuspendCancelStepwiseReason->LookupRejectSuspendCancelHistoryType->find('list', array('fields' => array('LookupRejectSuspendCancelHistoryType.id', 'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type')));
        $reject_suspend_cancel_category_options = $this->LookupRejectSuspendCancelStepwiseReason->LookupRejectSuspendCancelStepCategory->find('list', array('fields' => array('LookupRejectSuspendCancelStepCategory.id', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category')));
        $this->set(compact('reject_suspend_cancel_history_type_options','reject_suspend_cancel_category_options'));
   
        $post = $this->LookupRejectSuspendCancelStepwiseReason->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Rejection Reason');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupRejectSuspendCancelStepwiseReason->id = $id;
            if ($this->LookupRejectSuspendCancelStepwiseReason->save($this->request->data)) {                
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
        if ($this->LookupRejectSuspendCancelStepwiseReason->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
    
    function category_select() {
        $reject_suspend_cancel_history_type_id = $this->request->data['LookupRejectSuspendCancelStepwiseReason']['reject_suspend_cancel_history_type_id'];

        $category_options = $this->LookupRejectSuspendCancelStepwiseReason->LookupRejectSuspendCancelStepCategory->find('list', array(
            'fields' => array('LookupRejectSuspendCancelStepCategory.id', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category'),
            'conditions' => array('LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_history_type_id' => $reject_suspend_cancel_history_type_id),
            'recursive' => -1
        ));

        $this->set(compact('category_options'));
        $this->layout = 'ajax';
    }
}