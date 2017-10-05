<?php

App::uses('AppController', 'Controller');

class BasicModuleRejectionHistoriesController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('BasicModuleBasicInformation.full_name_of_org' => 'ASC')
    );

    public function view() {
//        if ($this->request->is('post'))
//        {
//            $keyword = $this->request->data['BasicModuleRejectionHistory']['search_keyword'];
//
//            $condition = array("OR" => array("BasicModuleBasicInformation.full_name_of_org LIKE '%$keyword%'",
//                                        "BasicModuleBasicInformation.short_name_of_org LIKE '%$keyword%'",
//                                        "BasicModuleRejectionHistory.lastFinalRejectionDate LIKE '%$keyword%'",
//                                        "BasicModuleRejectionHistory.firstRejectionDate LIKE '%$keyword%'"));
//            $this->paginate = array(                
//            'order' => array('BasicModuleBasicInformation.full_name_of_org' => 'ASC'),
//            'limit' => 10,
//            'conditions' => $condition);            
//        }

        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleRejectionHistory']['search_option'];
            $keyword = $this->request->data['BasicModuleRejectionHistory']['search_keyword'];

            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('BasicModuleBasicInformation.full_name_of_org' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->BasicModuleRejectionHistory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $value = $this->Paginator->paginate('BasicModuleRejectionHistory');
        $this->set('values', $value);
    }

    public function add() {
        $orgNameOptions = $this->BasicModuleRejectionHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));

        $this->set(compact('orgNameOptions'));
        
        
        if ($this->request->is('post')) {
            $this->BasicModuleRejectionHistory->create();
            $newData = $this->BasicModuleRejectionHistory->save($this->request->data);
            if ($newData) {
                $id = $newData['BasicModuleRejectionHistory']['id'];
                $this->redirect(array('action' => 'preview', $id));
            }
        }
        

        if ($this->request->is('post')) {
            $this->BasicModuleRejectionHistory->create();
            if ($this->BasicModuleRejectionHistory->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        $orgNameOptions = $this->BasicModuleRejectionHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));

        $this->set(compact('orgNameOptions'));

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $post = $this->BasicModuleRejectionHistory->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->BasicModuleRejectionHistory->id = $id;
            if ($this->BasicModuleRejectionHistory->save($this->request->data)) {
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

        $rejectHistDetails = $this->BasicModuleRejectionHistory->findById($id);
        if (!$rejectHistDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('rejectHistDetails'));
    }

    public function preview($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $rejectHistDetails = $this->BasicModuleRejectionHistory->findById($id);
        if (!$rejectHistDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('rejectHistDetails'));
    }
    
}
