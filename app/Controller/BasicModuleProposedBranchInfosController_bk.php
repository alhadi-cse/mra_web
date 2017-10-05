<?php

App::uses('AppController', 'Controller');

class BasicModuleProposedBranchInfosController extends AppController {

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
            $condition = array('BasicModuleProposedBranchInfo.org_id' => $org_id);                
        } else {
            $condition = array();
        }
        
        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleProposedBranchInfo']['search_option'];
            $keyword = $this->request->data['BasicModuleProposedBranchInfo']['search_keyword'];
            
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

        $this->BasicModuleProposedBranchInfo->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleProposedBranchInfo');

        //if($opt && $opt!='all' && $values && sizeof($values)==1)
        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['BasicModuleProposedBranchInfo']['id'];

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
        
        $orgNameOptions = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $districtsOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $this->set(compact('IsValidUser', 'org_id', 'orgNameOptions', 'districtsOptions'));
        
        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } else {
            $reqData = $this->request->data;
            if ($reqData && $reqData['BasicModuleProposedBranchInfo']) {
                if(empty($reqData['BasicModuleProposedBranchInfo']['org_id']) && !empty($org_id))
                    $reqData['BasicModuleProposedBranchInfo']['org_id'] = $org_id;
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
                $this->BasicModuleProposedBranchInfo->create();
                $newData = $this->BasicModuleProposedBranchInfo->save($reqData);

                if ($newData) {
                    $data_id = $newData['BasicModuleProposedBranchInfo']['id'];
                    $this->Session->write('Data.Id', $data_id);
                    $this->Session->write('Data.Mode', 'update');
                    //$this->redirect(array('action' => 'preview', $data_id));
                }
            } else {
                $data_id = $this->Session->read('Data.Id');
                $this->BasicModuleProposedBranchInfo->id = $data_id;
                if ($this->BasicModuleProposedBranchInfo->save($reqData)) {
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
                $msg['msg'] = 'Invalid Branch data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $orgNameOptions = $this->BasicModuleProposedBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $districtsOptions = $this->BasicModuleProposedBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
            $district_id = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.district_id'), 'conditions' => array("AND" => array('BasicModuleProposedBranchInfo.id' => $id, 'BasicModuleProposedBranchInfo.org_id' => $org_id))));
            $upazila_id = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.upazila_id'), 'conditions' => array("AND" => array('BasicModuleProposedBranchInfo.id' => $id, 'BasicModuleProposedBranchInfo.org_id' => $org_id))));
            $union_id = $this->BasicModuleProposedBranchInfo->find('list', array('fields' => array('BasicModuleProposedBranchInfo.union_id'), 'conditions' => array("AND" => array('BasicModuleProposedBranchInfo.id' => $id, 'BasicModuleProposedBranchInfo.org_id' => $org_id))));

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

            $this->set(compact('back_opt', 'orgNameOptions', 'districtsOptions', 'upazilaOptions', 'unionOptions', 'mauzaOptions'));

            $post = $this->BasicModuleProposedBranchInfo->findById($id);
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
            $this->BasicModuleProposedBranchInfo->id = $id;
            if ($this->BasicModuleProposedBranchInfo->save($this->request->data)) {
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
            $allDataDetails = $this->BasicModuleProposedBranchInfo->findById($id);
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
            $this->set(compact('org_id','mfiDetails','allProposedBranchDetails'));
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

        $branchDetails = $this->BasicModuleProposedBranchInfo->findById($id);
        if (!$branchDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Branch data !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'branchDetails'));
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

}
