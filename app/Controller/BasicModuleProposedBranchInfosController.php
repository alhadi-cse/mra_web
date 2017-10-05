<?php
App::uses('AppController', 'Controller');
App::uses('File', 'Utility');

class BasicModuleProposedBranchInfosController extends AppController {
    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view($opt = null, $mode = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_id = $this->Session->read('User.GroupIds');
        $page_limit = 10;        
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
	    $total_values = $this->executeQuery("select count(auto_number) as total from basic_module_proposed_branch_infos where org_id=$org_id");            
            $total=(int)$total_values[0][0]['total'];
            $lower_value = 1;
            $upper_value = $total;
            if($total<=$page_limit){
                $page_limit = $total;
            }
            $org_condition = array('BasicModuleProposedBranchInfo.org_id' => $org_id);
            $head_office_details = $this->BasicModuleProposedBranchInfo->find('first', array('conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id, 'BasicModuleProposedBranchInfo.branch_serial' => 1), 'recursive' => -1));
            if(!empty($head_office_details)) {
                $lower_value = $total-$page_limit+1;                
            }
            else{
                $max_min_values = $this->executeQuery("select max(branch_serial) as max_serial, min(branch_serial) as min_serial from basic_module_proposed_branch_infos where org_id=$org_id");       
                $lower_value =(int)$max_min_values[0][0]['min_serial'];
                $upper_value = (int)$max_min_values[0][0]['max_serial'];
            }            
            $data_range_condition = array('BasicModuleProposedBranchInfo.branch_serial BETWEEN ? AND ?' => array($lower_value,$upper_value));
        } else {
	    $total_values = $this->executeQuery("select count(auto_number) as total from basic_module_proposed_branch_infos");            
            $total=(int)$total_values[0][0]['total'];
            if($total<=$page_limit){
                $page_limit = $total;
            }
            $org_condition = array();
            $data_range_condition = array('BasicModuleProposedBranchInfo.auto_number BETWEEN ? AND ?' => array($total-$page_limit+1,$total));
        }        
        $condition = array_merge($org_condition, $data_range_condition); 
        if($total==1) {
            $condition = $org_condition;
        }        
        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleProposedBranchInfo']['search_option'];
            $keyword = $this->request->data['BasicModuleProposedBranchInfo']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($org_condition)) {
                    $condition = array_merge(array("$option LIKE '%$keyword%'"), $org_condition);
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }                
        $this->paginate = array(
            'limit' => $page_limit,
            'order' => array('BasicModuleProposedBranchInfo.autonumber' => 'desc'),
            'conditions' => $condition);        
        $this->BasicModuleProposedBranchInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;        
        $values = $this->Paginator->paginate('BasicModuleProposedBranchInfo');
        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'opt_all', 'values','total', 'page_limit'));
    }

    public function submission_summary() {
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
        $page_limit = 20;
        $this->paginate = array(
            'limit' => $page_limit,
            'fields'=>array('BasicModuleBasicInformation.id','BasicModuleBasicInformation.full_name_of_org','BasicModuleBasicInformation.license_no','COUNT(BasicModuleProposedBranchInfo.id) AS total_branch'),
            'group' => 'BasicModuleProposedBranchInfo.org_id',
        );
        $total_values = $this->executeQuery("select count(auto_number) as total from basic_module_proposed_branch_infos");            
        $total=$total_values[0][0]['total'];
        //$total = $this->BasicModuleProposedBranchInfo->find('count');

        $this->BasicModuleProposedBranchInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleProposedBranchInfo');        
        $this->set(compact('values','total', 'page_limit'));
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
        $orgNameOptions = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $officeTypeOptions = $this->BasicModuleProposedBranchInfo->LookupBasicProposedOfficeType->find('list', array('fields' => array('LookupBasicProposedOfficeType.id', 'LookupBasicProposedOfficeType.office_type')));
        $districtsOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'officeTypeOptions', 'districtsOptions'));
        if ($this->request->is('post')) {
            $reqData = $this->request->data;
            if (!empty($reqData) && !empty($reqData['BasicModuleProposedBranchInfo'])) {
                $branch_code = $reqData['BasicModuleProposedBranchInfo']['branch_code'];
                if($reqData['BasicModuleProposedBranchInfo']['office_type_id']==1) {
                    $branch_serial = 1;
                }
                else {
                    $branch_serial = $this->serial_generator_by_max('BasicModuleProposedBranchInfo', 'branch_serial', array('org_id'=>$org_id));
                    if($branch_serial==1) {
                        $branch_serial = $branch_serial+1;
                    }
                }
                $org_serial = sprintf("%04s", $org_id);
                $new_branch_serial = sprintf("%04s", $branch_serial);
                $branch_id = $org_serial."".$new_branch_serial;

                if (empty($reqData['BasicModuleProposedBranchInfo']['org_id']) && !empty($org_id))
                    $reqData['BasicModuleProposedBranchInfo']['id'] = $branch_id;
                    $reqData['BasicModuleProposedBranchInfo']['org_id'] = $org_id;
                    $reqData['BasicModuleProposedBranchInfo']['branch_serial'] = $branch_serial;
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
            if(!empty($reqData)&&$reqData['BasicModuleProposedBranchInfo']['office_type_id']==1) {                
                $this->BasicModuleProposedBranchInfo->validate['email_address'] = array(                   
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
            $this->BasicModuleProposedBranchInfo->set($reqData);
            if ($this->BasicModuleProposedBranchInfo->validates()) {
                $is_exists_office_type_values = $this->BasicModuleProposedBranchInfo->find('first', array('conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id, 'BasicModuleProposedBranchInfo.office_type_id' => 1)));
                $is_exists_office_code_values = array();
                if(!empty($branch_code)) {
                    $is_exists_office_code_values = $this->BasicModuleProposedBranchInfo->find('first', array('conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id, 'BasicModuleProposedBranchInfo.branch_code' => $branch_code)));                    
                }                
                if(!empty($is_exists_office_type_values)&&!empty($reqData)&&$reqData['BasicModuleProposedBranchInfo']['office_type_id']==1) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Head office information of this organization is already exist'
                    );
                    $this->set(compact('msg'));
                }                
                elseif(!empty($is_exists_office_code_values)&&!empty($reqData)&&$reqData['BasicModuleProposedBranchInfo']['branch_code']==$branch_code) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Office code of this organization is already exist'
                    );
                    $this->set(compact('msg'));
                }
                else {
                    $this->BasicModuleProposedBranchInfo->create();
                    $newData = $this->BasicModuleProposedBranchInfo->save($reqData);
                    if ($newData) {
                        $data_id = $newData['BasicModuleProposedBranchInfo']['id'];
                        $data = array(
                            'org_id' => $org_id,
                            'branch_id' => $data_id,
                        );
                        $this->loadModel('BasicModuleProposedBranchImage');
                        $this->BasicModuleProposedBranchImage->create();
                        $this->BasicModuleProposedBranchImage->save($data);
                        $this->Session->write('Data.Id', $data_id);
                        $this->Session->write('Data.Mode', 'update');
                        $this->redirect(array('action' => 'add_branch_image', $data_id));
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
        $orgNameOptions = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $officeTypeOptions = $this->BasicModuleProposedBranchInfo->LookupBasicProposedOfficeType->find('list', array('fields' => array('LookupBasicProposedOfficeType.id', 'LookupBasicProposedOfficeType.office_type')));
        $districtsOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $district_id = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.district_id'), 'conditions' => array("AND" => array('BasicModuleProposedBranchInfo.id' => $branch_id, 'BasicModuleProposedBranchInfo.org_id' => $org_id))));
        $upazila_id = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.upazila_id'), 'conditions' => array("AND" => array('BasicModuleProposedBranchInfo.id' => $branch_id, 'BasicModuleProposedBranchInfo.org_id' => $org_id))));
        $union_id = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.union_id'), 'conditions' => array("AND" => array('BasicModuleProposedBranchInfo.id' => $branch_id, 'BasicModuleProposedBranchInfo.org_id' => $org_id))));

        $upazilaOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryUpazila->find('list', array(
            'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
            'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
            'recursive' => -1
        ));

        $unionOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryUnion->find('list', array(
            'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
            'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
            'recursive' => -1
        ));
        $mauzaOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryMauza->find('list', array(
            'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
            'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
            'recursive' => -1
        ));
        $this->set(compact('IsValidUser', 'org_id', 'branch_id','orgNameOptions','officeTypeOptions','districtsOptions', 'upazilaOptions', 'unionOptions', 'mauzaOptions'));
        if (!$this->request->is(array('post', 'put'))) {            
            $post = $this->BasicModuleProposedBranchInfo->findById($branch_id);
            if (empty($post)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Branch Information !'
                );
                $this->set(compact('msg'));
                return;
            }
            $office_type_id = $post['BasicModuleProposedBranchInfo']['office_type_id'];            
            $this->set(compact('office_type_id'));
            $this->request->data = $post;
        } else {           
            $this->BasicModuleProposedBranchInfo->id = $branch_id;
            $this->request->data['BasicModuleProposedBranchInfo']['org_id'] = $org_id;            
            $reqData = $this->request->data;            
            if(!empty($reqData)&&$reqData['BasicModuleProposedBranchInfo']['office_type_id']==1) {                
                $this->BasicModuleProposedBranchInfo->validate['email_address'] = array(                   
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
            $this->BasicModuleProposedBranchInfo->set($this->request->data);
            if ($this->BasicModuleProposedBranchInfo->validates()) {
                $flag1 = false;
                $flag2 = false;
                $is_exists_office_type_values = $this->BasicModuleProposedBranchInfo->find('first', array('conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id, 'BasicModuleProposedBranchInfo.office_type_id' => 1),'recursive'=>-1));
                $branch_code = $this->request->data['BasicModuleProposedBranchInfo']['branch_code'];
                $is_exists_office_code_values = array();
                if(!empty($branch_code)) {
                    $is_exists_office_code_values = $this->BasicModuleProposedBranchInfo->find('first', array('conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id, 'BasicModuleProposedBranchInfo.branch_code' =>$branch_code),'recursive'=>-1));
                }
                if(!empty($is_exists_office_type_values)&&$this->request->data['BasicModuleProposedBranchInfo']['office_type_id']==1) {                    
                    if($branch_id==$is_exists_office_type_values['BasicModuleProposedBranchInfo']['id']){
                        $flag1 = true;
                    }
                    else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => 'Head office information of this organization is already exist'
                        );
                        $this->set(compact('msg'));
                    }
                }
                else{
                    $flag1 = true;
                }
                
                if(!empty($is_exists_office_code_values)&&$this->request->data['BasicModuleProposedBranchInfo']['branch_code']==$is_exists_office_code_values['BasicModuleProposedBranchInfo']['branch_code']) {                                   
                    if($branch_id==$is_exists_office_code_values['BasicModuleProposedBranchInfo']['id']){                        
                        $flag2 = true;
                    }
                    else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => 'Office code of this organization is already exist'
                        );
                        $this->set(compact('msg'));
                    }                      
                }
                else{
                    $flag2 = true;
                }
                if($flag1&&$flag2){                    
                    if ($this->BasicModuleProposedBranchInfo->save($this->request->data)) {
                        $this->redirect(array('action' => 'edit_branch_image', $branch_id));
                    }
                }
            }
            else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Enter Required Information first.'
                );
                $this->set(compact('msg'));
            }
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
            $allDataDetails = $this->BasicModuleProposedBranchInfo->findById($branch_id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->BasicModuleProposedBranchInfo->find('all', array('conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id)));
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
        $this->BasicModuleProposedBranchInfo->recursive = 0;
        $branchDetails = $this->BasicModuleProposedBranchInfo->findById($branch_id);
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

    function update_upazila_select() {
        $district_id = $this->request->data['BasicModuleProposedBranchInfo']['district_id'];

        $upazila_options = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryUpazila->find('list', array(
            'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
            'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
            'recursive' => -1
        ));

        $this->set(compact('upazila_options'));        
        $this->layout = 'ajax';
    }

    function update_union_select() {
        $upazila_id = $this->request->data['BasicModuleProposedBranchInfo']['upazila_id'];

        $union_options = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryUnion->find('list', array(
            'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
            'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
            'recursive' => -1
        ));

        $this->set(compact('union_options'));
        $this->layout = 'ajax';
    }

    function update_mauza_select() {
        $union_id = $this->request->data['BasicModuleProposedBranchInfo']['union_id'];

        $mauza_options = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryMauza->find('list', array(
            'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
            'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
            'recursive' => -1
        ));

        $this->set(compact('mauza_options'));
        $this->layout = 'ajax';
    }

    function branch_deactivation_request($branch_id = null) {
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

        $org_name_options = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $this->set(compact('IsValidUser', 'org_id', 'org_name_options', 'branch_id'));

        if ($this->request->is('post', 'put')) {
            $conditions = array('BasicModuleProposedBranchInfo.org_id' => (int) $org_id, 'BasicModuleProposedBranchInfo.id' => (int) $branch_id);
            $data = array(
                'deactivation_request_date' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['deactivation_request_date'] . "'",
                'deactivation_reasons' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['deactivation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleProposedBranchInfo->updateAll($data, $conditions);
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
            $org_name_options = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $this->BasicModuleProposedBranchInfo->recursive = -1;
            $branch_values = $this->BasicModuleProposedBranchInfo->findById($branch_id);

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
            $conditions = array('BasicModuleProposedBranchInfo.org_id' => (int) $org_id, 'BasicModuleProposedBranchInfo.id' => (int) $branch_id);
            $data = array(
                'deactivation_request_date' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['deactivation_request_date'] . "'",
                'deactivation_reasons' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['deactivation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleProposedBranchInfo->updateAll($data, $conditions);
            $this->redirect(array('action' => 'view'));
        }
    }

    function cancel_deactivation_request($branch_id = null) {
        $this->autoRender = false;
        $org_id = $this->Session->read('Org.Id');
        $conditions = array('BasicModuleProposedBranchInfo.org_id' => (int) $org_id, 'BasicModuleProposedBranchInfo.id' => (int) $branch_id);
        $data = array(
            'deactivation_request_date' => null,
            'deactivation_reasons' => null,
            'is_approved' => null
        );
        $this->BasicModuleProposedBranchInfo->updateAll($data, $conditions);
        $this->redirect(array('action' => 'view'));
    }

    function branch_activation_request($branch_id = null) {
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

        $org_name_options = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $this->set(compact('IsValidUser', 'org_id', 'org_name_options', 'branch_id'));

        if ($this->request->is('post', 'put')) {
            $conditions = array('BasicModuleProposedBranchInfo.org_id' => (int) $org_id, 'BasicModuleProposedBranchInfo.id' => (int) $branch_id);
            $data = array(
                'activation_request_date' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['activation_request_date'] . "'",
                'activation_reasons' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['activation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleProposedBranchInfo->updateAll($data, $conditions);
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
            $org_name_options = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $this->BasicModuleProposedBranchInfo->recursive = -1;
            $branch_values = $this->BasicModuleProposedBranchInfo->findById($branch_id);

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
            $conditions = array('BasicModuleProposedBranchInfo.org_id' => (int) $org_id, 'BasicModuleProposedBranchInfo.id' => (int) $branch_id);
            $data = array(
                'activation_request_date' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['activation_request_date'] . "'",
                'activation_reasons' => "'" . $this->request->data['BasicModuleProposedBranchInfo']['activation_reasons'] . "'",
                'is_approved' => 0
            );
            $this->BasicModuleProposedBranchInfo->updateAll($data, $conditions);
            $this->redirect(array('action' => 'view'));
        }
    }

    function cancel_activation_request($branch_id = null) {
        $this->autoRender = false;
        $org_id = $this->Session->read('Org.Id');
        $conditions = array('BasicModuleProposedBranchInfo.org_id' => (int) $org_id, 'BasicModuleProposedBranchInfo.id' => (int) $branch_id);
        $data = array(
            'activation_request_date' => null,
            'activation_reasons' => null,
            'is_approved' => null
        );
        $this->BasicModuleProposedBranchInfo->updateAll($data, $conditions);
        $this->redirect(array('action' => 'view'));
    }

    function delete($branch_id = null) {
        $this->loadModel('BasicModuleProposedBranchImage');
        $org_id = $this->Session->read('Org.Id');
        $conditions = array('BasicModuleProposedBranchImage.org_id' => $org_id, 'BasicModuleProposedBranchImage.branch_id' => $branch_id);
        $branch_image_details = $this->BasicModuleProposedBranchImage->find('first', array('conditions' => $conditions, 'recursive' => -1));
        $img_file_name = $branch_image_details['BasicModuleProposedBranchImage']['file_name']; 
        $img_path = WWW_ROOT . DS . "files" . DS . "uploads" . DS . "proposed_branches" . DS . $branch_image_details['BasicModuleProposedBranchImage']['file_name'];        
        if(!empty($img_file_name)&&is_file($img_path)) {
            $image_deleted = unlink($img_path);
        }
        $branch_image_deleted = $this->BasicModuleProposedBranchImage->deleteAll($conditions, false);
        if($branch_image_deleted) {
            $branch_deleted = $this->BasicModuleProposedBranchInfo->delete($branch_id);
            if ($branch_deleted) {
                return $this->redirect(array('action' => 'view'));
            }
        }
    }
    
    function add_branch_image($branch_id = null) {
        $org_id = $this->Session->read('Org.Id');
        $orgNameOptions = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $branchNameOptions = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.id', 'BasicModuleProposedBranchInfo.branch_name'), 'conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id), 'recursive' => -1));
        $this->loadModel('BasicModuleProposedBranchImage');
        $branch_image_details = $this->BasicModuleProposedBranchImage->find('first', array('conditions' => array('BasicModuleProposedBranchImage.org_id' => $org_id, 'BasicModuleProposedBranchImage.branch_id' => $branch_id), 'recursive' => -1));
        $file_name = $branch_image_details['BasicModuleProposedBranchImage']['file_name'];
        $file_id = $branch_image_details['BasicModuleProposedBranchImage']['id'];
        $allDataDetails = $this->BasicModuleProposedBranchInfo->findById($branch_id);
        $branch_serial = $allDataDetails['BasicModuleProposedBranchInfo']['branch_serial'];
        $this->set(compact('orgNameOptions', 'branchNameOptions', 'org_id', 'branch_id', 'branch_serial', 'branch_image_details', 'file_name', 'file_id'));        
    }

    function edit_branch_image($branch_id = null) {
        $org_id = $this->Session->read('Org.Id');
        $orgNameOptions = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $branchNameOptions = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.id', 'BasicModuleProposedBranchInfo.branch_name'), 'conditions' => array('BasicModuleProposedBranchInfo.org_id' => $org_id), 'recursive' => -1));
        $this->loadModel('BasicModuleProposedBranchImage');
        $branch_image_details = $this->BasicModuleProposedBranchImage->find('first', array('conditions' => array('BasicModuleProposedBranchImage.org_id' => $org_id, 'BasicModuleProposedBranchImage.branch_id' => $branch_id), 'recursive' => -1));
        if(!empty($branch_image_details)) {
            $file_name = $branch_image_details['BasicModuleProposedBranchImage']['file_name'];
            $file_id = $branch_image_details['BasicModuleProposedBranchImage']['id'];
        }
        $allDataDetails = $this->BasicModuleProposedBranchInfo->findById($branch_id);
        $branch_serial = $allDataDetails['BasicModuleProposedBranchInfo']['branch_serial'];
        $this->set(compact('orgNameOptions', 'branchNameOptions', 'org_id', 'branch_id', 'branch_serial', 'branch_image_details', 'file_name', 'file_id'));
        if ($this->request->is('post')) {
            $this->redirect(array('action' => 'final_preview', $file_id, $branch_id));
        }
    }

    public function file_upload($id = null) {
        $data = file_get_contents($_POST['data']);
        $fileName = $_POST['name'];
        $branchId = $_POST['branch_id'];
        $branchSerial = $_POST['branch_serial'];        
        $orgId = $_POST['org_id'];
        $fileId = $_POST['file_Id'];

        $fileCode = $orgId . "_" . $branchSerial;
        $file_ext = explode('.', $fileName);
        $file_ext_final = end($file_ext);
        $serverFile = $fileCode . "." . $file_ext_final;
        $fp = fopen(WWW_ROOT . 'files'. DS .'uploads'. DS .'proposed_branches' . DS . $serverFile, 'w');
        fwrite($fp, $data);
        fclose($fp);
        try {
            $this->loadModel('BasicModuleProposedBranchImage');
            $this->BasicModuleProposedBranchImage->create();
            $new_data = array('org_id' => $orgId, 'branch_id' => $branchId, 'file_code' => $fileCode, 'file_name' => $serverFile);
            $this->BasicModuleProposedBranchImage->id = $fileId;
            $this->BasicModuleProposedBranchImage->save($new_data);
            return $this->redirect(array('action' => 'final_preview',$fileId,$branchId));
        }
        catch (Exception $ex) {}
    }

    public function final_preview($image_file_id = null, $branch_id = null) {
        $this->set(compact('image_file_id','branch_id'));               
    }
    
    function download($org_id = null) { 
        $this->layout = 'ajax';
        $org_id = 20;
        $conditions = array();
        if(!empty($org_id)) {
            $conditions = array('BasicModuleProposedBranchInfo.org_id' => $org_id);
        }
        $values = $this->BasicModuleProposedBranchInfo->find('all',array('conditions'=> $conditions,'recursive'=>0));
        $this->set(compact('values'));
        //$this->layout = null;
        //$this->autoLayout = false;
        //Configure::write('debug', '0');
    }
}