<?php

App::uses('AppController', 'Controller');

class BasicModuleOfficeSpaceUsagesController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    
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
            $condition = array('BasicModuleOfficeSpaceUsage.org_id' => $org_id);                
        } else {
            $condition = array();
        }
        
        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleOfficeSpaceUsage']['search_option'];
            $keyword = $this->request->data['BasicModuleOfficeSpaceUsage']['search_keyword'];
            
            if(!empty($option) && !empty($keyword)) {
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

        $this->BasicModuleOfficeSpaceUsage->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleOfficeSpaceUsage');

        //if($opt && $opt!='all' && $values && sizeof($values)==1)
        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['BasicModuleOfficeSpaceUsage']['id'];

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
        
        $orgNameOptions = $this->BasicModuleOfficeSpaceUsage->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $districtsOptions = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $usage_type_options = $this->BasicModuleOfficeSpaceUsage->LookupBasicOfficeUsageType->find('list', array('fields' => array('LookupBasicOfficeUsageType.id', 'LookupBasicOfficeUsageType.usage_type')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'districtsOptions', 'usage_type_options'));
        
        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['BasicModuleOfficeSpaceUsage']) {
                if(empty($reqData['BasicModuleOfficeSpaceUsage']['org_id']) && !empty($org_id))
                    $reqData['BasicModuleOfficeSpaceUsage']['org_id'] = $org_id;
                
                if(!empty($reqData['BasicModuleOfficeSpaceUsage']['usage_type_id'])) {
                    $usage_type_id = $reqData['BasicModuleOfficeSpaceUsage']['usage_type_id'];
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
        
                $existing_office_space_usages = $this->BasicModuleOfficeSpaceUsage->find('first', array('recursive' => -1, 'conditions' => array('org_id' => $org_id, 'usage_type_id' => $usage_type_id)));
                if(!empty($existing_office_space_usages)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Information already exists!'
                    );
                    $this->set(compact('msg'));
                    return;
                }
                else {                        
                    $this->BasicModuleOfficeSpaceUsage->create();
                    $newData = $this->BasicModuleOfficeSpaceUsage->save($reqData);

                    if ($newData) {
                        $data_id = $newData['BasicModuleOfficeSpaceUsage']['id'];
                        $this->Session->write('Data.Id', $data_id);
                        $this->Session->write('Data.Mode', 'update');
                        //$this->redirect(array('action' => 'preview', $data_id));
                        $this->redirect(array('action' => 'view'));
                    }
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
                $msg['msg'] = 'Invalid Branch data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->BasicModuleOfficeSpaceUsage->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $usage_type_options = $this->BasicModuleOfficeSpaceUsage->LookupBasicOfficeUsageType->find('list', array('fields' => array('LookupBasicOfficeUsageType.id', 'LookupBasicOfficeUsageType.usage_type')));
            $districtsOptions = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
            $district_id = $this->BasicModuleOfficeSpaceUsage->find('list', array('fields' => array('BasicModuleOfficeSpaceUsage.district_id'), 'conditions' => array("AND" => array('BasicModuleOfficeSpaceUsage.id' => $id, 'BasicModuleOfficeSpaceUsage.org_id' => $org_id))));
            $upazila_id = $this->BasicModuleOfficeSpaceUsage->find('list', array('fields' => array('BasicModuleOfficeSpaceUsage.upazila_id'), 'conditions' => array("AND" => array('BasicModuleOfficeSpaceUsage.id' => $id, 'BasicModuleOfficeSpaceUsage.org_id' => $org_id))));
            $union_id = $this->BasicModuleOfficeSpaceUsage->find('list', array('fields' => array('BasicModuleOfficeSpaceUsage.union_id'), 'conditions' => array("AND" => array('BasicModuleOfficeSpaceUsage.id' => $id, 'BasicModuleOfficeSpaceUsage.org_id' => $org_id))));

            $upazilaOptions = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryUpazila->find('list', array(
                'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
                'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
                'recursive' => -1
            ));

            $unionOptions = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryUnion->find('list', array(
                'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
                'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
                'recursive' => -1
            ));
            $mauzaOptions = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryMauza->find('list', array(
                'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
                'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
                'recursive' => -1
            ));

            $this->set(compact('back_opt', 'orgNameOptions', 'usage_type_options', 'districtsOptions', 'upazilaOptions', 'unionOptions', 'mauzaOptions'));

            $post = $this->BasicModuleOfficeSpaceUsage->findById($id);
            if (!$post) {
                //throw new NotFoundException('Invalid BranchInfo Information');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid branchInfo information !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $post;
        } else {
            $this->BasicModuleOfficeSpaceUsage->id = $id;
            if ($this->BasicModuleOfficeSpaceUsage->save($this->request->data)) {
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
            $allDataDetails = $this->BasicModuleOfficeSpaceUsage->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->BasicModuleOfficeSpaceUsage->find('all', array('conditions' => array('BasicModuleOfficeSpaceUsage.org_id' => $org_id)));
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
            $this->loadModel('LookupBasicOfficeUsageType');
            $usage_types = $this->LookupBasicOfficeUsageType->find('all');
            $allOfficeSpaceUsageDetails = $allDetails['BasicModuleOfficeSpaceUsage'];            
            $this->set(compact('org_id','mfiDetails','proposed_address_types','usage_types','allOfficeSpaceUsageDetails'));
        }
        catch(Exception $ex) {
            debug($ex->getMessage());
        }
    }

    public function preview($id = null) {
        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Branch data !'
            );
            $this->set(compact('msg'));
        }

        $addDetails = $this->BasicModuleOfficeSpaceUsage->findById($id);
        if (!$addDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Branch data !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'addDetails'));
    }
        
    
    function update_upazila_select() {
        $district_id = $this->request->data['BasicModuleOfficeSpaceUsage']['district_id'];

        $upazila_options = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryUpazila->find('list', array(
            'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
            'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
            'recursive' => -1
        ));

        $this->set(compact('upazila_options'));
        $this->layout = 'ajax';
    }

    function update_union_select() {
        $upazila_id = $this->request->data['BasicModuleOfficeSpaceUsage']['upazila_id'];

        $union_options = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryUnion->find('list', array(
            'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
            'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
            'recursive' => -1
        ));

        $this->set(compact('union_options'));
        $this->layout = 'ajax';
    }

    function update_mauza_select() {
        $union_id = $this->request->data['BasicModuleOfficeSpaceUsage']['union_id'];

        $mauza_options = $this->BasicModuleOfficeSpaceUsage->LookupAdminBoundaryMauza->find('list', array(
            'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
            'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
            'recursive' => -1
        ));

        $this->set(compact('mauza_options'));
        $this->layout = 'ajax';
    }

}

