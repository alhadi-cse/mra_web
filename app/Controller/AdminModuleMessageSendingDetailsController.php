<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AdminModuleMessageSendingDetailsController extends AppController {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');

    public function view($message_sent_option = null, $licensed_mfi = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        
        if (in_array(2,$user_group_id)) {
            $org_id = $this->Session->read('Org.Id');

            if (!empty($org_id))
                $condition = array('AdminModuleMessageSendingDetail.org_id' => $org_id);
            else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid Organization Information !'
                );
                $this->set(compact('msg'));
                return;
            }
        } elseif ($user_group_id > 2) {           //`mail_to``mail_cc``mail_bcc`//mail_receiver//mail_sender//mail_creator
            $user_id = $this->Session->read('User.Id');
            if (!empty($user_id)) {
                $this->loadModel('AdminModuleUserProfile');
                $user_email = $this->AdminModuleUserProfile->field('AdminModuleUserProfile.email', array('AdminModuleUserProfile.user_id' => $user_id));
                if (!empty($user_email)) {
                    $condition = array('OR' => array(array('AdminModuleMessageSendingDetail.mail_creator LIKE' => "%$user_id%"),
                            array('AdminModuleMessageSendingDetail.mail_sender LIKE' => "%$user_id%"),
                            array('AdminModuleMessageSendingDetail.mail_receiver LIKE' => "%$user_id%"),
                            array('AdminModuleMessageSendingDetail.mail_to LIKE' => "%$user_email%"),
                            array('AdminModuleMessageSendingDetail.mail_cc LIKE' => "%$user_email%"),
                            array('AdminModuleMessageSendingDetail.mail_bcc LIKE' => "%$user_email%")));
                } else {
                    $condition = array('OR' => array(array('AdminModuleMessageSendingDetail.mail_creator LIKE' => "%$user_id%"),
                            array('AdminModuleMessageSendingDetail.mail_sender LIKE' => "%$user_id%"),
                            array('AdminModuleMessageSendingDetail.mail_receiver LIKE' => "%$user_id%")));
                }
            } else {
                $msg = array(
                    'type' => 'warning',
                    'title' => 'Warning... . . !',
                    'msg' => 'Invalid user information !'
                );
                $this->set(compact('msg'));
                return;
            }
        }

        if (empty($message_sent_option))
            $message_sent_option = $this->request->query('message_sent_option');

        //$licensed_mfi = !isset($licensed_mfi) || $licensed_mfi == 1;
        if (!isset($licensed_mfi)) {
            $licensed_mfi = $this->request->query('licensed_mfi');
        }

        $redirect_url = array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'view', '?' => array('message_sent_option' => $message_sent_option, 'licensed_mfi' => $licensed_mfi));
        $this->Session->write('Current.RedirectUrl', $redirect_url);

        $this->set(compact('user_group_id', 'licensed_mfi'));
        
        
        if ($this->request->is('post')) {
            $option = $this->request->data['AdminModuleMessageSendingDetail']['search_option'];
            $keyword = $this->request->data['AdminModuleMessageSendingDetail']['search_keyword'];
            
            if (!empty($condition))
                $condition = array_merge($condition, array("$option LIKE '%$keyword%'"));
            else
                $condition = array("$option LIKE '%$keyword%'");
        }

        if (isset($message_sent_option)) {
            if (!empty($condition))
                $condition_sent = array_merge($condition, array('AdminModuleMessageSendingDetail.mail_is_sent' => $message_sent_option));
            else
                $condition_not_sent = array('AdminModuleMessageSendingDetail.mail_is_sent' => $message_sent_option);
        }
        else {
            if (!empty($condition)) {
                $condition_sent = array_merge($condition, array('AdminModuleMessageSendingDetail.mail_is_sent' => 1));
                $condition_not_sent = array_merge($condition, array('AdminModuleMessageSendingDetail.mail_is_sent !=' => 1));
            } else {
                $condition_sent = array('AdminModuleMessageSendingDetail.mail_is_sent' => 1);
                $condition_not_sent = array('AdminModuleMessageSendingDetail.mail_is_sent !=' => 1);
            }
        }


        $fields = array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.form_serial_no', 'BasicModuleBasicInformation.full_name_of_org', 'BasicModuleBasicInformation.short_name_of_org', 'BasicModuleBasicInformation.license_no',
            'AdminModuleMessageSendingDetail.id', 'AdminModuleMessageSendingDetail.mail_creation_date', 'AdminModuleMessageSendingDetail.mail_to', 'AdminModuleMessageSendingDetail.mail_subject', 'AdminModuleMessageSendingDetail.mail_sending_date');

        $this->Paginator->settings = array('fields' => $fields, 'recursive' => 0, 'limit' => 10, 'order' => array('form_serial_no' => 'ASC'));

        $this->Paginator->settings['conditions'] = $condition_not_sent;
        $values_message_not_sent = $this->Paginator->paginate('AdminModuleMessageSendingDetail');

        $this->Paginator->settings['conditions'] = $condition_sent;
        $values_message_sent = $this->Paginator->paginate('AdminModuleMessageSendingDetail');

        $this->set(compact('values_message_sent', 'values_message_not_sent'));
    }

    public function message_new($org_id = null, $message_no = null, $message_sent_option = null, $licensed_mfi = null) {

        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(2,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licensed_mfi = !isset($licensed_mfi) || $licensed_mfi == 1;

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        if ($this->request->is(array('post', 'put')) && !empty($this->request->data['AdminModuleMessageSendingDetail'])) {
            $mail_details = $this->request->data['AdminModuleMessageSendingDetail'];

            if (!empty($mail_details['mail_from_email']) && !empty($mail_details['mail_to'])) {

                $mail_from = array($mail_details['mail_from_email'] => $mail_details['mail_from_details']);
                $mail_to = $mail_details['mail_to'];
                $mail_cc = $mail_details['mail_cc'];
                $mail_bcc = $mail_details['mail_bcc'];
                $mail_subject = $mail_details['mail_subject'];
                $mail_message = $mail_details['mail_message'];
//            $mail_attachments = array(
//                'example.txt' => array(
//                    'file' => 'full/path/to/example.txt',
//                    'mimetype' => 'text/plain'
//                ),
//                'my_image.jpef' => array(
//                    'file' => '/full/path/to/my_image.jpeg',
//                    'mimetype' => 'image/jpeg'
//                )
//            );


                $mail_to = str_replace(' ', '', $mail_to);
                $mail_to = str_replace(',', ';', $mail_to);
                $mail_to = explode(';', $mail_to);
                $mail_to = array_values($mail_to);

                if (empty($mail_subject))
                    $mail_subject = 'Subject unknown !';

                $email = new CakeEmail('gmail');

                $email->emailFormat('text');
                $email->from($mail_from);
                $email->to($mail_to);

                if (!empty($mail_cc)) {
                    $mail_cc = str_replace(' ', '', $mail_cc);
                    $mail_cc = str_replace(',', ';', $mail_cc);
                    $mail_cc = explode(';', $mail_cc);
                    $mail_cc = array_values($mail_cc);
                    $email->cc($mail_cc);
                }

                if (!empty($mail_bcc)) {
                    $mail_bcc = str_replace(' ', '', $mail_bcc);
                    $mail_bcc = str_replace(',', ';', $mail_bcc);
                    $mail_bcc = explode(';', $mail_bcc);
                    $mail_bcc = array_values($mail_bcc);
                    $email->bcc($mail_bcc);
                }

                $email->subject($mail_subject);

//                if(!empty($mail_attachments))
//                    $email->attachments($mail_attachments);

                $is_send = $email->send($mail_message);

                if ($is_send) {
                    $mail_details['mail_is_sent'] = 1;
                    $mail_details['mail_sending_date'] = date('Y-m-d');
                    $mail_details['mail_sender'] = $this->Session->read('User.Id');
                } else {
                    $mail_details['mail_is_sent'] = 0;
                }

                $this->AdminModuleMessageSendingDetail->create();
                $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

                if ($is_send && $done) {
                    $this->redirect(array('action' => 'message_send_report', $done['AdminModuleMessageSendingDetail']['id']));
                } else {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... !',
                        'msg' => 'Message Sending/Saving Failed !'
                    );
                    $this->set(compact('msg'));
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... !',
                    'msg' => 'Invalid Message Sender or Receiver !'
                );
                $this->set(compact('msg'));
            }
        }

        $orgName = '';
        $mail_from_email = 'mfi.dbms.mra@gmail.com';
        $mail_from_details = 'Message From MFI DBMS of MRA';
        $mail_to = $mail_subject = $mail_body = '';

        if (!empty($org_id)) {
            $this->loadModel('BasicModuleBasicInformation');
            $orgDetails = $this->BasicModuleBasicInformation->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));

            if (isset($orgDetails['BasicModuleBasicInformation'])) {
                $orgDetails = $orgDetails['BasicModuleBasicInformation'];

                $org_id = $orgDetails['id'];
                $orgName = $orgDetails['short_name_of_org'];
                $orgFullName = $orgDetails['full_name_of_org'];
                $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
            }

            $this->loadModel('AdminModuleUserProfile');
            $mail_to = $this->AdminModuleUserProfile->field('email', array('org_id' => $org_id));
        }

        if (isset($message_no)) {
            $this->loadModel('AdminModuleMessageDetail');
            $messageDetails = $this->AdminModuleMessageDetail->find('first', array('fields' => array('message_title', 'message_details'), 'conditions' => array('message_no' => $message_no), 'recursive' => -1));
            if (!empty($messageDetails['AdminModuleMessageDetail'])) {
                $mail_subject = $messageDetails['AdminModuleMessageDetail']['message_title'];
                $mail_body = $messageDetails['AdminModuleMessageDetail']['message_details'];
            }

//            $mail_subject = $this->AdminModuleMessageDetail->field('message_title', array('AdminModuleMessageDetail.message_no' => $message_no));
//            $mail_body = $this->AdminModuleMessageDetail->field('message_details', array('AdminModuleMessageDetail.message_no' => $message_no));
        }

        $this->set(compact('org_id', 'orgName', 'mail_from_email', 'mail_from_details', 'mail_to', 'mail_subject', 'mail_body', 'redirect_url'));
    }
        
    public function automatic_message($org_id = null, $message_no = null, $message_sent_option = null, $licensed_mfi = null) {
        $user_group_id = $this->Session->read('User.GroupIds');
        if (empty($user_group_id) || !(in_array(1,$user_group_id) || in_array(2,$user_group_id))) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licensed_mfi = !isset($licensed_mfi) || $licensed_mfi == 1;

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        if ($this->request->is(array('post', 'put')) && !empty($this->request->data['AdminModuleMessageSendingDetail'])) {
            //$mail_details = $this->request->data['AdminModuleMessageSendingDetail'];

            if (!empty($mail_details['mail_from_email']) && !empty($mail_details['mail_to'])) {

                $mail_from = array($mail_details['mail_from_email'] => $mail_details['mail_from_details']);
                $mail_to = $mail_details['mail_to'];
                $mail_cc = $mail_details['mail_cc'];
                $mail_bcc = $mail_details['mail_bcc'];
                $mail_subject = $mail_details['mail_subject'];
                $mail_message = $mail_details['mail_message'];
//            $mail_attachments = array(
//                'example.txt' => array(
//                    'file' => 'full/path/to/example.txt',
//                    'mimetype' => 'text/plain'
//                ),
//                'my_image.jpef' => array(
//                    'file' => '/full/path/to/my_image.jpeg',
//                    'mimetype' => 'image/jpeg'
//                )
//            );


                $mail_to = str_replace(' ', '', $mail_to);
                $mail_to = str_replace(',', ';', $mail_to);
                $mail_to = explode(';', $mail_to);
                $mail_to = array_values($mail_to);

                if (empty($mail_subject))
                    $mail_subject = 'Subject unknown !';

                $email = new CakeEmail('gmail');

                $email->emailFormat('text');
                $email->from($mail_from);
                $email->to($mail_to);

                if (!empty($mail_cc)) {
                    $mail_cc = str_replace(' ', '', $mail_cc);
                    $mail_cc = str_replace(',', ';', $mail_cc);
                    $mail_cc = explode(';', $mail_cc);
                    $mail_cc = array_values($mail_cc);
                    $email->cc($mail_cc);
                }

                if (!empty($mail_bcc)) {
                    $mail_bcc = str_replace(' ', '', $mail_bcc);
                    $mail_bcc = str_replace(',', ';', $mail_bcc);
                    $mail_bcc = explode(';', $mail_bcc);
                    $mail_bcc = array_values($mail_bcc);
                    $email->bcc($mail_bcc);
                }

                $email->subject($mail_subject);

//                if(!empty($mail_attachments))
//                    $email->attachments($mail_attachments);

                $is_send = $email->send($mail_message);

                if ($is_send) {
                    $mail_details['mail_is_sent'] = 1;
                    $mail_details['mail_sending_date'] = date('Y-m-d');
                    $mail_details['mail_sender'] = $this->Session->read('User.Id');
                } else {
                    $mail_details['mail_is_sent'] = 0;
                }

                $this->AdminModuleMessageSendingDetail->create();
                $done = $this->AdminModuleMessageSendingDetail->save($mail_details);

                if ($is_send && $done) {
                    $this->redirect(array('action' => 'message_send_report', $done['AdminModuleMessageSendingDetail']['id']));
                } else {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... !',
                        'msg' => 'Message Sending/Saving Failed !'
                    );
                    $this->set(compact('msg'));
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... !',
                    'msg' => 'Invalid Message Sender or Receiver !'
                );
                $this->set(compact('msg'));
            }
        }

        $orgName = '';
        $mail_from_email = 'mfi.dbms.mra@gmail.com';
        $mail_from_details = 'Message From MFI DBMS of MRA';
        $mail_to = $mail_subject = $mail_body = '';

        if (!empty($org_id)) {
            $this->loadModel('BasicModuleBasicInformation');
            $orgDetails = $this->BasicModuleBasicInformation->find('first', array('conditions' => array('BasicModuleBasicInformation.id' => $org_id), 'recursive' => -1));

            if (isset($orgDetails['BasicModuleBasicInformation'])) {
                $orgDetails = $orgDetails['BasicModuleBasicInformation'];

                $org_id = $orgDetails['id'];
                $orgName = $orgDetails['short_name_of_org'];
                $orgFullName = $orgDetails['full_name_of_org'];
                $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
            }

            $this->loadModel('AdminModuleUserProfile');
            $mail_to = $this->AdminModuleUserProfile->field('email', array('org_id' => $org_id));
        }

        if (isset($message_no)) {
            $this->loadModel('AdminModuleMessageDetail');
            $messageDetails = $this->AdminModuleMessageDetail->find('first', array('fields' => array('message_title', 'message_details'), 'conditions' => array('message_no' => $message_no), 'recursive' => -1));
            if (!empty($messageDetails['AdminModuleMessageDetail'])) {
                $mail_subject = $messageDetails['AdminModuleMessageDetail']['message_title'];
                $mail_body = $messageDetails['AdminModuleMessageDetail']['message_details'];
            }

//            $mail_subject = $this->AdminModuleMessageDetail->field('message_title', array('AdminModuleMessageDetail.message_no' => $message_no));
//            $mail_body = $this->AdminModuleMessageDetail->field('message_details', array('AdminModuleMessageDetail.message_no' => $message_no));
        }

        $this->set(compact('org_id', 'orgName', 'mail_from_email', 'mail_from_details', 'mail_to', 'mail_subject', 'mail_body', 'redirect_url'));
    
    }

    public function message_send($message_id = null, $re_send = null) {

        if (empty($message_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        if ($this->request->is(array('post', 'put')) && !empty($this->request->data['AdminModuleMessageSendingDetail'])) {
            $mail_details = $this->request->data['AdminModuleMessageSendingDetail'];

            if (!empty($mail_details['mail_from_email']) && !empty($mail_details['mail_to'])) {

                $mail_from = array($mail_details['mail_from_email'] => $mail_details['mail_from_details']);
                $mail_to = $mail_details['mail_to'];
                $mail_cc = $mail_details['mail_cc'];
                $mail_bcc = $mail_details['mail_bcc'];
                $mail_subject = $mail_details['mail_subject'];
                $mail_message = $mail_details['mail_message'];
//            $mail_attachments = array(
//                'example.txt' => array(
//                    'file' => 'full/path/to/example.txt',
//                    'mimetype' => 'text/plain'
//                ),
//                'my_image.jpef' => array(
//                    'file' => '/full/path/to/my_image.jpeg',
//                    'mimetype' => 'image/jpeg'
//                )
//            );


                $mail_to = str_replace(' ', '', $mail_to);
                $mail_to = str_replace(',', ';', $mail_to);
                $mail_to = explode(';', $mail_to);
                $mail_to = array_values($mail_to);

                if (empty($mail_subject))
                    $mail_subject = 'Subject unknown !';

                $email = new CakeEmail('gmail');

                $email->emailFormat('text');
                $email->from($mail_from);
                $email->to($mail_to);

                //$email->from('<no-reply@noreply.com>');

                if (!empty($mail_cc)) {
                    $mail_cc = str_replace(' ', '', $mail_cc);
                    $mail_cc = str_replace(',', ';', $mail_cc);
                    $mail_cc = explode(';', $mail_cc);
                    $mail_cc = array_values($mail_cc);
                    $email->cc($mail_cc);
                }

                if (!empty($mail_bcc)) {
                    $mail_bcc = str_replace(' ', '', $mail_bcc);
                    $mail_bcc = str_replace(',', ';', $mail_bcc);
                    $mail_bcc = explode(';', $mail_bcc);
                    $mail_bcc = array_values($mail_bcc);
                    $email->bcc($mail_bcc);
                }

                $email->subject($mail_subject);

//                if(!empty($mail_attachments))
//                    $email->attachments($mail_attachments);

                $is_send = $email->send($mail_message);

                if ($is_send) {
                    $mail_details['mail_is_sent'] = 1;
                    $mail_details['mail_sending_date'] = date('Y-m-d');
                    $mail_details['mail_sender'] = $this->Session->read('User.Id');

                    $this->AdminModuleMessageSendingDetail->id = $message_id;
                    $this->AdminModuleMessageSendingDetail->save($mail_details);

                    $this->redirect(array('action' => 'message_send_report', $message_id));
                    //$this->redirect($redirect_url);
                } else {
                    $mail_details['mail_is_sent'] = 0;

                    $this->AdminModuleMessageSendingDetail->id = $message_id;
                    $this->AdminModuleMessageSendingDetail->save($mail_details);

                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... !',
                        'msg' => 'Message Sending Failed !'
                    );
                    $this->set(compact('msg'));
                }
            } else {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... !',
                    'msg' => 'Invalid Message Sender or Receiver !'
                );
                $this->set(compact('msg'));
            }
        }

        $this->set(compact('re_send', 'redirect_url'));

        $messageDetails = $this->AdminModuleMessageSendingDetail->find('first', array('conditions' => array('AdminModuleMessageSendingDetail.id' => $message_id), 'recursive' => 0));

        if (!empty($messageDetails)) {
            if (isset($messageDetails['BasicModuleBasicInformation'])) {
                $orgDetails = $messageDetails['BasicModuleBasicInformation'];

                $org_id = $orgDetails['id'];
                $orgName = $orgDetails['short_name_of_org'];
                $orgFullName = $orgDetails['full_name_of_org'];
                $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
            } else
                $org_id = $orgName = '';

            $this->set(compact('org_id', 'orgName'));

            unset($messageDetails['BasicModuleBasicInformation']);
            if (!$this->request->data)
                $this->request->data = $messageDetails;
        }
    }

    public function message_modify($message_id = null) {

        if (empty($message_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Organization information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $licensed_mfi = !isset($licensed_mfi) || $licensed_mfi == 1;

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        if ($this->request->is(array('post', 'put')) && !empty($this->request->data['AdminModuleMessageSendingDetail'])) {
            $mail_details = $this->request->data['AdminModuleMessageSendingDetail'];

            $this->AdminModuleMessageSendingDetail->id = $message_id;
            $this->AdminModuleMessageSendingDetail->save($mail_details);

            $this->redirect($redirect_url);
        }

        $messageDetails = $this->AdminModuleMessageSendingDetail->find('first', array('conditions' => array('AdminModuleMessageSendingDetail.id' => $message_id), 'recursive' => 0));

        if (!empty($messageDetails)) {
            if (isset($messageDetails['BasicModuleBasicInformation'])) {
                $orgDetails = $messageDetails['BasicModuleBasicInformation'];

                $org_id = $orgDetails['id'];
                $orgName = $orgDetails['short_name_of_org'];
                $orgFullName = $orgDetails['full_name_of_org'];
                $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
            } else
                $org_id = $orgName = '';

            $this->set(compact('org_id', 'orgName', 'redirect_url'));

            unset($messageDetails['BasicModuleBasicInformation']);
            if (!$this->request->data)
                $this->request->data = $messageDetails;
        }
    }

    public function message_send_report($message_id = null) {

        if (empty($message_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Message Details !'
            );
            $this->set(compact('msg'));
            //return;
        }

        $redirect_url = $this->Session->read('Current.RedirectUrl');
        if (empty($redirect_url))
            $redirect_url = array('action' => 'view');

        $orgName = '';
        if (!empty($message_id)) {
            $messageDetails = $this->AdminModuleMessageSendingDetail->find('first', array('conditions' => array('AdminModuleMessageSendingDetail.id' => $message_id), 'recursive' => 0));

            if (!empty($messageDetails)) {
                if (isset($messageDetails['BasicModuleBasicInformation'])) {
                    $orgDetails = $messageDetails['BasicModuleBasicInformation'];

                    $orgName = $orgDetails['short_name_of_org'];
                    $orgFullName = $orgDetails['full_name_of_org'];
                    $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
                }

                //$this->set(compact('org_id', 'redirect_url'));

                unset($messageDetails['BasicModuleBasicInformation']);
                $messageDetails = $messageDetails['AdminModuleMessageSendingDetail'];
            }
        } else {
            $messageDetails = null;
        }

        $this->set(compact('orgName', 'message_id', 'redirect_url', 'messageDetails'));
    }

    public function message_preview($id = null) {

        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $this->set(compact('id'));
    }

    public function message_details($message_id = null, $is_send = null) {

        if (empty($message_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Message Details !'
            );
            $this->set(compact('msg'));
            return;
        }

        $orgName = '';
        $messageDetails = $this->AdminModuleMessageSendingDetail->find('first', array('conditions' => array('AdminModuleMessageSendingDetail.id' => $message_id), 'recursive' => 0));

        if (!empty($messageDetails)) {
            if (isset($messageDetails['BasicModuleBasicInformation'])) {
                $orgDetails = $messageDetails['BasicModuleBasicInformation'];

                $org_id = $orgDetails['id'];
                $orgName = $orgDetails['short_name_of_org'];
                $orgFullName = $orgDetails['full_name_of_org'];
                $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
            }

            //$this->set(compact('org_id', 'redirect_url'));

            unset($messageDetails['BasicModuleBasicInformation']);
            $messageDetails = $messageDetails['AdminModuleMessageSendingDetail'];
        }

        $this->set(compact('orgName', 'is_send', 'messageDetails'));
    }

    public function details($message_id = null, $is_send = null) {

        if (empty($message_id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Message Details !'
            );
            $this->set(compact('msg'));
            return;
        }

        $orgName = '';
        $messageDetails = $this->AdminModuleMessageSendingDetail->findById($message_id);

        if (!empty($messageDetails)) {
            if (isset($messageDetails['BasicModuleBasicInformation'])) {
                $orgDetails = $messageDetails['BasicModuleBasicInformation'];

                $org_id = $orgDetails['id'];
                $orgName = $orgDetails['short_name_of_org'];
                $orgFullName = $orgDetails['full_name_of_org'];
                $orgName = ((!empty($orgName) && !empty($orgFullName)) ? "$orgFullName ($orgName)" : "$orgFullName$orgName");
            }

            unset($messageDetails['BasicModuleBasicInformation']);
            $messageDetails = $messageDetails['AdminModuleMessageSendingDetail'];
        }

        $this->set(compact('orgName', 'is_send', 'messageDetails'));
    }

    public function preview($id = null) {

        if (empty($id)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid Payment Information !'
            );
            $this->set(compact('msg'));
            return;
        }

//        if (empty($payment_type_id))
//            $payment_type_id = $this->Session->read('Payment.TypeId');
        //$this->loadModel('AdminModuleMessageSendingDetail');
        $messageDetails = $this->AdminModuleMessageSendingDetail->findById($id);
        if (!$messageDetails) {
            throw new NotFoundException('Invalid Information');
        }
        $this->set(compact('messageDetails'));
    }

}
