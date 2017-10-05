<?php

App::uses('AppController', 'Controller');

class BasicModulePaymentInfosController extends AppController {
        
    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js');

    public function view($opt = null, $mode = null) {

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($IsValidUser)) {

            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $opt_all = false;
        if ($user_group_id && in_array(1,$user_group_id)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array('BasicModulePaymentInfo.org_id' => $org_id);
        } else {
            $condition = array();
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModulePaymentInfo']['search_option'];
            $keyword = $this->request->data['BasicModulePaymentInfo']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($condition)) {
                    $condition = array("AND" => array("$option LIKE '%$keyword%'", $condition));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }
        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all'));

        $this->paginate = array(
            'limit' => 10,
            'order' => array('BasicModuleBasicInformation.full_name_of_org' => 'asc'),
            'conditions' => $condition);

        $this->BasicModulePaymentInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModulePaymentInfo');

        //if($opt && $opt!='all' && $values && sizeof($values)==1)
        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['BasicModulePaymentInfo']['id'];

            if ($mode && $mode == 'edit') {
                $this->redirect(array('action' => 'edit', $data_id, $org_id));
            } else {
                $this->redirect(array('action' => 'details', $data_id));
            }
            return;
        }

        $this->set(compact('values'));
    }

    public function add() {

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');

        if ((empty($org_id) && !empty($user_group_id) && !in_array(1,$user_group_id)) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $orgNameOptions = $this->BasicModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $paymentTypeOptions = $this->BasicModulePaymentInfo->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'paymentTypeOptions'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['BasicModulePaymentInfo']) {
                if(empty($reqData['BasicModulePaymentInfo']['org_id']) && !empty($org_id))
                    $reqData['BasicModulePaymentInfo']['org_id'] = $org_id;
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Organization information is empty !'
                );
                $this->set(compact('msg'));
                return;
            }

            $opt = $this->Session->read('Data.Mode');
            if (!$opt || $opt == 'insert') {
                $this->BasicModulePaymentInfo->create();
                $newData = $this->BasicModulePaymentInfo->save($reqData);

                if ($newData) {
                    $data_id = $newData['BasicModulePaymentInfo']['id'];
                    $this->Session->write('Data.Id', $data_id);
                    $this->Session->write('Data.Mode', 'update');
                    //$this->redirect(array('action' => 'preview', $data_id));
                }
            } else {
                $data_id = $this->Session->read('Data.Id');
                $this->BasicModulePaymentInfo->id = $data_id;
                if ($this->BasicModulePaymentInfo->save($reqData)) {
                    $this->redirect(array('action' => 'preview', $data_id));
                }
            }
        }
    }

    public function edit($id = null, $org_id = null, $back_opt = null) {

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }

        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($id) || empty($org_id) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($id)) {
                $msg['msg'] = 'Invalid payment information !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->BasicModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $paymentTypeOptions = $this->BasicModulePaymentInfo->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type')));
            $this->set(compact('back_opt', 'orgNameOptions', 'paymentTypeOptions'));

            $post = $this->BasicModulePaymentInfo->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid Payment Information');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid payment information !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->BasicModulePaymentInfo->id = $id;
            if ($this->BasicModulePaymentInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'preview', $id));
            }
        }
    }

    public function details($org_id = null, $id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }

        $data_count = 0;
        if (!empty($id)) {
            $allDataDetails = $this->BasicModulePaymentInfo->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->BasicModulePaymentInfo->find('all', array('conditions' => array('BasicModulePaymentInfo.org_id' => $org_id)));
            if (!empty($allDataDetails) && is_array($allDataDetails) && count($allDataDetails) > 0) {
                if (count($allDataDetails) === 1) {
                    $allDataDetails = $allDataDetails[0];
                    $data_count = 1;
                } else {
                    $data_count = 'all';
                }
            }
        }

        if (empty($allDataDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Payment data not found !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('data_count', 'allDataDetails'));
    }

    public function preview($id = null) {
        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment information !'
            );
            $this->set(compact('msg'));
        }

        $paymentDetails = $this->BasicModulePaymentInfo->findById($id);
        if (!$paymentDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Payment information !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'paymentDetails'));
    }
    

    public function viewP() {
        if ($this->request->is('post')) {
            $keyword = $this->request->data['BasicModulePaymentInfo']['search_keyword'];

            $condition = array("OR" => array("BasicModuleBasicInformation.full_name_of_org LIKE '%$keyword%'",
                    "BasicModuleBasicInformation.short_name_of_org LIKE '%$keyword%'",
                    "LookupPaymentType.payment_type LIKE '%$keyword%'",
                    "BasicModulePaymentInfo.paymentDocNumber LIKE '%$keyword%'"));
            $this->paginate = array(
                'order' => array('BasicModuleBasicInformation.full_name_of_org' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->BasicModulePaymentInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $value = $this->Paginator->paginate('BasicModulePaymentInfo');
        $this->set('values', $value);
    }

    public function addP() {
        $orgNameOptions = $this->BasicModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $paymentTypeOptions = $this->BasicModulePaymentInfo->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type')));
        $this->set(compact('orgNameOptions', 'paymentTypeOptions'));

        if ($this->request->is('post')) {
            $this->BasicModulePaymentInfo->create();
            if ($this->BasicModulePaymentInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function editP($id = null) {
        $orgNameOptions = $this->BasicModulePaymentInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $paymentTypeOptions = $this->BasicModulePaymentInfo->LookupPaymentType->find('list', array('fields' => array('LookupPaymentType.id', 'LookupPaymentType.payment_type')));
        $this->set(compact('orgNameOptions', 'paymentTypeOptions'));

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $post = $this->BasicModulePaymentInfo->findById($id);

        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->BasicModulePaymentInfo->id = $id;
            if ($this->BasicModulePaymentInfo->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function detailsP($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $paymentDetails = $this->BasicModulePaymentInfo->findById($id);
        if (!$paymentDetails) {
            throw new NotFoundException('Invalid Information');
        }

        $this->set(compact('paymentDetails'));
    }

}
