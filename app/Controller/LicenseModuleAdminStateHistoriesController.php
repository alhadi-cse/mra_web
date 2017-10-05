<?php

App::uses('AppController', 'Controller');

class LicenseModuleAdminStateHistoriesController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($mode = null) {

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($IsValidUser) || !in_array(1,$user_group_id)) {

            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information(only for admin) !'
            );
            $this->set(compact('msg'));
            return;
        }
        $current_value = $this->LicenseModuleAdminStateHistory->find('first', array('conditions' => array('LicenseModuleAdminStateHistory.is_current' => 1)));

        $condition = array();
        if (!empty($current_value)) {
            $current_year = $current_value['LicenseModuleAdminStateHistory']['licensing_year'];
            $condition = array('LicenseModuleAdminStateHistory.licensing_year' => $current_year);
        }

        $current_year_values = $this->LicenseModuleAdminStateHistory->find('all', array('conditions' => $condition));
        $this->set(compact('IsValidUser', 'user_group_id'));
        $this->paginate = array(
            'limit' => 10,
            'order' => array('LicenseModuleAdminStateHistory.licensing_year' => 'asc'),
            'conditions' => array('NOT' => $condition));

        $this->LicenseModuleAdminStateHistory->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $previous_year_values = $this->Paginator->paginate('LicenseModuleAdminStateHistory');
        $this->set(compact('current_year_values', 'previous_year_values'));
    }

    public function update_state() {
        $this->loadModel('LicenseModuleLicensingYear');
        $licensing_year_options = $this->LicenseModuleLicensingYear->find('list', array('fields' => array('LicenseModuleLicensingYear.licensing_year', 'LicenseModuleLicensingYear.licensing_year'), 'conditions' => array('LicenseModuleLicensingYear.is_current_year' => 1)));
        $value = $this->LicenseModuleAdminStateHistory->find('all', array('fields' => array('LicenseModuleAdminStateHistory.licensing_year')));
        $licensing_years = hash::extract($value, '{n}.LicenseModuleAdminStateHistory.licensing_year');
        $licensing_years = array_values(array_unique($licensing_years, SORT_REGULAR));
        $current_licensing_years = array();
        $licensing_state_ids = array();


        $completion_status_options = array('1' => 'Running');
        $this->set(compact('licensing_year_options', 'state_options', 'completion_status_options', 'current_licensing_years'));

        if ($this->request->is('post')) {
            if (!empty($this->request->data)) {
                $starting_date_array = new DateTime(implode('-', array(
                            $this->request->data['LicenseModuleAdminStateHistory']['starting_date']['year'],
                            $this->request->data['LicenseModuleAdminStateHistory']['starting_date']['month'],
                            $this->request->data['LicenseModuleAdminStateHistory']['starting_date']['day']
                )));
                $state_completion_days = $this->request->data['LicenseModuleAdminStateHistory']['state_completion_days'];
                $starting_date = $starting_date_array->format('Y-m-d');
                $starting_date_array->add(new DateInterval('P' . $state_completion_days . 'D'));
                $ending_date = $starting_date_array->format('Y-m-d');
                $data_to_save = array('licensing_year' => $this->request->data['LicenseModuleAdminStateHistory']['licensing_year'],
                    'licensing_state_id' => $this->request->data['LicenseModuleAdminStateHistory']['licensing_state_id'],
                    'state_completion_days' => $this->request->data['LicenseModuleAdminStateHistory']['state_completion_days'],
                    'starting_date' => $starting_date, //$this->request->data['LicenseModuleAdminStateHistory']['starting_date'],
                    'ending_date' => $ending_date, //$this->request->data['LicenseModuleAdminStateHistory']['ending_date'],
                    'user_name' => '',
                    'is_current' => $this->request->data['LicenseModuleAdminStateHistory']['is_current'],
                );
                $condition = array('LicenseModuleAdminStateHistory.is_current' => 1);
                $this->LicenseModuleAdminStateHistory->updateAll(array('is_current' => 0), $condition);

                $this->LicenseModuleAdminStateHistory->create();
                if ($this->LicenseModuleAdminStateHistory->save($data_to_save)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
        }
    }

    public function select_year_wise_state() {
        $licensing_year = $this->request->data['LicenseModuleAdminStateHistory']['licensing_year'];
        $licensing_state_ids = array();
        $currently_available_states = $this->LicenseModuleAdminStateHistory->find('all', array('fields' => array('LicenseModuleAdminStateHistory.licensing_state_id'), 'conditions' => array('LicenseModuleAdminStateHistory.licensing_year' => $licensing_year)));
        $licensing_state_ids = hash::extract($currently_available_states, '{n}.LicenseModuleAdminStateHistory.licensing_state_id');
        $state_options = $this->LicenseModuleAdminStateHistory->LicenseModuleAdminStateName->find('list', array('fields' => array('LicenseModuleAdminStateName.id', 'LicenseModuleAdminStateName.state_title'), 'conditions' => array("Not" => array('LicenseModuleAdminStateName.id' => $licensing_state_ids))));

        $this->set(compact('state_options'));
        $this->layout = 'ajax';
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

            $orgNameOptions = $this->LicenseModuleAdminStateHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.OrgFullName')));
            $branch_name_options = $this->LicenseModuleAdminStateHistory->BasicModuleBranchInfo->find('list', array(
                'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
                'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
                'recursive' => -1
            ));

            $loan_activity_category_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

            $loan_activity_category_id = $this->LicenseModuleAdminStateHistory->find('list', array('fields' => array('LicenseModuleAdminStateHistory.loan_activity_category_id'), 'conditions' => array("AND" => array('LicenseModuleAdminStateHistory.id' => $id, 'LicenseModuleAdminStateHistory.org_id' => $org_id))));
            $subcategory_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivitySubcategory->find('list', array(
                'fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory'),
                'conditions' => array('LookupLoanActivitySubcategory.loan_activity_category_id' => $loan_activity_category_id),
                'recursive' => -1
            ));

            $loan_activity_subcategory_id = $this->LicenseModuleAdminStateHistory->find('list', array('fields' => array('LicenseModuleAdminStateHistory.loan_activity_subcategory_id'), 'conditions' => array("AND" => array('LicenseModuleAdminStateHistory.id' => $id, 'LicenseModuleAdminStateHistory.org_id' => $org_id))));
            $scheme_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivityScheme->find('list', array(
                'fields' => array('LookupLoanActivityScheme.id', 'LookupLoanActivityScheme.loan_activity_scheme'),
                'conditions' => array('LookupLoanActivityScheme.loan_activity_subcategory_id' => $loan_activity_subcategory_id),
                'recursive' => -1
            ));

            $this->set(compact('back_opt', 'orgNameOptions', 'branch_name_options', 'loan_activity_category_options', 'subcategory_options', 'scheme_options'));

            $post = $this->LicenseModuleAdminStateHistory->findById($id);
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
            $this->LicenseModuleAdminStateHistory->id = $id;
            if ($this->LicenseModuleAdminStateHistory->save($this->request->data)) {
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
            $allDataDetails = $this->LicenseModuleAdminStateHistory->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->LicenseModuleAdminStateHistory->find('all', array('conditions' => array('LicenseModuleAdminStateHistory.org_id' => $org_id)));
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

        $loanDetails = $this->LicenseModuleAdminStateHistory->findById($id);
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
        $orgNameOptions = $this->LicenseModuleAdminStateHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $loan_activity_category_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

        if ($this->request->is('post')) {
            $this->LicenseModuleAdminStateHistory->create();
            if ($this->LicenseModuleAdminStateHistory->save($this->request->data)) {

                $this->redirect(array('action' => 'add'));
            }
        }
        $this->set(compact('orgNameOptions', 'loan_activity_category_options'));
    }

    public function editP($id = null, $org_id = null) {

        $orgNameOptions = $this->LicenseModuleAdminStateHistory->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));

        $branch_id = $this->LicenseModuleAdminStateHistory->find('list', array('fields' => array('LicenseModuleAdminStateHistory.branch_id'), 'conditions' => array("AND" => array('LicenseModuleAdminStateHistory.id' => $id, 'LicenseModuleAdminStateHistory.org_id' => $org_id))));
        $branch_name_options = $this->LicenseModuleAdminStateHistory->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name'),
            'conditions' => array('BasicModuleBranchInfo.id' => $branch_id),
            'recursive' => -1
        ));

        $loan_activity_category_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivityCategory->find('list', array('fields' => array('LookupLoanActivityCategory.id', 'LookupLoanActivityCategory.loan_activity_category')));

        $loan_activity_category_id = $this->LicenseModuleAdminStateHistory->find('list', array('fields' => array('LicenseModuleAdminStateHistory.loan_activity_category_id'), 'conditions' => array("AND" => array('LicenseModuleAdminStateHistory.id' => $id, 'LicenseModuleAdminStateHistory.org_id' => $org_id))));
        $subcategory_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivitySubcategory->find('list', array(
            'fields' => array('LookupLoanActivitySubcategory.id', 'LookupLoanActivitySubcategory.loan_activity_subcategory'),
            'conditions' => array('LookupLoanActivitySubcategory.loan_activity_category_id' => $loan_activity_category_id),
            'recursive' => -1
        ));

        $loan_activity_subcategory_id = $this->LicenseModuleAdminStateHistory->find('list', array('fields' => array('LicenseModuleAdminStateHistory.loan_activity_subcategory_id'), 'conditions' => array("AND" => array('LicenseModuleAdminStateHistory.id' => $id, 'LicenseModuleAdminStateHistory.org_id' => $org_id))));
        $scheme_options = $this->LicenseModuleAdminStateHistory->LookupLoanActivityScheme->find('list', array(
            'fields' => array('LookupLoanActivityScheme.id', 'LookupLoanActivityScheme.loan_activity_scheme'),
            'conditions' => array('LookupLoanActivityScheme.loan_activity_subcategory_id' => $loan_activity_subcategory_id),
            'recursive' => -1
        ));

        $this->set(compact('orgNameOptions', 'loan_activity_category_options', 'subcategory_options', 'scheme_options', 'branch_name_options'));

        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $post = $this->LicenseModuleAdminStateHistory->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LicenseModuleAdminStateHistory->id = $id;
            if ($this->LicenseModuleAdminStateHistory->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

}
