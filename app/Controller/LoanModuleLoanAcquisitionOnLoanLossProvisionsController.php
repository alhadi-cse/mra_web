<?php

App::uses('AppController', 'Controller');

class LoanModuleLoanAcquisitionOnLoanLossProvisionsController extends AppController {

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
            $condition = array('LoanModuleLoanAcquisitionOnLoanLossProvision.org_id' => $org_id);
        } else {
            $condition = array();
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LoanModuleLoanAcquisitionOnLoanLossProvision']['search_option'];
            $keyword = $this->request->data['LoanModuleLoanAcquisitionOnLoanLossProvision']['search_keyword'];

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

        $this->LoanModuleLoanAcquisitionOnLoanLossProvision->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LoanModuleLoanAcquisitionOnLoanLossProvision');

        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['LoanModuleLoanAcquisitionOnLoanLossProvision']['id'];

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

        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $branch_name_options = null;

        $loan_provision_type_options = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->LookupLoanLossProvisioning->find('list', array('fields' => array('LookupLoanLossProvisioning.id', 'LookupLoanLossProvisioning.loan_loss_provisionings')));

        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'branch_name_options', 'loan_provision_type_options'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['LoanModuleLoanAcquisitionOnLoanLossProvision']) {
                if (empty($reqData['LoanModuleLoanAcquisitionOnLoanLossProvision']['org_id']) && !empty($org_id))
                    $reqData['LoanModuleLoanAcquisitionOnLoanLossProvision']['org_id'] = $org_id;
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
                $this->LoanModuleLoanAcquisitionOnLoanLossProvision->create();
                $newData = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->save($reqData);

                if ($newData) {
                    $data_id = $newData['LoanModuleLoanAcquisitionOnLoanLossProvision']['id'];
                    $this->Session->write('Data.Id', $data_id);
                    $this->Session->write('Data.Mode', 'update');

                    $org_id = $newData['LoanModuleLoanAcquisitionOnLoanLossProvision']['org_id'];
                    $branch_name_options = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->BasicModuleBranchInfo->find('list', array(
                        'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                        'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                        'recursive' => -1
                    ));

                    $this->set(compact('org_id', 'branch_name_options'));
                }
            } else {
                $data_id = $this->Session->read('Data.Id');
                $this->LoanModuleLoanAcquisitionOnLoanLossProvision->id = $data_id;
                if ($this->LoanModuleLoanAcquisitionOnLoanLossProvision->save($reqData)) {
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
                $msg['msg'] = 'Invalid Loan Acquisition on Loan Loss Provision !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $branch_name_options = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->BasicModuleBranchInfo->find('list', array(
                'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                'recursive' => -1
            ));

            $loan_provision_type_options = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->LookupLoanLossProvisioning->find('list', array('fields' => array('LookupLoanLossProvisioning.id', 'LookupLoanLossProvisioning.loan_loss_provisionings')));

            $this->set(compact('back_opt', 'orgNameOptions', 'branch_name_options', 'loan_provision_type_options'));

            $post = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid Loan Acquisition on Loan Loss Provision');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Loan Acquisition on Loan Loss Provision !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->LoanModuleLoanAcquisitionOnLoanLossProvision->id = $id;
            if ($this->LoanModuleLoanAcquisitionOnLoanLossProvision->save($this->request->data)) {
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
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->find('all', array('conditions' => array('LoanModuleLoanAcquisitionOnLoanLossProvision.org_id' => $org_id)));
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
                'msg' => 'Invalid Loan Acquisition on Loan Loss Provision !'
            );
            $this->set(compact('msg'));
        }

        $loanDetails = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->findById($id);
        if (!$loanDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Loan Acquisition on Loan Loss Provision !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'loanDetails'));
    }

//    public $components = array('Paginator');
//    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function addP() {
        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $loan_provision_type_options = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->LookupLoanLossProvisioning->find('list', array('fields' => array('LookupLoanLossProvisioning.id', 'LookupLoanLossProvisioning.loan_provisionings')));
        if ($this->request->is('post')) {
            $this->LoanModuleLoanAcquisitionOnLoanLossProvision->create();
            if ($this->LoanModuleLoanAcquisitionOnLoanLossProvision->save($this->request->data)) {

                $this->redirect(array('action' => 'add'));
            }
        }
        $this->set(compact('orgNameOptions', 'loan_provision_type_options'));
    }

    function branch_select() {
        $org_id = $this->request->data['LoanModuleLoanAcquisitionOnLoanLossProvision']['org_id'];

        $branch_options = $this->LoanModuleLoanAcquisitionOnLoanLossProvision->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
            'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
            'recursive' => -1
        ));

        $this->set(compact('branch_options'));
        $this->layout = 'ajax';
    }

}
