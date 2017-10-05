<?php

App::uses('AppController', 'Controller');

class SupervisionModuleFieldInspectionInspectorDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view() {
        $user_group_ids = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_ids) || !in_array(1, $user_group_ids) || in_array($committee_group_id, $user_group_ids)) {
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

            if (count($thisStateIds) < 3) {
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

        $inspector_group_id = $this->request->query('inspector_group_id');

        if (empty($inspector_group_id))
            $inspector_group_id = $this->Session->read('Inspector.GroupId');
        else
            $this->Session->write('Inspector.GroupId', $inspector_group_id);

        $office_type_id = $this->request->query('office_type_id');

        if (!empty($office_type_id))
            $this->Session->write('Address.TypeId', $office_type_id);
        else
            $office_type_id = $this->Session->read('Address.TypeId');

        $parameter = array('this_state_ids' => $this_state_ids, 'inspector_group_id' => $inspector_group_id, 'office_type_id' => $office_type_id);
        $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 'view', '?' => $parameter);
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $options['limit'] = 10;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
        $options['group'] = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id');

        $options['fields'] = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id',
            'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id', 'SupervisionModuleAssignedInspectorApprovalDetail.inspection_date',
            'SupervisionModuleBasicInformation.id', 'LookupSupervisionCategory.case_categories',
            'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'BasicModuleBranchInfo.id', 'LookupAdminBoundaryDistrict.district_name');

        $condition_assign = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1], 'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
        $options['conditions'] = $condition_assign;

//        $this->paginate = $options;
//        $this->paginate['conditions'] = $condition_assign;
//        $this->Paginator->settings = $this->paginate;
        $this->Paginator->settings = $options;
        $values_assigned = $this->Paginator->paginate('SupervisionModuleFieldInspectionInspectorDetail');

        $fields = array('SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', 'SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept', 'SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id');
        $assigned_inspectors_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('conditions' => $condition_assign, 'fields' => $fields, 'recursive' => 0));

        $this->loadModel('SupervisionModuleBasicInformation');
        $condition = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0], 'SupervisionModuleOrgSelectionDetail.is_running_case' => 1);
        $org_ids = $this->SupervisionModuleBasicInformation->find('list', array('fields' => array('id', 'org_id'), 'conditions' => $condition, 'group' => array('org_id'), 'recursive' => 0));

        if (!empty($org_ids)) {
            $options['limit'] = 10;
            $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
            $options['group'] = array('BasicModuleBasicInformation.id');

            $options['fields'] = array('LookupSupervisionCategory.case_categories', 'SupervisionModuleOrgSelectionDetail.supervision_reason', 'SupervisionModuleOrgSelectionDetail.from_date',
                'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
                'BasicModuleBranchInfo.id', 'LookupAdminBoundaryDistrict.district_name');

            $condition = array('BasicModuleBasicInformation.id' => $org_ids, 'SupervisionModuleOrgSelectionDetail.is_running_case' => 1);
            $options['conditions'] = $condition;

            $this->Paginator->settings = $options;
            $values_pending = $this->Paginator->paginate('SupervisionModuleOrgSelectionDetail');
        } else {
            $values_pending = null;
        }

        $this->set(compact('values_assigned', 'assigned_inspectors_list', 'values_pending'));
    }

    public function assign() {
        $inspector_group_id = $this->Session->read('Inspector.GroupId');
        $office_type_id = $this->Session->read('Address.TypeId');
        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids) || !in_array(1, $user_group_ids) || in_array($committee_group_id, $user_group_ids)) {
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
            if (count($thisStateIds) < 3) {
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
        $fields = array('AdminModuleUser.id', 'AdminModuleUserProfile.full_name_of_user');
        $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $inspector_group_id, 'AdminModuleUser.activation_status_id' => 1);

        //$this->AdminModuleUser->virtualFields = array('name_with_designation_and_dept' => 'CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office)');
        //$this->AdminModuleUser->virtualFields['name_with_designation_and_dept'] = 
        $inspector_list = $this->AdminModuleUser->find('list', array('fields' => $fields, 'recursive' => 0, 'conditions' => $conditions));

        $this->loadModel('SupervisionModuleBasicInformation');
        $condition = array('SupervisionModuleOrgSelectionDetail.is_running_case' => 1, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]);
        $org_ids = $this->SupervisionModuleBasicInformation->find('list', array('fields' => array('org_id'), 'conditions' => $condition, 'group' => array('org_id'), 'recursive' => 0));

        $this->loadModel('BasicModuleBranchInfo');
        $condition = array('BasicModuleBranchInfo.org_id' => $org_ids, 'BasicModuleBranchInfo.office_type_id' => $office_type_id);
        $fields = array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name');
        $dist_list = $this->BasicModuleBranchInfo->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'group' => 'BasicModuleBranchInfo.district_id', 'order' => 'LookupAdminBoundaryDistrict.district_name'));


//        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.district_id');
//        $orgDetailsAll = $this->BasicModuleBranchInfo->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'order' => array('BasicModuleBranchInfo.district_id' => 'asc')));


        $options = array();
        $options['recursive'] = 0;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
        $options['group'] = array('BasicModuleBasicInformation.id');

        $options['fields'] = array('LookupSupervisionCategory.case_categories', 'SupervisionModuleOrgSelectionDetail.supervision_reason', 'SupervisionModuleOrgSelectionDetail.from_date',
            'BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no', 'BasicModuleBranchInfo.district_id');
        $options['conditions'] = array('BasicModuleBasicInformation.id' => $org_ids, 'SupervisionModuleOrgSelectionDetail.is_running_case' => 1);

        $this->loadModel('SupervisionModuleOrgSelectionDetail');
        $orgDetailsAll = $this->SupervisionModuleOrgSelectionDetail->find('all', $options);

        $this->set(compact('dist_list', 'inspector_list', 'orgDetailsAll'));

        if ($this->request->is('post')) {
            $posted_data = $this->request->data['SupervisionModuleFieldInspectionInspectorDetail'];
            if (!empty($posted_data)) {
                $all_is_ok = true;
                $all_org_ids = array();
                $new_data_to_save = array();
                $all_org_state_history = array();

                $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
                foreach ($posted_data as $data) {
                    if (empty($data))
                        continue;

                    $org_ids = $data['org_ids'];
                    $district_id = $data['district_id'];
                    $inspector_user_ids = $data['inspector_user_ids'];
                    $team_leader_user_id = isset($data['team_leader_user_id']) ? $data['team_leader_user_id'] : null;
                    $inspection_dates = $data['inspection_dates'];

                    if (!empty($org_ids) && !empty($district_id) && !empty($inspector_user_ids) && !empty($inspection_dates)) {
                        $this->loadModel('SupervisionModuleBasicInformation');
                        $condition = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]);
                        $supervision_basic_ids = $this->SupervisionModuleBasicInformation->find('list', array('fields' => array('org_id', 'id'), 'conditions' => $condition, 'group' => array('org_id'), 'recursive' => -1));

                        $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
                        $data_to_save = array(
                            'district_id' => $district_id,
                            'is_approved' => '0',
                            'is_completed' => '0'
                        );

                        foreach ($org_ids as $org_id) {
                            if (!empty($org_id) && !empty($inspection_dates[$org_id])) {
                                $inspection_date = $inspection_dates[$org_id];
                                $supervision_basic_id = $supervision_basic_ids[$org_id];
                                if (empty($supervision_basic_id))
                                    continue;

                                $data_to_save['supervision_basic_id'] = $supervision_basic_id;
                                $data_to_save['inspection_date'] = $inspection_date;
                                if (empty($inspection_date)) {
                                    $msg = array(
                                        'type' => 'warning',
                                        'title' => 'Warning... . . !',
                                        'msg' => 'Please Enter Inspection Date !'
                                    );
                                    $this->set(compact('msg'));
                                    return;
                                }
                                $approval_values = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id)));
                                $inspection_schedule_id = '';
                                if (!empty($approval_values)) {
                                    $inspection_schedule_id = $approval_values['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
                                }
                                $condition_inspectors = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id' => $inspection_schedule_id);
                                $condition_approval = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                                    'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);

                                $existing_inspectors = $this->SupervisionModuleFieldInspectionInspectorDetail->find('first', array('conditions' => $condition_inspectors));
                                if (!empty($existing_approval_values)) {
                                    $inspectors_deleted = $this->SupervisionModuleFieldInspectionInspectorDetail->deleteAll($condition_inspectors, false);
                                }

                                $existing_approval_values = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => $condition_approval));
                                if (!empty($existing_approval_values)) {
                                    $approval_deleted = $this->SupervisionModuleAssignedInspectorApprovalDetail->deleteAll($condition_approval, false);
                                }
                                $this->SupervisionModuleAssignedInspectorApprovalDetail->create();
                                $saved_approval_data = $this->SupervisionModuleAssignedInspectorApprovalDetail->save($data_to_save);

                                foreach ($inspector_user_ids as $inspector_user_id) {
                                    if (!empty($inspector_user_id)) {
                                        $is_team_leader = (!empty($team_leader_user_id) && ($team_leader_user_id == $inspector_user_id)) ? '1' : '0';
                                        $new_data = array(
                                            'inspection_schedule_id' => $saved_approval_data['SupervisionModuleAssignedInspectorApprovalDetail']['id'],
                                            'inspector_user_id' => $inspector_user_id,
                                            'is_team_leader' => $is_team_leader
                                        );
                                        $new_data_to_save = array_merge($new_data_to_save, array($new_data));
                                    } else
                                        $all_is_ok = false;
                                }

                                $all_org_ids = array_merge($all_org_ids, array($org_id));

                                $org_state_history = array(
                                    'supervision_state_id' => $thisStateIds[1],
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    'user_name' => $this->Session->read('User.Name'));
                                $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                            } else
                                $all_is_ok = false;
                        }
                    }
                }
                if (!empty($new_data_to_save)) {

                    $this->SupervisionModuleFieldInspectionInspectorDetail->create();
                    $done = $this->SupervisionModuleFieldInspectionInspectorDetail->saveAll($new_data_to_save);
                    if ($done) {
                        $this->loadModel('SupervisionModuleBasicInformation');
                        $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]), array('SupervisionModuleBasicInformation.org_id' => $all_org_ids, 'SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]));

                        if (!empty($all_org_state_history)) {
                            $this->loadModel('SupervisionModuleStateHistory');
                            $this->SupervisionModuleStateHistory->create();
                            $this->SupervisionModuleStateHistory->saveAll($all_org_state_history);
                        }

                        if ($all_is_ok) {
                            $redirect_url = $this->Session->read('Current.RedirectUrl');
                            if (empty($redirect_url))
                                $redirect_url = array('action' => 'view');
                            $this->redirect($redirect_url);
                            return;
                        } else {
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'Some error in inspector assign !'
                            );
                            $this->set(compact('msg'));
                        }
                    }
                } else {
                    $msg = array(
                        'type' => 'warning',
                        'title' => 'Warning... . . !',
                        'msg' => 'Inspector not assign !'
                    );
                    $this->set(compact('msg'));
                }
            }
        }
    }

    public function re_assign() {

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids) || !in_array(1, $user_group_ids) || in_array($committee_group_id, $user_group_ids)) {
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
            if (count($thisStateIds) < 3) {
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

        $condition_assign = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1],
            'SupervisionModuleFieldInspectionInspectorDetail.is_completed' => 0);

        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.license_no',
            'SupervisionModuleFieldInspectionInspectorDetail.inspection_date',
            'LookupAdminBoundaryDistrict.district_name',
            'GROUP_CONCAT(CONCAT("<strong>", AdminModuleUserProfile.full_name_of_user, CASE WHEN is_team_leader = 1 THEN " <span style=\"color:#af4305;\">(Team Leader)</span>" ELSE "" END, "</strong>, <br />", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office) SEPARATOR " <br /> ") as inspectors_name_with_designation_and_dept');

        $values_assign = $this->SupervisionModuleFieldInspectionInspectorDetail->find('all', array('fields' => $fields, 'conditions' => $condition_assign, 'group' => 'SupervisionModuleBasicInformation.org_id'));

        $this->set(compact('values_assign'));
    }

    public function cancel($supervision_basic_id = null) {
        $this->autoRender = false;
        $this->SupervisionModuleFieldInspectionInspectorDetail->bindModel(
                array('belongsTo' => array(
                        'SupervisionModuleAssignedInspectorApprovalDetail' => array(
                            'foreignKey' => 'inspection_schedule_id'
                        )
                    )
                )
        );
        $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
        $approval_values = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id)));
        $inspection_schedule_id = $approval_values['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
        $committee_group_id = $this->Session->read('Committee.GroupId');
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
        if (!empty($this_state_ids)) {
            $this->Session->write('Current.StateIds', $this_state_ids);
        } else {
            $this_state_ids = $this->Session->read('Current.StateIds');
        }

        if (!empty($this_state_ids)) {
            $thisStateIds = explode('_', $this_state_ids);
            if (count($thisStateIds) < 3) {
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
        $condition_inspectors = array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id' => $inspection_schedule_id);
        $condition_approval = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);

        $inspectors_deleted = $this->SupervisionModuleFieldInspectionInspectorDetail->deleteAll($condition_inspectors, false);
        if ($inspectors_deleted) {
            $approval_deleted = $this->SupervisionModuleAssignedInspectorApprovalDetail->deleteAll($condition_approval, false);
            if ($approval_deleted) {
                $this->loadModel('SupervisionModuleBasicInformation');
                $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id));

                $org_state_history = array(
                    'supervision_basic_id' => $supervision_basic_id,
                    'supervision_state_id' => $thisStateIds[0],
                    'date_of_state_update' => date('Y-m-d'),
                    'date_of_starting' => date('Y-m-d'),
                    'user_name' => $this->Session->read('User.Name'));

                $this->loadModel('SupervisionModuleStateHistory');
                $this->SupervisionModuleStateHistory->create();
                $this->SupervisionModuleStateHistory->save($org_state_history);

                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view');
                $this->redirect($redirect_url);
                return;
            }
        }
    }

    public function cancel_all() {
        $this->autoRender = false;
        $this->SupervisionModuleFieldInspectionInspectorDetail->bindModel(
                array('belongsTo' => array(
                        'SupervisionModuleAssignedInspectorApprovalDetail' => array(
                            'foreignKey' => 'inspection_schedule_id'
                        )
                    )
                )
        );
        $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
        $this->SupervisionModuleAssignedInspectorApprovalDetail->bindModel(
                array('belongsTo' => array(
                        'SupervisionModuleBasicInformation' => array(
                            'foreignKey' => 'supervision_basic_id'
                        )
                    )
                )
        );

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids) || !in_array(1, $user_group_ids) || in_array($committee_group_id, $user_group_ids)) {
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
            if (count($thisStateIds) < 3) {
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
        $condition_for_approvals = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1], 'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 0);
        $inspector_approval_values = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('all', array('fields' => array('id', 'supervision_basic_id'), 'conditions' => $condition_for_approvals, 'recursive' => 0));
        $supervision_basic_ids = Hash::extract($inspector_approval_values, '{n}.SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id');

        if (!empty($inspector_approval_values)) {
            $inspectors_deleted = false;
            foreach ($inspector_approval_values as $inspector_approval_value) {
                $supervision_basic_id = $inspector_approval_value['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id'];
                $inspection_schedule_id = $inspector_approval_value['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
                $approval_deleted = $this->SupervisionModuleAssignedInspectorApprovalDetail->deleteAll(array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id), false);
                if ($approval_deleted) {
                    $inspectors_deleted = $this->SupervisionModuleFieldInspectionInspectorDetail->deleteAll(array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id' => $inspection_schedule_id), false);
                }
            }
            if ($inspectors_deleted) {
                $this->loadModel('SupervisionModuleBasicInformation');
                $done = $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_ids));
                if ($done) {
                    $all_org_state_history = array();
                    foreach ($supervision_basic_ids as $supervision_basic_id) {
                        $org_state_history = array(
                            'supervision_basic_id' => $supervision_basic_id,
                            'supervision_state_id' => $thisStateIds[0],
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $all_org_state_history = array_merge($all_org_state_history, array($org_state_history));
                    }

                    $this->loadModel('SupervisionModuleStateHistory');
                    $this->SupervisionModuleStateHistory->create();
                    $this->SupervisionModuleStateHistory->saveAll($all_org_state_history);
                }

                $redirect_url = $this->Session->read('Current.RedirectUrl');
                if (empty($redirect_url))
                    $redirect_url = array('action' => 'view');
                $this->redirect($redirect_url);
                return;
            }
        }
    }

}
