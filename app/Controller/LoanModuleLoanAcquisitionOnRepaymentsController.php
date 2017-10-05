<?php

App::uses('AppController', 'Controller');

class LoanModuleLoanAcquisitionOnRepaymentsController extends AppController {

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
            $condition = array('LoanModuleLoanAcquisitionOnRepayment.org_id' => $org_id);
        } else {
            $condition = array();
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LoanModuleLoanAcquisitionOnRepayment']['search_option'];
            $keyword = $this->request->data['LoanModuleLoanAcquisitionOnRepayment']['search_keyword'];

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

        $this->LoanModuleLoanAcquisitionOnRepayment->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LoanModuleLoanAcquisitionOnRepayment');

        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['LoanModuleLoanAcquisitionOnRepayment']['id'];

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

        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnRepayment->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $branch_name_options = null;

        $repayment_mode_type_options = $this->LoanModuleLoanAcquisitionOnRepayment->LookupLoanRepaymentMode->find('list', array('fields' => array('LookupLoanRepaymentMode.id', 'LookupLoanRepaymentMode.repayment_modes')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'branch_name_options', 'repayment_mode_type_options'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['LoanModuleLoanAcquisitionOnRepayment']) {
                if (empty($reqData['LoanModuleLoanAcquisitionOnRepayment']['org_id']) && !empty($org_id))
                    $reqData['LoanModuleLoanAcquisitionOnRepayment']['org_id'] = $org_id;
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
                $this->LoanModuleLoanAcquisitionOnRepayment->create();
                $newData = $this->LoanModuleLoanAcquisitionOnRepayment->save($reqData);

                if ($newData) {
                    $data_id = $newData['LoanModuleLoanAcquisitionOnRepayment']['id'];
                    $this->Session->write('Data.Id', $data_id);
                    $this->Session->write('Data.Mode', 'update');

                    $org_id = $newData['LoanModuleLoanAcquisitionOnRepayment']['org_id'];
                    $branch_name_options = $this->LoanModuleLoanAcquisitionOnRepayment->BasicModuleBranchInfo->find('list', array(
                        'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                        'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                        'recursive' => -1
                    ));

                    $this->set(compact('org_id', 'branch_name_options'));
                }
            } else {
                $data_id = $this->Session->read('Data.Id');
                $this->LoanModuleLoanAcquisitionOnRepayment->id = $data_id;
                if ($this->LoanModuleLoanAcquisitionOnRepayment->save($reqData)) {
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
                $msg['msg'] = 'Invalid Loan Acquisition on Repayment !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->LoanModuleLoanAcquisitionOnRepayment->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $branch_name_options = $this->LoanModuleLoanAcquisitionOnRepayment->BasicModuleBranchInfo->find('list', array(
                'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                'recursive' => -1
            ));

            $repayment_mode_type_options = $this->LoanModuleLoanAcquisitionOnRepayment->LookupLoanRepaymentMode->find('list', array('fields' => array('LookupLoanRepaymentMode.id', 'LookupLoanRepaymentMode.repayment_modes')));

            $this->set(compact('back_opt', 'orgNameOptions', 'branch_name_options', 'repayment_mode_type_options'));

            $post = $this->LoanModuleLoanAcquisitionOnRepayment->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid Loan Acquisition on Repayment');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Loan Acquisition on Repayment !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->LoanModuleLoanAcquisitionOnRepayment->id = $id;
            if ($this->LoanModuleLoanAcquisitionOnRepayment->save($this->request->data)) {
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
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnRepayment->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnRepayment->find('all', array('conditions' => array('LoanModuleLoanAcquisitionOnRepayment.org_id' => $org_id)));
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
                'msg' => 'Invalid Loan Acquisition on Repayment !'
            );
            $this->set(compact('msg'));
        }

        $loanDetails = $this->LoanModuleLoanAcquisitionOnRepayment->findById($id);
        if (!$loanDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Loan Acquisition on Repayment !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'loanDetails'));
    }

    public function addP() {
        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnRepayment->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $repayment_mode_type_options = $this->LoanModuleLoanAcquisitionOnRepayment->LookupLoanRepaymentMode->find('list', array('fields' => array('LookupLoanRepaymentMode.id', 'LookupLoanRepaymentMode.repayment_modes')));
        if ($this->request->is('post')) {
            $this->LoanModuleLoanAcquisitionOnRepayment->create();
            if ($this->LoanModuleLoanAcquisitionOnRepayment->save($this->request->data)) {

                $this->redirect(array('action' => 'add'));
            }
        }
        $this->set(compact('orgNameOptions', 'repayment_mode_type_options'));
    }

    function branch_select() {
        $org_id = $this->request->data['LoanModuleLoanAcquisitionOnRepayment']['org_id'];

        $branch_options = $this->LoanModuleLoanAcquisitionOnRepayment->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
            'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
            'recursive' => -1
        ));

        $this->set(compact('branch_options'));
        $this->layout = 'ajax';
    }

}
