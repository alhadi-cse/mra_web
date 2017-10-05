<?php

App::uses('AppController', 'Controller');

class LoanModuleLoanAcquisitionOnActivitiesOrProjectsController extends AppController {

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
            $condition = array('LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id);
        } else {
            $condition = array();
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LoanModuleLoanAcquisitionOnActivitiesOrProject']['search_option'];
            $keyword = $this->request->data['LoanModuleLoanAcquisitionOnActivitiesOrProject']['search_keyword'];

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

        $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LoanModuleLoanAcquisitionOnActivitiesOrProject');

        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['LoanModuleLoanAcquisitionOnActivitiesOrProject']['id'];

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

        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $branch_name_options = null;

        $loan_activity_category_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'branch_name_options', 'loan_activity_category_options'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['LoanModuleLoanAcquisitionOnActivitiesOrProject']) {
                if (empty($reqData['LoanModuleLoanAcquisitionOnActivitiesOrProject']['org_id']) && !empty($org_id))
                    $reqData['LoanModuleLoanAcquisitionOnActivitiesOrProject']['org_id'] = $org_id;
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
                $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->create();
                $newData = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->save($reqData);

                if ($newData) {
                    $data_id = $newData['LoanModuleLoanAcquisitionOnActivitiesOrProject']['id'];
                    $this->Session->write('Data.Id', $data_id);
                    $this->Session->write('Data.Mode', 'update');

                    $org_id = $newData['LoanModuleLoanAcquisitionOnActivitiesOrProject']['org_id'];
                    $branch_name_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBranchInfo->find('list', array(
                        'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                        'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                        'recursive' => -1
                    ));

                    $this->set(compact('org_id', 'branch_name_options'));
                }
            } else {
                $data_id = $this->Session->read('Data.Id');
                $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->id = $data_id;
                if ($this->LoanModuleLoanAcquisitionOnActivitiesOrProject->save($reqData)) {
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
                $msg['msg'] = 'Invalid Loan Acquisition on Activities or Projects !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $branch_name_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBranchInfo->find('list', array(
                'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                'recursive' => -1
            ));

            $loan_activity_category_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

            $loan_activity_category_id = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->find('list', array('fields' => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.loan_activity_category_id'), 'conditions' => array("AND" => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.id' => $id, 'LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id))));
            $subcategory_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivitySubcategory->find('list', array(
                'fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory'),
                'conditions' => array('LookupLoanActivitySubcategory.loan_activity_category_id' => $loan_activity_category_id),
                'recursive' => -1
            ));

            $loan_activity_subcategory_id = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->find('list', array('fields' => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.loan_activity_subcategory_id'), 'conditions' => array("AND" => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.id' => $id, 'LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id))));
            $scheme_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityScheme->find('list', array(
                'fields' => array('LookupLoanActivityScheme.id', 'LookupLoanActivityScheme.loan_activity_scheme'),
                'conditions' => array('LookupLoanActivityScheme.loan_activity_subcategory_id' => $loan_activity_subcategory_id),
                'recursive' => -1
            ));

            $this->set(compact('back_opt', 'orgNameOptions', 'branch_name_options', 'loan_activity_category_options', 'subcategory_options', 'scheme_options'));

            $post = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid Loan Acquisition on Activities or Projects');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Loan Acquisition on Activities or Projects !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->id = $id;
            if ($this->LoanModuleLoanAcquisitionOnActivitiesOrProject->save($this->request->data)) {
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
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->find('all', array('conditions' => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id)));
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
                'msg' => 'Invalid Loan Acquisition on Activities or Projects !'
            );
            $this->set(compact('msg'));
        }

        $loanDetails = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->findById($id);
        if (!$loanDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Loan Acquisition on Activities or Projects !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'loanDetails'));
    }

    public function addP() {
        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $loan_activity_category_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

        if ($this->request->is('post')) {
            $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->create();
            if ($this->LoanModuleLoanAcquisitionOnActivitiesOrProject->save($this->request->data)) {

                $this->redirect(array('action' => 'add'));
            }
        }
        $this->set(compact('orgNameOptions', 'loan_activity_category_options'));
    }

    public function editP($id = null, $org_id = null) {

        $orgNameOptions = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));

        $branch_id = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->find('list', array('fields' => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.branch_id'), 'conditions' => array("AND" => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.id' => $id, 'LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id))));
        $branch_name_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
            'conditions' => array('BasicModuleBranchInfo.id' => $branch_id),
            'recursive' => -1
        ));

        $loan_activity_category_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

        $loan_activity_category_id = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->find('list', array('fields' => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.loan_activity_category_id'), 'conditions' => array("AND" => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.id' => $id, 'LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id))));
        $subcategory_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivitySubcategory->find('list', array(
            'fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory'),
            'conditions' => array('LookupLoanActivitySubcategory.loan_activity_category_id' => $loan_activity_category_id),
            'recursive' => -1
        ));

        $loan_activity_subcategory_id = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->find('list', array('fields' => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.loan_activity_subcategory_id'), 'conditions' => array("AND" => array('LoanModuleLoanAcquisitionOnActivitiesOrProject.id' => $id, 'LoanModuleLoanAcquisitionOnActivitiesOrProject.org_id' => $org_id))));
        $scheme_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityScheme->find('list', array(
            'fields' => array('LookupLoanActivityScheme.id', 'LookupLoanActivityScheme.loan_activity_scheme'),
            'conditions' => array('LookupLoanActivityScheme.loan_activity_subcategory_id' => $loan_activity_subcategory_id),
            'recursive' => -1
        ));

        $this->set(compact('orgNameOptions', 'loan_activity_category_options', 'subcategory_options', 'scheme_options', 'branch_name_options'));

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $post = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->id = $id;
            if ($this->LoanModuleLoanAcquisitionOnActivitiesOrProject->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    function branch_select() {
        $org_id = $this->request->data['LoanModuleLoanAcquisitionOnActivitiesOrProject']['org_id'];

        $branch_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
            'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
            'recursive' => -1
        ));

        $this->set(compact('branch_options'));
        $this->layout = 'ajax';
    }

    function loan_activity_subcategory_select() {
        $loan_activity_category_id = $this->request->data['LoanModuleLoanAcquisitionOnActivitiesOrProject']['loan_activity_category_id'];

        $loan_activity_subcategory_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivitySubcategory->find('list', array(
            'fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory'),
            'conditions' => array('LookupLoanActivitySubcategory.loan_activity_category_id' => $loan_activity_category_id),
            'recursive' => -1
        ));

        $this->set(compact('loan_activity_subcategory_options'));
        $this->layout = 'ajax';
    }

    function loan_activity_scheme_select() {
        $loan_activity_subcategory_id = $this->request->data['LoanModuleLoanAcquisitionOnActivitiesOrProject']['loan_activity_subcategory_id'];

        $loan_activity_scheme_options = $this->LoanModuleLoanAcquisitionOnActivitiesOrProject->LookupLoanActivityScheme->find('list', array(
            'fields' => array('LookupLoanActivityScheme.id', 'LookupLoanActivityScheme.loan_activity_scheme'),
            'conditions' => array('LookupLoanActivityScheme.loan_activity_subcategory_id' => $loan_activity_subcategory_id),
            'recursive' => -1
        ));

        $this->set(compact('loan_activity_scheme_options'));
        $this->layout = 'ajax';
    }

}
