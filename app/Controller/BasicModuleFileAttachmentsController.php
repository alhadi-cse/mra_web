<?php

App::uses('AppController', 'Controller');

class BasicModuleFileAttachmentsController extends AppController {

    public $components = array('Paginator');
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');

    public function view() {
        $IsValidUser = $this->Session->read('User.IsValid');        
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
       
        $org_id = $this->Session->read('Org.Id');
        $condition = array();
        if (!empty($org_id)) {
            $condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        $fields = array('id','short_name_of_org','full_name_of_org');
        $this->loadModel('BasicModuleBasicInformation');
        $values = $this->BasicModuleBasicInformation->find('first',array('fields'=>$fields,'conditions' => $condition,'recursive'=>-1));
        $file_type_options = $this->BasicModuleFileAttachment->LookupBasicAttachmentType->find('list', array('fields' => array('LookupBasicAttachmentType.id', 'LookupBasicAttachmentType.attachment_types')));
        $this->set(compact('values','file_type_options','IsValidUser', 'org_id', 'user_group_id'));
    }

    public function file_upload() {
        $this->set('files', $this->BasicModuleFileAttachment->find('all'));
        $data = file_get_contents($_POST['data']);
        $orgId = $_POST['org_id'];        
        $fileTypeId = $_POST['file_type_ids'];
        $fileName = $_POST['file_name'];
        
        $fileCode = $orgId . "_" . $fileTypeId;
        $file_ext = explode('.', $fileName);
        $file_ext_final = end($file_ext);
        $serverFile = $fileCode . "." . $file_ext_final;
        
        $fp = fopen(WWW_ROOT . DS . 'files'. DS .'uploads'. DS .'new_mfis' . DS . $serverFile, 'w');
        fwrite($fp, $data);
        fclose($fp);
        try {
            $new_data = array('org_id'=>$orgId,'file_type_id'=>$fileTypeId,'file_name' => $serverFile);
            $this->loadModel('BasicModuleFileAttachment');
            $file_infos = $this->BasicModuleFileAttachment->find('first',array('conditions'=>array('org_id'=>$orgId,'file_type_id'=>$fileTypeId)));
            if(!empty($file_infos)) {
                $this->BasicModuleFileAttachment->updateAll($new_data,array('org_id'=>$orgId,'file_type_id'=>$fileTypeId));
            }
            else {
                $this->BasicModuleFileAttachment->create();            
                $this->BasicModuleFileAttachment->save($new_data);
            }
            
        } catch (Exception $ex) {
            debug($ex->getMessage());
        }
        return $this->redirect(array('action' => 'view'));
    }

}
