<?php

App::uses('AppController', 'Controller');

class LookupLoanActivitySubcategoriesController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 10,
        'order' => array('LookupLoanActivitySubcategory.serial_no' => 'ASC')
   );

   public function view()
   {            
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupLoanActivitySubcategory']['search_option'];  
            $keyword = $this->request->data['LookupLoanActivitySubcategory']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupLoanActivitySubcategory.serial_no' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupLoanActivitySubcategory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLoanActivitySubcategory');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupLoanActivitySubcategory->save($this->request->data))
            {
                $this->LookupLoanActivitySubcategory->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }
        $loan_activity_category_options = $this->LookupLoanActivitySubcategory->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));
        $this->set(compact('loan_activity_category_options'));
   }

   public function edit($id = null)
   {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Information');
        }
        $loan_activity_category_options = $this->LookupLoanActivitySubcategory->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));
        $this->set(compact('loan_activity_category_options'));
        
        $post = $this->LookupLoanActivitySubcategory->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupLoanActivitySubcategory->id = $id;
            if ($this->LookupLoanActivitySubcategory->save($this->request->data)) {                
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
        if ($this->LookupLoanActivitySubcategory->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }    
}


