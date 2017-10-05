
<?php

App::uses('AppController', 'Controller');

class LicenseModuleRejectSuspendCancelHistoriesController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('form_serial_no' => 'asc')
    );

    public function view() {
        
        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleRejectSuspendCancelHistory']['search_option'];
            $keyword = $this->request->data['LicenseModuleRejectSuspendCancelHistory']['search_keyword'];

            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('form_serial_no' => 'asc'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LicenseModuleRejectSuspendCancelHistory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $value = $this->Paginator->paginate('LicenseModuleRejectSuspendCancelHistory');
        $this->set('values', $value);
    }

    public function add() {
        $orgNameOptions = $this->LicenseModuleRejectSuspendCancelHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $reject_suspend_cancel_history_type_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelHistoryType->find('list', array('fields' => array('LookupRejectSuspendCancelHistoryType.id', 'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type')));
        $reject_suspend_cancel_category_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelStepCategory->find('list', array('fields' => array('LookupRejectSuspendCancelStepCategory.id', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category')));
        $this->set(compact('orgNameOptions','reject_suspend_cancel_history_type_options','reject_suspend_cancel_category_options'));

        if ($this->request->is('post')) {
            $this->LicenseModuleRejectSuspendCancelHistory->create();
            $newData = $this->LicenseModuleRejectSuspendCancelHistory->save($this->request->data);
            if ($newData) {
                $id = $newData['LicenseModuleRejectSuspendCancelHistory']['id'];
                $this->redirect(array('action' => 'preview', $id));
            }
        }
        

        if ($this->request->is('post')) {
            $this->LicenseModuleRejectSuspendCancelHistory->create();
            if ($this->LicenseModuleRejectSuspendCancelHistory->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null, $org_id = null) {
        $orgNameOptions = $this->LicenseModuleRejectSuspendCancelHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));

        $this->set(compact('orgNameOptions'));

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        
        $orgNameOptions = $this->LicenseModuleRejectSuspendCancelHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $reject_suspend_cancel_history_type_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelHistoryType->find('list', array('fields' => array('LookupRejectSuspendCancelHistoryType.id', 'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type')));
        $reject_suspend_cancel_history_type_id = $this->LicenseModuleRejectSuspendCancelHistory->find('list', array('fields' => array('LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_history_type_id'), 'conditions' => array("AND" => array('LicenseModuleRejectSuspendCancelHistory.id' => $id, 'LicenseModuleRejectSuspendCancelHistory.org_id' => $org_id))));
        $reject_suspend_cancel_category_id = $this->LicenseModuleRejectSuspendCancelHistory->find('list', array('fields' => array('LicenseModuleRejectSuspendCancelHistory.reject_suspend_cancel_category_id'), 'conditions' => array("AND" => array('LicenseModuleRejectSuspendCancelHistory.id' => $id, 'LicenseModuleRejectSuspendCancelHistory.org_id' => $org_id))));
                
        $reject_suspend_cancel_category_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelStepCategory->find('list', array(
                'fields' => array('LookupRejectSuspendCancelStepCategory.id', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category'),
                'conditions' => array('LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_history_type_id' => $reject_suspend_cancel_history_type_id),
                'recursive' => -1
            ));

        $reject_suspend_cancel_reason_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelStepwiseReason->find('list', array(
            'fields' => array('LookupRejectSuspendCancelStepwiseReason.id', 'LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_reason'),
            'conditions' => array('LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_category_id' => $reject_suspend_cancel_category_id),
            'recursive' => -1
        ));
        
        $this->set(compact('orgNameOptions','reject_suspend_cancel_history_type_options','reject_suspend_cancel_category_options','reject_suspend_cancel_reason_options'));
             
        $post = $this->LicenseModuleRejectSuspendCancelHistory->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LicenseModuleRejectSuspendCancelHistory->id = $id;
            if ($this->LicenseModuleRejectSuspendCancelHistory->save($this->request->data)) {
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

        $rejectHistDetails = $this->LicenseModuleRejectSuspendCancelHistory->findById($id);
        if (!$rejectHistDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('rejectHistDetails'));
    }

    public function preview($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $rejectHistDetails = $this->LicenseModuleRejectSuspendCancelHistory->findById($id);
        if (!$rejectHistDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('rejectHistDetails'));
    }
    
    function category_select() {
        $reject_suspend_cancel_history_type_id = $this->request->data['LicenseModuleRejectSuspendCancelHistory']['reject_suspend_cancel_history_type_id'];

        $category_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelStepCategory->find('list', array(
            'fields' => array('LookupRejectSuspendCancelStepCategory.id', 'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category'),
            'conditions' => array('LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_history_type_id' => $reject_suspend_cancel_history_type_id),
            'recursive' => -1
        ));

        $this->set(compact('category_options'));
        $this->layout = 'ajax';
    }
    
    function reason_select() {        
        $reject_suspend_cancel_category_id = $this->request->data['LicenseModuleRejectSuspendCancelHistory']['reject_suspend_cancel_category_id'];
        $condition = array('LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_category_id' =>$reject_suspend_cancel_category_id);
        $reason_options = $this->LicenseModuleRejectSuspendCancelHistory->LookupRejectSuspendCancelStepwiseReason->find('list', array(
            'fields' => array('LookupRejectSuspendCancelStepwiseReason.id', 'LookupRejectSuspendCancelStepwiseReason.reject_suspend_cancel_reason'),
            'conditions' => $condition,
            'recursive' => -1
        ));
        
        $this->set(compact('reason_options'));
        $this->layout = 'ajax';
    }    
}

