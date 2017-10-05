
<?php

App::uses('AppController', 'Controller');

class LookupLoanRepaymentModesController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 5, 'order' => array('LookupLoanRepaymentMode.serial_no' => 'ASC'));

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLoanRepaymentMode']['search_option'];
            $keyword = $this->request->data['LookupLoanRepaymentMode']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('LookupLoanRepaymentMode.serial_no' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LookupLoanRepaymentMode->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLoanRepaymentMode');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLoanRepaymentMode->save($this->request->data)) {
                $this->LookupLoanRepaymentMode->save($this->request->data);
                $this->redirect(array('action' => 'view'));
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Invalid Data'
                );
                $this->set(compact('msg'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Repayment Mode');
        }

        $post = $this->LookupLoanRepaymentMode->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Repayment Mode');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLoanRepaymentMode->id = $id;
            if ($this->LookupLoanRepaymentMode->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Invalid Data'
                );
                $this->set(compact('msg'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLoanRepaymentMode->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
