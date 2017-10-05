<?php
App::uses('AppController', 'Controller');

class LicenseModuleCancelByMraActivityCancelNotifyDetailsController extends AppController {
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $paginate = array();
    var $uses = array('BasicModuleBasicInformation','LicenseModuleCancelByMraActivityCancelNotifyDetail','AdminModuleUserProfile');

    public function view($opt = 'all') {
        $user_group_id = $this->Session->read('User.GroupId');        
        $this->set(compact('user_group_id'));
        
        if (empty($user_group_id) || !($user_group_id == 1 || $user_group_id == 2)) {
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
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }        
        $current_year = $this->Session->read('Current.LicensingYear');
        if (empty($current_year)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Licensing Year Information !'
            );
            $this->set(compact('msg'));
            return;
        }
                
        $opt_all = false;
        if ($user_group_id == 1) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }

        if ($user_group_id == 2) {
            $org_id = $this->Session->read('Org.Id');            
            if (empty($org_id)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Organization Information !'
                );
                $this->set(compact('msg'));
                return;
            }
            $condition_licensed = array('BasicModuleBasicInformation.id' => $org_id, array('OR'=>array(
                                                    array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]),
                                                    array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1])
                                                )
                                            )
                                        );

        } elseif ($user_group_id == 1) {
            $condition_licensed = array('OR'=>array(
                                                    array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]),
                                                    array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1])
                                            )
                                    );
            if ($this->request->is('post')) {
                $reqData = $this->request->data['LicenseModuleCancelByMraActivityCancelNotifyDetail'];
                $option = $reqData['search_option'];
                $keyword = $reqData['search_keyword'];

                if (!empty($option) && !empty($keyword))
                    $condition_licensed = array_merge($condition_licensed, array("$option LIKE '%$keyword%'"));
            }
        }

        $this->paginate = array('conditions' => $condition_licensed, 'limit' => 10, 'order' => array('licensing_state_id' => 'DESC'));
        $this->Paginator->settings = $this->paginate;
                
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_licensed = $this->Paginator->paginate('BasicModuleBasicInformation');
        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'thisStateIds', 'values_licensed'));
    }
    
    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }
    
    
    public function details($org_id = null) {
        if ($org_id=='') {
            $org_id = $this->Session->read('Org.Id');        
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->BasicModuleBasicInformation->recursive = -1;
        $basicInfoDetails = $this->BasicModuleBasicInformation->findById($org_id);
        if (!$basicInfoDetails) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $cancel_request_values = $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->find('first', array('conditions' => array('org_id'=>$org_id)));

        if(!empty($cancel_request_values)){
            $allDetails = array_merge($basicInfoDetails,$cancel_request_values);
        }
        else{
            $allDetails = array_merge($basicInfoDetails,array('LicenseModuleCancelByMraActivityCancelNotifyDetail'=>array('notification_date'=>'','notification_details'=>'')));
        }        
        $this->set(compact('allDetails'));
    }
    
    public function notify_about_activity_closing($org_id = null, $next_state_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($next_state_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid next state information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this_state_ids = $this->Session->read('Current.StateIds');
        $user_group_id = $this->Session->read('User.GroupId');
        if (empty($user_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $org_infos = $this->BasicModuleBasicInformation->find('first',array('fields'=>array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.license_issue_date'),'conditions'=>array('BasicModuleBasicInformation.id'=>$org_id)));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'].' ('.$org_infos['BasicModuleBasicInformation']['short_name_of_org'].')';
        $this->set(compact('orgName'));

        if($this->request->is('post')){
            $notification_details = $this->request->data['LicenseModuleCancelByMraActivityCancelNotifyDetail']['notification_details'];
            $data_to_save = array(
                'org_id' =>$org_id,
                'notification_date' => date('Y-m-d'),
                'notification_details' => $notification_details
            );
            $dataSource = $this->dtSource();
            $dataSource->begin();/* Begin Transaction */

            $existing_value_condition = array('LicenseModuleCancelByMraActivityCancelNotifyDetail.org_id'=>$org_id);
            $existing_values = $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->find('first',array('conditions'=>$existing_value_condition));
            if(!empty($existing_values)){                
                $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->deleteAll($existing_value_condition, false);
            }
            $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->create();
            $saved = $this->LicenseModuleCancelByMraActivityCancelNotifyDetail->save($data_to_save);
            
            if($saved){
                $basic_info_condition = array('BasicModuleBasicInformation.id'=>$org_id);
                $data_to_save_in_basic_info = array('licensing_state_id'=>$next_state_id);
                $this->BasicModuleBasicInformation->updateAll($data_to_save_in_basic_info,$basic_info_condition);
                $dataSource->commit();                       
                $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids));
            }
            else{
                $dataSource->rollback();
                $message = 'Activity closing notification sending failed!';
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
            }
            /* End Transaction */
        }
    }
}