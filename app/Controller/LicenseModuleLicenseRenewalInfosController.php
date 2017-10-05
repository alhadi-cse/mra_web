
<?php

App::uses('AppController', 'Controller');

class LicenseModuleLicenseRenewalInfosController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('form_serial_no' => 'asc')
    );

    public function view() {
        
        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleLicenseRenewalInfo']['search_option'];
            $keyword = $this->request->data['LicenseModuleLicenseRenewalInfo']['search_keyword'];

            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('form_serial_no' => 'asc'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LicenseModuleLicenseRenewalInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $value = $this->Paginator->paginate('LicenseModuleLicenseRenewalInfo');
        $this->set('values', $value);
    }

    public function add() {
        $orgNameOptions = $this->LicenseModuleLicenseRenewalInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $this->set(compact('orgNameOptions'));            

        if ($this->request->is('post')) {
            $this->LicenseModuleLicenseRenewalInfo->create();
            if ($this->LicenseModuleLicenseRenewalInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        
        $orgNameOptions = $this->LicenseModuleLicenseRenewalInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $this->set(compact('orgNameOptions'));
             
        $post = $this->LicenseModuleLicenseRenewalInfo->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LicenseModuleLicenseRenewalInfo->id = $id;
            if ($this->LicenseModuleLicenseRenewalInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $licRenewalDetails = $this->LicenseModuleLicenseRenewalInfo->findById($id);
        if (!$licRenewalDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('licRenewalDetails'));
    }      
}

