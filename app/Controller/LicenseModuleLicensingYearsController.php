<?php

App::uses('AppController', 'Controller');

class LicenseModuleLicensingYearsController extends AppController {
    
   public $helpers = array('Html','Form','Js','Paginator');   
   public $components = array('Paginator');   
   public $paginate = array(
        'limit' => 5,
        'order' => array('LicenseModuleLicensingYear.serial_no' => 'ASC')
   );

   public function view()
   {
       if ($this->request->is('post'))
       {
            $option = $this->request->data['LicenseModuleLicensingYear']['search_option'];  
            $keyword = $this->request->data['LicenseModuleLicensingYear']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LicenseModuleLicensingYear.serial_no' => 'ASC'),
            'limit' => 8,
            'conditions' => $condition);
        }

        $this->LicenseModuleLicensingYear->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LicenseModuleLicensingYear');
        $this->set(compact('values'));
   }

   public function add()
   {       
       $year_status_options = array('0'=>'Complete', '1'=>'Running'); 
       $this->set(compact('year_status_options'));
       
       if ($this->request->is('post'))
       {             
            if(!empty($this->request->data))
            {                
                if($this->request->data['LicenseModuleLicensingYear']['is_current_year']=='1'){
                    $condition = array('LicenseModuleLicensingYear.is_current_year' => 1);
                    $this->LicenseModuleLicensingYear->updateAll(array('is_current_year' => 0), $condition);
                }
                if($this->LicenseModuleLicensingYear->save($this->request->data)){                
                    $this->redirect(array('action'=>'view'));                   
                }            
            }   
        }
    } 

    public function edit($id = null)
    {
        if (!$id) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }
        $year_status_options = array('0'=>'Completed', '1'=>'Running'); 
        $this->set(compact('year_status_options'));
        $post = $this->LicenseModuleLicensingYear->findById($id);
        if (!$post) 
        {
            throw new NotFoundException('Invalid Licensing Status');
        }

        if ($this->request->is(array('post', 'put')))
        {
            if($this->request->data['LicenseModuleLicensingYear']['is_current_year']=='1'){
                    $condition = array('LicenseModuleLicensingYear.is_current_year' => 1);
                    $this->LicenseModuleLicensingYear->updateAll(array('is_current_year' => 0), $condition);
                }
            $this->LicenseModuleLicensingYear->id = $id;
            if ($this->LicenseModuleLicensingYear->save($this->request->data)) {                
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
        if ($this->LicenseModuleLicensingYear->delete($id))
        {
            return $this->redirect(array('action' => 'view'));            
        }
    }
}
