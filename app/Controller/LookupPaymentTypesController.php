<?php

App::uses('AppController', 'Controller');

class LookupPaymentTypesController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 5,
        'order' => array('LookupPaymentType.serial_no' => 'ASC')
   );

   public function view()
   {            
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupPaymentType']['search_option'];  
            $keyword = $this->request->data['LookupPaymentType']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupPaymentType.serial_no' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupPaymentType->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupPaymentType');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupPaymentType->save($this->request->data))
            {
                $this->LookupPaymentType->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }        
    } 

    public function edit($id = null)
    {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }

        $post = $this->LookupPaymentType->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupPaymentType->id = $id;
            if ($this->LookupPaymentType->save($this->request->data)) {                
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
        if ($this->LookupPaymentType->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}
