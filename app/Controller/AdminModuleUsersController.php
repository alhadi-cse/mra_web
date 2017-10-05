<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

class AdminModuleUsersController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Session', 'Captcha.Captcha');
    var $components = array('Session', 'Paginator', 'Captcha.Captcha' => array('Model' => 'AdminModuleUser', 'field' => 'captcha')); //'Captcha'
    var $uses = array('AdminModuleUser', 'AdminModuleUserProfile', 'AdminModuleUserGroupDistribution', 'BasicModuleBasicInformation', 'BasicModuleBranchInfo', 'LookupCurrentLicensingYear', 'LookupBasicRegistrationAuthority', 'LookupUserCommitteeMemberType');

    function captcha() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Captcha->create();
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'home_info');
    }

    public function view() {
        $is_committee_group = $this->request->query('is_committee_group');
        $search_condition = array();
        if ($this->request->is('post')) {
            $is_committee_group = $this->Session->read('Committee.Is_Group');
            $option = $this->request->data['AdminModuleUser']['search_option'];
            $keyword = $this->request->data['AdminModuleUser']['search_keyword'];
            $search_condition = array("$option LIKE '%$keyword%'");
        }
        $conditions = array();
        if ($is_committee_group == '0') {
            $this->Session->write('Committee.Is_Group', $is_committee_group);
            $conditions = !empty($search_condition) ? $search_condition : array();
        } else if ($is_committee_group == '1') {
            $conditions = !empty($search_condition) ? array_merge($search_condition, array('AdminModuleUserGroupDistribution.user_group_id' => $this->committee_group_ids)) : array('AdminModuleUserGroupDistribution.user_group_id' => $this->committee_group_ids);
            $this->Session->write('Committee.Is_Group', $is_committee_group);
        }

        $this->AdminModuleUser->virtualFields['name_with_designation_and_division'] = $this->AdminModuleUser->AdminModuleUserProfile->virtualFields['name_with_designation_and_division'];
        $fields = array('AdminModuleUser.id', 'AdminModuleUser.user_name', 'AdminModuleUser.activation_status_id', 'AdminModuleUserGroup.group_name', 'AdminModuleUserGroup.id',
            'AdminModuleUser.name_with_designation_and_division', 'LookupUserCommitteeMemberType.committee_member_type');
        $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'limit' => 10, 'order' => array('AdminModuleUser.user_name' => 'ASC'), 'recursive' => 0);
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('AdminModuleUser');
        $this->set(compact('values'));
    }

    public function view_branch_users() {
        $branch_user_group_id = $this->request->query('bu_grp');

        if (!empty($branch_user_group_id)) {
            $this->Session->write('BranchUser.GroupId', $branch_user_group_id);
        } else {
            $branch_user_group_id = $this->Session->read('BranchUser.GroupId');
        }

        $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id)) {
            $conditions = array('AdminModuleUserProfile.org_id' => $org_id, 'AdminModuleUserGroupDistribution.user_group_id' => $branch_user_group_id);
        } else {
            $conditions = array('AdminModuleUserGroupDistribution.user_group_id' => $branch_user_group_id);
        }

        $org_name = $this->AdminModuleUserProfile->BasicModuleBasicInformation->field('name_of_org', array('BasicModuleBasicInformation.id' => $org_id));

        if ($this->request->is('post')) {
            $option = $this->request->data['AdminModuleUser']['search_option'];
            $keyword = $this->request->data['AdminModuleUser']['search_keyword'];
            $conditions = array_merge($conditions, array("$option LIKE '%$keyword%'"));
        }

        $this->AdminModuleUserGroupDistribution->virtualFields['branch_with_address'] = ($this->BasicModuleBranchInfo->hasField('branch_with_address', true)) ?
                $this->BasicModuleBranchInfo->virtualFields['branch_with_address'] :
                "CONCAT_WS(', ', CONCAT_WS('', branch_name, CASE WHEN branch_name != '' AND branch_code != '' THEN CONCAT_WS('', ' (<strong>', branch_code, '</strong>)') ELSE branch_code END), LookupAdminBoundaryUpazila.upazila_name, LookupAdminBoundaryDistrict.district_name)";
        //$this->AdminModuleUserGroupDistribution->virtualFields['name_with_designation_and_division'] = 'CONCAT_WS(", ", AdminModuleUserProfile.full_name_of_user, AdminModuleUserProfile.designation_of_user, AdminModuleUserProfile.div_name_in_office)';

        $this->AdminModuleUserGroupDistribution->virtualFields['name_with_designation_and_division'] = $this->AdminModuleUser->AdminModuleUserProfile->virtualFields['name_with_designation_and_division'];
        //$this->AdminModuleUserGroupDistribution->virtualFields['name_with_designation_and_division'] = 'CONCAT(AdminModuleUserProfile.full_name_of_user, ", ", AdminModuleUserProfile.designation_of_user, ", ", AdminModuleUserProfile.div_name_in_office)';


        $fields = array('AdminModuleUser.id', 'AdminModuleUser.user_name', 'AdminModuleUser.activation_status_id',
            'AdminModuleUserProfile.user_id', 'AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.branch_id',
            'AdminModuleUserGroupDistribution.name_with_designation_and_division', 'AdminModuleUserGroupDistribution.branch_with_address');
        $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'limit' => 10, 'order' => array('AdminModuleUser.user_name' => 'ASC'));
        $this->AdminModuleUserGroupDistribution->recursive = 0;
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('AdminModuleUserGroupDistribution');
        $this->set(compact('values', 'org_name'));

        //debug($values);
    }

    public function login() {
        if ($this->request->is('post')) {

            $this->layout = "mra_master";

            if (empty($this->request->data) || empty($this->request->data ['AdminModuleUser']['user_name']) || empty($this->request->data ['AdminModuleUser']['user_name'])) {
                $msg = 'Empty Username or Password !';
                $this->set(compact('msg'));
                return;
            }
            if ($this->Auth->login()) {
                try {
                    $condition = array('AdminModuleUser.user_name' => $this->request->data['AdminModuleUser']['user_name'], 'AdminModuleUser.activation_status_id' => 1);
                    $user_infos = $this->AdminModuleUser->find('first', array('conditions' => $condition, 'recursive' => 0));

                    if (!empty($user_infos['AdminModuleUser'])) {
                        $user_info = $user_infos['AdminModuleUser'];
                        $user_profile = $user_infos['AdminModuleUserProfile'];
                        $this->Session->write('User.IsValid', true);
                        $this->Session->write('User.Info', $user_info);
                        $this->Session->write('User.Id', $user_info['id']);
                        $this->Session->write('User.Name', $user_info['user_name']);
                        $this->Session->write('User.CommitteMemberTypeId', $user_info['committe_member_type_id']);

                        if (!empty($user_info)) {
                            $user_group_infos = $this->AdminModuleUserGroupDistribution->find('all', array('conditions' => array('AdminModuleUserGroupDistribution.user_id' => $user_info['id'])));
                            $user_group_ids = Hash::extract($user_group_infos, '{n}.AdminModuleUserGroupDistribution.user_group_id');
                            $this->Session->write('User.GroupIds', $user_group_ids);
                        }
                        $this_state_id = array('0', '30');
                        $licensing_years = $this->LookupCurrentLicensingYear->find('first', array('conditions' => array('LookupCurrentLicensingYear.is_current_year' => 1)));
                        $current_year = $licensing_years['LookupCurrentLicensingYear']['licensing_year'];
                        $this->Session->write('Current.LicensingYear', $current_year);
                        $this->Session->write('Form.IsEditable', false);
                        if (!empty($user_profile['org_id'])) {
                            $org_id = $user_profile['org_id'];
                            $this->Session->write('Org.Id', $org_id);
                            $this->Session->write('UserProfile.Email', $user_profile['email']);
                            $this->Session->write('UserProfile.Mobile', $user_profile['mobile_no']);

                            if (!empty($user_group_ids) && in_array(3, $user_group_ids)) {
                                $this->Session->write('Org.BranchId', $user_profile['branch_id']);
                            }

                            $condition = array('BasicModuleBasicInformation.id' => $org_id, 'BasicModuleBasicInformation.licensing_state_id' => $this_state_id);
                            $this->loadModel('BasicModuleBasicInformation');
                            $this->BasicModuleBasicInformation->recursive = -1;
                            if ($this->BasicModuleBasicInformation->hasAny($condition)) {
                                $this->Session->write('Form.IsEditable', true);
                            }
                        } else if (!empty($user_info['user_group_id']) && $user_info['user_group_id'] == 1) {
                            $this->Session->write('Form.IsEditable', true);
                        }
                        $this->loadModel('AdminModuleUserLogHistory');
                        $log_data = array();
                        $log_data['user_id'] = $user_info['id'];
                        $log_data['login_date_time'] = date('Y-m-d H:i:s');
                        $machine_ip = $this->request->clientIp();
                        if (($machine_ip == '::1') || ($machine_ip == '127.0.0.1')) {
                            $machine_ip = 'localhost';
                        }
                        $log_data['machine_ip'] = $machine_ip;
                        $this->AdminModuleUserLogHistory->save($log_data);
                    } else {
                        $msg = 'Invalid Username or Password, try correct username/password !';
                        $this->set(compact('msg'));
                        return;
                    }
                } catch (Exception $ex) {

                    $msg = $ex->getMessage();
                    $this->set(compact('msg'));
                    return;
                }
            } else {
                $msg = 'Login Failed ! Invalid Username or Password.';
                $this->set(compact('msg'));
                return;
            }

            try {
                return $this->render('/Elements/main_content');
            } catch (Exception $ex) {
                $msg = $ex->getMessage();
                $this->set(compact('msg'));
                return;
            }
        }
    }

    public function logout() {
        try {
            $this->Session->delete('User.Id');
            $this->Session->delete('User.Name');
            $this->Session->delete('User.Info');
            $this->Session->delete('User.GroupIds');
            $this->Session->destroy();
            $this->Auth->logout();
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            $this->set(compact('msg'));
            return;
        }

        try {
            $this->layout = "mra_master";
            return $this->render('/Elements/main_content');
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            $this->set(compact('msg'));
            return;
        }
    }

    public function activate_deactivate($activation_status = null, $user_name = null) {
        $this->AdminModuleUser->updateAll(array('activation_status_id' => (empty($activation_status) ? 1 : 0)), array('AdminModuleUser.user_name' => $user_name));
        $user_group_ids = $this->Session->read('User.GroupIds');
        $this->redirect(array('action' => (!empty($user_group_ids) && in_array(2, $user_group_ids)) ? 'view_branch_users' : 'view'));
    }

    public function change_password() {
        $user_group_ids = $this->Session->read('User.GroupIds');
        $this->set(compact('user_group'));

        if ($this->request->is('post')) {
            $reqData = $this->request->data;
            $user_name = "";
            if (!empty($reqData['AdminModuleUser']['user_name'])) {
                $user_name = $reqData['AdminModuleUser']['user_name'];
            }
            if (empty($user_name)) {
                $user_name = $this->Session->read('User.Name');
            }
            if (!empty($reqData['AdminModuleUser'])) {
                $user_info = $this->AdminModuleUser->find('first', array('conditions' => array('AdminModuleUser.user_name' => $user_name), 'recursive' => 0));
                $storedHash = $user_info['AdminModuleUser']['user_passwrd'];
                $current_passwrd_hash = Security::hash($this->request->data['AdminModuleUser']['current_passwrd'], 'sha256', true);
                $is_current_passwrd_correct = $storedHash == $current_passwrd_hash;
                $new_password_hash = Security::hash($this->request->data['AdminModuleUser']['user_passwrd'], 'sha256', true);
                $mail_to = $user_info['AdminModuleUserProfile']['email'];
                //$mail_to = "anis112@gmail.com";
            }
            $flag = 0;
            $message = '';
            $this->AdminModuleUser->setCaptcha('captcha', $this->Captcha->getCode('AdminModuleUser.captcha'));
            $valid_field_list = array("current_passwrd", "user_passwrd", "confirm_passwrd", "captcha");
            if (!empty($user_group_ids) && in_array(1, $user_group_ids)) {
                $valid_field_list[] = 'user_name';
            }
            $this->AdminModuleUser->set($reqData);
            if ($this->AdminModuleUser->validates(array('fieldList' => $valid_field_list))) {
                if ($is_current_passwrd_correct) {
                    try {
                        $password_changed = $this->AdminModuleUser->updateAll(array('user_passwrd' => "'" . $new_password_hash . "'"), array('AdminModuleUser.user_name' => $user_name));
                        if ($password_changed) {
                            if (!empty($mail_to)) {
                                $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'Your password has been changed successfully.' . " Now your \r\n" . "User Id: $user_name" . "\r\n" . "Password: " . $this->request->data['AdminModuleUser'] ['user_passwrd'] . "\r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                                $is_sent_mail = $this->send_mail($mail_to, 'Password Changed', $message_body);
                                if ($is_sent_mail) {
                                    $flag = 1;
                                } else {
                                    $message = $this->get('error_message');
                                }
                            }
                            if (!empty($user_group_ids) && in_array(1, $user_group_ids)) {
                                $this->redirect(array('action' => 'view'));
                            } else {
                                $this->Session->delete('User.Info');
                                $this->Session->destroy();
                                echo ' <script type="text/javascript"> alert("Pasword has been updated successfully. Redirecting to home page. Please login again."); parent.window.location.reload(true); </script> ';
                            }
                        }
                    } catch (Exception $ex) {
                        $message = $ex->getMessage();
                    }
                } else {
                    $message = 'Current password is wrong!';
                }
            } else {
                $message = 'Fill up all the required fieds';
            }
            if ($flag == 0) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
            }
        }
    }

    public function add($is_committee_group = null) {
        try {
            $this->loadModel('LookupBasicPrimaryRegistrationAct');
            $this->loadModel('BasicModulePrimaryRegActDetail');
            $this->loadModel('AdminModuleUserOrgType');
            $this->loadModel('LookupCDBNonMfiMinistryName');
            $user_org_type_options = $this->AdminModuleUserOrgType->find('list', array('fields' => array('AdminModuleUserOrgType.id', 'AdminModuleUserOrgType.user_org_types')));
            $mra_user_group_options = array();
            $mfi_with_other_user_group_options = array();
            $flag = 0;
            if ($is_committee_group == '0') {
                $mra_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.user_org_type_id' => 1), 'order' => array('AdminModuleUserGroup.sorting_order' => 'ASC')));
                $mfi_with_other_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.user_org_type_id' => 2)));
            } else if ($is_committee_group == '1') {
                $mra_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $this->committee_group_ids, 'AdminModuleUserGroup.user_org_type_id' => 1), 'order' => array('AdminModuleUserGroup.group_name' => 'ASC')));
                $mfi_with_other_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $this->committee_group_ids, 'AdminModuleUserGroup.user_org_type_id' => 2), 'order' => array('AdminModuleUserGroup.group_name' => 'ASC')));
            }
            $primary_reg_act_options = $this->LookupBasicPrimaryRegistrationAct->find('list', array('fields' => array('LookupBasicPrimaryRegistrationAct.id', 'LookupBasicPrimaryRegistrationAct.primary_registration_act')));
            $committe_member_type_options = $this->AdminModuleUser->LookupUserCommitteeMemberType->find('list', array('fields' => array('LookupUserCommitteeMemberType.id', 'LookupUserCommitteeMemberType.committee_member_type')));
            $org_name_options = $this->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.licensing_state_id' => 30)));
            $regulatory_ministry_options = $this->LookupCDBNonMfiMinistryName->find('list', array('fields' => array('LookupCDBNonMfiMinistryName.id', 'LookupCDBNonMfiMinistryName.name_of_ministry')));
            $this->set(compact('user_org_type_options', 'mra_user_group_options', 'mfi_with_other_user_group_options', 'primary_reg_act_options', 'committe_member_type_options', 'org_name_options', 'regulatory_ministry_options'));
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => $message
            );
            $this->set(compact('msg'));
            return;
        }

        if ($this->request->is('post')) {
            $new_data = $this->request->data;
            $basic_new_data = $new_data['BasicModuleBasicInformation'];
            $cdb_non_mfi_new_data = $new_data['CDBNonMfiBasicInfo'];
            $user_new_data = $new_data['AdminModuleUser'];
            $user_profile_new_data = $new_data['AdminModuleUserProfile'];
            $user_group_distributions = $new_data['AdminModuleUserGroupDistribution'];

            $user_group_ids = array();
            if (!empty($user_group_distributions[1]['user_group_id'])) {
                $user_group_ids = $user_group_distributions[1]['user_group_id'];
            } elseif (!empty($user_group_distributions[2]['user_group_id'])) {
                $user_group_ids = $user_group_distributions[2]['user_group_id'];
            }

            $this->AdminModuleUser->setCaptcha('captcha', $this->Captcha->getCode('AdminModuleUser.captcha'));
            $this->AdminModuleUser->set($this->data);
            $valid_user_field_list = array("user_name", "user_passwrd", "confirm_passwrd", "captcha");
            $validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));

//            debug($validate_admin_module_user);

            $this->AdminModuleUserProfile->set($this->data);
            $valid_user_profile_field_list = array("full_name_of_user", "designation_of_user", "email", "mobile_no");
            if ($user_group_distributions[2]['user_group_id'] == 4) {
                $valid_user_profile_field_list = array_diff($valid_user_profile_field_list, array("full_name_of_user", "designation_of_user"));
            }
            $validate_admin_module_user_profile = $this->AdminModuleUserProfile->validates(array('fieldList' => $valid_user_profile_field_list));

            $this->AdminModuleUserGroupDistribution->set($this->data);
            $valid_user_group_field_list = array("user_group_id");
            $validate_admin_module_user_group = $this->AdminModuleUserGroupDistribution->validates(array('fieldList' => $valid_user_group_field_list));


            if ($validate_admin_module_user && $validate_admin_module_user_profile && $validate_admin_module_user_group) {
                $user_infos = $this->AdminModuleUser->find('all', array('fields' => array('AdminModuleUser.user_name', 'AdminModuleUser.committe_member_type_id', 'AdminModuleUserProfile.full_name_of_user'), 'conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $user_group_ids), 'recursive' => 0));

                $editor_user_id_with_full_name = '';
                $is_editor_exists = false;

                foreach ($user_infos as $user_info) {
                    $committe_member_type_id = $user_info['AdminModuleUser']['committe_member_type_id'];
                    if ($committe_member_type_id == '2') {
                        $is_editor_exists = true;
                        $editor_user_id_with_full_name = $user_info['AdminModuleUserProfile']['full_name_of_user'] . ' (' . $user_info['AdminModuleUser']['user_name'] . ')';
                        break;
                    }
                }

                if (($is_editor_exists) && ($committe_member_type_id == '2')) {
                    $message = $editor_user_id_with_full_name . ' is already assigned as Editor';
                }

                $email_id = $user_profile_new_data['email'];
                $mobile_no = $user_profile_new_data['mobile_no'];
                $message = '';

                if ($email_id != '') {
                    $existing_email_from_user_profile = $this->AdminModuleUserProfile->find('first', array('conditions' => array('AdminModuleUserProfile.email' => $email_id)));
                    if (!empty($existing_email_from_user_profile)) {
                        $message = 'Email Id already exists!';
                    }
                } else if ($mobile_no != '') {
                    $existing_mobile_from_user_profile = $this->AdminModuleUserProfile->find('first', array('conditions' => array('AdminModuleUserProfile.mobile_no' => $mobile_no)));
                    if (!empty($existing_mobile_from_user_profile)) {
                        $message = 'Mobile no. already exists!';
                    }
                }

                if (!empty($message)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                    return;
                }

                $current_year = $this->LookupCurrentLicensingYear->field('licensing_year', array('LookupCurrentLicensingYear.is_current_year' => 1));
                unset($user_new_data['confirm_password']);
                $user_new_data['user_passwrd'] = Security::hash($user_new_data['user_passwrd'], 'sha256', true);
                $user_new_data['created_date'] = date('Y-m-d H:i:s');
                $user_new_data['created_by'] = $this->Session->read('User.Name');

                try {
                    $this->AdminModuleUser->create();
                    $saved_data_in_user = $this->AdminModuleUser->save($user_new_data);
                } catch (Exception $ex) {
                    debug($ex);
                }

                if ($saved_data_in_user) {
                    $user_profile_new_data['user_id'] = $saved_data_in_user['AdminModuleUser']['id'];
                    $org_id = null;

                    if (!empty($user_group_ids) && (in_array('2', $user_group_ids) || in_array('5', $user_group_ids))) {
                        $data_to_save_in_basic_info = array(
                            'short_name_of_org' => $basic_new_data['short_name_of_org'],
                            'full_name_of_org' => $basic_new_data['full_name_of_org'],
                            'licensing_year' => $current_year,
                            'name_of_authorized_person' => $basic_new_data['name_of_authorized_person'],
                            'designation_of_authorized_person' => $basic_new_data['designation_of_authorized_person'],
                            'licensing_state_id' => 0
                        );

                        $this->BasicModuleBasicInformation->create();
                        $saved_in_basic_info = $this->BasicModuleBasicInformation->save($data_to_save_in_basic_info);
                        $org_id = $saved_in_basic_info['BasicModuleBasicInformation']['id'];
                        $user_profile_new_data['org_id'] = $org_id;
                        $user_profile_new_data['full_name_of_user'] = $basic_new_data['name_of_authorized_person'];
                        $user_profile_new_data['designation_of_user'] = $basic_new_data['designation_of_authorized_person'];
                        $user_profile_new_data['org_name'] = $basic_new_data['full_name_of_org'];
                        $primary_reg_act_ids = $new_data['PrimaryRegistrationActInfo']['primary_reg_act_id'];

                        $rc = 0;
                        $reg_act_data = array();
                        foreach ($primary_reg_act_ids as $primary_reg_act_id) {
                            $reg_act_data[$rc++] = array('org_id' => $org_id, 'primary_reg_act_id' => $primary_reg_act_id);
                        }

                        $this->BasicModulePrimaryRegActDetail->create();
                        $this->BasicModulePrimaryRegActDetail->saveAll($reg_act_data);
                    } elseif (!empty($user_group_ids) && in_array('3', $user_group_ids)) {
                        $org_id = $user_profile_new_data['org_id'];
                        $user_profile_new_data['org_name'] = $org_name_options[$org_id];
                    } elseif (!empty($user_group_ids) && in_array('4', $user_group_ids)) {
                        $keyword = $cdb_non_mfi_new_data['name_of_org'];
                        $this->loadModel('CDBNonMfiBasicInfo');
                        $non_mfi_name_exists = $this->CDBNonMfiBasicInfo->find('first', array('conditions' => array("CDBNonMfiBasicInfo.name_of_org LIKE '%$keyword%'")));
                        if (!empty($non_mfi_name_exists)) {
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'Organization name already exists'
                            );
                            $this->set(compact('msg'));
                            return;
                        }
                        $this->CDBNonMfiBasicInfo->create();
                        $saved_in_cdb_non_mfi = $this->CDBNonMfiBasicInfo->save($cdb_non_mfi_new_data);
                        if ($saved_in_cdb_non_mfi) {
                            $org_id = $saved_in_cdb_non_mfi['CDBNonMfiBasicInfo']['id'];
                            $user_profile_new_data['org_id'] = $org_id;
                            $user_profile_new_data['full_name_of_user'] = $cdb_non_mfi_new_data['name_of_officer'];
                            $user_profile_new_data['designation_of_user'] = $cdb_non_mfi_new_data['designation_of_officer'];
                            $user_profile_new_data['org_name'] = $cdb_non_mfi_new_data['name_of_org'];
                        }
                    }

                    $this->AdminModuleUserProfile->create();
                    $saved_data_in_user_profile = $this->AdminModuleUserProfile->save($user_profile_new_data);

                    if ($saved_data_in_user_profile) {
                        $user_group_new_data = array();
                        if (!empty($user_group_ids)) {
                            foreach ($user_group_ids as $user_group_id) {
                                $user_group_new_data['user_id'] = $saved_data_in_user['AdminModuleUser']['id'];
                                $user_group_new_data['user_group_id'] = $user_group_id;
                                $this->AdminModuleUserGroupDistribution->create();
                                $this->AdminModuleUserGroupDistribution->save($user_group_new_data);
                            }
                        }

                        $flag = 1;
                        $message_body = 'Congratulation! you are added as a user of MFI DBMS.' . "\r\n" . "\r\n" . 'You can login to MFI DBMS System using' . " \r\n" . " User Id: " . $user_new_data['user_name'] . " and\r\n " . "Password: " . $user_new_data['user_passwrd'] . "\r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                        $Email = new CakeEmail('gmail');
                        $Email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))
                                ->to($email_id)
                                ->subject('New user added in MFI-DBMS');
                        //if ($Email->send($message_body)) 
                        //{
                        $this->redirect(array('action' => 'view?is_committee_group=' . $is_committee_group));
                        //}
                        //else {                        
                        //$message = 'User creation and mail sending failed';
                        //
                        //}
                    } else {
                        $message = 'User Profile creation failed !';
                    }
                } else { // didn't save User logic                    
                    $message = 'User creation failed !';
                }
            } else {
                $message = 'Enter all the required information';
            }
            if ($flag == 0) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
                return;
            }
        }
    }

    public function edit($is_committee_group = null, $user_id = null) {
        try {
            $this->loadModel('LookupBasicPrimaryRegistrationAct');
            $this->loadModel('BasicModulePrimaryRegActDetail');
            $this->loadModel('AdminModuleUserOrgType');
            $this->loadModel('LookupCDBNonMfiMinistryName');
            $user_info = $this->AdminModuleUser->find('first', array('conditions' => array('AdminModuleUser.id' => $user_id)));
            $user_org_type_options = $this->AdminModuleUserOrgType->find('list', array('fields' => array('AdminModuleUserOrgType.id', 'AdminModuleUserOrgType.user_org_types')));
            $mra_user_group_options = array();
            $mfi_with_other_user_group_options = array();
            $flag = 0;
            if ($is_committee_group == '0') {
                $mra_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.user_org_type_id' => 1), 'order' => array('AdminModuleUserGroup.group_name' => 'ASC')));
                $mfi_with_other_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.user_org_type_id' => 2)));
            } else if ($is_committee_group == '1') {
                $mra_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $this->committee_group_ids, 'AdminModuleUserGroup.user_org_type_id' => 1), 'order' => array('AdminModuleUserGroup.group_name' => 'ASC')));
                $mfi_with_other_user_group_options = $this->AdminModuleUser->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $this->committee_group_ids, 'AdminModuleUserGroup.user_org_type_id' => 2), 'order' => array('AdminModuleUserGroup.group_name' => 'ASC')));
            }
            $primary_reg_act_options = $this->LookupBasicPrimaryRegistrationAct->find('list', array('fields' => array('LookupBasicPrimaryRegistrationAct.id', 'LookupBasicPrimaryRegistrationAct.primary_registration_act')));
            $committe_member_type_options = $this->AdminModuleUser->LookupUserCommitteeMemberType->find('list', array('fields' => array('LookupUserCommitteeMemberType.id', 'LookupUserCommitteeMemberType.committee_member_type')));
            $org_name_options = $this->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'), 'conditions' => array('BasicModuleBasicInformation.licensing_state_id' => 30)));
            $regulatory_ministry_options = $this->LookupCDBNonMfiMinistryName->find('list', array('fields' => array('LookupCDBNonMfiMinistryName.id', 'LookupCDBNonMfiMinistryName.name_of_ministry')));
            $this->set(compact('user_org_type_options', 'mra_user_group_options', 'mfi_with_other_user_group_options', 'primary_reg_act_options', 'committe_member_type_options', 'org_name_options', 'regulatory_ministry_options'));
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => $message
            );
            $this->set(compact('msg'));
            return;
        }
        if (!$this->request->data) {
            $this->request->data = $user_info;
            if (!empty($org_info)) {
                $this->request->data = array_merge($this->request->data, $org_info);
            }
        }

        if ($this->request->is(array('post', 'put'))) {
            $new_data = $this->request->data;
            $basic_new_data = $new_data['BasicModuleBasicInformation'];
            $cdb_non_mfi_new_data = $new_data['CDBNonMfiBasicInfo'];
            $user_new_data = $new_data['AdminModuleUser'];
            $user_profile_new_data = $new_data['AdminModuleUserProfile'];
            $user_group_distributions = $new_data['AdminModuleUserGroupDistribution'];

            $user_group_ids = array();
            if (!empty($user_group_distributions[1]['user_group_id'])) {
                $user_group_ids[] = $user_group_distributions[1]['user_group_id'];
            } elseif (!empty($user_group_distributions[2]['user_group_id'])) {
                $user_group_ids[] = $user_group_distributions[2]['user_group_id'];
            }

            $this->AdminModuleUser->setCaptcha('captcha', $this->Captcha->getCode('AdminModuleUser.captcha'));
            $this->AdminModuleUser->set($this->data);
            $valid_user_field_list = array("user_name", "user_passwrd", "confirm_passwrd", "captcha");
            $validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));

            $this->AdminModuleUserProfile->set($this->data);
            $valid_user_profile_field_list = array("full_name_of_user", "designation_of_user", "email", "mobile_no");
            if ($user_group_distributions[2]['user_group_id'] == 4) {
                $valid_user_profile_field_list = array_diff($valid_user_profile_field_list, array("full_name_of_user", "designation_of_user"));
            }
            $validate_admin_module_user_profile = $this->AdminModuleUserProfile->validates(array('fieldList' => $valid_user_profile_field_list));

            $this->AdminModuleUserGroupDistribution->set($this->data);
            $valid_user_group_field_list = array("user_group_id");
            $validate_admin_module_user_group = $this->AdminModuleUserGroupDistribution->validates(array('fieldList' => $valid_user_group_field_list));

            if ($validate_admin_module_user && $validate_admin_module_user_profile && $validate_admin_module_user_group) {
                $user_infos = $this->AdminModuleUser->find('all', array('conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $user_group_ids), 'recursive' => 1));
                $editor_user_id_with_full_name = '';
                $is_editor_exists = false;

                foreach ($user_infos as $user_info) {
                    $committe_member_type_id = $user_info['AdminModuleUser']['committe_member_type_id'];
                    if ($committe_member_type_id == '2') {
                        $is_editor_exists = true;
                        $editor_user_id_with_full_name = $user_info['AdminModuleUserProfile']['full_name_of_user'] . ' (' . $user_info['AdminModuleUser']['user_name'] . ')';
                        break;
                    }
                }

                if (($is_editor_exists) && ($committe_member_type_id == '2')) {
                    $message = $editor_user_id_with_full_name . ' is already assigned as Editor';
                }

                $email_id = $user_profile_new_data['email'];
                $mobile_no = $user_profile_new_data['mobile_no'];
                $message = '';

                if ($email_id != '') {
                    $existing_email_from_user_profile = $this->AdminModuleUserProfile->find('first', array('conditions' => array('AdminModuleUserProfile.email' => $email_id)));
                    if (!empty($existing_email_from_user_profile)) {
                        $message = 'Email Id already exists!';
                    }
                } else if ($mobile_no != '') {
                    $existing_mobile_from_user_profile = $this->AdminModuleUserProfile->find('first', array('conditions' => array('AdminModuleUserProfile.mobile_no' => $mobile_no)));
                    if (!empty($existing_mobile_from_user_profile)) {
                        $message = 'Mobile no. already exists!';
                    }
                }

                if (!empty($message)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                    return;
                }

                $current_year = $this->LookupCurrentLicensingYear->field('licensing_year', array('LookupCurrentLicensingYear.is_current_year' => 1));
                unset($user_new_data['confirm_password']);
                $user_new_data['user_passwrd'] = Security::hash($user_new_data['user_passwrd'], 'sha256', true);
                $user_new_data['created_date'] = date('Y-m-d H:i:s');
                $user_new_data['created_by'] = $this->Session->read('User.Name');

                try {
                    $this->AdminModuleUser->create();
                    $saved_data_in_user = $this->AdminModuleUser->save($user_new_data);
                } catch (Exception $ex) {
                    debug($ex);
                }

                if ($saved_data_in_user) {
                    $user_profile_new_data['user_id'] = $saved_data_in_user['AdminModuleUser']['id'];
                    $org_id = null;

                    if (!empty($user_group_ids) && (in_array('2', $user_group_ids) || in_array('5', $user_group_ids))) {
                        $data_to_save_in_basic_info = array(
                            'short_name_of_org' => $basic_new_data['short_name_of_org'],
                            'full_name_of_org' => $basic_new_data['full_name_of_org'],
                            'licensing_year' => $current_year,
                            'name_of_authorized_person' => $basic_new_data['name_of_authorized_person'],
                            'designation_of_authorized_person' => $basic_new_data['designation_of_authorized_person'],
                            'licensing_state_id' => 0
                        );

                        $this->BasicModuleBasicInformation->create();
                        $saved_in_basic_info = $this->BasicModuleBasicInformation->save($data_to_save_in_basic_info);
                        $org_id = $saved_in_basic_info['BasicModuleBasicInformation']['id'];
                        $user_profile_new_data['org_id'] = $org_id;
                        $user_profile_new_data['full_name_of_user'] = $basic_new_data['name_of_authorized_person'];
                        $user_profile_new_data['designation_of_user'] = $basic_new_data['designation_of_authorized_person'];
                        $user_profile_new_data['org_name'] = $basic_new_data['full_name_of_org'];
                        $primary_reg_act_ids = $new_data['PrimaryRegistrationActInfo']['primary_reg_act_id'];

                        $rc = 0;
                        $reg_act_data = array();
                        foreach ($primary_reg_act_ids as $primary_reg_act_id) {
                            $reg_act_data[$rc++] = array('org_id' => $org_id, 'primary_reg_act_id' => $primary_reg_act_id);
                        }

                        $this->BasicModulePrimaryRegActDetail->create();
                        $this->BasicModulePrimaryRegActDetail->saveAll($reg_act_data);
                    } elseif (!empty($user_group_ids) && in_array('3', $user_group_ids)) {
                        $org_id = $user_profile_new_data['org_id'];
                        $user_profile_new_data['org_name'] = $org_name_options[$org_id];
                    } elseif (!empty($user_group_ids) && in_array('4', $user_group_ids)) {
                        $keyword = $cdb_non_mfi_new_data['name_of_org'];
                        $this->loadModel('CDBNonMfiBasicInfo');
                        $non_mfi_name_exists = $this->CDBNonMfiBasicInfo->find('first', array('conditions' => array("CDBNonMfiBasicInfo.name_of_org LIKE '%$keyword%'")));
                        if (!empty($non_mfi_name_exists)) {
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'Organization name already exists'
                            );
                            $this->set(compact('msg'));
                            return;
                        }
                        $this->CDBNonMfiBasicInfo->create();
                        $saved_in_cdb_non_mfi = $this->CDBNonMfiBasicInfo->save($cdb_non_mfi_new_data);
                        if ($saved_in_cdb_non_mfi) {
                            $org_id = $saved_in_cdb_non_mfi['CDBNonMfiBasicInfo']['id'];
                            $user_profile_new_data['org_id'] = $org_id;
                            $user_profile_new_data['full_name_of_user'] = $cdb_non_mfi_new_data['name_of_officer'];
                            $user_profile_new_data['designation_of_user'] = $cdb_non_mfi_new_data['designation_of_officer'];
                            $user_profile_new_data['org_name'] = $cdb_non_mfi_new_data['name_of_org'];
                        }
                    }

                    $this->AdminModuleUserProfile->create();
                    $saved_data_in_user_profile = $this->AdminModuleUserProfile->save($user_profile_new_data);

                    if ($saved_data_in_user_profile) {
                        $user_group_new_data = array();
                        if (!empty($user_group_ids)) {
                            foreach ($user_group_ids as $user_group_id) {
                                $user_group_new_data['user_id'] = $saved_data_in_user['AdminModuleUser']['id'];
                                $user_group_new_data['user_group_id'] = $user_group_id;
                                $this->AdminModuleUserGroupDistribution->create();
                                $this->AdminModuleUserGroupDistribution->save($user_group_new_data);
                            }
                        }

                        $flag = 1;
                        $message_body = 'Congratulation! you are added as a user of MFI DBMS.' . "\r\n" . "\r\n" . 'You can login to MFI DBMS System using' . " \r\n" . " User Id: " . $user_new_data['user_name'] . " and\r\n " . "Password: " . $user_new_data['user_passwrd'] . "\r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                        $Email = new CakeEmail('gmail');
                        $Email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))
                                ->to($email_id)
                                ->subject('New user added in MFI-DBMS');
                        //if ($Email->send($message_body)) 
                        //{
                        $this->redirect(array('action' => 'view?is_committee_group=' . $is_committee_group));
                        //}
                        //else {                        
                        //$message = 'User creation and mail sending failed';
                        //
                        //}
                    } else {
                        $message = 'User Profile creation failed !';
                    }
                } else { // didn't save User logic                    
                    $message = 'User creation failed !';
                }
            } else {
                $message = 'Enter all the required information';
            }
            if ($flag == 0) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
                return;
            }
        }
    }

    function select_branch() {
        $this->layout = 'ajax';

        $this->loadModel('BasicModuleBranchInfo');
        if (!$this->BasicModuleBranchInfo->hasField('branch_with_address', true))
            $this->BasicModuleBranchInfo->virtualFields = array('branch_with_address' => "CONCAT_WS(', ', BasicModuleBranchInfo.branch_name, LookupAdminBoundaryUpazila.upazila_name, LookupAdminBoundaryDistrict.district_name)");

        $org_id = $this->request->data['AdminModuleUserProfile']['org_id'];
        $branch_options = $this->BasicModuleBranchInfo->find('list', array(
            'fields' => array('BasicModuleBranchInfo.id', 'BasicModuleBranchInfo.branch_with_address'),
//            'contain' => array('LookupAdminBoundaryUpazila', 'LookupAdminBoundaryDistrict'),
            'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id),
            'recursive' => -1
        ));

        $this->set(compact('branch_options'));
    }

    public function add_branch_user($org_id = null) {
        try {
            $branch_user_group_id = $this->Session->read('BranchUser.GroupId');
            $org_id = $this->Session->read('Org.Id');
            $org_name = $this->BasicModuleBasicInformation->field('BasicModuleBasicInformation.name_of_org', array('BasicModuleBasicInformation.id' => $org_id));
            $this->loadModel('BasicModuleBranchInfo');
            $this->BasicModuleBranchInfo->virtualFields = array('branch_with_address' => "CONCAT_WS(', ', BasicModuleBranchInfo.branch_name, LookupAdminBoundaryUpazila.upazila_name, LookupAdminBoundaryDistrict.district_name)");
            $existing_branches_in_added_users = $this->AdminModuleUserProfile->find('list', array('fields' => array('AdminModuleUserProfile.branch_id'), 'conditions' => array('NOT' => array('AdminModuleUserProfile.branch_id' => 'null'), 'AdminModuleUserProfile.org_id' => $org_id)));

            $this->BasicModuleBranchInfo->virtualFields['branch_with_code'] = "CONCAT_WS('', branch_name, CASE WHEN branch_name != '' AND branch_code != '' THEN CONCAT_WS('', ' (<strong>', branch_code, '</strong>)') ELSE branch_code END)";
            $branch_name_options = $this->BasicModuleBranchInfo->find('list', array('fields' => array('id', 'branch_with_code'), 'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id >' => 1), 'recursive' => -1));
            $this->set(compact('org_id', 'org_name', 'branch_name_options'));
            $flag = 0;
            $message = '';

            if ($this->request->is('post')) {
                $this->AdminModuleUserProfile->validate = array('branch_id' => array(
                        'required' => array(
                            'rule' => array('notBlank'),
                            'message' => 'Branch is required'
                        )
                    ),
                    'full_name_of_user' => array(
                        'required' => array(
                            'rule' => array('notBlank'),
                            'message' => 'Full name of user is required'
                        )
                ));

                $this->AdminModuleUser->setCaptcha('captcha', $this->Captcha->getCode('AdminModuleUser.captcha'));
                $this->AdminModuleUser->set($this->data);
                $valid_user_field_list = array("user_name", "user_passwrd", "confirm_passwrd", "captcha");
                $validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));

                $this->AdminModuleUserProfile->set($this->data);
                $valid_user_profile_field_list = array("branch_id", "full_name_of_user");
                $validate_admin_module_user_profile = $this->AdminModuleUserProfile->validates(array('fieldList' => $valid_user_profile_field_list));

                if ($validate_admin_module_user && $validate_admin_module_user_profile) {
                    $user_name = $this->request->data['AdminModuleUser']['user_name'];
                    $is_exists_users = $this->AdminModuleUser->find('first', array('conditions' => array('AdminModuleUser.user_name' => $user_name)));

                    if (!empty($is_exists_users)) {
                        $message = "User name already exists!";
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => $message
                        );
                        $this->set(compact('msg'));
                        return;
                    }
                    $password = $this->request->data['AdminModuleUser']['user_passwrd'];
                    $password_hash = Security::hash($password, 'sha256', true);
                    $created_by = $this->Session->read('User.Name');

                    $data_to_save_in_user = array(
                        'user_name' => $user_name,
                        'user_passwrd' => $password_hash,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $created_by,
                        'activation_status_id' => 1
                    );
                    $this->AdminModuleUser->create();
                    $saved_data_in_user = $this->AdminModuleUser->save($data_to_save_in_user);

                    if ($saved_data_in_user) {
                        $this->request->data['AdminModuleUserProfile']['user_id'] = $saved_data_in_user['AdminModuleUser']['id'];
                        $this->request->data['AdminModuleUserProfile']['org_id'] = $org_id;
                        $this->AdminModuleUserProfile->create();
                        $saved_data_in_user_profile = $this->AdminModuleUserProfile->save($this->request->data['AdminModuleUserProfile']);
                        if ($saved_data_in_user_profile) {
                            if (!empty($branch_user_group_id)) {
                                $user_group_new_data = array();
                                $user_group_new_data['user_id'] = $saved_data_in_user['AdminModuleUser']['id'];
                                $user_group_new_data['user_group_id'] = $branch_user_group_id;
                                $this->AdminModuleUserGroupDistribution->create();
                                $this->AdminModuleUserGroupDistribution->save($user_group_new_data);
                                $flag = 1;
                            }
                        } else {
                            $message = 'User Profile creation failed';
                        }
                    } else {
                        // didn't save User logic                        
                        $message = 'User creation failed';
                    }
                } else {
                    $message = 'Fill up all the required fieds';
                }
                if ($flag == 0) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                } elseif ($flag == 1) {
                    $this->redirect(array('action' => 'view_branch_users'));
                }
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => $message
            );
            $this->set(compact('msg'));
        }
    }

    public function edit_branch_user($user_id = null, $org_id = null, $branch_id = null) {

        if (empty($user_id) || empty($org_id) || empty($branch_id)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid Branch Inforamtion !'
            );
            $this->set(compact('msg'));
        }

        try {
            $org_name = $this->BasicModuleBasicInformation->field('name_of_org', array('BasicModuleBasicInformation.id' => $org_id));

            $this->loadModel('BasicModuleBranchInfo');
            $branch_with_address = $this->BasicModuleBranchInfo->field('branch_with_address', array('id' => $branch_id));
            $this->set(compact('org_name', 'branch_with_address'));

            if ($this->request->is(array('post', 'put')) && !empty($this->request->data['AdminModuleUser'])) {
//                debug($this->request->data['AdminModuleUser']);

                $new_user_data = $this->request->data['AdminModuleUser'];
                debug($new_user_data);

//                array(
//                    'full_name_of_user' => 'Tangail Branch - 01',
//                    'user_name' => 'branch1',
//                    'new_name_of_user' => 'Tangail Branch - 01',
//                    'new_user_name' => 'branch-123',
//                    'new_user_passwrd' => 'aaa',
//                    'new_confirm_passwrd' => 'aaa',
//                    'captcha' => '21'
//                )

                $is_ok = true;
                $new_user_info = array();
                if (!empty($new_user_data['new_user_passwrd'])) {
                    if ($new_user_data['new_user_passwrd'] == $new_user_data['new_confirm_passwrd']) {
                        $password = $new_user_data['new_user_passwrd'];
                        $hash_password = Security::hash($password, 'sha256', true);
                        $new_user_info['user_passwrd'] = $hash_password;
                    } else {
                        $is_ok = false;
                        $msg = array(
                            'type' => 'warning',
                            'title' => 'Warning... . . !',
                            'msg' => 'New password does not match with confirmed password !'
                        );
                        $this->set(compact('msg'));
                    }
                }

                if ($is_ok) {
                    if (!empty($new_user_data['new_user_name']) && $new_user_data['new_user_name'] != $new_user_data['new_user_name']) {
                        $new_user_info['user_name'] = $new_user_data['new_user_name'];
                    }

                    if (!empty($new_user_info)) {
                        $user_condition = array('AdminModuleUser.id' => $user_id);
                        $is_ok = $this->AdminModuleUser->updateAll($new_user_info, $user_condition);

                        debug($user_condition);
                        debug($new_user_info);

                        if ($is_ok) {
                            debug($is_ok);

                            if (!empty($new_user_data['new_name_of_user']) && $new_user_data['new_name_of_user'] != $new_user_data['full_name_of_user']) {
                                $profile_condition = array('user_id' => $user_id, 'org_id' => $org_id);
                                $new_user_profile = array('full_name_of_user' => $new_user_data['new_name_of_user']);
                                $is_ok = $this->AdminModuleUserProfile->updateAll($new_user_profile, $profile_condition);

                                debug($new_user_profile);
                                debug($profile_condition);
                                if ($is_ok)
                                    return $this->redirect(array('action' => 'view_branch_users'));
                            }
                            //return $this->redirect(array('action' => 'view_branch_users'));
                        } else {
                            $is_ok = false;
                            $msg = array(
                                'type' => 'warning',
                                'title' => 'Warning... . . !',
                                'msg' => 'User Profile update failed !'
                            );
                            $this->set(compact('msg'));
                        }
                    }
                }

                exit();

                $valid_user_field_list = array('new_user_passwrd', 'new_confirm_passwrd');
                //$validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));

                $validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));
                debug($validate_admin_module_user);

                exit();

//                $validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));
//                debug($validate_admin_module_user);
//                $valid_user_profile_field_list = array("full_name_of_user");
//                $validate_admin_module_user_profile = $this->AdminModuleUserProfile->validates(array('fieldList' => $valid_user_profile_field_list));
//
//                if ($validate_admin_module_user_profile && $validate_admin_module_user) {
                if ($validate_admin_module_user) {
                    $req_data = $this->request->data;
                    $password = $this->request->data['AdminModuleUser']['new_user_passwrd'];
                    $user_password = Security::hash($password, 'sha256', true);
                    $new_user_profile = array();
                    $new_user_profile['AdminModuleUser']['user_passwrd'] = $user_password;
                    $new_user_profile['AdminModuleUserProfile']['full_name_of_user'] = $req_data['AdminModuleUserProfile']['new_name_of_user'];
                    $this->AdminModuleUserProfile->updateAll($new_user_profile, $profile_condition);
                    return $this->redirect(array('action' => 'view_branch_users'));
                }
            }

            $user_info = $this->AdminModuleUser->findById($user_id, array('AdminModuleUser.user_name', 'AdminModuleUserProfile.full_name_of_user'));
//            debug($user_info);
            if (!$this->request->data && !empty($user_info)) {
                $user_info['AdminModuleUser']['new_user_name'] = $user_info['AdminModuleUser']['user_name'];
                $user_info['AdminModuleUser']['new_name_of_user'] = $user_info['AdminModuleUserProfile']['full_name_of_user'];

                $this->request->data = $user_info;
            }
        } catch (Exception $ex) {
            debug('err:' + $ex->getMessage());
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => $ex->getMessage()
            );
            $this->set(compact('msg'));
        }
    }

    public function edit_bk($is_committee_group = null, $user_id = null) {
        try {
            $this->loadModel('LookupCDBNonMfiMinistryName');
            $this->loadModel('CDBNonMfiBasicInfo');
            $user_info = $this->AdminModuleUser->find('first', array('conditions' => array('AdminModuleUser.id' => $user_id)));
            $user_name = $user_info['AdminModuleUser']['user_name'];
            $created_date = $user_info['AdminModuleUser']['created_date'];
            $created_by = $user_info['AdminModuleUser']['created_by'];
            $user_group_id = $user_info['AdminModuleUserGroupDistribution']['user_group_id'];
            $org_id = $user_info['AdminModuleUserProfile']['org_id'];
            $org_info = array();
            if ($user_group_id == 2 || $user_group_id == 3 || $user_group_id == 5) {
                $org_info = $this->BasicModuleBasicInformation->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id)));
            } elseif ($user_group_id == 4) {
                $org_info = $this->CDBNonMfiBasicInfo->find('first', array('conditions' => array('CDBNonMfiBasicInfo.id' => $org_id)));
            }

            $primary_reg_act_options = $this->LookupBasicRegistrationAuthority->find('list', array('fields' => array('LookupBasicRegistrationAuthority.id', 'LookupBasicRegistrationAuthority.registration_authority')));
            $this->loadModel('AdminModuleUserGroup');
            if ($is_committee_group == '0') {
                $user_group_options = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name')));
            } else if ($is_committee_group == '1') {
                $user_group_options = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $this->committee_group_ids)));
            }
            $committe_member_type_options = $this->AdminModuleUser->LookupUserCommitteeMemberType->find('list', array('fields' => array('LookupUserCommitteeMemberType.id', 'LookupUserCommitteeMemberType.committee_member_type')));
            $regulatory_ministry_options = $this->LookupCDBNonMfiMinistryName->find('list', array('fields' => array('LookupCDBNonMfiMinistryName.id', 'LookupCDBNonMfiMinistryName.name_of_ministry')));
            $this->set(compact('user_group_options', 'primary_reg_act_options', 'user_name', 'created_date', 'created_by', 'full_name', 'designation', 'div_name', 'org_name', 'mobile_no', 'email', 'committe_member_type_options', 'regulatory_ministry_options'));

            if (!$this->request->data) {
                $this->request->data = $user_info;
                if (!empty($org_info)) {
                    $this->request->data = array_merge($this->request->data, $org_info);
                }
            }

            if ($this->request->is(array('post', 'put'))) {
                $this->AdminModuleUser->setCaptcha('captcha', $this->Captcha->getCode('AdminModuleUser.captcha'));
                $validate_admin_module_user = $this->AdminModuleUser->validates(array('fieldList' => $valid_user_field_list));

                $this->AdminModuleUserProfile->set($this->data);
                $valid_user_profile_field_list = array("full_name_of_user", "designation_of_user", "email", "mobile_no");
                if ($user_group_distributions[2]['user_group_id'] == 4) {
                    $valid_user_profile_field_list = array_diff($valid_user_profile_field_list, array("full_name_of_user", "designation_of_user"));
                }
                $validate_admin_module_user_profile = $this->AdminModuleUserProfile->validates(array('fieldList' => $valid_user_profile_field_list));

                $this->AdminModuleUserGroupDistribution->set($this->data);
                $valid_user_group_field_list = array("user_group_id");
                $validate_admin_module_user_group = $this->AdminModuleUserGroupDistribution->validates(array('fieldList' => $valid_user_group_field_list));

                if ($validate_admin_module_user && $validate_admin_module_user_profile && $validate_admin_module_user_group) {

                    $licensing_years = $this->LookupCurrentLicensingYear->find('first', array('conditions' => array('LookupCurrentLicensingYear.is_current_year' => 1)));
                    $current_year = $licensing_years['LookupCurrentLicensingYear']['licensing_year'];
                    $user_group_id = -1;
                    $flag = 0;

                    $user_group_ids = $this->request->data['AdminModuleUserGroupDistribution']['user_group_id'];
                    $committe_member_type_id_new = $this->request->data['AdminModuleUser']['committe_member_type_id'];
                    $modified_by = $this->Session->read('User.Name');
                    $data_to_save_in_user = array(
                        'user_group_id' => (int) $user_group_id,
                        'committe_member_type_id' => (int) $committe_member_type_id_new,
                        'modified_date' => "'" . date('Y-m-d H:i:s') . "'",
                        'modified_by' => "'" . $modified_by . "'"
                    );

                    $this->AdminModuleUser->recursive = 1;
                    $user_infos = $this->AdminModuleUser->find('all', array('conditions' => array('AdminModuleUserGroupDistribution.user_group_id' => $user_group_id)));

                    $editor_user_id_with_full_name = '';
                    $is_editor_exists = false;

                    foreach ($user_infos as $user_info) {
                        $committe_member_type_id = $user_info['AdminModuleUser']['committe_member_type_id'];
                        if ($committe_member_type_id == '2') {
                            $is_editor_exists = true;
                            $editor_user_id_with_full_name = $user_info['AdminModuleUserProfile']['full_name_of_user'] . ' (' . $user_info['AdminModuleUser']['user_name'] . ')';
                            break;
                        }
                    }

                    if (in_array(2, $user_group_id)) {
                        $short_name_of_org = $this->request->data['BasicModuleBasicInformation'] ['short_name_of_org'];
                        $org_full_name = $this->request->data['BasicModuleBasicInformation']['full_name_of_org'];
                        $full_name_of_user = $this->request->data['BasicModuleBasicInformation']['full_name_of_authorized_person'];
                        $designation_of_user = $this->request->data['BasicModuleBasicInformation']['designation_of_authorized_person'];
                        $div_name_in_office = '';

                        $data_to_save_in_basic_info = array(
                            'short_name_of_org' => $short_name_of_org,
                            'full_name_of_org' => $org_full_name,
                            'licensing_year' => $current_year,
                            'name_of_authorized_person' => $full_name_of_user,
                            'designation_of_authorized_person' => $designation_of_user,
                            'licensing_state_id' => 0
                        );
                        $this->BasicModuleBasicInformation->create();
                        $saved_in_basic_info = $this->BasicModuleBasicInformation->save($data_to_save_in_basic_info);
                        $org_id = $saved_in_basic_info['BasicModuleBasicInformation']['id'];
                    } else {
                        $org_id = '';
                        $org_full_name = $this->request->data['AdminModuleUserProfile']['org_name'];
                        $full_name_of_user = $this->request->data['AdminModuleUserProfile']['full_name_of_user'];
                        $designation_of_user = $this->request->data['AdminModuleUserProfile']['designation_of_user'];
                        $div_name_in_office = $this->request->data['AdminModuleUserProfile']['div_name_in_office'];
                    }
                    $email_id = $this->request->data['AdminModuleUserProfile']['email'];
                    $mobile_no = $this->request->data['AdminModuleUserProfile']['mobile_no'];

                    if ($validate_admin_module_user) {
                        if (($is_editor_exists) && ($committe_member_type_id_new == '2')) {
                            $flag = 0;
                            $message = $editor_user_id_with_full_name . ' is already assigned as Editor';
                        } else {
                            $user_condition = array('AdminModuleUser.id' => $user_id);
                            $this->AdminModuleUser->updateAll($data_to_save_in_user, $user_condition);
                        }

                        $data_to_save_in_user_profile = array('full_name_of_user' => "'" . $full_name_of_user . "'",
                            'designation_of_user' => "'" . $designation_of_user . "'",
                            'div_name_in_office' => "'" . $div_name_in_office . "'",
                            'org_name' => "'" . $org_full_name . "'",
                            'email' => "'" . $email_id . "'",
                            'mobile_no' => "'" . $mobile_no . "'"
                        );

                        if ($validate_admin_module_user_profile) {
                            $profile_condition = array('AdminModuleUserProfile.user_id' => $user_id);
                            $user_profile_info = $this->AdminModuleUserProfile->find('first', array('conditions' => $profile_condition));

                            if (!empty($user_profile_info)) {
                                $this->AdminModuleUserProfile->updateAll($data_to_save_in_user_profile, $profile_condition);
                            } else {
                                $data_to_save_in_user_profile = array('user_id' => $user_id,
                                    'full_name_of_user' => $full_name_of_user,
                                    'designation_of_user' => $designation_of_user,
                                    'div_name_in_office' => $div_name_in_office,
                                    'org_name' => $org_full_name,
                                    'email' => $email_id,
                                    'mobile_no' => $mobile_no
                                );
                                $this->AdminModuleUserProfile->create();
                                $this->AdminModuleUserProfile->save($data_to_save_in_user_profile);
                            }
                            $flag == 1;

                            $message_body = 'Congratulation! Your user information of MFI DBMS has been updated.' . "\r\n" . "\r\n" . 'You can login to MFI DBMS System using' . " \r\n" . "\r\n" . 'Thanks' . "\r\n" . 'Microcredit Regulatory Authority';
                            $Email = new CakeEmail('gmail');
                            $Email->from(array('mfi.dbms.mra@gmail.com' => 'Message From MFI DBMS of MRA'))
                                    ->to($email_id)
                                    ->subject('New user added in MFI-DBMS');
                            if ($Email->send($message_body)) {
                                $flag = 1;
                                $this->redirect(array('action' => 'view?is_committee_group=' . $is_committee_group));
                            } else {
                                $message = 'User creation and mail sending failed';
                                $flag = 0;
                            }
                        } else {
                            $errors = $this->AdminModuleUserProfile->validationErrors;
                            $message = "Invalid User Profile Information";
                            $flag = 0;
                        }
                    } else {
                        $errors = $this->AdminModuleUser->validationErrors;
                        $message = "Invalid User Information";
                        $flag = 0;
                    }
                } else {
                    $message = 'Fill up all the required fieds';
                }
                if ($flag == 0) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => $message
                    );
                    $this->set(compact('msg'));
                }
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => $message
            );
            $this->set(compact('msg'));
        }
    }

    public function branch_user_details($user_id = null) {

        $this->AdminModuleUser->recursive = 0;
        $user_info_details = $this->AdminModuleUser->findById($user_id);

        if (!$user_info_details) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid user information!'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->set(compact('user_info_details'));
    }

    public function details($is_committee_group = null, $user_id = null, $user_group_id = null) {
        $this->loadModel('AdminModuleUserGroup');
        if ($is_committee_group == '0') {
            $user_group_options = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name')));
        } else if ($is_committee_group == '1') {
            $user_group_options = $this->AdminModuleUserGroup->find('list', array('fields' => array('AdminModuleUserGroup.id', 'AdminModuleUserGroup.group_name'), 'conditions' => array('AdminModuleUserGroup.id' => $this->committee_group_ids)));
        }
        $this->AdminModuleUser->recursive = 0;
        $user_info_details = $this->AdminModuleUser->find('first', array('conditions' => array('AdminModuleUser.id' => $user_id, 'AdminModuleUserGroup.id' => $user_group_id)));

        if (!$user_info_details) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... . . !',
                'msg' => 'Invalid user information!'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->set(compact('user_info_details', 'is_committee_group'));
    }

    public function user_details() {
        $user_id = $this->Session->read('User.Id');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($user_id)) {
            $this->set(compact('user_id'));
            return;
        }
        $fields = array('AdminModuleUser.user_name', 'AdminModuleUserProfile.org_id', 'AdminModuleUserProfile.full_name_of_user',
            'AdminModuleUserProfile.designation_of_user', 'AdminModuleUserProfile.div_name_in_office',
            'AdminModuleUserProfile.org_name', 'AdminModuleUserProfile.mobile_no', 'AdminModuleUserProfile.email',
            'LookupUserCommitteeMemberType.committee_member_type');

        $user_infos = $this->AdminModuleUser->findById($user_id, $fields);

        $org_id = $user_infos['AdminModuleUserProfile']['org_id'];
        $fields = array('full_name_of_org', 'short_name_of_org', 'license_no', 'form_serial_no', 'date_of_application');
        if (!empty($user_group_ids) && (in_array('2', $user_group_ids) || in_array('3', $user_group_ids) || in_array('5', $user_group_ids))) {
            $this->loadModel('BasicModuleBasicInformation');
            $this->BasicModuleBasicInformation->recursive = -1;
            $orgDetails = $this->BasicModuleBasicInformation->findById($org_id, $fields);
        } elseif (!empty($user_group_ids) && in_array('4', $user_group_ids)) {
            $this->loadModel('CDBNonMfiBasicInfo');
            $this->CDBNonMfiBasicInfo->recursive = 0;
            $orgDetails = $this->CDBNonMfiBasicInfo->findById($org_id);
        }
        $this->set(compact('user_id', 'user_infos', 'orgDetails'));
    }

    public function recover_password() {

        if ($this->request->is('post')) {
            $new_data = $this->request->data;
            $userid = $new_data['AdminModuleUser']['user_name'];
            $email = $new_data['AdminModuleUser']['email'];
            $this->loadModel('AdminModuleUserProfile');
            $values = $this->AdminModuleUserProfile->find('first', array('conditions' => array('OR' => array('AdminModuleUser.user_name' => $userid, 'AdminModuleUserProfile.email' => $email)), 'recursive' => 0));
            $flag = 0;

            if (!empty($values)) {
                $subject = 'Password Reset';
                $password = $this->randPassword(8);
                $new_password_hash = Security::hash($password, 'sha256', true);
                $password_changed = $this->AdminModuleUser->updateAll(array('user_passwrd' => "'" . $new_password_hash . "'"), array('AdminModuleUser.user_name' => $userid));

                if ($password_changed) {
                    $flag = 1;
                }

                $message_body = 'Dear User,' . "\r\n" . "\r\n" . 'As per your request, your password has been reset. Please login to the MFI-DBMS system using' . "\r\n" .
                        "User Id: $userid " . "\r\n" . "Password: $password " . "\r\n" . "\r\n" . 'Thanks' .
                        "\r\n" . 'Microcredit Regulatory Authority';
                $is_sent_mail = false;
                if ($flag == 1) {
                    $is_sent_mail = $this->send_mail($subject, $message_body, $email);
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Please enter a valid UserId or E-Mail address'
                );
                $this->set(compact('msg'));
            }
        }
    }

}
