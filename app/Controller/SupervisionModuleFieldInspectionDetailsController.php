<?php

App::uses('AppController', 'Controller');

class SupervisionModuleFieldInspectionDetailsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $components = array('Paginator');
    public $paginate = array();

    public function view($opt = 'all', $mode = null) {
        $inspector_group_id = $this->request->query('inspector_group_id');
        if (empty($inspector_group_id))
            $inspector_group_id = $this->Session->read('Current.GroupId');
        else
            $this->Session->write('Current.GroupId', $inspector_group_id);

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

        $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'view', '?' => array('inspector_group_id' => $inspector_group_id, 'this_state_ids' => $this_state_ids));
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $opt_all = false;
        $user_is_editor = false;
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_group_ids) || !in_array(1, $user_group_ids)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        else {
            $user_committe_member_type_id = $this->Session->read('User.CommitteMemberTypeId');
            $user_is_editor = (!empty($user_committe_member_type_id) && $user_committe_member_type_id == 2);
        }

        $user_is_inspector = in_array($inspector_group_id, $user_group_ids);
        $this->set(compact('org_id', 'user_is_inspector', 'user_is_editor', 'opt_all'));


        $condition_for_not_inspected = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[0]);
        $condition_for_inspected_not_yet_submitted = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1], 'SupervisionModuleFieldInspectionDetail.is_approved' => -1);
        $condition_for_inspected_not_approved = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1], 'SupervisionModuleFieldInspectionDetail.is_approved' => 0);
        $condition_for_inspected = array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[2], 'SupervisionModuleFieldInspectionDetail.is_approved' => 1);

        $all_fields = array('SupervisionModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no',
            'SupervisionModuleOrgSelectionDetail.supervision_reason', 'LookupSupervisionCategory.case_categories');

        $this->loadModel('SupervisionModuleBasicInformation');
        $values_not_inspected = $this->SupervisionModuleBasicInformation->find('all', array('fields' => $all_fields, 'conditions' => $condition_for_not_inspected, 'recursive' => 0));

        $all_fields = array_merge($all_fields, array('SupervisionModuleFieldInspectionDetail.inspection_date', 'SupervisionModuleFieldInspectionDetail.submission_date', 'SupervisionModuleFieldInspectionDetail.inspection_note'));
        $values_inspected_not_yet_submitted = $this->SupervisionModuleFieldInspectionDetail->find('all', array('fields' => $all_fields, 'conditions' => $condition_for_inspected_not_yet_submitted, 'recursive' => 0, 'group' => 'SupervisionModuleBasicInformation.org_id', 'limit' => 10));

        $values_inspected_not_approved = $this->SupervisionModuleFieldInspectionDetail->find('all', array('conditions' => $condition_for_inspected_not_approved, 'group' => 'SupervisionModuleBasicInformation.org_id', 'limit' => 10));

        $this->paginate = array('conditions' => $condition_for_inspected, 'group' => 'SupervisionModuleBasicInformation.org_id', 'limit' => 10, 'order' => array('SupervisionModuleBasicInformation.id' => 'asc'));
        $this->Paginator->settings = $this->paginate;
        $values_inspected = $this->Paginator->paginate('SupervisionModuleFieldInspectionDetail');

        $this->set(compact('values_inspected', 'values_inspected_not_yet_submitted', 'values_inspected_not_approved', 'values_not_inspected'));
    }

    public function inspection($supervision_basic_id = null) {
        $inspector_group_id = $this->Session->read('Current.GroupId');
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

        $this->loadModel('SupervisionModuleBasicInformation');
        $orgDetail = $this->SupervisionModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('SupervisionModuleBasicInformation.id' => $supervision_basic_id), 'recursive' => 1));
        $orgName = '';
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
        }

        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');

        $fields = array('SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', 'SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept');
        $condition_inspector = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id);
        $inspector_name_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => $fields, 'conditions' => $condition_inspector, 'recursive' => 0));
        $inspector_names = implode('<br />', $inspector_name_list);

        $this->set(compact('orgName', 'inspector_names'));

        if ($this->request->is('post')) {
            $supervisionData = $this->request->data['SupervisionModuleFieldInspectionDetail'];
            $inspection_date = $supervisionData['inspection_date'];
            if (empty($inspection_date)) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Please Enter Inspection Date !'
                );
                $this->set(compact('msg'));
                return;
            }
            if (!empty($supervisionData)) {
                try {
                    $supervisionData['supervision_basic_id'] = $supervision_basic_id;
                    $supervisionData['is_approved'] = -1;
                    $value_exists = $this->SupervisionModuleFieldInspectionDetail->find('first', array('conditions' => array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id)));
                    if (!empty($value_exists)) {
                        $this->SupervisionModuleFieldInspectionDetail->deleteAll(array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id), false);
                    }
                    $this->SupervisionModuleFieldInspectionDetail->create();
                    $saved = $this->SupervisionModuleFieldInspectionDetail->save($supervisionData);

                    if ($saved) {
                        $this->loadModel('SupervisionModuleBasicInformation');
                        $state_updated = $this->SupervisionModuleBasicInformation->updateAll(array('SupervisionModuleBasicInformation.supervision_state_id' => $thisStateIds[1]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id));

                        if ($state_updated) {
                            $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
                            $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll(array('SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 2), array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id));

                            $org_state_history = array(
                                'supervision_state_id' => $thisStateIds[1],
                                'date_of_state_update' => date('Y-m-d'),
                                'date_of_starting' => date('Y-m-d'),
                                'user_name' => $this->Session->read('User.Name'));

                            $this->loadModel('SupervisionModuleStateHistory');
                            $this->SupervisionModuleStateHistory->create();
                            $this->SupervisionModuleStateHistory->save($org_state_history);
                            $this->redirect(array('action' => "view?this_state_ids=$this_state_ids&inspector_group_id=$inspector_group_id"));
                        }
                        return;
                    }
                } catch (Exception $ex) {
                    
                }
            }
        }
    }

    public function re_inspection($supervision_basic_id = null, $option = null) {
        $user_id = $this->Session->read('User.Id');
        if (empty($supervision_basic_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Field Inspection Serial No. !'
            );
            $this->set(compact('msg'));
            return;
        }

        $inspector_group_id = $this->Session->read('Current.GroupId');
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
        $conditions = array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleFieldInspectionDetail.is_approved' => -1);
        $fields = array('SupervisionModuleFieldInspectionDetail.*', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $inspectionDetails = $this->SupervisionModuleFieldInspectionDetail->find('first', array('fields' => $fields, 'conditions' => $conditions));
        $orgName = '';

//        debug($supervision_basic_id);
//        debug($inspectionDetails);

        if (!empty($inspectionDetails) && !empty($inspectionDetails['SupervisionModuleFieldInspectionDetail'])) {
            if (!empty($inspectionDetails['BasicModuleBasicInformation'])) {
                $orgDetail = $inspectionDetails['BasicModuleBasicInformation'];
                unset($inspectionDetails['BasicModuleBasicInformation']);
                $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
            }
            if (!$this->request->data) {
                $this->request->data = $inspectionDetails;
            }
        }

        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
        //$condition_inspector = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 1, 'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 2);
        $fields = array('SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', 'SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept');
        $condition_inspector = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id);
        $inspector_name_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => $fields, 'conditions' => $condition_inspector, 'recursive' => 0));

        $inspector_names = implode('<br />', $inspector_name_list);
        $is_inspector_group = in_array($inspector_group_id, $user_group_ids);
        $this->set(compact('supervision_basic_id', 'orgName', 'inspector_names', 'is_inspector_group'));

        if ($this->request->is(array('post', 'put'))) {
            $supervisionData = $this->request->data['SupervisionModuleFieldInspectionDetail'];

            if (!empty($supervisionData)) {
                try {
                    $is_all_ok = false;
                    $this->SupervisionModuleFieldInspectionDetail->id = $supervisionData['id'];
                    $done = $this->SupervisionModuleFieldInspectionDetail->save($supervisionData);
                    if ($done) {
                        $is_all_ok = true;
                    }
                    if ($done && !empty($option) && $option == 1) {
                        $approval_data = array(
                            'submission_date' => date('Y-m-d'),
                            'supervision_basic_id' => $supervision_basic_id,
                            'inspection_approval_id' => 1,
                            'inspection_comment' => $supervisionData['inspection_note'],
                            'inspector_user_id' => $user_id,
                            'is_editor_user' => 1
                        );
                        $approval_condition = array('SupervisionModuleFieldInspectionApprovalDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleFieldInspectionApprovalDetail.inspector_user_id' => $user_id);
                        $this->loadModel('SupervisionModuleFieldInspectionApprovalDetail');
                        $value_exists = $this->SupervisionModuleFieldInspectionApprovalDetail->find('first', array('conditions' => $approval_condition));
                        if (!empty($value_exists)) {
                            $this->SupervisionModuleFieldInspectionApprovalDetail->deleteAll($approval_condition, false);
                        }
                        $this->SupervisionModuleFieldInspectionApprovalDetail->create();
                        $done = $this->SupervisionModuleFieldInspectionApprovalDetail->save($approval_data);
                        if ($done) {
                            $is_all_ok = true;
                            $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
                            $inspection_schedule_details = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('supervision_basic_id' => $supervision_basic_id)));
                            $inspection_schedule_id = $inspection_schedule_details['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
                            $inspector_conditions = array('inspection_schedule_id' => $inspection_schedule_id);
                            $is_team_leader_conditions = array_merge($inspector_conditions, array('inspector_user_id' => $user_id, 'is_team_leader' => 1));

                            $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
                            //$inspector_id_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => $inspector_conditions, 'recursive' => -1, 'group' => 'inspector_user_id'));
                            $is_team_leader_of_inspectors = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => $is_team_leader_conditions, 'recursive' => -1));
                            $is_team_leader = false;

                            if (!empty($is_team_leader_of_inspectors)) {
                                $is_team_leader = true;
                            }

                            if ($is_inspector_group && $is_team_leader) {
                                try {
                                    $this->loadModel('SupervisionModuleBasicInformation');
                                    $this->SupervisionModuleBasicInformation->recursive = -1;
                                    $done = $this->SupervisionModuleBasicInformation->updateAll(array('supervision_state_id' => $thisStateIds[2]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id));
                                    $approval_status = 0;
                                    if ($done) {
                                        $is_all_ok = true;
                                        $this->SupervisionModuleFieldInspectionDetail->updateAll(array('is_approved' => 1), array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id));
                                        $conditions = array(
                                            'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                                            'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 1,
                                            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 2);
                                        $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll(array('SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 1), $conditions);
                                        $org_state_history = array(
                                            'supervision_state_id' => $thisStateIds[2],
                                            'date_of_state_update' => date('Y-m-d'),
                                            'date_of_starting' => date('Y-m-d'),
                                            'user_name' => $this->Session->read('User.Name'));

                                        $this->loadModel('SupervisionModuleStateHistory');
                                        $this->SupervisionModuleStateHistory->create();
                                        $this->SupervisionModuleStateHistory->save($org_state_history);
                                    } else {
                                        $is_all_ok = false;
                                        $this->SupervisionModuleFieldInspectionDetail->recursive = -1;
                                        $this->SupervisionModuleFieldInspectionDetail->updateAll(array('is_approved' => -1), array('supervision_basic_id' => $supervision_basic_id));
                                    }
                                } catch (Exception $ex) {
                                    $is_all_ok = false;
                                    $this->SupervisionModuleFieldInspectionApprovalDetail->recursive = -1;
                                    $this->SupervisionModuleFieldInspectionApprovalDetail->deleteAll(array('supervision_basic_id' => $supervision_basic_id), false);

                                    $msg = array(
                                        'type' => 'error',
                                        'title' => 'Error... . . !',
                                        'msg' => 'Submission failed ! <br \>' . $ex->getMessage()
                                    );
                                    $this->set(compact('msg'));
                                }
                            } elseif ($is_inspector_group && !$is_team_leader) {
                                $this->SupervisionModuleFieldInspectionDetail->updateAll(array('SupervisionModuleFieldInspectionDetail.is_approved' => -1), array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id));
                                $msg = array(
                                    'type' => 'warning',
                                    'title' => 'Warning... . . !',
                                    'msg' => 'Field Inspection Report will be Submitted after Submission of Team Leader!'
                                );
                                $this->set(compact('msg'));
                                return;
                            }
                        }
                    }
                    if ($is_all_ok) {
                        $redirect_url = array('action' => "view?this_state_ids=$this_state_ids&inspector_group_id=$inspector_group_id");
                        return $this->redirect($redirect_url);
                    }
                } catch (Exception $ex) {

                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Submission failed ! <br \>' . $ex->getMessage()
                    );
                    $this->set(compact('msg'));
                }
            }
        }
    }

    public function inspection_approval($supervision_basic_id = null) {
        $inspector_group_id = $this->Session->read('Current.GroupId');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $is_inspector_group = in_array($inspector_group_id, $user_group_ids);
        $user_id = $this->Session->read('User.Id');
        if (empty($user_group_ids)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id', 'SupervisionModuleFieldInspectionInspectorDetail.name_with_designation_and_dept');
        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
        $condition_inspector = array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id);
        $inspector_name_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => $fields, 'conditions' => $condition_inspector, 'recursive' => 0));

        $inspector_names = implode('<br />', $inspector_name_list);
        $this->loadModel('LookupLicenseApprovalStatus');
        $approval_status_options = $this->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $conditions = array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleFieldInspectionDetail.is_approved' => 0);
        $inspectionDetails = $this->SupervisionModuleFieldInspectionDetail->find('first', array('conditions' => $conditions, 'order' => array('submission_date' => 'desc')));

        $orgName = '';
        if (!empty($inspectionDetails)) {
            //$this->request->data = $inspectionDetails;
            if (!empty($inspectionDetails['BasicModuleBasicInformation'])) {
                $orgDetail = $inspectionDetails['BasicModuleBasicInformation'];
                $orgName = $orgDetail['full_name_of_org'];
                $orgName = $orgName . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
            }
        }
        $this->set(compact('supervision_basic_id', 'orgName', 'approval_status_options', 'inspector_names'));

        if ($this->request->is(array('put', 'post'))) {
            $approval_data = array(
                'submission_date' => date('Y-m-d'),
                'supervision_basic_id' => $supervision_basic_id,
                'inspection_approval_id' => 1,
                'inspection_comment' => 'approved by inspector',
                'inspector_user_id' => $user_id,
                'is_editor_user' => 1
            );
            $approval_condition = array('SupervisionModuleFieldInspectionApprovalDetail.supervision_basic_id' => $supervision_basic_id, 'SupervisionModuleFieldInspectionApprovalDetail.inspector_user_id' => $user_id);
            $this->loadModel('SupervisionModuleFieldInspectionApprovalDetail');
            $value_exists = $this->SupervisionModuleFieldInspectionApprovalDetail->find('first', array('conditions' => $approval_condition));
            if (!empty($value_exists)) {
                $this->SupervisionModuleFieldInspectionApprovalDetail->deleteAll($approval_condition, false);
            }
            $this->SupervisionModuleFieldInspectionApprovalDetail->create();
            $done = $this->SupervisionModuleFieldInspectionApprovalDetail->save($approval_data);

            $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
            $inspection_schedule_details = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('supervision_basic_id' => $supervision_basic_id)));
            $inspection_schedule_id = $inspection_schedule_details['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
            $inspector_conditions = array('inspection_schedule_id' => $inspection_schedule_id);
            $is_team_leader_conditions = array_merge($inspector_conditions, array('inspector_user_id' => $user_id, 'is_team_leader' => 1));

            $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
            //$inspector_id_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => $inspector_conditions, 'recursive' => -1, 'group' => 'inspector_user_id'));
            $is_team_leader_of_inspectors = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'inspector_user_id', 'conditions' => $is_team_leader_conditions, 'recursive' => -1));
            $is_team_leader = false;

            if (!empty($is_team_leader_of_inspectors)) {
                $is_team_leader = true;
            }

            if ($is_inspector_group && $is_team_leader) {
                try {
                    $this->loadModel('SupervisionModuleBasicInformation');
                    $this->SupervisionModuleBasicInformation->recursive = -1;
                    $done = $this->SupervisionModuleBasicInformation->updateAll(array('supervision_state_id' => $thisStateIds[2]), array('SupervisionModuleBasicInformation.id' => $supervision_basic_id));
                    $approval_status = 0;
                    if ($done) {
                        $is_all_ok = true;
                        $this->SupervisionModuleFieldInspectionDetail->updateAll(array('is_approved' => 1), array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id));
                        $conditions = array(
                            'SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id,
                            'SupervisionModuleAssignedInspectorApprovalDetail.is_approved' => 1,
                            'SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 2);
                        $this->SupervisionModuleAssignedInspectorApprovalDetail->updateAll(array('SupervisionModuleAssignedInspectorApprovalDetail.is_completed' => 1), $conditions);
                        $org_state_history = array(
                            'supervision_state_id' => $thisStateIds[2],
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('SupervisionModuleStateHistory');
                        $this->SupervisionModuleStateHistory->create();
                        $this->SupervisionModuleStateHistory->save($org_state_history);
                    } else {
                        $is_all_ok = false;
                        $this->SupervisionModuleFieldInspectionDetail->recursive = -1;
                        $this->SupervisionModuleFieldInspectionDetail->updateAll(array('is_approved' => 0), array('supervision_basic_id' => $supervision_basic_id));
                    }
                } catch (Exception $ex) {
                    $is_all_ok = false;
                    $this->SupervisionModuleFieldInspectionApprovalDetail->recursive = -1;
                    $this->SupervisionModuleFieldInspectionApprovalDetail->deleteAll(array('supervision_basic_id' => $supervision_basic_id), false);

                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Submission failed ! <br \>' . $ex->getMessage()
                    );
                    $this->set(compact('msg'));
                }
            } elseif ($is_inspector_group && !$is_team_leader) {
                $this->SupervisionModuleFieldInspectionDetail->updateAll(array('SupervisionModuleFieldInspectionDetail.is_approved' => 0), array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id));
            }
            $redirect_url = $this->Session->read('Current.RedirectUrl');
            if (empty($redirect_url)) {
                $redirect_url = array('action' => 'view');
            }
            return $this->redirect($redirect_url);
        }
    }

    public function inspection_details($supervision_basic_id = null) {
        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
        $this->loadModel('SupervisionModuleAssignedInspectorApprovalDetail');
        $this->SupervisionModuleFieldInspectionInspectorDetail->bindModel(
                array('belongsTo' => array(
                        'AdminModuleUserProfile' => array(
                            'className' => 'AdminModuleUserProfile',
                            'foreignKey' => false,
                            'conditions' => 'AdminModuleUserProfile.user_id = SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id'
                        )
                    )
                )
        );

        $no_approval_details = $this->request->query('no_approval_details');
        $no_approval_details = empty($no_approval_details) ? false : true;
        $this->set(compact('no_approval_details'));

        $assigned_inspector_approval_details = $this->SupervisionModuleAssignedInspectorApprovalDetail->find('first', array('conditions' => array('SupervisionModuleAssignedInspectorApprovalDetail.supervision_basic_id' => $supervision_basic_id), 'recursive' => -1));
        if (!empty($assigned_inspector_approval_details)) {
            $inspection_schedule_id = $assigned_inspector_approval_details['SupervisionModuleAssignedInspectorApprovalDetail']['id'];
            $inspector_details = $this->SupervisionModuleFieldInspectionInspectorDetail->find('all', array('conditions' => array('SupervisionModuleFieldInspectionInspectorDetail.inspection_schedule_id' => $inspection_schedule_id), 'recursive' => 0));
            if (!empty($supervision_basic_id)) {
                $conditions = array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id);
                $inspectionDetails = $this->SupervisionModuleFieldInspectionDetail->find('first', array('conditions' => $conditions, 'recursive' => -1));
                $inspector_name_list = Hash::extract($inspector_details, '{n}.AdminModuleUserProfile.name_with_designation_and_division');
                $inspector_names = implode('<br />', $inspector_name_list);
                $this->set(compact('supervision_basic_id', 'inspector_names', 'inspectionDetails'));
            }
        }
    }

    public function preview($supervision_basic_id = null) {
        $fields = array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org');
        $conditions = array('SupervisionModuleFieldInspectionDetail.supervision_basic_id' => $supervision_basic_id);
        $inspectionDetails = $this->SupervisionModuleFieldInspectionDetail->find('first', array('fields' => $fields, 'conditions' => $conditions));
        $orgName = '';
        if (!empty($inspectionDetails['BasicModuleBasicInformation'])) {
            $orgDetail = $inspectionDetails['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        }
        $this->set(compact('supervision_basic_id', 'orgName'));
    }

    function inspector_select() {
        $this->layout = 'ajax';
        $org_id = $this->request->data['SupervisionModuleBasicInformation']['org_id'];
        $this->loadModel('SupervisionModuleFieldInspectionInspectorDetail');
        $inspector_name_list = $this->SupervisionModuleFieldInspectionInspectorDetail->find('list', array('fields' => 'name_with_designation_and_dept', 'recursive' => 0, 'conditions' => array('SupervisionModuleBasicInformation.org_id' => $org_id, 'SupervisionModuleFieldInspectionInspectorDetail.is_approved' => 1), 'group' => 'SupervisionModuleFieldInspectionInspectorDetail.inspector_user_id'));
        $inspector_names = implode('<br />', $inspector_name_list);
        $this->set(compact('inspector_names'));
    }

    public function inspection_approval_details($supervision_basic_id = null) {
        $fields = array('SupervisionModuleFieldInspectionApprovalDetail.submission_date', 'SupervisionModuleFieldInspectionApprovalDetail.inspection_comment', 'LookupLicenseApprovalStatus.approval_status', 'AdminModuleUserProfile.full_name_of_user');
        $conditions = array('SupervisionModuleFieldInspectionApprovalDetail.supervision_basic_id' => $supervision_basic_id);

        $this->loadModel('SupervisionModuleFieldInspectionApprovalDetail');
        $approvalDetails = $this->SupervisionModuleFieldInspectionApprovalDetail->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => array('submission_date' => 'desc', 'SupervisionModuleFieldInspectionApprovalDetail.id' => 'desc')));
        $this->set(compact('supervision_basic_id', 'approvalDetails'));
    }

}
