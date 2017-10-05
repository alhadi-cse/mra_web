<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
App::uses('PHPExcel', 'Lib/PHPExcelClasses');

//App::uses('PHPExcel', 'Lib/PHPExcelLibrary');
//App::uses('Vendor', 'PHPExcelClasses/PHPExcel');
//App::uses('PHPExcel', 'Vendor/PHPExcelClasses/PHPExcel');

class AppController extends Controller {

    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time', 'Session');
    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array('controller' => 'mrahome', 'action' => 'home', 'false'),
            'loginRedirect' => array('controller' => 'mrahome', 'action' => 'home', 'false'),
            'logoutRedirect' => array('controller' => 'mrahome', 'action' => 'home', 'false'), //array('controller' => 'AdminModuleUsers', 'action' => 'login'),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'AdminModuleUser',
                    'fields' => array(
                        'username' => 'user_name',
                        'password' => 'user_passwrd'
                    ),
                    'passwordHasher' => array(
                        'className' => 'Simple',
                        'hashType' => 'sha256'
                    )
                )
            )
        )
    );

    public function beforeFilter() {
        $list_of_allowable_action_before_login = array('home', 'home_info', 'sign_up', 'recover_password', 'captcha');
        $this->Auth->allow($list_of_allowable_action_before_login);
    }

    function randPassword($length = 8) {
        //$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$#%";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    function executeQuery($sql = null) {
        App::import('Model', 'ConnectionManager');
        $con = new ConnectionManager;
        $cn = $con->getDataSource('default');
        return $cn->query($sql);
    }

    function dtSource() {
        App::import('Model', 'ConnectionManager');
        $con = new ConnectionManager;
        $cn = $con->getDataSource('default');
        return $cn;
    }

    function serial_generator_by_count($model_name, $serial_field_name, $condition) {
        $total_serial_value = 0;
        $this->loadModel($model_name);
        $total_serial_no = $this->$model_name->find('first', array('fields' => "COUNT($serial_field_name) as total_$serial_field_name", 'conditions' => $condition, 'recursive' => -1));
        if (!empty($total_serial_no[0]['total_' . $serial_field_name])) {
            $total_serial_value = $total_serial_no[0]['total_' . $serial_field_name];
        }
        $new_serial_no = $total_serial_value + 1;
        return $new_serial_no;
    }

    function serial_generator_by_max($model_name, $serial_field_name, $condition) {
        $max_serial_value = 0;
        $this->loadModel($model_name);
        $max_serial_no = $this->$model_name->find('first', array('fields' => "MAX($serial_field_name) as max_$serial_field_name", 'conditions' => $condition, 'recursive' => -1));
        if (!empty($max_serial_no[0]['max_' . $serial_field_name])) {
            $max_serial_value = $max_serial_no[0]['max_' . $serial_field_name];
        }
        $new_serial_no = $max_serial_value + 1;
        return $new_serial_no;
    }

    function cardinal_to_ordinal_number($cardinal_number, $additional_text) {
        $this->loadModel('LookupSupervisionOrdinalNumber');
        $values = $this->LookupSupervisionOrdinalNumber->find('list', array('fields' => array('cardinal_number', 'ordinal_number')));
        $ordinal_number = '';
        if (!empty($values))
            $ordinal_number = $values[$cardinal_number];
        return $ordinal_number . ' ' . $additional_text;
    }

    function send_mail($mail_subject = null, $mail_message = null, $mail_to = null) {
        $org_id = $this->Session->read('Org.Id');
        if (empty($mail_to)) {
            $mail_to = 'mail.test@mra.gov.bd';
        }
        if (!empty($org_id)) {
            $this->loadModel('BasicModuleBranchInfo');
            $branch_infos = $this->BasicModuleBranchInfo->find('first', array('fields' => array('BasicModuleBranchInfo.email_address'), 'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => 1)));
            if (!empty($branch_infos)) {
                $mail_to = $branch_infos['BasicModuleBranchInfo']['email_address'];
            }
        }
        $internet_connected = @fsockopen('www.google.com', 80);
        $Email = new CakeEmail('gmail');
        $mail_from = 'mfi.dbms.mra@gmail.com';
        $Email->from(array($mail_from => "MFI-DBMS of MRA : $mail_subject"))
                ->to($mail_to)
                ->subject($mail_subject);
        $flag = false;
        $mail_is_sent = 0;
        if ($internet_connected && !empty($mail_to)) {
            $Email->send($mail_message);
            $flag = true;
            $mail_is_sent = 1;
        } elseif (empty($mail_to)) {
            $error_message = 'There is no recipient email id';
            $this->set(compact('error_message'));
        } elseif (!$internet_connected) {
            $error_message = 'Internet connection of web server is down';
            $this->set(compact('error_message'));
        } else {
            $error_message = 'Message sending failed';
            $this->set(compact('error_message'));
        }
        $mail_details = array();
        $mail_details['mail_subject'] = $mail_subject;
        $mail_details['mail_from'] = $mail_from;
        $mail_details['mail_to'] = $mail_to;
        $mail_details['mail_message'] = "$mail_message";
        $mail_details['mail_is_sent'] = $mail_is_sent;
        if (!empty($org_id)) {
            $mail_details['org_id'] = $org_id;
        }
        $this->loadModel('AdminModuleMessageSendingDetail');
        $this->AdminModuleMessageSendingDetail->create();
        $saved_data = $this->AdminModuleMessageSendingDetail->save($mail_details);
        return $flag;
    }

    function replace_escape_chars($str = null) {
        if (empty($str))
            return $str;
        //Replaces single quote (') with two (2) single quotes ('')
        //solves the problem of inserting, updating or selecting a text with single quote (')
        //i.e.: Cox's Bazar, World's economy etc.
        $str = str_replace("'", "''", $str);
        return $str;
    }

    function export_to_excel($fileName = null, $headerRow = null, $data = null, $fixedCol = 1, $fixedRow = 2) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);

        $filtColStart = $filtColEnd = null;

        for ($dc = 0; $dc < count($headerRow); $dc++) {
            if (is_array($headerRow[$dc])) {
                $col_width = (isset($headerRow[$dc]['width']) && is_numeric($headerRow[$dc]['width'])) ? $headerRow[$dc]['width'] : 20;

                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($dc)->setWidth($col_width);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($dc, 1)->getStyle()->getFont()->setName('Cambria')->setSize(11)->setBold(true);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($dc, 1)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dc, 1, $headerRow[$dc]['label']);

                if (isset($headerRow[$dc]['wrap']) && $headerRow[$dc]['wrap'])
                    $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($dc, 1)->getStyle()->getAlignment()->setWrapText(true);

                if (isset($headerRow[$dc]['filter']) && $headerRow[$dc]['filter']) {
                    if ($filtColStart == null)
                        $filtColStart = $filtColEnd = $dc;
                    else
                        $filtColEnd = $dc;
                }
            } else {
                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($dc)->setWidth(20);
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($dc, 1)->getStyle()->getFont()->setBold(true)->setName('Cambria')->setSize(11);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dc, 1, $headerRow[$dc]);
            }
        }
        //$objPHPExcel->getActiveSheet()->setAutoFilterByColumnAndRow(1, 1);
        if ($filtColStart != null)
            $objPHPExcel->getActiveSheet()->setAutoFilterByColumnAndRow($filtColStart, 1, $filtColEnd, 1);
        $objPHPExcel->getActiveSheet()->freezePaneByColumnAndRow($fixedCol, $fixedRow);

        for ($rc = 0; $rc < count($data); $rc++) {
            for ($dc = 0; $dc < count($data[$rc]); $dc++) {
                //$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($dc, $rc + 2)->getStyle()->getFont()->setBold(false)->setName('Calibri')->setSize(11);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dc, $rc + 2, $data[$rc][$dc]);
            }
        }
        
        unset($headerRow);
        unset($data);

        $objPHPExcel->getProperties()->setCreator("MRA :: MFI-DBMS")
                ->setLastModifiedBy("MRA :: MFI-DBMS")
                ->setTitle("MRA :: MFI-DBMS Report")
                ->setSubject("MRA Report")
                ->setDescription("MRA :: MFI-DBMS Report");
        $objPHPExcel->getActiveSheet()->setTitle('MFI-DBMS Data');
//        $objPHPExcel->setActiveSheetIndex(0);

        $current_datetime = date('Ymd_His');

        ob_end_clean();
        /*
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment;filename=$fileName-$current_datetime.xls");
          header("Pragma: no-cache");
          header("Expires: 0");
          flush();

          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          $objWriter->save('php://output');
         */

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=$fileName-$current_datetime.xlsx");
        header("Cache-Control: max-age=0");
        header("Cache-Control: max-age=1");
        header("Cache-Control: cache, must-revalidate");
        header("Pragma: public");
        flush();

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        exit;
    }

}
