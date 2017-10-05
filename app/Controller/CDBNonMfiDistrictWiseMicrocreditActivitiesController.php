<?php

App::uses('AppController', 'Controller');

class CDBNonMfiDistrictWiseMicrocreditActivitiesController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $paginate = array('limit' => 10, 'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC'));
    public $components = array('Paginator');

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['CDBNonMfiDistrictWiseMicrocreditActivity']['search_option'];
            $keyword = $this->request->data['CDBNonMfiDistrictWiseMicrocreditActivity']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($org_id)) {
                    $condition = array("AND" => array("$option LIKE '%$keyword%'", 'CDBNonMfiDistrictWiseMicrocreditActivity.org_id' => $org_id));
                } else {
                    $condition = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            } else {
                $condition = array();
            }
            $this->paginate = array(
                'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->CDBNonMfiDistrictWiseMicrocreditActivity->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('CDBNonMfiDistrictWiseMicrocreditActivity');
        $this->set('values', $values);
    }

    public function add() {
        $districtsOptions = $this->CDBNonMfiDistrictWiseMicrocreditActivity->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $orgNameOptions = $this->CDBNonMfiDistrictWiseMicrocreditActivity->CDBNonMfiBasicInfo->find('list', array('fields' => array('CDBNonMfiBasicInfo.id', 'CDBNonMfiBasicInfo.name_of_org')));
        $this->set(compact('orgNameOptions', 'districtsOptions'));

        if ($this->request->is('post')) {
            $this->CDBNonMfiDistrictWiseMicrocreditActivity->create();
            if ($this->CDBNonMfiDistrictWiseMicrocreditActivity->save($this->request->data)) {

                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($id = null) {
        $districtsOptions = $this->CDBNonMfiDistrictWiseMicrocreditActivity->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
        $orgNameOptions = $this->CDBNonMfiDistrictWiseMicrocreditActivity->CDBNonMfiBasicInfo->find('list', array('fields' => array('CDBNonMfiBasicInfo.id', 'CDBNonMfiBasicInfo.name_of_org')));
        $this->set(compact('orgNameOptions', 'districtsOptions'));

        if (!$id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }

        $post = $this->CDBNonMfiDistrictWiseMicrocreditActivity->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->CDBNonMfiDistrictWiseMicrocreditActivity->id = $id;
            if ($this->CDBNonMfiDistrictWiseMicrocreditActivity->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
            $this->Session->setFlash('Unable to update the information of organization');
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }

        $yearlyMicrocreditInfo = $this->CDBNonMfiDistrictWiseMicrocreditActivity->findById($id);
        if (!$yearlyMicrocreditInfo) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }
        $this->set(compact('yearlyMicrocreditInfo'));
    }

}
