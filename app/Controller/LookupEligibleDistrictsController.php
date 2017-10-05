<?php

App::uses('AppController', 'Controller');

class LookupEligibleDistrictsController extends AppController {
    //public $components = array('RequestHandler','Paginator');
    public $components = array('Paginator');
    public $helpers = array('Html', 'Form', 'Paginator','Js');

    public $paginate = array(
        'limit' => 10,
        'order' => array('LookupAdminBoundaryDistrict.district_id' => 'ASC')
    );

    public function view() { 

        if ($this->request->is('post')) {
            $option = $this->request->data['LookupEligibleDistrict']['search_option'];  
            $keyword = $this->request->data['LookupEligibleDistrict']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'"); 
            
            $this->paginate = array(
            'order' => array('LookupEligibleDistrict.district_id' => 'ASC'),
            'limit' => 10,
            'conditions' => $condition);
        }

        $this->LookupEligibleDistrict->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupEligibleDistrict');
        $this->set(compact('values'));
    }

    public function add() {       
        $districtsOptions = $this->LookupEligibleDistrict->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id','LookupAdminBoundaryDistrict.district_name')));       
        $this->set(compact('districtsOptions'));
//        $msg='';
//        $this->set(compact('msg'));
        
        if ($this->request->is('post')) {
            $this->LookupEligibleDistrict->create();
            $requestedData = $this->request->data;
            $requested_district_id = $requestedData['LookupEligibleDistrict']['district_id'];
            $district_id = $this->LookupEligibleDistrict->find('list', array('fields' => array('LookupEligibleDistrict.district_id'),'conditions' =>array('LookupEligibleDistrict.district_id' => $requested_district_id)));
            
            if($district_id==null)
            {
                if ($this->LookupEligibleDistrict->save($this->request->data)) {                
                    $this->redirect(array('action' => 'view'));
                }
            }
            else 
            {
                debug($district_id); 
                $msg='District information already exists';
                $this->set(compact('msg'));    
            }
                               
        }        
    }

    public function edit($id = null) {

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $districtsOptions = $this->LookupEligibleDistrict->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id','LookupAdminBoundaryDistrict.district_name')));       
        $this->set(compact('districtsOptions'));
//        $msg='';
//        $this->set(compact('msg'));
        
        $post = $this->LookupEligibleDistrict->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupEligibleDistrict->id = $id;
            if ($this->LookupEligibleDistrict->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id)
    {      
        if ($this->LookupEligibleDistrict->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}
