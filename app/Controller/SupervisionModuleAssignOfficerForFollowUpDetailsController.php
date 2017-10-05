<?php

App::uses('AppController', 'Controller');

class SupervisionModuleAssignOfficerForFollowUpDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view() {
        $this->SupervisionModuleAssignOfficerForFollowUpDetail->virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", CONCAT_WS("", "<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user), AdminModuleUserProfile.div_name_in_office)');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 1) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $follow_up_group_id = $this->request->query('follow_up_group_id');
        
        if (!empty($follow_up_group_id))
            $this->Session->write('FollowUpOfficer.GroupId', $follow_up_group_id);        
        $fields = array('SupervisionModuleAssignOfficerForFollowUpDetail.id',
                        'AdminModuleUserProfile.full_name_of_user',
                        'AdminModuleUserProfile.designation_of_user', 'AdminModuleUserProfile.div_name_in_office',
                        'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no');
        $conditions = array();
        if ($this->request->is('post')) {
            $option = $this->request->data['SupervisionModuleAssignOfficerForFollowUpDetail']['search_option'];
            $keyword = $this->request->data['SupervisionModuleAssignOfficerForFollowUpDetail']['search_keyword'];
            $conditions = array_merge($conditions, array("$option LIKE '%$keyword%'"));
        }
        $this->SupervisionModuleAssignOfficerForFollowUpDetail->recursive = 0;
        $conditions['is_current'] = 1;
        $values_assigned = $this->SupervisionModuleAssignOfficerForFollowUpDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => array('SupervisionModuleAssignOfficerForFollowUpDetail.id' => 'asc')));
        $fields = array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no');
        $basic_conditions = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $this->loadModel('BasicModuleBasicInformation');
        $org_values = $this->BasicModuleBasicInformation->find('all', array('conditions' => $basic_conditions));
        $this->set(compact('values_assigned', 'org_values'));
    }

    public function assign() {
        $this->SupervisionModuleAssignOfficerForFollowUpDetail->virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", CONCAT_WS("", "<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user), AdminModuleUserProfile.div_name_in_office)');
        $follow_up_group_id = $this->Session->read('FollowUpOfficer.GroupId'); 
        
        $user_group_ids = $this->Session->read('User.GroupIds');    
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds'); 
        
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);            
            if (count($thisStateIds) < 1) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $this->loadModel('AdminModuleUser');
        $fields = array('AdminModuleUser.id', 'AdminModuleUser.name_with_designation_and_dept');
        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $follow_up_group_id, 'AdminModuleUser.activation_status_id' => 1);
        
        $this->AdminModuleUser->virtualFields = array('name_with_designation_and_dept' => 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong> <br />", CONCAT_WS(", ", AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office))');
        $follow_up_officer_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'recursive' => 0, 'conditions' => $conditions));
        
        $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]);
        $this->loadModel('BasicModuleBasicInformation');
        $orgDetailsAll = $this->BasicModuleBasicInformation->find('all', array('conditions' => $condition,'recursive'=>-1));        
        $this->set(compact('follow_up_officer_list','orgDetailsAll')); 

        if ($this->request->is('post')) {
            $posted_data = $this->request->data['SupervisionModuleAssignOfficerForFollowUpDetail'];
            
            if (!empty($posted_data)) {                
                foreach ($posted_data as $data) {
                    if (empty($data))
                        continue;                                
                    $org_ids = $data['org_ids'];                    
                    $follow_up_officer_user_id = $data['follow_up_officer_user_id'];                    
                    $is_current = $data['is_current'];
                    if (!empty($org_ids)) {
                        foreach ($org_ids as $org_id) {                            
                            if($org_id!='0'){
                                $condition = array('follow_up_officer_user_id' => $follow_up_officer_user_id);
                                $existing_value_condition = array('org_id' => $org_id,'follow_up_officer_user_id' => $follow_up_officer_user_id);
                                $max_serial_value = 0;
                                $max_serial_no = $this->SupervisionModuleAssignOfficerForFollowUpDetail->find('first', array('fields' => 'MAX(serial_no) as max_serial_no', 'conditions' => $condition, 'recursive' => -1));
                                if(!empty($max_serial_no[0]['max_serial_no'])) {
                                    $max_serial_value = $max_serial_no[0]['max_serial_no'];
                                }                     
                                $serial_no = $max_serial_value + 1;
                                $new_data = array(                                
                                    'org_id' => $org_id,                                
                                    'serial_no' => $serial_no,                                
                                    'follow_up_officer_user_id' => $follow_up_officer_user_id,
                                    'is_current' => $is_current,
                                    'date_of_getting_in_charge' => date('Y-m-d')
                                ); 
                                $this->SupervisionModuleAssignOfficerForFollowUpDetail->create();
                                $done = $this->SupervisionModuleAssignOfficerForFollowUpDetail->save($new_data);
                            }                      
                        }
                    }
                }
                if($done) {
                    return $this->redirect(array('action' => 'view'));
                }
            }
        }
               
    }
    
    public function re_assign() { 
        $this->SupervisionModuleAssignOfficerForFollowUpDetail->virtualFields = array('name_with_designation_and_dept' => 'CONCAT_WS(", ", CONCAT_WS("", "<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user), AdminModuleUserProfile.div_name_in_office)');
        $follow_up_group_id = $this->Session->read('FollowUpOfficer.GroupId');        
        $user_group_ids = $this->Session->read('User.GroupIds');    
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds'); 
        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);            
            if (count($thisStateIds) < 1) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is('post')) {
            $posted_data = $this->request->data['SupervisionModuleAssignOfficerForFollowUpDetail'];
            if (!empty($posted_data)) {
                foreach ($posted_data as $data) {
                    if (empty($data))
                        continue;                                
                    $org_ids = $data['org_ids'];                    
                    $follow_up_officer_user_id = $data['follow_up_officer_user_id'];                    
                    $is_current = $data['is_current'];
                    if (!empty($org_ids)) {
                        foreach ($org_ids as $org_id) {                            
                            if($org_id!='0'){
                                $condition = array('follow_up_officer_user_id' => $follow_up_officer_user_id);
                                $existing_value_condition = array('org_id' => $org_id,'follow_up_officer_user_id' => $follow_up_officer_user_id);
                                $max_serial_value = 0;
                                $max_serial_no = $this->SupervisionModuleAssignOfficerForFollowUpDetail->find('first', array('fields' => 'MAX(serial_no) as max_serial_no', 'conditions' => $condition, 'recursive' => -1));
                                if(!empty($max_serial_no[0]['max_serial_no'])) {
                                    $max_serial_value = $max_serial_no[0]['max_serial_no'];
                                }                     
                                $serial_no = $max_serial_value + 1;
                                $new_data = array(                                
                                    'org_id' => $org_id,                                
                                    'serial_no' => $serial_no,                                
                                    'follow_up_officer_user_id' => $follow_up_officer_user_id,
                                    'is_current' => $is_current,
                                    'date_of_getting_in_charge' => date('Y-m-d')
                                );                                
                                $this->SupervisionModuleAssignOfficerForFollowUpDetail->create();
                                $done = $this->SupervisionModuleAssignOfficerForFollowUpDetail->save($new_data);                                
                            }                      
                        }
                    }
                }
                if($done) {
                    return $this->redirect(array('action' => 'view'));
                }
            }
        }
        $this->loadModel('AdminModuleUser');
        $fields = array('AdminModuleUser.id', 'AdminModuleUser.name_with_designation_and_dept');
        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $follow_up_group_id, 'AdminModuleUser.activation_status_id' => 1);            
        $this->AdminModuleUser->virtualFields = array('name_with_designation_and_dept' => 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong> <br />", CONCAT_WS(", ", AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office))');
        $follow_up_officer_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'recursive' => 0, 'conditions' => $conditions));
        $existing_org_ids = $this->SupervisionModuleAssignOfficerForFollowUpDetail->find('list', array('fields' => array('org_id'),'conditions' => array('is_current'=>1), 'recursive' => -1));
        $condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0],array('NOT'=>array('BasicModuleBasicInformation.id'=>$existing_org_ids)));        
        $this->loadModel('BasicModuleBasicInformation');
        $orgDetailsAll = $this->BasicModuleBasicInformation->find('all', array('conditions' => $condition,'recursive'=>-1));        
        $this->set(compact('follow_up_officer_list','orgDetailsAll'));        
    }
    
    public function delete($id = null) {
        $this_state_ids = $this->request->query('this_state_ids');
        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 1) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $deleted_values = $this->SupervisionModuleAssignOfficerForFollowUpDetail->findById($id);        
        $deleted_data = $deleted_values['SupervisionModuleAssignOfficerForFollowUpDetail']; 
        $org_id = $deleted_data['org_id'];
        if (!empty($deleted_data)) {            
            $updated =  $this->SupervisionModuleAssignOfficerForFollowUpDetail->updateAll(array('SupervisionModuleAssignOfficerForFollowUpDetail.is_current' => 0,'date_of_leaving_from_charge' => "'".date('Y-m-d')."'"),array('SupervisionModuleAssignOfficerForFollowUpDetail.id'=>$id));
            if ($updated) {
                return $this->redirect(array('action' => 'view'));
            }
        }
    }
}