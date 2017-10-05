<?php

App::uses('AppController', 'Controller');

class LicenseModuleEligibleDistrictsController extends AppController {
    public $components = array('RequestHandler','Paginator');
    //public $components = array('Paginator');
    public $helpers = array('Html', 'Form', 'Paginator','Js');

    public $paginate = array(
        'limit' => 10,
        'order' => array('LookupAdminBoundaryDistrict.district_name' => 'ASC')
    );

    public function view() { 

        if ($this->request->is('post')) {
            $keyword = $this->request->data['LicenseModuleEligibleDistrict']['search_keyword'];

            $condition = array("OR" => array("LookupAdminBoundaryDistrict.district_name LIKE '%$keyword%'",
                                        "LicenseModuleEligibleDistrict.year LIKE '%$keyword%'"));
            $this->paginate = array(
                'order' => array('LookupAdminBoundaryDistrict.district_name' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LicenseModuleEligibleDistrict->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LicenseModuleEligibleDistrict');
        $this->set(compact('values'));
    }

    public function add() {       
        $districtsOptions = $this->LicenseModuleEligibleDistrict->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id','LookupAdminBoundaryDistrict.district_name')));       
        $this->set(compact('districtsOptions'));
//        $msg='';
//        $this->set(compact('msg'));
        
        if ($this->request->is('post')) {
            $this->LicenseModuleEligibleDistrict->create();
            $requestedData = $this->request->data;
            $requested_district_id = $requestedData['LicenseModuleEligibleDistrict']['district_id'];
            $district_id = $this->LicenseModuleEligibleDistrict->find('list', array('fields' => array('LicenseModuleEligibleDistrict.district_id'),'conditions' =>array('LicenseModuleEligibleDistrict.district_id' => $requested_district_id)));
            
            if($district_id==null)
            {
                if ($this->LicenseModuleEligibleDistrict->save($this->request->data)) {                
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

        $districtsOptions = $this->LicenseModuleEligibleDistrict->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id','LookupAdminBoundaryDistrict.district_name')));       
        $this->set(compact('districtsOptions'));
//        $msg='';
//        $this->set(compact('msg'));
        
        $post = $this->LicenseModuleEligibleDistrict->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LicenseModuleEligibleDistrict->id = $id;
            if ($this->LicenseModuleEligibleDistrict->save($this->request->data)) {
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
        if ($this->LicenseModuleEligibleDistrict->delete($id))
        {            
            return $this->redirect(array('action' => 'view'));            
        }             
    }
}
