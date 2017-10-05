
<?php

App::uses('AppController', 'Controller');

class LookupLicenseApplicationRejectionReasonsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array('limit' => 10, 'order' => array('LookupLicenseApplicationRejectionReason.rejection_reason' => 'ASC'));

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupLicenseApplicationRejectionReason']['search_option'];
            $keyword = $this->request->data['LookupLicenseApplicationRejectionReason']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('LookupLicenseApplicationRejectionReason.LookupLicenseApplicationRejectionReason' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LookupLicenseApplicationRejectionReason->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupLicenseApplicationRejectionReason');
        $this->set(compact('values'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            if ($this->LookupLicenseApplicationRejectionReason->save($this->request->data)) {
                $this->redirect(array('action' => 'view'));
            }
        }

        $rejection_type_options = $this->LookupLicenseApplicationRejectionReason->LookupLicenseApplicationRejectionType->find('list', array('fields' => array('id', 'rejection_type')));

        $rejection_category_options = $this->LookupLicenseApplicationRejectionReason->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('LookupLicenseApplicationRejectionCategory.id', 'LookupLicenseApplicationRejectionCategory.rejection_category')));
        $this->set(compact('rejection_type_options', 'rejection_category_options'));
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Rejection Reason');
        }
        $post = $this->LookupLicenseApplicationRejectionReason->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Rejection Reason');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupLicenseApplicationRejectionReason->id = $id;
            if ($this->LookupLicenseApplicationRejectionReason->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }

        $rejection_type_options = $this->LookupLicenseApplicationRejectionReason->LookupLicenseApplicationRejectionType->find('list', array('fields' => array('id', 'rejection_type')));

        $rejection_category_options = $this->LookupLicenseApplicationRejectionReason->LookupLicenseApplicationRejectionCategory->find('list', array('fields' => array('LookupLicenseApplicationRejectionCategory.id', 'LookupLicenseApplicationRejectionCategory.rejection_category')));
        $this->set(compact('rejection_type_options', 'rejection_category_options'));
    }

    public function delete($id) {
        if ($this->LookupLicenseApplicationRejectionReason->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

    public function selected_reasons() {

        $rejection_type_id = $rejection_category_id = '';
        if (!empty($this->request->data)) {
            foreach ($this->request->data as $reqData) {
                if (isset($reqData['rejection_type_id'])) {
                    $rejection_type_id = $reqData['rejection_type_id'];

                    if (isset($reqData['rejection_category_id']))
                        $rejection_category_id = $reqData['rejection_category_id'];

                    break;
                }
            }
        }

        if (!empty($rejection_type_id)) {
            $conditions = array('rejection_type_id' => $rejection_type_id);
        }

        if (!empty($rejection_category_id)) {
            if (!empty($conditions))
                $conditions = array_merge($conditions, array('rejection_category_id' => $rejection_category_id));
            else
                $conditions = array('rejection_category_id' => $rejection_category_id);
        }

        if (!empty($conditions))
            $reason_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => array('id', 'rejection_reason'), 'conditions' => $conditions, 'recursive' => -1));
        else
            $reason_options = $this->LookupLicenseApplicationRejectionReason->find('list', array('fields' => array('id', 'rejection_reason'), 'recursive' => -1));

        $this->set(compact('reason_options'));
        $this->layout = 'ajax';
    }

}
