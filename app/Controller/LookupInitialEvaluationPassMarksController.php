<?php

App::uses('AppController', 'Controller');

class LookupInitialEvaluationPassMarksController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 10,
        'order' => array('LookupInitialEvaluationPassMark.id' => 'ASC')
   );

   public function view()
   {            
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupInitialEvaluationPassMark']['search_option'];  
            $keyword = $this->request->data['LookupInitialEvaluationPassMark']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupInitialEvaluationPassMark.id' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupInitialEvaluationPassMark->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupInitialEvaluationPassMark');
        $this->set(compact('values'));
   }

   public function add()
   {
        if(!empty($this->request->data))
        {
            if($this->LookupInitialEvaluationPassMark->save($this->request->data))
            {                
                $this->redirect(array('action'=>'view'));
            }
        }
        $initial_evaluation_pass_mark_type_options = $this->LookupInitialEvaluationPassMark->LookupInitialEvaluationPassMarkType->find('list', array('fields' => array('LookupInitialEvaluationPassMarkType.id', 'LookupInitialEvaluationPassMarkType.initial_evaluation_pass_mark_type')));
        $this->set(compact('initial_evaluation_pass_mark_type_options'));        
    }

    public function edit($id = null)
    {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Information');
        }
        $initial_evaluation_pass_mark_type_options = $this->LookupInitialEvaluationPassMark->LookupInitialEvaluationPassMarkType->find('list', array('fields' => array('LookupInitialEvaluationPassMarkType.id', 'LookupInitialEvaluationPassMarkType.initial_evaluation_pass_mark_type')));
        $this->set(compact('initial_evaluation_pass_mark_type_options')); 
        $post = $this->LookupInitialEvaluationPassMark->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupInitialEvaluationPassMark->id = $id;
            if ($this->LookupInitialEvaluationPassMark->save($this->request->data)) {                
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
        if ($this->LookupInitialEvaluationPassMark->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}
