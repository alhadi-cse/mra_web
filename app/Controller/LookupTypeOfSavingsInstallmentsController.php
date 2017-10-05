<?php

App::uses('AppController', 'Controller');

class LookupTypeOfSavingsInstallmentsController extends AppController {
     
    public $helpers = array('Html','Form','Js','Paginator');   
    public $components = array('Paginator');   
    public $paginate = array(
         'limit' => 5,
         'order' => array('LookupTypeOfSavingsInstallment.serial_no' => 'ASC')
    );

    public function view()
    {            
        if ($this->request->is('post'))
        {
            $option = $this->request->data['LookupTypeOfSavingsInstallment']['search_option'];  
            $keyword = $this->request->data['LookupTypeOfSavingsInstallment']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array('order' => array('LookupTypeOfSavingsInstallment.serial_no' => 'ASC'),
                                    'limit' => 10, 'conditions' => $condition);
        }

        $this->LookupTypeOfSavingsInstallment->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupTypeOfSavingsInstallment');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupTypeOfSavingsInstallment->save($this->request->data))
            {
                $this->LookupTypeOfSavingsInstallment->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }        
    } 

    public function edit($id = null)
    {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Type of Savings Installment');
        }

        $post = $this->LookupTypeOfSavingsInstallment->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Type of Savings Installment');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupTypeOfSavingsInstallment->id = $id;
            if ($this->LookupTypeOfSavingsInstallment->save($this->request->data)) {                
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
        if ($this->LookupTypeOfSavingsInstallment->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}

