<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AdminModuleMfiSignUpDetailsController extends AppController {

    var $components = array('Captcha.Captcha' => array('Model' => 'AdminModuleMfiSignUpDetail', 'field' => 'captcha'));
    var $uses = array('AdminModuleMfiSignUpDetail');
    public $helpers = array('Form', 'Html', 'Js', 'Session', 'Captcha.Captcha');

    function captcha() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Captcha->create();
    }

    public function sign_up() {
        
        try {
            $this->loadModel('AdminModuleUser');
            $this->loadModel('AdminModuleUserProfile');
            $this->loadModel('LookupBasicPrimaryRegistrationAct');
            $this->loadModel('BasicModulePrimaryRegActDetail');

            $district_options = $this->AdminModuleMfiSignUpDetail->LookupAdminBoundaryDistrict->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.district_name')));
            //$primary_reg_act_options = $this->AdminModuleMfiSignUpDetail->LookupBasicRegistrationAuthority->find('list', array('fields' => array('LookupBasicRegistrationAuthority.id', 'LookupBasicRegistrationAuthority.registration_authority')));
            $primary_reg_act_options = $this->LookupBasicPrimaryRegistrationAct->find('list', array('fields' => array('LookupBasicPrimaryRegistrationAct.id', 'LookupBasicPrimaryRegistrationAct.primary_registration_act')));
            $org_type_options = array('1' => 'New License Applicant', '2' => 'Licensed MFI', '3' => 'Non-MFI (NGO, Banks, Others)');
            $this->set(compact('district_options', 'org_type_options', 'primary_reg_act_options'));

            if ($this->request->is('post')) {
                if (!empty($this->request->data['AdminModuleMfiSignUpDetail'])) {
                    $new_data = $this->request->data['AdminModuleMfiSignUpDetail'];
                    
                    $this->layout = 'mra_master';
                    $this->AdminModuleMfiSignUpDetail->setCaptcha('captcha', $this->Captcha->getCode('AdminModuleMfiSignUpDetail.captcha'));
                    $this->AdminModuleMfiSignUpDetail->set($this->request->data);

                    if (!empty($new_data['org_type_id'])) {
                        switch ($new_data['org_type_id']) {
                            case '1':
                                $this->AdminModuleMfiSignUpDetail->validate = array('district_id' => array(
                                        'required' => array(
                                            'rule' => array('notBlank'),
                                            'message' => 'A district is required'
                                        )
                                ));
                                break;

                            case '2':
                                $this->AdminModuleMfiSignUpDetail->validate = array('license_no' => array(
                                        'required' => array(
                                            'rule' => array('notBlank'),
                                            'message' => 'A License No. is required'
                                        )
                                ));
                                break;

                            default:
                                break;
                        }
                    }

                    if ($this->AdminModuleMfiSignUpDetail->validates()) {
                        $new_email = $new_data['email'];
                        $new_mobile_no = $new_data['mobile_no'];

                        $orgs_is_exist = $this->AdminModuleMfiSignUpDetail->hasAny(array('or' => array('AdminModuleMfiSignUpDetail.email' => $new_email, 'AdminModuleMfiSignUpDetail.mobile_no' => $new_mobile_no)));
                        $orgs_in_users_is_exist = $this->AdminModuleUser->hasAny(array('or' => array('AdminModuleUser.user_name' => $new_email)));
                        $orgs_in_user_profiles_is_exist = $this->AdminModuleUserProfile->hasAny(array('or' => array('AdminModuleUserProfile.email' => $new_email, 'AdminModuleUserProfile.mobile_no' => $new_mobile_no)));

                        if ($orgs_is_exist || $orgs_in_users_is_exist || $orgs_in_user_profiles_is_exist) {
                            $flag = 0;
                            $message = 'E-mail ID or Mobile no. already used by an Organization !';
                        } elseif (!empty($new_data['primary_reg_act_id'])) {
                            $primary_reg_act_ids = $new_data['primary_reg_act_id'];
                            unset($new_data['primary_reg_act_id']);

                            $this->AdminModuleMfiSignUpDetail->create();
                            $saved_signup_data = $this->AdminModuleMfiSignUpDetail->save($new_data);

                            if ($saved_signup_data) {
                                $sign_up_org_id = $saved_signup_data['AdminModuleMfiSignUpDetail']['id'];

                                $rc = 0;
                                $reg_act_data = array();
                                foreach ($primary_reg_act_ids as $primary_reg_act_id) {
                                    $reg_act_data[$rc++] = array('sign_up_org_id' => $sign_up_org_id, 'primary_reg_act_id' => $primary_reg_act_id);
                                }

                                $this->BasicModulePrimaryRegActDetail->create();
                                $this->BasicModulePrimaryRegActDetail->saveAll($reg_act_data);
                                $flag = 1;
                              
                                $subject = 'Initial Registration for License Application';
                                $message_body = 'Dear Applicant,'
                                        . "\r\n" . "\r\n"
                                        . 'Your Organization ' . $new_data['full_name_of_org']
                                        . ' has been successfully signed up in to the MFI-DBMS of MRA. You will be confirmed with user id and password by mail soon.'
                                        . "\r\n" . "\r\n"
                                        . 'Thanks' . "\r\n"
                                        . 'Microcredit Regulatory Authority';
                                
                                $is_sent_mail = $this->send_mail($subject, $message_body, $new_email);
                                
                                if ($is_sent_mail) {
                                    $this->redirect(array('controller' => 'Mrahome', 'action' => 'home'));
                                    return;
                                }
                                else {
                                    $flag = 0;
                                    $message = 'E-mail Sending Failed!';
                                }
                            } else {
                                $flag = 0;
                                $message = 'Insertion Failed!';
                            }
                        } else {
                            $flag = 0;
                            $message = 'Primary Registration Act Invalid/Not Selected !';
                        }
                    } else {
                        $flag = 0;
                        $message = 'Invalid Data ! ';
                    }

                    if ($flag == 0) {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => $message
                        );
                        $this->set(compact('msg'));
                    }
                }
            }
        } catch (Exception $ex) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Error Detected, ' . $ex->getMessage()
            );
            $this->set(compact('msg'));
        }
    }

    function index($id = null, $value = null) {
        $code = '';
        ?>
        <script type="text/javascript">
            alert('Hello!');
        </script>
        
        <script type="text/javascript">
            modal_open('license_app');
        </script>
        <?php

        return $code;
    }
}
