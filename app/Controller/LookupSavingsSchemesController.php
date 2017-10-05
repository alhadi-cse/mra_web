<?php

App::uses('AppController', 'Controller');

class LookupSavingsSchemesController extends AppController {

    
    public $helpers = array('Html','Form','Js','Paginator');   
    public $components = array('Paginator');   
    public $paginate = array(
         'limit' => 5,
         'order' => array('LookupSavingsScheme.serial_no' => 'ASC')
    );

    public function view()
    {
        if ($this->request->is('post'))
        {
            $option = $this->request->data['LookupSavingsScheme']['search_option'];  
            $keyword = $this->request->data['LookupSavingsScheme']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 

            $this->paginate = array(
            'order' => array('LookupSavingsScheme.serial_no' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupSavingsScheme->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupSavingsScheme');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupSavingsScheme->save($this->request->data))
            {
                $this->LookupSavingsScheme->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }        
    } 

    public function edit($id = null)
    {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Savings Scheme');
        }

        $post = $this->LookupSavingsScheme->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Savings Scheme');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupSavingsScheme->id = $id;
            if ($this->LookupSavingsScheme->save($this->request->data)) {                
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
        if ($this->LookupSavingsScheme->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}

