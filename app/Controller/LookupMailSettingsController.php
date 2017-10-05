<?php

App::uses('AppController', 'Controller');

class LookupMailSettingsController extends AppController {

   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 5,
        'order' => array('LookupMailSetting.mail_user_id' => 'ASC')
   );

   public function view()
   {            
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LookupMailSetting']['search_option'];  
            $keyword = $this->request->data['LookupMailSetting']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupMailSetting.serial_no' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupMailSetting->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupMailSetting');
        $this->set(compact('values'));
   }

   public function add()
   {       
        if(!empty($this->request->data))
        {
            if($this->LookupMailSetting->save($this->request->data))
            {
                $this->LookupMailSetting->save($this->request->data);
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

        $post = $this->LookupMailSetting->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put')))
        {
            $this->LookupMailSetting->id = $id;
            if ($this->LookupMailSetting->save($this->request->data)) {                
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
        if ($this->LookupMailSetting->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}