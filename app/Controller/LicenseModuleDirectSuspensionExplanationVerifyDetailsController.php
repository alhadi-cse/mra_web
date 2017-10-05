<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LicenseModuleDirectSuspensionExplanationVerifyDetailsController extends AppController {

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
            if (count($thisStateIds) < 3) {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid State Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } 
        else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $condition = array();
        $opt_all = false;
        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $condition = array('BasicModuleBasicInformation.id' => $org_id);
        }

        if ($this->request->is('post')) {
            $option = $this->request->data['LicenseModuleDirectSuspensionExplanationVerifyDetail']['search_option'];
            $keyword = $this->request->data['LicenseModuleDirectSuspensionExplanationVerifyDetail']['search_keyword'];

            if (!empty($option) && !empty($keyword)) {
                if (!empty($basic_condition)) {
                    $condition = array_merge($condition, array("AND" => array("$option LIKE '%$keyword%'", $condition)));
                } else {
                    $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
                }
                $opt_all = true;
            }
        }

        $user_is_committee_member = (in_array($committee_group_id,$user_group_id));
        $this->set(compact('org_id', 'user_is_committee_member', 'opt_all'));
        $viewable_state_ids = explode('^', $thisStateIds[0]);
        $condition1 = array( 'BasicModuleBasicInformation.licensing_state_id' => $viewable_state_ids);
        $condition2 = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[1]);
        $condition4 = array( 'BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]);

        if (!empty($user_is_committee_member)) {
            $user_id = $this->Session->read('User.Id');
            $this->loadModel('LicenseModuleCancelByMraExplanationVerifyApprovalDetail');
            $approved_org_id_list = $this->LicenseModuleCancelByMraExplanationVerifyApprovalDetail->find('list', array('fields' => 'LicenseModuleCancelByMraExplanationVerifyApprovalDetail.org_id', 'conditions' => array('committee_user_id' => $user_id, 'approval_status_id' => 1)));

            if (!empty($approved_org_id_list)) {
                $condition3 = array_merge($condition2, array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $approved_org_id_list));
                $condition2 = array_merge($condition2, array('NOT' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $approved_org_id_list)));
            }
        }

        if (!empty($condition)) {
            $condition1 = array_merge($condition1, $condition);
            $condition2 = array_merge($condition2, $condition);

            if (!empty($condition3))
                $condition3 = array_merge($condition3, $condition);

            $condition4 = array_merge($condition4, $condition);
        }

        $values_not_verified = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->find('all', array('conditions' => $condition2));

        if (!empty($condition3))
            $values_not_verified_by_all = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->find('all', array('conditions' => $condition3, 'group' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id'), 'order' => array('license_no' => 'asc')));
        else
            $values_not_verified_by_all = null;

        $this->paginate = array('conditions' => $condition4, 'group' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id'), 'limit' => 10, 'order' => array('license_no' => 'asc'));
        $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values_verified = $this->Paginator->paginate('LicenseModuleDirectSuspensionExplanationVerifyDetail');
        
        $this->loadModel('LicenseModuleDirectSuspensionMfiExplanationDetail');
        $this->LicenseModuleDirectSuspensionMfiExplanationDetail->recursive = 0;
        $values_pending = $this->LicenseModuleDirectSuspensionMfiExplanationDetail->find('all', array('conditions' => $condition1));
        
        $this->set(compact('org_id', 'user_group_id', 'opt_all', 'values_verified', 'values_not_verified', 'values_not_verified_by_all', 'values_pending'));
    }

    public function verification($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        $this->loadModel('BasicModuleBasicInformation');
        $org_infos = $this->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.id,  BasicModuleBasicInformation.full_name_of_org, BasicModuleBasicInformation.short_name_of_org, BasicModuleBasicInformation.license_no, BasicModuleBasicInformation.license_issue_date'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
        $orgName = $org_infos['BasicModuleBasicInformation']['full_name_of_org'];
        $orgName = $orgName. (!empty($org_infos['BasicModuleBasicInformation']['short_name_of_org'])?' (' . $org_infos['BasicModuleBasicInformation']['short_name_of_org'] . ')':'');
        $this->set(compact('orgName'));

        $verification_status_options = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('org_id', 'orgName', 'verification_status_options'));
        
        if ($this->request->is('post')) {
            try {
                $posted_data = $this->request->data['LicenseModuleDirectSuspensionExplanationVerifyDetail'];

                if (!empty($posted_data) && !empty($posted_data['verification_status_id']) && $posted_data['verification_status_id'] == 1) {
                    if (empty($org_id))
                        $org_id = $posted_data['org_id'];

                    $posted_data['is_approved'] = 0;
                    $posted_data['verification_committee_user_id'] = $this->Session->read('User.Id');

                    $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->deleteAll(array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id), false);
                    $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->create();
                    $done = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->save($posted_data);

                    if ($done) {

                        $current_year = $this->Session->read('Current.LicensingYear');

                        $approval_data = array(
                            'org_id' => $org_id,
                            'approval_status_id' => 1,
                            'approval_date' => date('Y-m-d'),
                            'approval_comment' => "",
                            'committee_user_id' => $this->Session->read('User.Id'));

                        $this->loadModel('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail');
                        $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->create();
                        $done = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->save($approval_data);

                        if ($done) {

                            $committee_group_id = $this->Session->read('Committee.GroupId');

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
                            
                            $next_state_id = $thisStateIds[1];
                            $committee_member_id_list = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUser.id', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id, 'AdminModuleUser.activation_status_id' => 1), 'recursive' => 0));
                            $approve_count = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->find('count', array('fields' => 'committee_user_id', 'conditions' => array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.org_id' => $org_id, 'approval_status_id' => 1, 'committee_user_id' => $committee_member_id_list), 'group' => 'committee_user_id'));
                            
                            if (!empty($approve_count) && $approve_count == count($committee_member_id_list)) {
                                $done = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->updateAll(array('is_approved' => 1), array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id));
                                if ($done) {
                                    $next_state_id = $thisStateIds[2];
                                }
                            }
                            
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $next_state_id), array('BasicModuleBasicInformation.id' => $org_id));

                            $org_state_history = array(
                                'org_id' => $org_id,
                                'state_id' => $next_state_id,
                                'licensing_year' => $current_year,
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
                }
            } catch (Exception $ex) {

                debug($ex);
            }
        }
        
    }

    public function re_verification($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $approvalDetails = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->find('first', array('conditions' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id)));

        if (empty($approvalDetails)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {
            $posted_data = $this->request->data['LicenseModuleDirectSuspensionExplanationVerifyDetail'];
            if (!empty($posted_data)) {
                $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->id = $approvalDetails['LicenseModuleDirectSuspensionExplanationVerifyDetail']['id'];
                if ($this->LicenseModuleDirectSuspensionExplanationVerifyDetail->save($posted_data)) {
                    $this->redirect(array('action' => 'view'));
                }
            }
            return;
        }

        $orgName = '';
        if (!empty($approvalDetails['BasicModuleBasicInformation'])) {
            $orgDetail = $approvalDetails['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        }
        
        $approvalDetails = $approvalDetails['LicenseModuleDirectSuspensionExplanationVerifyDetail'];
        $verification_status_options = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.verification_status')));
        $this->set(compact('org_id', 'orgName', 'approvalDetails', 'verification_status_options'));
    }

    public function verification_approval($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data['LicenseModuleDirectSuspensionExplnVerifyApprovalDetail'];

            if (empty($org_id))
                $org_id = $posted_data['org_id'];

            $option = $posted_data['approval_status_id'];

            if (!empty($option)) {

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

                $current_year = $this->Session->read('Current.LicensingYear');
                $user_id = $this->Session->read('User.Id');
                $posted_data['committee_user_id'] = $user_id;

                $this->loadModel('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail');

                if ($option == 1) {

                    $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->create();
                    $done = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->save($posted_data);

                    if ($done) {
                        $approve_id = $done['LicenseModuleDirectSuspensionExplnVerifyApprovalDetail']['id'];

                        $committee_member_id_list = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUser.id', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id, 'AdminModuleUser.activation_status_id' => 1), 'recursive' => 0));
                        $approve_count = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->find('count', array('fields' => 'committee_user_id', 'conditions' => array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.org_id' => $org_id, 'approval_status_id' => 1, 'committee_user_id' => $committee_member_id_list), 'group' => 'committee_user_id'));

                        if ((!empty($approve_count) && $approve_count == count($committee_member_id_list))) {
                            $done = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->updateAll(array('is_approved' => 1), array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id));
                            if ($done) {
                                $this->loadModel('BasicModuleBasicInformation');
                                $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[2]), array('BasicModuleBasicInformation.id' => $org_id));

                                $org_state_history = array(
                                    'org_id' => $org_id,
                                    'state_id' => $thisStateIds[2],
                                    'licensing_year' => $current_year,
                                    'date_of_state_update' => date('Y-m-d'),
                                    'date_of_starting' => date('Y-m-d'),
                                    'user_name' => $this->Session->read('User.Name'));

                                $this->loadModel('LicenseModuleStateHistory');
                                $this->LicenseModuleStateHistory->create();
                                $this->LicenseModuleStateHistory->save($org_state_history);

                                $this->redirect(array('action' => 'view'));
                                return;
                            } else {
                                $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->delete($approve_id);
                            }
                        }
                    }
                } else if ($option == 2) {

                    $done = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->deleteAll(array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id), false);
                    if ($done) {
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[0],
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);

                        $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->updateAll(array('approval_status_id' => -1), array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.org_id' => $org_id, 'approval_status_id' => 1));
                        $done = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->save($posted_data);
                        if ($done) {
                            $committee_member_email_list = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUserProfile.email', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id), 'recursive' => 0));
                            if (!empty($committee_member_email_list)) {
                                try {
                                    $msg = $posted_data['approval_comment'];
									
									$committee_member_name = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->AdminModuleUserProfile->field('AdminModuleUserProfile.full_name_of_user', array('AdminModuleUserProfile.user_id' => $user_id));

                                    $orgDetails = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('license_no', 'short_name_of_org', 'full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
                                    if (!empty($orgDetails)) {
                                        $org_info = $orgDetails['BasicModuleBasicInformation'];
                                        $org_name = $org_info['full_name_of_org'] . (!empty($org_info['short_name_of_org']) ? ' (' . $org_info['short_name_of_org'] . ')' : '');
                                        $org_license_no = $org_info['license_no'];
                                    }

                                    $email = new CakeEmail('gmail');
                                    $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->subject('License Field Inspection not Approve !');
                                    $email->to(array_values($committee_member_email_list));
                                    $message_body = 'Dear User,'
                                            . "\r\n" . "\r\n"
                                            . "Inspector Name: $committee_member_name"
                                            . " did not approve the Field Inspection of \"$org_name\" with Form No.:$org_license_no."
                                            . " As a result the approval status of all members has been reset."
                                            . " Due to the \"$msg\" isue." . "\r\n \r\n"
                                            . "Please re-approve the Field Inspection."
                                            . "\r\n \r\n"
                                            . "Thanks" . "\r\n"
                                            . "Microcredit Regulatory Authority";

                                    //$email->send($message_body);
                                } catch (Exception $ex) {
                                    $msg = array(
                                        'type' => 'error',
                                        'title' => 'Error... . . !',
                                        'msg' => 'Error in mail sending !\r\n' . $ex
                                    );
                                    $this->set(compact('msg'));
                                }
                            }
                        }
                    }
                }

                $this->redirect(array('action' => 'view'));
                return;
            }
        }

        $this->loadModel('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail');
        $orgDetail = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
        if (!empty($orgDetail['BasicModuleBasicInformation'])) {
            $orgDetail = $orgDetail['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
        } else {
            $orgName = '';
        }

        $approval_status_options = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
    }

    public function verification_re_approval($org_id = null) {

        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $user_id = $this->Session->read('User.Id');
        $user_group_id = $this->Session->read('User.GroupIds');
        $committee_group_id = $this->request->query('committee_group_id');
        if (empty($committee_group_id))
            $committee_group_id = $this->Session->read('Committee.GroupId');
        else
            $this->Session->write('Committee.GroupId', $committee_group_id);

        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is(array('post', 'put'))) {

            $posted_data = $this->request->data['LicenseModuleDirectSuspensionExplnVerifyApprovalDetail'];
            
            if (empty($org_id))
                $org_id = $posted_data['org_id'];

            $option = $posted_data['approval_status_id'];

            if (!empty($option)) {

                $current_year = $this->Session->read('Current.LicensingYear');
                $posted_data['committee_user_id'] = $user_id;

                $this->loadModel('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail');
                $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->create();
                $done = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->save($posted_data);
                
                if ($done && $option == 2) {
                    $done = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->deleteAll(array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id), false);
                    if ($done) {

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
                        $this->loadModel('BasicModuleBasicInformation');
                        $this->BasicModuleBasicInformation->updateAll(array('BasicModuleBasicInformation.licensing_state_id' => $thisStateIds[0]), array('BasicModuleBasicInformation.id' => $org_id));

                        $org_state_history = array(
                            'org_id' => $org_id,
                            'state_id' => $thisStateIds[0],
                            'licensing_year' => $current_year,
                            'date_of_state_update' => date('Y-m-d'),
                            'date_of_starting' => date('Y-m-d'),
                            'user_name' => $this->Session->read('User.Name'));

                        $this->loadModel('LicenseModuleStateHistory');
                        $this->LicenseModuleStateHistory->create();
                        $this->LicenseModuleStateHistory->save($org_state_history);
                    }

                    $committee_member_email_list = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->AdminModuleUser->find('list', array('fields' => 'AdminModuleUserProfile.email', 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $committee_group_id), 'recursive' => 0));
                    if (!empty($committee_member_email_list)) {
                        try {
                            $msg = $posted_data['approval_comment'];
							
							$committee_member_name = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->AdminModuleUserProfile->field('AdminModuleUserProfile.full_name_of_user', array('AdminModuleUserProfile.user_id' => $user_id));

                            $orgDetails = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->BasicModuleBasicInformation->find('first', array('fields' => array('license_no', 'short_name_of_org', 'full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));
                            if (!empty($orgDetails)) {
                                $org_info = $orgDetails['BasicModuleBasicInformation'];
                                $org_name = $org_info['full_name_of_org'] . (!empty($org_info['short_name_of_org']) ? ' (' . $org_info['short_name_of_org'] . ')' : '');
                                $org_license_no = $org_info['license_no'];
                            }

                            $email = new CakeEmail('gmail');
                            $email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))->subject('License Field Inspection not Approve !');
                            $email->to(array_values($committee_member_email_list));
                            $message_body = 'Dear User,'
                                    . "\r\n" . "\r\n"
                                    . "Inspector Name: $committee_member_name"
                                    . " did not approve the Field Inspection of \"$org_name\" with Form No.:$org_license_no."
                                    . " As a result the approval status of all members has been reset."
                                    . " Due to the \"$msg\" isue." . "\r\n \r\n"
                                    . "Please re-approve the Field Inspection."
                                    . "\r\n \r\n"
                                    . "Thanks" . "\r\n"
                                    . "Microcredit Regulatory Authority";

                            //$email->send($message_body);
                        } catch (Exception $ex) {
                            $msg = array(
                                'type' => 'error',
                                'title' => 'Error... . . !',
                                'msg' => 'Error in mail sending !\r\n' . $ex
                            );
                            $this->set(compact('msg'));
                        }
                    }
                }

                $this->redirect(array('action' => 'view'));
                return;
            }
        }

        $this->loadModel('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail');
        $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->unbindModel(array('belongsTo' => array('AdminModuleUser', 'AdminModuleUserProfile', 'LookupLicenseApprovalStatus')), true);

        $approvalDetails = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id, 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.committee_user_id' => $user_id), 'recursive' => 0));

        if (!empty($approvalDetails['BasicModuleBasicInformation'])) {
            $orgDetail = $approvalDetails['BasicModuleBasicInformation'];
            $orgName = $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '');
            unset($approvalDetails['BasicModuleBasicInformation']);
        } else {
            $orgName = '';
        }

        if (!$this->request->data) {
            $this->request->data = $approvalDetails;
        }

        $approval_status_options = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->LookupLicenseApprovalStatus->find('list', array('fields' => array('LookupLicenseApprovalStatus.id', 'LookupLicenseApprovalStatus.approval_status')));
        $this->set(compact('org_id', 'orgName', 'approval_status_options'));
    }

    public function verification_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'LookupLicenseApprovalStatus.verification_status', 'LicenseModuleDirectSuspensionExplanationVerifyDetail.verification_date', 'LicenseModuleDirectSuspensionExplanationVerifyDetail.verification_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationDetails = $this->LicenseModuleDirectSuspensionExplanationVerifyDetail->find('first', array('fields' => $fields, 'conditions' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id' => $org_id), 'recursive' => 0, 'group' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.org_id'), 'order' => array('LicenseModuleDirectSuspensionExplanationVerifyDetail.verification_date' => 'desc')));

        $this->loadModel('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail');
        $fields = array('LookupLicenseApprovalStatus.approval_status', 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.approval_date', 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.approval_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationApprovalDetails = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->find('all', array('fields' => $fields, 'conditions' => array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.org_id' => $org_id), 'recursive' => 0, 'order' => array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.approval_date' => 'desc', 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.id' => 'desc')));

        $this->set(compact('vrificationDetails', 'vrificationApprovalDetails'));
    }

    public function verification_approval_details($org_id = null) {
        if (empty($org_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('LookupLicenseApprovalStatus.approval_status', 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.approval_date', 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.approval_comment', 'AdminModuleUserProfile.full_name_of_user');
        $vrificationApprovalDetails = $this->LicenseModuleDirectSuspensionExplnVerifyApprovalDetail->find('all', array('fields' => $fields, 'conditions' => array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.org_id' => $org_id), 'recursive' => 0, 'order' => array('LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.approval_date' => 'desc', 'LicenseModuleDirectSuspensionExplnVerifyApprovalDetail.id' => 'desc')));

        $this->set(compact('vrificationApprovalDetails'));
    }

    public function preview($org_id = null) {
        $this->set(compact('org_id'));
    }    
}

