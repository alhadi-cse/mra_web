<?php

App::uses('AppController', 'Controller');

class BasicModuleProposedAddressesController extends AppController {

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
            $condition = array('BasicModuleProposedAddress.org_id' => $org_id);                
        } else {
            $condition = array();
        }
        
        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleProposedAddress']['search_option'];
            $keyword = $this->request->data['BasicModuleProposedAddress']['search_keyword'];
            
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

        $this->BasicModuleProposedAddress->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleProposedAddress');

        //if($opt && $opt!='all' && $values && sizeof($values)==1)
        if ($opt && $opt != 'mfi' && $values && sizeof($values) == 1) {
            $data_id = $values[0]['BasicModuleProposedAddress']['id'];

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

        $org_name_options = $this->BasicModuleProposedAddress->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
        $address_type_options = $this->BasicModuleProposedAddress->LookupBasicProposedAddressType->find('list', array('fields' => array('LookupBasicProposedAddressType.id', 'LookupBasicProposedAddressType.address_type')));
        $districtsOptions = $this->BasicModuleProposedAddress->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name'), 'order' => array('LookupAdminBoundaryDistrict.district_name')));
        $this->set(compact('IsValidUser', 'org_id', 'org_name_options', 'address_type_options', 'districtsOptions'));

        if (!$this->request->is('post')) {
            $this->Session->write('Data.Mode', 'insert');
        } 
        else {
            $reqData = $this->request->data;            
            if ($reqData && $reqData['BasicModuleProposedAddress']) {
                if(empty($reqData['BasicModuleProposedAddress']['org_id']) && !empty($org_id))
                    $reqData['BasicModuleProposedAddress']['org_id'] = $org_id;
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
            $opt = $this->Session->read('Data.Mode');
            if (!$opt || $opt == 'insert') {
                if(!empty($reqData['BasicModuleProposedAddress']['address_type_id'])) {
                    $address_type_id = $reqData['BasicModuleProposedAddress']['address_type_id'];
                    if(!empty($address_type_id)&&$address_type_id=='3') {
                        $existing_values = $this->BasicModuleProposedAddress->find('first', array('recursive' => -1, 'conditions' => array('org_id' => $org_id, 'address_type_id' => array(1,2))));
                        if(!empty($existing_values)) {
                            $this->BasicModuleProposedAddress->deleteAll(array('BasicModuleProposedAddress.org_id' => $org_id, 'address_type_id' => array(1,2)), false);
                        }                        
                    }
                    else {
                        $existing_values = $this->BasicModuleProposedAddress->find('first', array('recursive' => -1, 'conditions' => array('org_id' => $org_id, 'address_type_id' => array(3))));
                        if(!empty($existing_values)) {
                            $this->BasicModuleProposedAddress->deleteAll(array('BasicModuleProposedAddress.org_id' => $org_id, 'address_type_id' => array(3)), false);
                        }
                    }
                    $existing_addresses = $this->BasicModuleProposedAddress->find('first', array('recursive' => -1, 'conditions' => array('org_id' => $org_id, 'address_type_id' => $address_type_id)));
                    if(!empty($existing_addresses)) {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => 'Address information already exists!'
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                    else {                        
                        $this->BasicModuleProposedAddress->create();
                        $newData = $this->BasicModuleProposedAddress->save($reqData);

                        if ($newData) {
//                            $data_id = $newData['BasicModuleProposedAddress']['id'];
//                            $this->Session->write('Data.Id', $data_id);
//                            $this->Session->write('Data.Mode', 'update');
//                            $this->redirect(array('action' => 'preview', $data_id));
                            $this->redirect(array('action' => 'view'));
                        }
                    }
                }
            }
            else {
                $data_id = $this->Session->read('Data.Id');
                $this->BasicModuleProposedAddress->id = $data_id;
                if ($this->BasicModuleProposedAddress->save($reqData)) {
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
                $msg['msg'] = 'Invalid address data !';
            } else if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('IsValidUser', 'org_id', 'id'));
        if (!$this->request->is(array('post', 'put'))) {

            $org_name_options = $this->BasicModuleProposedAddress->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org')));
            $address_type_options = $this->BasicModuleProposedAddress->LookupBasicProposedAddressType->find('list', array('fields' => array('LookupBasicProposedAddressType.id', 'LookupBasicProposedAddressType.address_type')));
            $districtsOptions = $this->BasicModuleProposedAddress->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name'), 'order' => array('LookupAdminBoundaryDistrict.district_name')));
            $this->BasicModuleProposedAddress->recursive = -1;
            $address_values = $this->BasicModuleProposedAddress->findById($id);
//            $address_values = $this->BasicModuleProposedAddress->find('first',array('recursive' => -1,'conditions' => array('BasicModuleProposedAddress.id' => $id))); 
//            debug($post);
//            exit;
            $address_type_id = $address_values['BasicModuleProposedAddress']['address_type_id'];
            $district_id = $address_values['BasicModuleProposedAddress']['district_id'];
            $upazila_id = $address_values['BasicModuleProposedAddress']['upazila_id'];
            $union_id = $address_values['BasicModuleProposedAddress']['union_id'];

            $upazilaOptions = $this->BasicModuleProposedAddress->LookupAdminBoundaryUpazila->find('list', array(
                'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
                'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
                'order' => array('LookupAdminBoundaryUpazila.upazila_name'),
                'recursive' => -1
            ));

            $unionOptions = $this->BasicModuleProposedAddress->LookupAdminBoundaryUnion->find('list', array(
                'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
                'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
                'order' => array('LookupAdminBoundaryUnion.union_name'),
                'recursive' => -1
            ));
            $mauzaOptions = $this->BasicModuleProposedAddress->LookupAdminBoundaryMauza->find('list', array(
                'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
                'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
                'order' => array('LookupAdminBoundaryMauza.mauza_name'),
                'recursive' => -1
            ));

            $this->set(compact('back_opt', 'org_name_options', 'address_type_options', 'address_type_id', 'districtsOptions', 'upazilaOptions', 'unionOptions', 'mauzaOptions'));

            
            if (!$address_values) {
                //throw new NotFoundException('Invalid Information');
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid address information !'
                );

                $this->set(compact('msg'));
                return;
            }
            $this->request->data = $address_values;
        } else {
            $this->BasicModuleProposedAddress->id = $id;
            if ($this->BasicModuleProposedAddress->save($this->request->data)) {
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
            $allDataDetails = $this->BasicModuleProposedAddress->findById($id);
            if (!empty($allDataDetails) && is_array($allDataDetails))
                $data_count = 1;
        } else if (!empty($org_id)) {
            $allDataDetails = $this->BasicModuleProposedAddress->find('all', array('conditions' => array('BasicModuleProposedAddress.org_id' => $org_id)));
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
                'msg' => 'Address data not found !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('data_count', 'allDataDetails'));
    }
    
    public function details_all($org_id = null) {
                    
        $data_count = 0;        
        if (!empty($org_id)) {
            $allDataDetails = $this->BasicModuleProposedAddress->find('all', array('conditions' => array('BasicModuleProposedAddress.org_id' => $org_id)));
            if (!empty($allDataDetails) && is_array($allDataDetails) && count($allDataDetails) > 0) {
                if (count($allDataDetails) === 1) {
                    $allDataDetails = $allDataDetails[0];
                    $data_count = 1;
                } else {
                    $data_count = 'all';
                }
            }
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
            $this->loadModel('LookupBasicProposedAddressType');
            $proposed_address_types = $this->LookupBasicProposedAddressType->find('all');
            $allProposedAddressDetails = $allDetails['BasicModuleProposedAddress'];             
            $this->set(compact('org_id','mfiDetails','proposed_address_types','allProposedAddressDetails'));
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
                'msg' => 'Invalid address data !'
            );
            $this->set(compact('msg'));
        }

        $addDetails = $this->BasicModuleProposedAddress->findById($id);
        if (!$addDetails) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid address data !'
            );
            $this->set(compact('msg'));
        }
        $this->set(compact('id', 'addDetails'));
    }

    function update_upazila_select() {
        $district_id = $this->request->data['BasicModuleProposedAddress']['district_id'];

        $upazila_options = $this->BasicModuleProposedAddress->LookupAdminBoundaryUpazila->find('list', array(
            'fields' => array('LookupAdminBoundaryUpazila.id', 'LookupAdminBoundaryUpazila.upazila_name'),
            'conditions' => array('LookupAdminBoundaryUpazila.district_id' => $district_id),
            'order' => array('LookupAdminBoundaryUpazila.upazila_name'),
            'recursive' => -1
        ));

        $this->set(compact('upazila_options'));
        $this->layout = 'ajax';
    }

    function update_union_select() {
        $upazila_id = $this->request->data['BasicModuleProposedAddress']['upazila_id'];

        $union_options = $this->BasicModuleProposedAddress->LookupAdminBoundaryUnion->find('list', array(
            'fields' => array('LookupAdminBoundaryUnion.id', 'LookupAdminBoundaryUnion.union_name'),
            'conditions' => array('LookupAdminBoundaryUnion.upazila_id' => $upazila_id),
            'order' => array('LookupAdminBoundaryUnion.union_name'),
            'recursive' => -1
        ));

        $this->set(compact('union_options'));
        $this->layout = 'ajax';
    }

    function update_mauza_select() {
        $union_id = $this->request->data['BasicModuleProposedAddress']['union_id'];

        $mauza_options = $this->BasicModuleProposedAddress->LookupAdminBoundaryMauza->find('list', array(
            'fields' => array('LookupAdminBoundaryMauza.id', 'LookupAdminBoundaryMauza.mauza_name'),
            'conditions' => array('LookupAdminBoundaryMauza.union_id' => $union_id),
            'order' => array('LookupAdminBoundaryMauza.mauza_name'),
            'recursive' => -1
        ));

        $this->set(compact('mauza_options'));
        $this->layout = 'ajax';
    }
}

