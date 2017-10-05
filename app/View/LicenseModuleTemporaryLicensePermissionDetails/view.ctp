<?php

    if (isset($msg) && !empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    $title = 'Temporary License Permission Information';

    $user_group_id = $this->Session->read('User.GroupIds');
    $committee_group_id = $this->Session->read('Committee.GroupId');
    
    $isAdmin = (!empty($user_group_id) && (in_array(1,$user_group_id) || in_array($committee_group_id,$user_group_id)));

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">

            <?php if (empty($org_id)) {
                echo $this->Form->create('LicenseModuleTemporaryLicensePermissionDetail');
                ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                'options' =>
                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                    'BasicModuleBasicInformation.form_serial_no' => 'Form No.')
                            ));
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                        <td style="text-align:left;">
                            <?php
                            echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                            ?>
                        </td>                                
                    </tr>
                </table>
                <?php echo $this->Form->end();
            }
            ?>
            
            
            <fieldset>
                <legend>Request for License</legend>

                <?php
                if ($values_request_for_license == null || !is_array($values_request_for_license) || count($values_request_for_license) < 1) {
                    echo '<p class="error-message">No data is available !</p>';
                } else {
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            if (!$this->Paginator->param('options'))
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                            else
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='min-width:185px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:70px;'>Date of Notification</th>";
                            echo "<th style='width:70px;'>Date of Acceptance</th>";
                            echo "<th style='width:70px;'>Date of Permission Issue</th>";
                            //echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        
                        <?php foreach ($values_request_for_license as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";
                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['notification_sent_date']));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['condition_accept_date']));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['permission_issue_date']));
                                ?>
                            </td>
<!--                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                //echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>-->
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
                
                <?php if ($this->Paginator->param('pageCount') > 1) { ?>
                    <div class="paginator">
                        <?php
                        if ($this->Paginator->param('pageCount') > 10) {
                            echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                            $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                            $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                        } else {
                            echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                            $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                        }
                        ?>
                    </div>
                <?php } ?>

            </fieldset>
            

            <fieldset>
                <legend>Temporary License Permission Issue</legend>

                <?php
                if ($values_temporarily_licensed == null || !is_array($values_temporarily_licensed) || count($values_temporarily_licensed) < 1) {
                    echo '<p class="error-message">No data is available !</p>';
                } else {
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            if (!$this->Paginator->param('options'))
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                            else
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='min-width:185px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:70px;'>Date of Notification</th>";
                            echo "<th style='width:70px;'>Date of Acceptance</th>";
                            echo "<th style='width:70px;'>Date of Permission Issue</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        
                        <?php foreach ($values_temporarily_licensed as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";
                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['notification_sent_date']));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['condition_accept_date']));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['permission_issue_date']));
                                ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                if($isAdmin)
                                    echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                else 
                                    echo $this->Js->link('Request for License', array('controller' => 'LicenseModuleTemporaryLicensePermissionDetails', 'action' => 'request_for_license', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => " Are you sure you to comply all terms and conditions ? \r\n \r\n Then asking for a license. \r\n \r\n", 'title' => 'Request for Permanent License.')));
                                
                                    
                                //echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
                
                <?php if ($this->Paginator->param('pageCount') > 1) { ?>
                    <div class="paginator">
                        <?php
                        if ($this->Paginator->param('pageCount') > 10) {
                            echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                            $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                            $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                        } else {
                            echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                            $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                        }
                        ?>
                    </div>
                <?php } ?>

            </fieldset>
            
            <?php if ($isAdmin) { ?>            
            <fieldset>
                <legend>Conditions Accept and Waiting for Acknowledgment</legend>

                <?php
                if ($values_condition_accepet == null || !is_array($values_condition_accepet) || count($values_condition_accepet) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            if (!$this->Paginator->param('options'))
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                            else
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:80px;'>Date of Notification</th>";
                            echo "<th style='width:80px;'>Date of Acceptance</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        
                        <?php foreach ($values_condition_accepet as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";
                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['notification_sent_date']));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date("d-m-Y", strtotime($value['LicenseModuleTemporaryLicensePermissionDetail']['condition_accept_date']));
                                ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                if($isAdmin)
                                    echo $this->Js->link('Acknowledge', array('controller' => 'LicenseModuleTemporaryLicensePermissionDetails', 'action' => 'acknowledge', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to accept Temporary License ?', 'title' => 'Temporary License Permission Accept/Approve.')))
                                                        . $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                    <?php } ?>

                    <?php if ($this->Paginator->param('pageCount') > 1) { ?>
                        <div class="paginator">
                        <?php
                        if ($this->Paginator->param('pageCount') > 10) {
                            echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                            $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                            $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                        } else {
                            echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                            $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                        }
                        ?>
                        </div>
                    <?php } ?>
                
                <?php if (count($values_condition_accepet) > 1 && $isAdmin) { ?>
                    <div class="btns-div" style="padding:7px; text-align:center;">
                    <?php
                    echo $this->Js->link('Acknowledge All', array('controller' => 'LicenseModuleTemporaryLicensePermissionDetails', 'action' => 'acknowledge_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Are you sure to accept All Temporary License ?')));
                    ?>
                    </div>
                <?php } ?>

            </fieldset>
            <?php } ?>
            
            <fieldset>
                <legend>Notification Sent and Waiting for Conditions Accept</legend>
                <?php
                if (empty($values_notification_sent) || !is_array($values_notification_sent) || count($values_notification_sent) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                    if (empty($total_marks) || $total_marks < 1)
                        $total_marks = 100;
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            if (!$this->Paginator->param('options'))
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
                            else
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:80px;'>Date of Notification</th>";
                            echo "<th style='width:80px;'>Date of Deadline</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        
                        <?php foreach ($values_notification_sent as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";
                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            
                            <?php
                                $deadline_date = $value['LicenseModuleTemporaryLicensePermissionDetail']['notification_sent_date'];

                                echo '<td>' . date("d-m-Y", strtotime($deadline_date)) . '</td>';

                                $deadline_date = date("Y-m-d", strtotime("$deadline_date +$deadline_days days"));
                                if($deadline_date < date('Y-m-d'))
                                    echo '<td  style="font-weight:bold; color:#fa4523;">' . date("d-m-Y", strtotime($deadline_date)) . '</td>';
                                else if($deadline_date <= date('Y-m-d', strtotime("+$warning_days days")))
                                    echo '<td  style="font-weight:bold; color:#ef8713;">' . date("d-m-Y", strtotime($deadline_date)) . '</td>';
                                else
                                    echo '<td  style="font-weight:bold; color:#13af24;">' . date("d-m-Y", strtotime($deadline_date)) . '</td>';
                            ?>
                            
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Accept', array('controller' => 'LicenseModuleTemporaryLicensePermissionDetails', 'action' => 'accept', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to accept all terms and conditions ?', 'title' => 'Accept all Terms and Conditions.')))
                                                        . $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>

                    <?php } ?>

            </fieldset>

            
            <?php if ($isAdmin) { ?>
            <fieldset>
                <legend>Selected for Temporary License Permission</legend>

                <?php
                if ($values_selected == null || !is_array($values_selected) || count($values_selected) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                    if (empty($total_marks) || $total_marks < 1)
                        $total_marks = 100;
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            if (!$this->Paginator->param('options'))
                                echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class' => 'asc')) . "</th>";
                            else
                                echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModuleInitialAssessmentMark.total_assessment_marks', 'Total Marks Obtained') . '<br /> <strong style="color:#faf0af;">(out of ' . $this->Number->precision($total_marks, 1) . ')</strong>' . "</th>";
                            echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Administrative Approval Status') . "</th>";
                            echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleInitialEvaluationAdminApprovalDetail.approval_date', 'Approval Date') . "</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_selected as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                            <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                            </td>
                            <td style="font-weight:bold; text-align:center;">
                                <?php
                                $obtained_marks = $value['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                                echo ($obtained_marks >= $pass_min_marks ? '<span style="color:#13af24;">' : '<span style="color:#fa2413;">')
                                        . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100)
                                        . '</span><br/>(' . $this->Number->precision($obtained_marks, 1) . ')';
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['approval_status']; ?></td>                        
                            <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleInitialEvaluationAdminApprovalDetail']['approval_date'], '%d-%m-%Y', ''); ?></td>
                            <td style="text-align:center; padding:2px; height:30px;">
                                <?php
                                if ($isAdmin) {
                                    echo $this->Js->link('Notify', array('controller' => 'LicenseModuleTemporaryLicensePermissionDetails', 'action' => 'notify', $value['BasicModuleBasicInformation']['id'], $this_state_ids), 
                                                                        array_merge($pageLoading, array('class' => 'btnlink', 'title' => 'Send notification with terms and conditions.')));//, 'confirm' => 'Are you sure to send notification with terms and conditions ?'
                                    echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                }
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>

                <?php } ?>


                <?php if ($values_selected && $this->Paginator->param('pageCount') > 1) { ?>
                    <div class="paginator">
                    <?php
                    if ($this->Paginator->param('pageCount') > 10) {
                        echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                        $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    } else {
                        echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    ?>
                    </div>

                    <div class="btns-div" style="padding:7px; text-align:center;">
                        <?php
                        echo $this->Js->link('Notify All', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'approve_edit_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Send notification to all the organization.')));
                        ?>
                    </div>

                    <?php } ?>

            </fieldset>
            <?php } ?>

        </div>
    </fieldset>
</div>
