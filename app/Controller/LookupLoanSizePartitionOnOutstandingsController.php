
<?php

App::uses('AppController', 'Controller');

class LookupLoanSizePartitionOnOutstandingsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 5, 'order' => array('LookupLoanSizePartitionOnOutstanding.serial_no' => 'ASC'));

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLoanSizePartitionOnOutstanding']['search_option'];
            $keyword = $this->request->data['LookupLoanSizePartitionOnOutstanding']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('LookupLoanSizePartitionOnOutstanding.serial_no' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LookupLoanSizePartitionOnOutstanding->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLoanSizePartitionOnOutstanding');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLoanSizePartitionOnOutstanding->save($this->request->data)) {
                $this->LookupLoanSizePartitionOnOutstanding->save($this->request->data);
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
            throw new NotFoundException('Invalid Loan Size Partition On Loan Outstanding');
        }

        $post = $this->LookupLoanSizePartitionOnOutstanding->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Loan Size Partition On Loan Outstanding');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLoanSizePartitionOnOutstanding->id = $id;
            if ($this->LookupLoanSizePartitionOnOutstanding->save($this->request->data)) {
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
        if ($this->LookupLoanSizePartitionOnOutstanding->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
