<?php

App::uses('AppController', 'Controller');

class LookupLicenseInspectionParameterGroupsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
//    public $paginate = array(
//         'limit' => 10,
//         'order' => array('LookupLicenseInspectionParameterGroup.serial_no' => 'ASC')
//    );

    public function view() {
//        if ($this->request->is('post')) {
//            $option = $this->request->data['LookupLicenseInspectionParameterGroup']['search_option'];
//            $keyword = $this->request->data['LookupLicenseInspectionParameterGroup']['search_keyword'];
//            $condition = array("$option LIKE '%$keyword%'");
//
//            $this->paginate = array(
//                'order' => array('LookupLicenseInspectionParameterGroup.serial_no' => 'ASC'),
//                'limit' => 10,
//                'conditions' => $condition);
//        }
//
//        $this->LookupLicenseInspectionParameterGroup->recursive = 0;
//        $this->Paginator->settings = $this->paginate;


        $paging_option = array('limit' => 10, 'order' => array('LookupLicenseInspectionParameterGroup.inspection_type', 'LookupLicenseInspectionParameterGroup.serial_no' => 'ASC'));

        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseInspectionParameterGroup']['search_option'];
            $keyword = $this->request->data['LookupLicenseInspectionParameterGroup']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                $paging_option = array_merge($paging_option, array('conditions' => array("$option LIKE '%$keyword%'")));
            }
        }

        $this->LookupLicenseInspectionParameterGroup->recursive = 0;
        $this->Paginator->settings = $paging_option;
        $values = $this->Paginator->paginate('LookupLicenseInspectionParameterGroup');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicenseInspectionParameterGroup->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }
        $inspection_parameter_group_options = $this->LookupLicenseInspectionParameterGroup->LookupLicenseInspectionType->find('list', array('fields' => array('LookupLicenseInspectionType.id', 'LookupLicenseInspectionType.inspection_type')));
        $this->set(compact('inspection_parameter_group_options'));
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }
        $inspection_parameter_group_options = $this->LookupLicenseInspectionParameterGroup->LookupLicenseInspectionType->find('list', array('fields' => array('LookupLicenseInspectionType.id', 'LookupLicenseInspectionType.inspection_type')));
        $this->set(compact('inspection_parameter_group_options'));

        $post = $this->LookupLicenseInspectionParameterGroup->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseInspectionParameterGroup->id = $id;
            if ($this->LookupLicenseInspectionParameterGroup->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupLicenseInspectionParameterGroup->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

}
