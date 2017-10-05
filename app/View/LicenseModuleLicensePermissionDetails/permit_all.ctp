<?php

    if (isset($msg) && !empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    
    $title = 'Temporary License Permission Notification';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleLicensePermissionDetail'); ?>
        
        <div style="width:780px; height:auto; padding:0; overflow-x:auto;">
            <?php 
                if($orgDetails == null || !is_array($orgDetails) || count($orgDetails) < 1) {
                   echo '<p class="error-message">';
                   echo 'No data is available!';
                   echo '</p>';

                   echo $this->Js->link('Back', array('controller' => 'LicenseModuleInitialEvaluationDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                } else {
                   if (empty($total_marks) || $total_marks < 1) $total_marks = 100;
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
                        echo "<th style='width:125px;'>Permit All <input type='checkbox' id='chkbApprovalAll'/> </th>";
                        echo "<th style='width:80px;'>Action</th>";
                        ?>
                    </tr>

                    <?php $rc = -1; foreach ($orgDetails as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                        <td>
                            <?php 
                            $rc++;
                            $org_id = $value['BasicModuleBasicInformation']['id'];
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
                            echo date("d-m-Y", strtotime($value['LicenseModuleLicensePermissionDetail']['notification_sent_date']));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo date("d-m-Y", strtotime($value['LicenseModuleLicensePermissionDetail']['condition_accept_date']));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            echo $this->Form->input("$rc.org_id", array('type' => 'hidden', 'value' => $value['BasicModuleBasicInformation']['id'], 'label' => false));
                            echo $this->Form->input("$rc.is_permit", array('type' => 'checkbox', 'class' => 'isApproved', 'div' => false, 'label' => 'Permit')); 
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;"> 
                            <?php
                            echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleInitialEvaluationAdminApprovalDetails', 'action' => 'preview', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            
        </div>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleLicensePermissionDetails', 'action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php                                               
                            echo $this->Js->submit('Permit All', array_merge($pageLoading, 
                                                    array('class'=>'mybtns', 'confirm' => 'Are you sure to permit all Temporary License ?', 'title' => 'Temporary License Permission Permit/Approve All.', 
                                                            'success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                            'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); } ?>
    </fieldset>
</div>


<script>
    $(document).ready(function() {        
        $("#chkbApprovalAll").on("change", function () {
            $(":checkbox.isApproved").prop("checked", this.checked);
        });
        
        $(":checkbox.isApproved").on("change", function () {
            var total = $(":checkbox.isApproved").length;
            var checked = $(":checkbox.isApproved:checked").length;
            if (total === checked) {
                $("#chkbApprovalAll").prop('checked', true);
                $("#chkbApprovalAll").prop('indeterminate', false);
            } else if (checked === 0) {
                $("#chkbApprovalAll").prop('checked', false);
                $("#chkbApprovalAll").prop('indeterminate', false);
            } else {
                $("#chkbApprovalAll").prop('indeterminate', true);
            }
        });
        
    });
</script>
