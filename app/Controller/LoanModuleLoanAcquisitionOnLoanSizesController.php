<?php

App::uses('AppController', 'Controller');

class LoanModuleLoanAcquisitionOnLoanSizesController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($opt = null, $mode = null) {

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_id = $this->Session->read('User.GroupId');
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
        if ($user_group_id && $user_group_id == 1) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $org_id = $this->Session->read('Org.Id');

        if (!empty($org_id)) {
            $condition = array('LoanModuleLoanAcquisitionOnLoanSize.org_id' => $org_id);
        } else {
            $condition = array();
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LoanModuleLoanAcquisitionOnLoanSize']['search_option'];
            $keyword = $this->request->data['LoanModuleLoanAcquisitionOnLoanSize']['search_keyword'];

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

        $this->LoanModuleLoanAcquisitionOnLoanSize->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LoanModuleLoanAcquisitionOnLoanSize');

        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['LoanModuleLoanAcquisitionOnLoanSize']['id'];

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
        $user_group_id = $this->Session->read('User.GroupId');
        $org_id = $this->Session->read('Org.Id');

        if ((empty($org_id) && !empty($user_group_id) && $user_group_id != 1) || empty($IsValidUser)) {
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

        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnLoanSize->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $branch_name_options = null;

        $loan_size_partition_disbursed_options = $this->LoanModuleLoanAcquisitionOnLoanSize->LookupLoanSizePartitionOnDisburse->find('list', array('fields' => array('LookupLoanSizePartitionOnDisburse.id', 'LookupLoanSizePartitionOnDisburse.loan_size_partition_on_disbursed')));
        $loan_size_partition_outstanding_options = $this->LoanModuleLoanAcquisitionOnLoanSize->LookupLoanSizePartitionOnOutstanding->find('list', array('fields' => array('LookupLoanSizePartitionOnOutstanding.id', 'LookupLoanSizePartitionOnOutstanding.loan_size_partition_on_outstanding')));

        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'branch_name_options', 'loan_size_partition_disbursed_options', 'loan_size_partition_outstanding_options'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;

            debug($reqData);


            if ($reqData && $reqData['LoanModuleLoanAcquisitionOnLoanSize']) {
                if (empty($reqData['LoanModuleLoanAcquisitionOnLoanSize']['org_id']) && !empty($org_id))
                    $reqData['LoanModuleLoanAcquisitionOnLoanSize']['org_id'] = $org_id;
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
                $this->LoanModuleLoanAcquisitionOnLoanSize->create();
                $newData = $this->LoanModuleLoanAcquisitionOnLoanSize->save($reqData);

                if ($newData) {
                    $data_id = $newData['LoanModuleLoanAcquisitionOnLoanSize']['id'];
                    $this->Session->write('Data.Id', $data_id);
                    $this->Session->write('Data.Mode', 'update');

                    $org_id = $newData['LoanModuleLoanAcquisitionOnLoanSize']['org_id'];
                    $branch_name_options = $this->LoanModuleLoanAcquisitionOnLoanSize->BasicModuleBranchInfo->find('list', array(
                        'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                        'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                        'recursive' => -1
                    ));
                    debug($newData);
                    $this->set(compact('org_id', 'branch_name_options'));
                }
            } else {
                $data_id = $this->Session->read('Data.Id');
                $this->LoanModuleLoanAcquisitionOnLoanSize->id = $data_id;
                if ($this->LoanModuleLoanAcquisitionOnLoanSize->save($reqData)) {
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
                $msg['msg'] = 'Invalid Loan Acquisition on Loan Size !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->LoanModuleLoanAcquisitionOnLoanSize->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $branch_name_options = $this->LoanModuleLoanAcquisitionOnLoanSize->BasicModuleBranchInfo->find('list', array(
                'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                'recursive' => -1
            ));

            $loan_size_partition_disbursed_options = $this->LoanModuleLoanAcquisitionOnLoanSize->LookupLoanSizePartitionOnDisburse->find('list', array('fields' => array('LookupLoanSizePartitionOnDisburse.id', 'LookupLoanSizePartitionOnDisburse.loan_size_partition_on_disbursed')));
            $loan_size_partition_outstanding_options = $this->LoanModuleLoanAcquisitionOnLoanSize->LookupLoanSizePartitionOnOutstanding->find('list', array('fields' => array('LookupLoanSizePartitionOnOutstanding.id', 'LookupLoanSizePartitionOnOutstanding.loan_size_partition_on_outstanding')));

            $this->set(compact('back_opt', 'orgNameOptions', 'branch_name_options', 'loan_size_partition_disbursed_options', 'loan_size_partition_outstanding_options'));

            $post = $this->LoanModuleLoanAcquisitionOnLoanSize->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid Loan Acquisition on Loan Size');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Loan Acquisition on Loan Size !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->LoanModuleLoanAcquisitionOnLoanSize->id = $id;
            if ($this->LoanModuleLoanAcquisitionOnLoanSize->save($this->request->data)) {
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
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnLoanSize->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnLoanSize->find('all', array('conditions' => array('LoanModuleLoanAcquisitionOnLoanSize.org_id' => $org_id)));
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
                'msg' => 'Case data not found !'
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
                'msg' => 'Invalid Loan Acquisition on Loan Size !'
            );
            $this->set(compact('msg'));
        }

        $loanDetails = $this->LoanModuleLoanAcquisitionOnLoanSize->findById($id);
        if (!$loanDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Loan Acquisition on Loan Size !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'loanDetails'));
    }

    function branch_select() {
        $org_id = $this->request->data['LoanModuleLoanAcquisitionOnLoanSize']['org_id'];

        $branch_options = $this->LoanModuleLoanAcquisitionOnLoanSize->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
            'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
            'recursive' => -1
        ));

        $this->set(compact('branch_options'));
        $this->layout = 'ajax';
    }

//    public $components = array('Paginator');
//    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
//    
    public function addP() {
        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnLoanSize->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $loan_size_partition_disbursed_options = $this->LoanModuleLoanAcquisitionOnLoanSize->LookupLoanSizePartition->find('list', array('fields' => array('LookupLoanSizePartition.id', 'LookupLoanSizePartition.loan_size_partitions')));
        $loan_size_partition_outstanding_options = $loan_size_partition_disbursed_options;
        $this->set(compact('orgNameOptions', 'loan_size_partition_disbursed_options', 'loan_size_partition_outstanding_options'));

        if ($this->request->is('post')) {
            $this->LoanModuleLoanAcquisitionOnLoanSize->create();
            if ($this->LoanModuleLoanAcquisitionOnLoanSize->save($this->request->data)) {

                $this->redirect(array('action' => 'add'));
            }
        }
    }

}
