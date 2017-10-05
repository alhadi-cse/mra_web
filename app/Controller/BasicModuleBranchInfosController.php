<?php

App::uses('AppController', 'Controller');
App::uses('File', 'Utility');
App::uses('ExportComponent', 'Export.Controller/Component');

//App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel.php'));

class BasicModuleBranchInfosController extends AppController {

    public $components = array('Paginator', 'Export.Export');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($opt = null) {
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

        //$opt_all = false;
        //$opt_all = (empty($opt) || $opt != 'all');
//        if (empty($opt) || $opt == 'all') {
//            $opt_all = false;
//            $this->Session->write('Search.Options', null);
//        }
//        if ($user_group_id && in_array(1, $user_group_id)) {
//            if ($opt && $opt == 'all')
//                $this->Session->write('Org.Id', null);
//            else
//                $opt_all = true;
//        }

        $org_id = $this->Session->read('Org.Id');

        $condition = array();

        if (!empty($org_id)) {
            $condition = array('BasicModuleBranchInfo.org_id' => $org_id);
        }

        if ($this->request->is('post')) {
            $search_options = $this->request->data['SearchOption'];
            if (!empty($search_options) && !empty($search_options['search_option']) && !empty($search_options['search_keyword'])) {
                $option = $search_options['search_option'];
                $keyword = $search_options['search_keyword'];

                if (!empty($condition)) {
                    $condition = array_merge(array("$option LIKE '%$keyword%'"), $condition);
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;

                $this->request->params['named']['page'] = 1;

                $this->Session->write('Search.Options', $search_options);
            } else {
                $opt_all = false;
                $this->Session->write('Search.Options', null);
            }
        } else {

            if (!empty($opt) && $opt == 'all') {
                $opt_all = false;
                $this->Session->write('Search.Options', null);
            } else {
                $search_options = $this->Session->read('Search.Options');

                if (!empty($search_options) && !empty($search_options['search_option']) && !empty($search_options['search_keyword'])) {
                    $option = $search_options['search_option'];
                    $keyword = $search_options['search_keyword'];

                    if (!empty($condition)) {
                        $condition = array_merge(array("$option LIKE '%$keyword%'"), $condition);
                    } else {
                        $condition = array("$option LIKE '%$keyword%'");
                    }
                    $opt_all = true;

                    $this->request->data['SearchOption'] = $search_options;
                } else {
                    $opt_all = false;
                    $this->Session->write('Search.Options', null);
                }
            }
        }

        $joins = array(
            array(
                'table' => 'basic_module_basic_informations',
                'alias' => 'BasicModuleBasicInformation',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id')
            ),
            array(
                'table' => 'lookup_basic_office_types',
                'alias' => 'LookupBasicOfficeType',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.office_type_id = LookupBasicOfficeType.id')
            ),
            array(
                'table' => 'lookup_admin_boundary_districts',
                'alias' => 'LookupAdminBoundaryDistrict',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id')
            ),
            array(
                'table' => 'lookup_admin_boundary_upazilas',
                'alias' => 'LookupAdminBoundaryUpazila',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.upazila_id = LookupAdminBoundaryUpazila.id')
            )
        );

        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];

        $total_branch_count = $this->BasicModuleBranchInfo->find('count', array('fields' => 'BasicModuleBranchInfo.id', 'joins' => $joins, 'conditions' => $condition, 'recursive' => -1));


        $page_limit = 20;
        $this->paginate = array(
            'joins' => $joins,
            'limit' => $page_limit,
            'conditions' => $condition,
            'fields' => array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.name_of_org',
                'BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_name', 'LookupBasicOfficeType.office_type',
                'LookupAdminBoundaryDistrict.district_name', 'LookupAdminBoundaryUpazila.upazila_name',
                'BasicModuleBranchInfo.image_name', 'BasicModuleBranchInfo.is_active'),
            'order' => array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.office_type_id'),
            'recursive' => -1);

        $this->Paginator->settings = $this->paginate;
        $all_branch_info = $this->Paginator->paginate('BasicModuleBranchInfo');

        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'all_branch_info', 'total_branch_count', 'page_limit'));
    }

    public function add($org_id = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');

        if ((empty($org_id) && !empty($user_group_id) && !in_array(1, $user_group_id)) || empty($IsValidUser)) {
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

        $orgNameOptions = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $officeTypeOptions = $this->BasicModuleBranchInfo->LookupBasicOfficeType->find('list', array('fields' => array('LookupBasicOfficeType.id', 'LookupBasicOfficeType.office_type'), 'group' => array('LookupBasicOfficeType.id')));
        $districtsOptions = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name'), 'group' => array('LookupAdminBoundaryDistrict.id')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'officeTypeOptions', 'districtsOptions'));

        if ($this->request->is('post')) {
            $reqData = $this->request->data;
            if (!empty($reqData) && !empty($reqData['BasicModuleBranchInfo'])) {
                $branch_code = $reqData['BasicModuleBranchInfo']['branch_code'];
                if ($reqData['BasicModuleBranchInfo']['office_type_id'] == 1) {
                    $branch_serial = 1;
                } else {
                    $branch_serial = $this->serial_generator_by_max('BasicModuleBranchInfo', 'branch_serial', array('org_id' => $org_id));
                    if ($branch_serial == 1) {
                        $branch_serial = $branch_serial + 1;
                    }
                }
                $org_serial = sprintf("%04s", $org_id);
                $new_branch_serial = sprintf("%04s", $branch_serial);
                $branch_id = $org_serial . "" . $new_branch_serial;

                $reqData['BasicModuleBranchInfo']['id'] = $branch_id;
                $reqData['BasicModuleBranchInfo']['branch_serial'] = $branch_serial;

                if (empty($reqData['BasicModuleBranchInfo']['org_id']) && !empty($org_id))
                    $reqData['BasicModuleBranchInfo']['org_id'] = $org_id;
            }
            else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Organization information is empty !'
                );
                $this->set(compact('msg'));
                return;
            }
            if (!empty($reqData) && $reqData['BasicModuleBranchInfo']['office_type_id'] == 1) {
                $this->BasicModuleBranchInfo->validate['email_address'] = array(
                    'required' => array(
                        'required' => true,
                        'allowEmpty' => false,
                        'rule' => array('notBlank'),
                        'on' => 'null',
                        'message' => 'A email is required'
                    ),
                    'mailFormat' => array(
                        'rule' => 'email',
                        'message' => 'Email format is invalid'
                    )
                );
            }
            //debug($reqData);
            $this->BasicModuleBranchInfo->set($reqData);
            if ($this->BasicModuleBranchInfo->validates()) {
                $is_exists_office_type_values = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => 1)));
                $is_exists_office_code_values = array();
                if (!empty($branch_code)) {
                    $is_exists_office_code_values = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.branch_code' => $branch_code)));
                }
                if (!empty($is_exists_office_type_values) && !empty($reqData) && $reqData['BasicModuleBranchInfo']['office_type_id'] == 1) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Head office information of this organization is already exist'
                    );
                    $this->set(compact('msg'));
                } elseif (!empty($is_exists_office_code_values) && !empty($reqData) && $reqData['BasicModuleBranchInfo']['branch_code'] == $branch_code) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Office code of this organization is already exist'
                    );
                    $this->set(compact('msg'));
                } else {
                    $this->BasicModuleBranchInfo->create();
                    $newData = $this->BasicModuleBranchInfo->save($reqData);
                    if ($newData) {
                        $data_id = $newData['BasicModuleBranchInfo']['id'];
//                        $data = array('org_id' => $org_id, 'branch_id' => $data_id);

                        $this->Session->write('Data.Id', $data_id);
                        $this->Session->write('Data.Mode', 'update');
                        $this->redirect(array('action' => 'add_branch_image', $data_id, $org_id));
                    }
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Enter required information first.'
                );
                $this->set(compact('msg'));
            }
        }
    }

    public function edit($branch_id = null, $org_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            if (empty($branch_id)) {
                $msg['msg'] = 'Invalid Branch data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }
            $this->set(compact('msg'));
            return;
        }

        $orgNameOptions = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.name_of_org')));
        $officeTypeOptions = $this->BasicModuleBranchInfo->LookupBasicOfficeType->find('list', array('fields' => array('LookupBasicOfficeType.id', 'LookupBasicOfficeType.office_type')));
        $districtsOptions = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $district_id = $this->BasicModuleBranchInfo->find('list', array('fields' => array('BasicModuleBranchInfo.district_id'), 'conditions' => array("AND" => array('BasicModuleBranchInfo.id' => $branch_id, 'BasicModuleBranchInfo.org_id' => $org_id))));
        $upazila_id = $this->BasicModuleBranchInfo->find('list', array('fields' => array('BasicModuleBranchInfo.upazila_id'), 'conditions' => array("AND" => array('BasicModuleBranchInfo.id' => $branch_id, 'BasicModuleBranchInfo.org_id' => $org_id))));
        $union_id = $this->BasicModuleBranchInfo->find('list', array('fields' => array('BasicModuleBranchInfo.union_id'), 'conditions' => array("AND" => array('BasicModuleBranchInfo.id' => $branch_id, 'BasicModuleBranchInfo.org_id' => $org_id))));

        $upazilaOptions = $this->BasicModuleBranchInfo->LookupAdminBoundaryUpazila->find('list', array(
            'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
            'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
            'recursive' => -1
        ));

        $unionOptions = $this->BasicModuleBranchInfo->LookupAdminBoundaryUnion->find('list', array(
            'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
            'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
            'recursive' => -1
        ));

        $mauzaOptions = $this->BasicModuleBranchInfo->LookupAdminBoundaryMauza->find('list', array(
            'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
            'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
            'recursive' => -1
        ));

        $this->set(compact('IsValidUser', 'org_id', 'branch_id', 'orgNameOptions', 'officeTypeOptions', 'districtsOptions', 'upazilaOptions', 'unionOptions', 'mauzaOptions'));

        if (!$this->request->is(array('post', 'put'))) {
            $post = $this->BasicModuleBranchInfo->findById($branch_id);
            if (empty($post)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Branch Information !'
                );
                $this->set(compact('msg'));
                return;
            }

            $office_type_id = $post['BasicModuleBranchInfo']['office_type_id'];
            $this->set(compact('office_type_id'));
            $this->request->data = $post;
        } else {
            $this->BasicModuleBranchInfo->id = $branch_id;
            $this->request->data['BasicModuleBranchInfo']['org_id'] = $org_id;
            $reqData = $this->request->data;
            if (!empty($reqData) && $reqData['BasicModuleBranchInfo']['office_type_id'] == 1) {
                $this->BasicModuleBranchInfo->validate['email_address'] = array(
                    'required' => array(
                        'required' => true,
                        'allowEmpty' => false,
                        'rule' => array('notBlank'),
                        'on' => 'null',
                        'message' => 'A email is required'
                    ),
                    'mailFormat' => array(
                        'rule' => 'email',
                        'message' => 'Email format is invalid'
                    )
                );
            }

            $this->BasicModuleBranchInfo->set($this->request->data);
            if ($this->BasicModuleBranchInfo->validates()) {
                $flag1 = false;
                $flag2 = false;
                $is_exists_office_type_values = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => 1), 'recursive' => -1));
                $branch_code = $this->request->data['BasicModuleBranchInfo']['branch_code'];
                $is_exists_office_code_values = array();
                if (!empty($branch_code)) {
                    $is_exists_office_code_values = $this->BasicModuleBranchInfo->find('first', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.branch_code' => $branch_code), 'recursive' => -1));
                }
                if (!empty($is_exists_office_type_values) && $this->request->data['BasicModuleBranchInfo']['office_type_id'] == 1) {
                    if ($branch_id == $is_exists_office_type_values['BasicModuleBranchInfo']['id']) {
                        $flag1 = true;
                    } else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => 'Head office information of this organization is already exist'
                        );
                        $this->set(compact('msg'));
                    }
                } else {
                    $flag1 = true;
                }

                if (!empty($is_exists_office_code_values) && $this->request->data['BasicModuleBranchInfo']['branch_code'] == $is_exists_office_code_values['BasicModuleBranchInfo']['branch_code']) {
                    if ($branch_id == $is_exists_office_code_values['BasicModuleBranchInfo']['id']) {
                        $flag2 = true;
                    } else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => 'Office code of this organization is already exist'
                        );
                        $this->set(compact('msg'));
                    }
                } else {
                    $flag2 = true;
                }

                if ($flag1 && $flag2) {
                    if ($this->BasicModuleBranchInfo->save($this->request->data)) {
                        $this->redirect(array('action' => 'edit_branch_image', $branch_id, $org_id));
                    }
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Enter Required Information first.'
                );
                $this->set(compact('msg'));
            }
        }
    }

    public function add_branch_image($branch_id = null, $org_id = null) {
        if (empty($org_id))
            $org_id = $this->Session->read('Org.Id');

        $fields = array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.org_id', 'BasicModuleBranchInfo.branch_name', 'BasicModuleBranchInfo.branch_serial', 'BasicModuleBranchInfo.image_name');
        $this->BasicModuleBranchInfo->recursive = -1;
        $allDataDetails = $this->BasicModuleBranchInfo->findById($branch_id, $fields);

        $orgName = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->field('name_of_org');

//        $orgName = $allDataDetails['BasicModuleBasicInformation']['name_of_org'];
        $branchName = $allDataDetails['BasicModuleBranchInfo']['branch_name'];

        $branch_serial = $allDataDetails['BasicModuleBranchInfo']['branch_serial'];
        $image_name = $allDataDetails['BasicModuleBranchInfo']['image_name'];

        $this->set(compact('org_id', 'branch_id', 'branch_serial', 'image_name', 'orgName', 'branchName'));
    }

    public function edit_branch_image($branch_id = null, $org_id = null) {
        if (empty($org_id))
            $org_id = $this->Session->read('Org.Id');

        $fields = array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.org_id', 'BasicModuleBranchInfo.branch_name', 'BasicModuleBranchInfo.branch_serial', 'BasicModuleBranchInfo.image_name');
        $this->BasicModuleBranchInfo->recursive = -1;
        $allDataDetails = $this->BasicModuleBranchInfo->findById($branch_id, $fields);

        $orgName = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->field('name_of_org');

//        $orgName = $allDataDetails['BasicModuleBasicInformation']['name_of_org'];
        $branchName = $allDataDetails['BasicModuleBranchInfo']['branch_name'];

        $branch_serial = $allDataDetails['BasicModuleBranchInfo']['branch_serial'];
        $image_name = $allDataDetails['BasicModuleBranchInfo']['image_name'];

        $this->set(compact('org_id', 'branch_id', 'branch_serial', 'image_name', 'orgName', 'branchName'));
    }

    //public function file_upload($branchId = null) {
    public function file_upload() {
        $data = file_get_contents($_POST['data']);
        $fileName = $_POST['name'];
        $branchId = $_POST['branch_id'];
        $branchSerial = $_POST['branch_serial'];
        $orgId = $_POST['org_id'];

        try {
            $fileCode = $orgId . "_" . $branchSerial;
            $file_ext = explode('.', $fileName);
            $file_ext_final = end($file_ext);
            $serverFile = "$fileCode.$file_ext_final";
            $fp = fopen(WWW_ROOT . 'files' . DS . 'uploads' . DS . 'branches' . DS . $serverFile, 'w');
            fwrite($fp, $data);
            fclose($fp);

//        } catch (Exception $ex) {
//            debug($ex);
//        }
//
//        try {

            $update_data = array('image_name' => $serverFile);
            //$conditions = array('org_id' => $orgId, 'branch_id' => $branchId); 'file_code' => $fileCode, 
            $this->BasicModuleBranchInfo->id = $branchId;
            $this->BasicModuleBranchInfo->save($update_data);

            debug($branchId);
            debug($fileCode);
            debug($update_data);

            //return $this->redirect(array('action' => 'final_preview', $fileId, $branchId));
        } catch (Exception $ex) {
            debug($ex);
        }
    }

    public function details($org_id = null, $branch_id = null) {
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
        if (!empty($branch_id)) {
            $allDataDetails = $this->BasicModuleBranchInfo->findById($branch_id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->BasicModuleBranchInfo->find('all', array('conditions' => array('BasicModuleBranchInfo.org_id' => $org_id)));
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
                'msg' => 'BranchInfo data not found !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('data_count', 'allDataDetails'));
    }

    public function individual_preview() {
        try {
            if (empty($org_id)) {
                $org_id = $this->Session->read('Org.Id');
                if (empty($org_id)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Invalid organization information !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
            }
            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->recursive = 2;
            $allDetails = $this->BasicModuleBasicInformation->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
            $mfiDetails = $allDetails['BasicModuleBasicInformation'];
            $allProposedBranchDetails = $allDetails['BasicModuleProposedBranchInfo'];
            $this->set(compact('org_id', 'mfiDetails', 'allProposedBranchDetails'));
        } catch (Exception $ex) {
            debug($ex->getMessage());
        }
    }

    public function branch_details($branch_id = null) {
        if (empty($branch_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Branch data !'
            );
            $this->set(compact('msg'));
        }
        $this->BasicModuleBranchInfo->recursive = 0;
        $branchDetails = $this->BasicModuleBranchInfo->findById($branch_id);
        if (!$branchDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Branch data !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('branch_id', 'branchDetails'));
    }

    public function preview($branch_id = null) {
        $this->set(compact('branch_id'));
    }

    public function update_upazila_select() {
        $district_id = $this->request->data['BasicModuleBranchInfo']['district_id'];

        $upazila_options = $this->BasicModuleBranchInfo->LookupAdminBoundaryUpazila->find('list', array(
            'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
            'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
            'recursive' => -1
        ));

        $this->set(compact('upazila_options'));
        $this->layout = 'ajax';
    }

    public function update_union_select() {
        $upazila_id = $this->request->data['BasicModuleBranchInfo']['upazila_id'];

        $union_options = $this->BasicModuleBranchInfo->LookupAdminBoundaryUnion->find('list', array(
            'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
            'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
            'recursive' => -1
        ));

        $this->set(compact('union_options'));
        $this->layout = 'ajax';
    }

    public function update_mauza_select() {
        $union_id = $this->request->data['BasicModuleBranchInfo']['union_id'];

        $mauza_options = $this->BasicModuleBranchInfo->LookupAdminBoundaryMauza->find('list', array(
            'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
            'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
            'recursive' => -1
        ));

        $this->set(compact('mauza_options'));
        $this->layout = 'ajax';
    }

    public function branch_deactivation_request($branch_id = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');

        if ((empty($org_id) && !empty($user_group_id) && !in_array(1, $user_group_id)) || empty($IsValidUser)) {
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

        $org_name_options = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $this->set(compact('IsValidUser', 'org_id', 'org_name_options', 'branch_id'));

        if ($this->request->is('post', 'put')) {
            $conditions = array('BasicModuleBranchInfo.org_id' => (int) $org_id, 'BasicModuleBranchInfo.id' => (int) $branch_id);
            $data = array(
                'deactivation_request_date' => "'" . $this->request->data['BasicModuleBranchInfo']['deactivation_request_date'] . "'",
                'deactivation_reasons' => "'" . $this->request->data['BasicModuleBranchInfo']['deactivation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleBranchInfo->updateAll($data, $conditions);
            $this->redirect(array('action' => 'view'));
        }
    }

    public function edit_deactivation_request($branch_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($branch_id) || empty($org_id) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($branch_id)) {
                $msg['msg'] = 'Invalid branch data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }
        $this->set(compact('IsValidUser', 'org_id', 'branch_id'));
        if (!$this->request->is(array('post', 'put'))) {
            $org_name_options = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $this->BasicModuleBranchInfo->recursive = -1;
            $branch_values = $this->BasicModuleBranchInfo->findById($branch_id);

            $this->set(compact('back_opt', 'org_name_options'));

            if (!$branch_values) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid branch information !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $branch_values;
        } else {
            $conditions = array('BasicModuleBranchInfo.org_id' => (int) $org_id, 'BasicModuleBranchInfo.id' => (int) $branch_id);
            $data = array(
                'deactivation_request_date' => "'" . $this->request->data['BasicModuleBranchInfo']['deactivation_request_date'] . "'",
                'deactivation_reasons' => "'" . $this->request->data['BasicModuleBranchInfo']['deactivation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleBranchInfo->updateAll($data, $conditions);
            $this->redirect(array('action' => 'view'));
        }
    }

    public function cancel_deactivation_request($branch_id = null) {
        $this->autoRender = false;
        $org_id = $this->Session->read('Org.Id');
        $conditions = array('BasicModuleBranchInfo.org_id' => (int) $org_id, 'BasicModuleBranchInfo.id' => (int) $branch_id);
        $data = array(
            'deactivation_request_date' => null,
            'deactivation_reasons' => null,
            'is_approved' => null
        );
        $this->BasicModuleBranchInfo->updateAll($data, $conditions);
        $this->redirect(array('action' => 'view'));
    }

    public function branch_activation_request($branch_id = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');

        if ((empty($org_id) && !empty($user_group_id) && !in_array(1, $user_group_id)) || empty($IsValidUser)) {
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

        $org_name_options = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $this->set(compact('IsValidUser', 'org_id', 'org_name_options', 'branch_id'));

        if ($this->request->is('post', 'put')) {
            $conditions = array('BasicModuleBranchInfo.org_id' => (int) $org_id, 'BasicModuleBranchInfo.id' => (int) $branch_id);
            $data = array(
                'activation_request_date' => "'" . $this->request->data['BasicModuleBranchInfo']['activation_request_date'] . "'",
                'activation_reasons' => "'" . $this->request->data['BasicModuleBranchInfo']['activation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleBranchInfo->updateAll($data, $conditions);
            $this->redirect(array('action' => 'view'));
        }
    }

    public function edit_activation_request($branch_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($branch_id) || empty($org_id) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($branch_id)) {
                $msg['msg'] = 'Invalid branch data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }
        $this->set(compact('IsValidUser', 'org_id', 'branch_id'));
        if (!$this->request->is(array('post', 'put'))) {
            $org_name_options = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $this->BasicModuleBranchInfo->recursive = -1;
            $branch_values = $this->BasicModuleBranchInfo->findById($branch_id);

            $this->set(compact('back_opt', 'org_name_options'));

            if (!$branch_values) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid branch information !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $branch_values;
        } else {
            $conditions = array('BasicModuleBranchInfo.org_id' => (int) $org_id, 'BasicModuleBranchInfo.id' => (int) $branch_id);
            $data = array(
                'activation_request_date' => "'" . $this->request->data['BasicModuleBranchInfo']['activation_request_date'] . "'",
                'activation_reasons' => "'" . $this->request->data['BasicModuleBranchInfo']['activation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleBranchInfo->updateAll($data, $conditions);
            $this->redirect(array('action' => 'view'));
        }
    }

    public function cancel_activation_request($branch_id = null) {
        $this->autoRender = false;
        $org_id = $this->Session->read('Org.Id');
        $conditions = array('BasicModuleBranchInfo.org_id' => (int) $org_id, 'BasicModuleBranchInfo.id' => (int) $branch_id);
        $data = array(
            'activation_request_date' => null,
            'activation_reasons' => null,
            'is_approved' => null
        );
        $this->BasicModuleBranchInfo->updateAll($data, $conditions);
        $this->redirect(array('action' => 'view'));
    }

    public function delete($branch_id = null) {
        $org_id = $this->Session->read('Org.Id');
        $conditions = array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.branch_id' => $branch_id);

        $image_name = $this->BasicModuleBranchInfo->field('image_name', $conditions);
        $image_path = WWW_ROOT . DS . "files" . DS . "uploads" . DS . "branches" . DS . $image_name;
        if (!empty($image_name) && is_file($image_path)) {
            if (unlink($image_path)) {
                if ($this->BasicModuleBranchInfo->delete($branch_id)) {
                    return $this->redirect(array('action' => 'view'));
                }
            }
        }
    }

    public function final_preview($image_file_id = null, $branch_id = null) {
        $this->set(compact('image_file_id', 'branch_id'));
    }

    public function branch_submission_summary() {
        $IsValidUser = $this->Session->read('User.IsValid');
        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $page_limit = 20;
        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'basic_module_basic_informations',
                    'alias' => 'BasicModuleBasicInformation',
                    'type' => 'INNER',
                    'conditions' => array('BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id')
                )
            ),
            'limit' => $page_limit,
            'fields' => array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.name_of_org', 'BasicModuleBranchInfo.branch_count'),
            'group' => 'BasicModuleBranchInfo.org_id',
            'order' => 'BasicModuleBasicInformation.license_no',
            'recursive' => -1);

        $this->BasicModuleBranchInfo->virtualFields["branch_count"] = "COUNT(BasicModuleBranchInfo.id)";
        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];


        $this->Paginator->settings = $this->paginate;
        $branch_summary = $this->Paginator->paginate('BasicModuleBranchInfo');

        $this->BasicModuleBranchInfo->recursive = -1;
        $total_branch = $this->BasicModuleBranchInfo->field('branch_count');

        $this->set(compact('page_limit', 'total_branch', 'branch_summary'));
    }

    public function submission_summary_all($sort_field = null, $sort_dir = null) {

//        debug($this->request->params);
//        debug($sort_field);
//        debug($sort_dir);
//        $this->autoRender = false;


        $IsValidUser = $this->Session->read('User.IsValid');

        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (!empty($this->request->params['named']['sort'])) {
            $sort_field = $this->request->params['named']['sort'];
        } else {
            $sort_field = '__license_no';
        }
        if (!empty($this->request->params['named']['direction'])) {
            $sort_dir = $this->request->params['named']['direction'];
        } else {
            $sort_dir = 'asc';
        }
        if (!empty($this->request->params['named']['page'])) {
            $page_no = $this->request->params['named']['page'];
        } else {
            $page_no = 1;
        }

        $page_limit = 10;

        $page_skip = ($page_no - 1) * $page_limit;

//        $this->paginate = array('limit' => $page_limit);
//        debug($this->request->params['named']);
//        debug($sort_dir);
//        $sort_dir = (strtoupper($sort_dir) == 'ASC') ? 'DESC' : 'ASC';
//        $this->paginate = array('order' => array($sort_field => $sort_dir));
//
//        debug($sort_field);
//        debug($sort_dir);

        $sql = "SELECT `basic_module_basic_informations`.`license_no` AS `__license_no`,
                COUNT(`basic_module_branch_infos`.`id`) AS `__branch_count`,
                CONCAT_WS('', full_name_of_org,
                    CASE
                      WHEN full_name_of_org != '' AND short_name_of_org != ''
                      THEN CONCAT_WS('', ' (<strong>', short_name_of_org, '</strong>)') 
                              ELSE short_name_of_org END) AS `__name_of_org`
              FROM
                `basic_module_branch_infos`
                LEFT JOIN `basic_module_basic_informations`
                  ON (
                    `basic_module_branch_infos`.`org_id` = `basic_module_basic_informations`.`id`
                  )
              WHERE 1 = 1
              GROUP BY `basic_module_branch_infos`.`org_id`
              ORDER BY $sort_field $sort_dir 
              LIMIT $page_skip, $page_limit";

        $branch_data = $this->executeQuery($sql);

        $sql = "SELECT COUNT(`basic_module_branch_infos`.`id`) AS `__branch_count` 
              FROM `basic_module_branch_infos`
              WHERE 1 = 1";

        $total_branch = $this->executeQuery($sql);

        $total_branch = empty($total_branch[0][0]['__branch_count']) ? 0 : $total_branch[0][0]['__branch_count'];

        $sql = "SELECT COUNT(`basic_module_branch_infos`.`org_id`) AS `__record_count` 
              FROM `basic_module_branch_infos`
              WHERE 1 = 1
              GROUP BY `basic_module_branch_infos`.`org_id`";

        $record_count = $this->executeQuery($sql);

        $record_count = empty($record_count[0][0]['__record_count']) ? 0 : $record_count[0][0]['__record_count'];

//        $this->paginate = array('order' => array($sort_field => $sort_dir));

        $this->set(compact('branch_data', 'record_count', 'total_branch', 'sort_field', 'sort_dir'));
    }

    public function export_branch_summary($is_office_type_wise = true, $org_id = null, $office_type_id = null) {
        $this->autoRender = false;

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }

        $conditions = array();
        if (!empty($org_id)) {
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);
        }

        if (!empty($office_type_id)) {
            $conditions = array('BasicModuleBranchInfo.office_type_id' => $office_type_id);
        }

        $group = array('BasicModuleBasicInformation.id');
        if (!empty($is_office_type_wise)) {
            $group[] = 'BasicModuleBranchInfo.office_type_id';
        }

        $fields = array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.name_of_org',
            'LookupBasicOfficeType.office_type', 'BasicModuleBranchInfo.branch_count');

        $this->BasicModuleBranchInfo->virtualFields["branch_count"] = "COUNT(BasicModuleBranchInfo.id)";
        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (', short_name_of_org, ')') ELSE short_name_of_org END)";
        $this->BasicModuleBranchInfo->unbindModel(array('belongsTo' => array('LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza')), true);
        $branch_data = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0,
            'group' => $group,
            'order' => array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.office_type_id')));

        $all_branch_data = array();
        if (!empty($is_office_type_wise)) {
            $file_name = 'MFI_office_wise_branch_count';
            $header_row = array(
                array('label' => __('License No.'), 'width' => 20, 'filter' => true),
                array('label' => __('Name of MFI'), 'width' => 40, 'filter' => true, 'wrap' => true),
                array('label' => __('Branch Type'), 'width' => 30, 'filter' => true, 'wrap' => true),
                array('label' => __('Branch Count'), 'width' => 20));

            foreach ($branch_data as $branch_info) {
                $all_branch_data[] = array(
                    $branch_info['BasicModuleBasicInformation']['license_no'],
                    $branch_info['BasicModuleBranchInfo']['name_of_org'],
                    $branch_info['LookupBasicOfficeType']['office_type'],
                    $branch_info['BasicModuleBranchInfo']['branch_count']
                );
            }
        } else {
            $file_name = 'MFI_branch_count';
            $header_row = array(
                array('label' => __('License No.'), 'width' => 20, 'filter' => true),
                array('label' => __('Name of MFI'), 'width' => 40, 'filter' => true, 'wrap' => true),
                array('label' => __('Branch Count'), 'width' => 20));

            foreach ($branch_data as $branch_info) {
                $all_branch_data[] = array(
                    $branch_info['BasicModuleBasicInformation']['license_no'],
                    $branch_info['BasicModuleBranchInfo']['name_of_org'],
                    $branch_info['BasicModuleBranchInfo']['branch_count']
                );
            }
        }

        $this->export_to_excel($file_name, $header_row, $all_branch_data);
        return;
    }

    public function export_branch_without_ho($office_type_id = 1) {
        $this->autoRender = false;

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }

        $conditions = array();

        if (!empty($office_type_id)) {
            $conditions = array('BasicModuleBranchInfo.office_type_id' => $office_type_id);
        }

        $fields = array('BasicModuleBranchInfo.org_id', 'BasicModuleBranchInfo.org_id');
        $org_ids = $this->BasicModuleBranchInfo->find('list', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => -1, 'group' => array('BasicModuleBranchInfo.org_id'), 'order' => array('BasicModuleBranchInfo.org_id')));

        $conditions = array('NOT' => array('BasicModuleBranchInfo.org_id' => $org_ids));
        $fields = array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.name_of_org', 'BasicModuleBranchInfo.branch_count');
        $this->BasicModuleBranchInfo->virtualFields["branch_count"] = "COUNT(BasicModuleBranchInfo.id)";
        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (', short_name_of_org, ')') ELSE short_name_of_org END)";
        $this->BasicModuleBranchInfo->unbindModel(array('belongsTo' => array('LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza')), true);
        $branch_data = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0,
            'group' => array('BasicModuleBasicInformation.id'),
            'order' => array('BasicModuleBasicInformation.license_no')));


        $file_name = 'MFI_list_without_headoffice';

        $header_row = array(
            array('label' => __('License No.'), 'width' => 20, 'filter' => true),
            array('label' => __('Name of MFI'), 'width' => 40, 'filter' => true, 'wrap' => true),
            array('label' => __('Branch Count'), 'width' => 20));

        $all_branch_data = array();

        foreach ($branch_data as $branch_info) {
            $all_branch_data[] = array(
                $branch_info['BasicModuleBasicInformation']['license_no'],
                $branch_info['BasicModuleBranchInfo']['name_of_org'],
                $branch_info['BasicModuleBranchInfo']['branch_count']
            );
        }

        $this->export_to_excel($file_name, $header_row, $all_branch_data);
        return;
    }

    public function export_all_branch($org_id = null) {

        if ($this->request->is('post')) {
            debug('post');
        }

        $this->layout = null;
        $this->autoLayout = false;
        $this->autoRender = false;

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }

        $conditions = array();
        if (!empty($org_id)) {
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);
        }

        $joins = array(
            array(
                'table' => 'basic_module_basic_informations',
                'alias' => 'BasicModuleBasicInformation',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id')
            ),
            array(
                'table' => 'lookup_basic_office_types',
                'alias' => 'LookupBasicOfficeType',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.office_type_id = LookupBasicOfficeType.id')
            ),
            array(
                'table' => 'lookup_admin_boundary_districts',
                'alias' => 'LookupAdminBoundaryDistrict',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id')
            ),
            array(
                'table' => 'lookup_admin_boundary_upazilas',
                'alias' => 'LookupAdminBoundaryUpazila',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.upazila_id = LookupAdminBoundaryUpazila.id')
            )
        );

        $fields = array('BasicModuleBasicInformation.license_no',
            'BasicModuleBranchInfo.name_of_org',
            'BasicModuleBranchInfo.branch_name',
            'BasicModuleBranchInfo.branch_code',
            'LookupBasicOfficeType.office_type',
            'BasicModuleBranchInfo.mailing_address',
            'LookupAdminBoundaryDistrict.district_name',
            'LookupAdminBoundaryUpazila.upazila_name',
            'BasicModuleBranchInfo.mohalla_or_post_office',
            'BasicModuleBranchInfo.road_name_or_village',
            'BasicModuleBranchInfo.mobile_no',
            'BasicModuleBranchInfo.phone_no',
            'BasicModuleBranchInfo.fax',
            'BasicModuleBranchInfo.email_address',
            'BasicModuleBranchInfo.lat',
            'BasicModuleBranchInfo.long',
            'BasicModuleBranchInfo.image_name');
//            'BasicModuleBranchInfo.deactivation_request_date',
//            'BasicModuleBranchInfo.deactivation_reasons'
//'limit'=>100 , 'limit' => 100, 

        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (', short_name_of_org, ')') ELSE short_name_of_org END)";
        $branch_data = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $conditions, 'joins' => $joins, 'recursive' => -1, 'order' => array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.office_type_id')));

        $header_row = array(
            array('label' => __('License No.'), 'width' => 15, 'filter' => true),
            array('label' => __('Name of MFI'), 'width' => 30, 'filter' => true, 'wrap' => true),
            array('label' => __('Branch Name'), 'width' => 25, 'wrap' => true),
            array('label' => __('Branch Code'), 'width' => 15),
            array('label' => __('Branch Type'), 'width' => 25, 'filter' => true),
            array('label' => __('Mailing Address'), 'width' => 35, 'wrap' => true),
            array('label' => __('District'), 'width' => 20, 'filter' => true),
            array('label' => __('Upazila'), 'width' => 20, 'filter' => true),
            array('label' => __('Post Office'), 'width' => 25, 'wrap' => true),
            array('label' => __('Road Name/Village'), 'width' => 30, 'wrap' => true),
            array('label' => __('Mobile No.'), 'width' => 15),
            array('label' => __('Phone No.'), 'width' => 15),
            array('label' => __('Fax'), 'width' => 15),
            array('label' => __('E-Mail'), 'width' => 20),
            array('label' => __('Latitude'), 'width' => 15),
            array('label' => __('Longitude'), 'width' => 15),
            array('label' => __('Picture'), 'width' => 15));

//        $this->set(compact('header_row', 'branch_data'));
//        return;

        $file_name = "all_branch_details";
        $all_branch_data = array();

        foreach ($branch_data as $branch_info) {
            $has_image = "Not available";
            if (!empty($branch_info['BasicModuleBranchInfo']['image_name'])) {
                $path = WWW_ROOT . DS . 'files' . DS . 'uploads' . DS . 'branches' . DS . $branch_info['BasicModuleBranchInfo']['image_name'];
                if (file_exists($path) == 1)
                    $has_image = "Available";
            }

            $all_branch_data[] = array(
                $branch_info['BasicModuleBasicInformation']['license_no'], $branch_info['BasicModuleBranchInfo']['name_of_org'],
                $branch_info['BasicModuleBranchInfo']['branch_name'], $branch_info['BasicModuleBranchInfo']['branch_code'],
                $branch_info['LookupBasicOfficeType']['office_type'], $branch_info['BasicModuleBranchInfo']['mailing_address'],
                $branch_info['LookupAdminBoundaryDistrict']['district_name'], $branch_info['LookupAdminBoundaryUpazila']['upazila_name'],
                $branch_info['BasicModuleBranchInfo']['mohalla_or_post_office'], $branch_info['BasicModuleBranchInfo']['road_name_or_village'],
                $branch_info['BasicModuleBranchInfo']['mobile_no'], $branch_info['BasicModuleBranchInfo']['phone_no'],
                $branch_info['BasicModuleBranchInfo']['fax'], $branch_info['BasicModuleBranchInfo']['email_address'],
                $branch_info['BasicModuleBranchInfo']['lat'], $branch_info['BasicModuleBranchInfo']['long'], $has_image);
        }

        $this->export_to_excel($file_name, $header_row, $all_branch_data, 3);
        return;
    }

    public function export_all_branch_csv($org_id = null) {

        $this->layout = null;
        $this->autoLayout = false;
        $this->autoRender = false;

        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
        }

        $conditions = array();
        if (!empty($org_id)) {
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);
        }

        $joins = array(
            array(
                'table' => 'basic_module_basic_informations',
                'alias' => 'BasicModuleBasicInformation',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.org_id = BasicModuleBasicInformation.id')
            ),
            array(
                'table' => 'lookup_basic_office_types',
                'alias' => 'LookupBasicOfficeType',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.office_type_id = LookupBasicOfficeType.id')
            ),
            array(
                'table' => 'lookup_admin_boundary_districts',
                'alias' => 'LookupAdminBoundaryDistrict',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.district_id = LookupAdminBoundaryDistrict.id')
            ),
            array(
                'table' => 'lookup_admin_boundary_upazilas',
                'alias' => 'LookupAdminBoundaryUpazila',
                'type' => 'LEFT',
                'conditions' => array('BasicModuleBranchInfo.upazila_id = LookupAdminBoundaryUpazila.id')
            )
        );

        $fields = array('BasicModuleBasicInformation.license_no',
            'BasicModuleBranchInfo.name_of_org',
            'BasicModuleBranchInfo.branch_name',
            'BasicModuleBranchInfo.branch_code',
            'LookupBasicOfficeType.office_type',
            'BasicModuleBranchInfo.mailing_address',
            'LookupAdminBoundaryDistrict.district_name',
            'LookupAdminBoundaryUpazila.upazila_name',
            'BasicModuleBranchInfo.mohalla_or_post_office',
            'BasicModuleBranchInfo.road_name_or_village',
            'BasicModuleBranchInfo.mobile_no',
            'BasicModuleBranchInfo.phone_no',
            'BasicModuleBranchInfo.fax',
            'BasicModuleBranchInfo.email_address',
            'BasicModuleBranchInfo.lat',
            'BasicModuleBranchInfo.long',
            'BasicModuleBranchInfo.image_name');
//            'BasicModuleBranchInfo.deactivation_request_date',
//            'BasicModuleBranchInfo.deactivation_reasons'
//'limit'=>100 , 'limit' => 100, 

        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = "CONCAT_WS('', full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT_WS('', ' (', short_name_of_org, ')') ELSE short_name_of_org END)";
        $branch_data = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $conditions, 'joins' => $joins, 'recursive' => -1, 'order' => array('BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.office_type_id')));

        $all_branch_data = array();
        foreach ($branch_data as $branch_info) {

            $has_image = "Not available";
            if (!empty($branch_info['BasicModuleBranchInfo']['image_name'])) {
                $path = WWW_ROOT . DS . 'files' . DS . 'uploads' . DS . 'branches' . DS . $branch_info['BasicModuleBranchInfo']['image_name'];
                if (file_exists($path) == 1)
                    $has_image = "Available";
            }

            $all_branch_data[] = array(
                'License No.' => $branch_info['BasicModuleBasicInformation']['license_no'],
                'Name of MFI' => $branch_info['BasicModuleBranchInfo']['name_of_org'],
                'Branch Name' => $branch_info['BasicModuleBranchInfo']['branch_name'],
                'Branch Code' => $branch_info['BasicModuleBranchInfo']['branch_code'],
                'Branch Type' => $branch_info['LookupBasicOfficeType']['office_type'],
                'Mailing Address' => $branch_info['BasicModuleBranchInfo']['mailing_address'],
                'District' => $branch_info['LookupAdminBoundaryDistrict']['district_name'],
                'Upazila' => $branch_info['LookupAdminBoundaryUpazila']['upazila_name'],
                'Post Office' => $branch_info['BasicModuleBranchInfo']['mohalla_or_post_office'],
                'Road Name/Village' => $branch_info['BasicModuleBranchInfo']['road_name_or_village'],
                'Mobile No.' => $branch_info['BasicModuleBranchInfo']['mobile_no'],
                'Phone No.' => $branch_info['BasicModuleBranchInfo']['phone_no'],
                'Fax' => $branch_info['BasicModuleBranchInfo']['fax'],
                'E-Mail' => $branch_info['BasicModuleBranchInfo']['email_address'],
                'Latitude' => $branch_info['BasicModuleBranchInfo']['lat'],
                'Longitude' => $branch_info['BasicModuleBranchInfo']['long'],
                'Picture' => $has_image);
        }

        $file_name = "all_branch_details" . date('Ymd_His') . ".csv";
        $this->Export->exportCsv($all_branch_data, $file_name);

        unset($all_branch_data);

        return;
    }

}
