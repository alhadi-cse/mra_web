<?php

App::uses('AppController', 'Controller');

class LookupLoanActivitySchemesController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 5,
        'order' => array('LookupLoanActivityScheme.loan_activity_scheme' => 'ASC')
   );

   public function view()
   {
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupLoanActivityScheme']['search_option']; 
            $keyword = $this->request->data['LookupLoanActivityScheme']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");                 

            $this->paginate = array(
            'order' => array('LookupLoanActivityScheme.serial_no' => 'ASC'),
            'limit' => 5,
            'conditions' => $condition);
        }

        $this->LookupLoanActivityScheme->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLoanActivityScheme');
        $this->set(compact('values'));
   }

   public function add()
   {
        if(!empty($this->request->data))
        {
            if($this->LookupLoanActivityScheme->save($this->request->data))
            {
                $this->LookupLoanActivityScheme->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }            
        }
        $loan_activity_category_options = $this->LookupLoanActivityScheme->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));        
        $this->set(compact('loan_activity_category_options'));
   }

   public function edit($id = null)
   {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Information');
        }
        $loan_activity_category_options = $this->LookupLoanActivityScheme->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));
        $loan_activity_subcategory_options = $this->LookupLoanActivityScheme->LookupLoanActivitySubcategory->find('list', array('fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory')));
        $this->set(compact('loan_activity_category_options','loan_activity_subcategory_options'));
   
        $post = $this->LookupLoanActivityScheme->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupLoanActivityScheme->id = $id;
            if ($this->LookupLoanActivityScheme->save($this->request->data)) {                
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
        if ($this->LookupLoanActivityScheme->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
        
    function subcategory_select() {
        $loan_activity_category_id = $this->request->data['LookupLoanActivityScheme']['loan_activity_category_id'];

        $subcategory_options = $this->LookupLoanActivityScheme->LookupLoanActivitySubcategory->find('list', array(
            'fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory'),
            'conditions' => array('LookupLoanActivitySubcategory.loan_activity_category_id' => $loan_activity_category_id),
            'recursive' => -1
        ));

        $this->set(compact('subcategory_options'));
        $this->layout = 'ajax';
    } 
    
}
