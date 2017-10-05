<?php

App::uses('AppController', 'Controller');

class CDBNonMfiCoveredDistrictsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');

//    public $paginate = array(
//        'limit' => 10,
//        'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC')
//    );

    public function view() {

        $conditions = $condition1 = array();
        if ($this->request->is('post')) {
            $option = $this->request->data['CDBNonMfiCoveredDistrict']['search_option'];
            $keyword = $this->request->data['CDBNonMfiCoveredDistrict']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($org_id)) {
                    $conditions = array("AND" => array("$option LIKE '%$keyword%'", 'CDBNonMfiBasicInfo.id' => $org_id));
                } else {
                    $conditions = array("$option LIKE '%$keyword%'");
                }
                $opt_all = true;
            }
        }

        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $conditions['CDBNonMfiBasicInfo.id'] = $org_id;
            $condition1['CDBNonMfiCoveredDistrict.org_id'] = $org_id;
        }

        $fields = array('CDBNonMfiCoveredDistrict.district_id', 'LookupAdminBoundaryDistrict.district_name', 'CDBNonMfiCoveredDistrict.org_id');
        $dist_list = $this->CDBNonMfiCoveredDistrict->find('list', array('fields' => $fields, 'conditions' => $condition1, 'order' => array('LookupAdminBoundaryDistrict.district_name'), 'contain' => array('LookupAdminBoundaryDistrict')));

        $fields = array('CDBNonMfiBasicInfo.id', 'CDBNonMfiBasicInfo.name_of_org', 'LookupCDBNonMfiType.type_name', 'LookupCDBNonMfiMinistryAuthorityName.name_of_ministry_or_authority');
        $org_list = $this->CDBNonMfiCoveredDistrict->CDBNonMfiBasicInfo->find('all', array(
            'fields' => $fields,
            'conditions' => $conditions,
            'order' => array('CDBNonMfiBasicInfo.name_of_org' => 'ASC'),
            'group' => array('CDBNonMfiBasicInfo.id')));

        $this->set(compact('org_list', 'dist_list'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->CDBNonMfiCoveredDistrict->create();
            if ($this->CDBNonMfiCoveredDistrict->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
    }

    public function edit($org_id = null) {
        if (!$org_id) {
            throw new NotFoundException('Invalid Non-MFI Information');
        }

        $fields = array('CDBNonMfiBasicInfo.id', 'CDBNonMfiBasicInfo.name_of_org', 'LookupCDBNonMfiType.type_name', 'LookupCDBNonMfiMinistryAuthorityName.name_of_ministry_or_authority');
        $org_details = $this->CDBNonMfiCoveredDistrict->CDBNonMfiBasicInfo->findById($org_id);

        //$covered_dist_list = $this->CDBNonMfiCoveredDistrict->find($org_id);
        $fields = array('CDBNonMfiCoveredDistrict.district_id', 'CDBNonMfiCoveredDistrict.district_id');
        $covered_dist_list = $this->CDBNonMfiCoveredDistrict->find('list', array('fields' => $fields, 'conditions' => array('CDBNonMfiCoveredDistrict.org_id' => $org_id), 'order' => array('LookupAdminBoundaryDistrict.district_name'), 'contain' => array('LookupAdminBoundaryDistrict')));

        //$fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_with_code');
        $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
        $all_dist_list = $this->CDBNonMfiCoveredDistrict->LookupAdminBoundaryDistrict->find('list', array('fields' => $fields, 'group' => array('LookupAdminBoundaryDistrict.id'), 'order' => array('LookupAdminBoundaryDistrict.district_name')));

        $this->set(compact('org_id', 'org_details', 'all_dist_list'));

        if ($this->request->is(array('post', 'put'))) {
            $covered_dist_list = $this->request->data['CDBNonMfiCoveredDistrict']['district_ids'];

            if (!empty($org_id) && !empty($covered_dist_list) && count($covered_dist_list) > 0) {

                $covered_dists_data = array();
                foreach ($covered_dist_list as $dist_id) {
                    $covered_dists_data[] = array('org_id' => $org_id, 'district_id' => $dist_id);
                }
            }

            $this->CDBNonMfiCoveredDistrict->deleteAll(array('CDBNonMfiCoveredDistrict.org_id' => $org_id), false);

            if (!empty($covered_dists_data) && count($covered_dists_data) > 0) {
                $this->CDBNonMfiCoveredDistrict->create();
                if ($this->CDBNonMfiCoveredDistrict->saveAll($covered_dists_data))
                    $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $post = array();
            //$post['CDBNonMfiCoveredDistrict']['org_id'] = $org_id;
            $post['CDBNonMfiCoveredDistrict']['district_ids'] = $covered_dist_list;
            $this->request->data = $post;
        }
    }

    public function details($org_id = null) {
        if (empty($org_id)) {
            $org_id = $this->Session->read('Org.Id');
            if (empty($org_id)) {
                throw new NotFoundException('Invalid Non-MFI Information');
                return;
            }
        }

        $conditions = $condition1 = array();

        if (!empty($org_id)) {
            $conditions['CDBNonMfiBasicInfo.id'] = $org_id;
            $condition1['CDBNonMfiCoveredDistrict.org_id'] = $org_id;
        }

        $fields = array('CDBNonMfiCoveredDistrict.district_id', 'LookupAdminBoundaryDistrict.district_name');
        $dist_list = $this->CDBNonMfiCoveredDistrict->find('list', array('fields' => $fields, 'conditions' => $condition1, 'order' => array('LookupAdminBoundaryDistrict.district_name'), 'contain' => array('LookupAdminBoundaryDistrict')));

        $fields = array('CDBNonMfiBasicInfo.id', 'CDBNonMfiBasicInfo.name_of_org', 'LookupCDBNonMfiType.type_name', 'LookupCDBNonMfiMinistryAuthorityName.name_of_ministry_or_authority');
        $org_details = $this->CDBNonMfiCoveredDistrict->CDBNonMfiBasicInfo->findById($org_id);

        $this->set(compact('org_details', 'dist_list'));
    }

}
