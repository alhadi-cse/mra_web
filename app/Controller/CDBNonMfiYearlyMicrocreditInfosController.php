<?php

App::uses('AppController', 'Controller');

class CDBNonMfiYearlyMicrocreditInfosController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC')
    );

    public function view() {

        if ($this->request->is('post')) {
            $option = $this->request->data['CDBNonMfiDistrictWiseMicrocreditActivity']['search_option'];
            $keyword = $this->request->data['CDBNonMfiDistrictWiseMicrocreditActivity']['search_keyword'];

            if(!empty($option) && !empty($keyword)) {
                if (!empty($org_id)) {
                    $condition = array("AND" => array("$option LIKE '%$keyword%'", 'CDBNonMfiDistrictWiseMicrocreditActivity.org_id' => $org_id));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            } else {
                $condition = array();
            }  
            $this->paginate = array(
                'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->CDBNonMfiYearlyMicrocreditInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('CDBNonMfiYearlyMicrocreditInfo');
        $this->set('values', $values);
    }

    public function add() {
        
        $orgNameOptions = $this->CDBNonMfiYearlyMicrocreditInfo->CDBNonMfiBasicInfo->find('list', array('fields' => array('CDBNonMfiBasicInfo.id','CDBNonMfiBasicInfo.name_of_org')));
        $this->set(compact('orgNameOptions'));
        
        if ($this->request->is('post')) {
            $this->CDBNonMfiYearlyMicrocreditInfo->create();
            if ($this->CDBNonMfiYearlyMicrocreditInfo->save($this->request->data)) {

                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        
        $orgNameOptions = $this->CDBNonMfiYearlyMicrocreditInfo->CDBNonMfiBasicInfo->find('list', array('fields' => array('CDBNonMfiBasicInfo.id','CDBNonMfiBasicInfo.name_of_org')));
        $this->set(compact('orgNameOptions'));
        
        if (!$id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        
        $post = $this->CDBNonMfiYearlyMicrocreditInfo->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->CDBNonMfiYearlyMicrocreditInfo->id = $id;
            if ($this->CDBNonMfiYearlyMicrocreditInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        
        $yearlyMicrocreditInfo = $this->CDBNonMfiYearlyMicrocreditInfo->findById($id);
        if (!$yearlyMicrocreditInfo) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        $this->set(compact('yearlyMicrocreditInfo'));
    }    

}
