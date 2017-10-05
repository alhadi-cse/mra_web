<?php

App::uses('AppController', 'Controller');

class LookupBasicMraAuthoritiesController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $uses = array('LookupBasicMraAuthority');

    public function view() {
        if ($this->request->is('post')) {
            $option = $this->request->data['LookupBasicMraAuthority']['search_option'];
            $keyword = $this->request->data['LookupBasicMraAuthority']['search_keyword'];
            $condition = array("$option LIKE '%$keyword%'");

            $this->paginate = array(
                'order' => array('LookupBasicMraAuthority.serial_no' => 'ASC'),
                'limit' => 10,
                'conditions' => $condition);
        }

        $this->LookupBasicMraAuthority->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('LookupBasicMraAuthority');
        $this->set(compact('values'));
    }

//    public function add() {
//        if ($this->request->is('post')) {
//            if ($this->LookupBasicMraAuthority->save($this->request->data)) {
//                $this->redirect(array('action' => 'view'));
//            }
//        }
//    }


    public function add() {
        
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('Invalid Information');
        }

        $post = $this->LookupBasicMraAuthority->findById($id);
        if (!$post) {
            throw new NotFoundException('Invalid Information');
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LookupBasicMraAuthority->id = $id;
            if ($this->LookupBasicMraAuthority->save($this->request->data)) {
                return $this->redirect(array('action' => 'view'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->LookupBasicMraAuthority->delete($id)) {
            return $this->redirect(array('action' => 'view'));
        }
    }

    public function file_upload() {
        $data = file_get_contents($_POST['data']);
        $inputSlNo = $_POST['inputSlNo'];

        $fileName = $_POST['name'];
        //$inputName = $_POST['inputName'];
        $inputAuthorizedName = $_POST['inputAuthorizedName'];
        $inputDesignation = $_POST['inputDesignation'];
        
        $serverFile = time() . $fileName;        
        $fp = fopen(WWW_ROOT . "/files/uploads/" . $serverFile, 'w');
        fwrite($fp, $data);
        fclose($fp);
        
        //$returnData = array("serverFile" => $serverFile);
        //echo json_encode($returnData);        
        try {
            $this->loadModel('LookupBasicMraAuthority');
            $this->LookupBasicMraAuthority->create();
            $new_date = array('serial_no' => $inputSlNo, 'authority_name' => $inputAuthorizedName, 'authority_designation' => $inputDesignation, 'authority_sign' => $serverFile);
            $this->LookupBasicMraAuthority->save($new_date);

            return $this->redirect(array('action' => 'view'));
        } catch (Exception $ex) {
            debug($ex);
        }
    }

    public function file_update($id = null) {

        $data = file_get_contents($_POST['data']);

        $inputSlNo = $_POST['inputSlNo'];
        $fileName = $_POST['name'];
        $id = $_POST['id'];
        $inputAuthorizedName = $_POST['inputAuthorizedName'];
        $inputDesignation = $_POST['inputDesignation'];
        
        $serverFile = time() . $fileName;
        $fp = fopen(WWW_ROOT . '/files/uploads/' . $serverFile, 'w');
        fwrite($fp, $data);
        fclose($fp);
        
        //$returnData = array("serverFile" => $serverFile);
        //echo json_encode($returnData);        
        try {
            $this->loadModel('LookupBasicMraAuthority');
            $this->LookupBasicMraAuthority->create();
            $new_date = array('serial_no' => $inputSlNo, 'authority_name' => $inputAuthorizedName, 'authority_designation' => $inputDesignation, 'authority_sign' => $serverFile);
            $this->LookupBasicMraAuthority->id = $id;
            $this->LookupBasicMraAuthority->save($new_date);

            return $this->redirect(array('action' => 'view'));
        } catch (Exception $ex) {
            //debug($ex);
        }
    }

    public function file_delete() {
        return 'abc';
    }

}
