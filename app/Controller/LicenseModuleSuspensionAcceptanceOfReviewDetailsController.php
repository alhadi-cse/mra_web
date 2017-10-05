<?php

App::uses('AppController', 'Controller');

class LicenseModuleSuspensionAcceptanceOfReviewDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    public function view($opt = 'all', $mode = null) {
        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
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
            $pending_viewable_states = explode('^', $thisStateIds[0]);
            if (count($thisStateIds) < 2) {
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

        $basic_condition = array();
        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $basic_condition = array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id' => $org_id);
        }

        if ($this->request->is('post')) {
            if (!empty($this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetailCompleted'])) {
                $reqData = $this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetailCompleted'];
                $option = $reqData['search_option_completed'];
                $keyword = $reqData['search_keyword_completed'];
            } elseif (!empty($this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetailPending'])) {
                $reqData = $this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetailPending'];
                $option = $reqData['search_option_pending'];
                $keyword = $reqData['search_keyword_pending'];
            }
            if (!empty($option) && !empty($keyword))
                $basic_condition = array_merge($basic_condition, array("$option LIKE '%$keyword%'"));
        }

        $pending_value_condition = array('BasicModuleBasicInformation.licensing_state_id' => $pending_viewable_states);
        $completed_value_condition = array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);

        $this->loadModel('BasicModuleBasicInformation');
        $this->Paginator->settings = array('conditions' => $pending_value_condition, 'limit' => 8);
        $this->BasicModuleBasicInformation->recursive = -1;
        $values_not_approved = $this->Paginator->paginate('BasicModuleBasicInformation');

        $this->Paginator->settings = array('conditions' => $completed_value_condition, 'limit' => 10);
        $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->recursive = 0;
        $values_approved = $this->Paginator->paginate('LicenseModuleSuspensionAcceptanceOfReviewDetail');

        $this->set(compact('IsValidUser', 'org_id', 'user_group_id', 'values_approved', 'values_not_approved'));
    }

    public function details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid organization data !'
            );
            $this->set(compact('msg'));
            return;
        }
        $allDetails = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->find('first', array('conditions' => array('org_id' => $org_id)));
        $this->set(compact('allDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }

    public function approve($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = split('_', $this_state_ids);
            if (count($thisStateIds) < 2) {
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

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->loadModel('BasicModuleBasicInformation');
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.license_issue_date'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'];
        $orgName = $orgName . (!empty($org_infos['BasicModuleBasicInformation']['short_name_of_org']) ? ' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')' : '');

        $approval_status_options = array('1' => 'Accepted', '0' => 'Rejected');
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));

        if ($this->request->is('post')) {
            try {
                $newData = $this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetail'];
                if (!empty($newData)) {
                    $existingData = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->find('first', array('fields' => array('LicenseModuleSuspensionAcceptanceOfReviewDetail.id'), 'recursive' => 0, 'conditions' => array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id' => $newData['org_id'])));

                    if ($existingData) {
                        $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->id = $existingData['LicenseModuleSuspensionAcceptanceOfReviewDetail']['id'];
                        $done = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->save($newData);
                    } else {
                        $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->create();
                        $done = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->save($newData);
                    }

                    if ($done) {
                        if (empty($org_id))
                            $org_id = $newData['org_id'];

                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));
                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[1],
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $this->redirect(array('action' => 'view'));
                        return;
                    }
                }
            } catch (Exception $ex) {
                
            }
        }
    }

    public function re_approve($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $committee_group_id = $this->Session->read('Committee.GroupId');
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1, $user_group_id) || in_array($committee_group_id, $user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->find('first', array('conditions' => array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id' => $org_id)));
        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = split('_', $this_state_ids);
            if (count($thisStateIds) < 2) {
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

        if ($this->request->is(array('post', 'put'))) {
            $newData = $this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetail'];
            if (!empty($newData)) {
                $state_id = '';
                if ($this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id'] == '0') {
                    $state_id = $thisStateIds[1];
                } else if ($this->request->data['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id'] == '1') {
                    $state_id = $thisStateIds[2];
                }

                $this->loadModel('BasicModuleBasicInformation');
                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]), array('BasicModuleBasicInformation.id' => $org_id));

                $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->id = $approvalDetails['LicenseModuleSuspensionAcceptanceOfReviewDetail']['id'];
                if ($this->LicenseModuleSuspensionAcceptanceOfReviewDetail->save($newData)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $approvalDetails = $this->LicenseModuleSuspensionAcceptanceOfReviewDetail->find('first', array('conditions' => array('LicenseModuleSuspensionAcceptanceOfReviewDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approval_status_options = array('1' => 'Revoke Show Cause', '0' => 'Cancel License');
        $this->set(compact('approvalDetails', 'approval_status_options'));
    }

}
