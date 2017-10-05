<?php

App::uses('AppController', 'Controller');

class BasicModuleAllDetailsPreviewsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 10,
        'order' => array('BasicModuleAllDetailsPreview.full_name_of_org' => 'ASC')
    );

    public function view() {

        if ($this->request->is('post')) {
            $option = $this->request->data['BasicModuleAllDetailsPreview']['search_option'];
            $keyword = $this->request->data['BasicModuleAllDetailsPreview']['search_keyword'];

            $condition = array("BasicModuleAllDetailsPreview.$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('BasicModuleAllDetailsPreview.full_name_of_org' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->BasicModuleAllDetailsPreview->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('BasicModuleAllDetailsPreview');
        $this->set(compact('values'));
    }

    public function details($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid MFI Information');
        }

        $mfiDetails = $this->BasicModuleAllDetailsPreview->findById($id);
        if (!$mfiDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('mfiDetails'));
    }

    public function preview($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid MFI Information');
        }

        $mfiDetails = $this->BasicModuleAllDetailsPreview->findById($id);
        if (!$mfiDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('mfiDetails'));
    }

}
