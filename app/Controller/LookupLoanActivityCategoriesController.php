<?php

App::uses('AppController', 'Controller');

class LookupLoanActivityCategoriesController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 5,
        'order' => array('LookupLoanActivityCategory.serial_no' => 'ASC')
   );

   public function view()
   {            
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupLoanActivityCategory']['search_option'];  
            $keyword = $this->request->data['LookupLoanActivityCategory']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupLoanActivityCategory.serial_no' => 'ASC'),
            'limit' => 5,
            'conditions' => $condition);
        }

        $this->LookupLoanActivityCategory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLoanActivityCategory');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupLoanActivityCategory->save($this->request->data))
            {
                $this->LookupLoanActivityCategory->save($this->request->data);
                $this->redirect(array('action'=>'view'));
            }
            else{ 
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Invalid Data'
                );
                $this->set(compact('msg'));
            }
            
        }        
    } 

    public function edit($id = null)
    {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Activity Category');
        }
        
        $post = $this->LookupLoanActivityCategory->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Activity Category');
        }        

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupLoanActivityCategory->id = $id;
            if ($this->LookupLoanActivityCategory->save($this->request->data)) {                
                return $this->redirect(array('action'=>'view'));            
            } 
            else{ 
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Invalid Data'
                );
                $this->set(compact('msg'));
            }
        }
        
        if (!$this->request->data)
        {
            $this->request->data = $post;
        }
    }

    public function delete($id)
    {      
        if ($this->LookupLoanActivityCategory->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}
