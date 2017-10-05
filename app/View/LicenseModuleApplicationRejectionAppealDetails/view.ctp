
<?php

    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        $title = 'Appeal Against Application Rejection';
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
        
?>

<div>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <fieldset>
            <legend>Waiting for Appeal</legend>

            <?php
            if ($values_waiting_for_appealed == null || !is_array($values_waiting_for_appealed) || count($values_waiting_for_appealed) < 1) {
                echo '<p class="error-message">';
                echo 'No data is available !';
                echo '</p>';
            } else {
            ?>

            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:80px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";

                    echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:200px;'>" . $this->Paginator->sort('LicenseModuleStateName.state_title', 'Previous State') . "</th>";
                    echo "<th style='width:270px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionReason.rejection_reason', 'Rejection Reason') . "</th>";
                    //echo "<th style='width:320px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.rejection_msg', 'Rejection Message') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.rejection_date', 'Rejection Date') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.deadline_date', 'Deadline Date') . "</th>";
                    echo "<th style='width:80px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values_waiting_for_appealed as $value) { ?>
                <tr>

                    <td style="text-align:center;">
                        <?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?>
                    </td>
                    <td>
                        <?php 
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                            
                            echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                        ?>
                    </td>
                    <td style="text-align:left;"><?php echo $value['LicenseModuleStateName']['state_title']; ?></td>
                    <td style="text-align:left;"><?php echo $value['LookupLicenseApplicationRejectionReason']['rejection_reason']; ?></td>
                    <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['rejection_date'], '%d-%m-%Y', ''); ?></td>
                    <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['deadline_date'], '%d-%m-%Y', ''); ?></td>

                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleApplicationRejectionDetails', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            
                            
                            echo $this->Js->link('Appeal', array('controller'=>'LicenseModuleApplicationRejectionAppealDetails', 'action'=>'appeal', $value['BasicModuleBasicInformation']['id'], $appeal_state_id), 
                                                            array_merge($pageLoading, array('class'=>'btnlink')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table> 
            <?php } ?>
        </fieldset>


        <fieldset style="margin-top:7px;">
            <legend>Appeal Deadline Over</legend>

            <?php
            if ($values_appeal_deadline_over == null || !is_array($values_appeal_deadline_over) || count($values_appeal_deadline_over) < 1) {
                echo '<p class="error-message">';
                echo 'No data is available !';
                echo '</p>';
            } else {
            ?>

            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:80px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";

                    echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:200px;'>" . $this->Paginator->sort('LicenseModuleStateName.state_title', 'Previous State') . "</th>";
                    echo "<th style='width:270px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionReason.rejection_reason', 'Rejection Reason') . "</th>";
                    //echo "<th style='width:320px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.rejection_msg', 'Rejection Message') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.rejection_date', 'Rejection Date') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.deadline_date', 'Deadline Date') . "</th>";
                    echo "<th style='width:80px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values_appeal_deadline_over as $value) { ?>
                <tr>

                    <td style="text-align:center;">
                        <?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?>
                    </td>
                    <td>
                        <?php 
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                            
                            echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                        ?>
                    </td>
                    <td style="text-align:left;"><?php echo $value['LicenseModuleStateName']['state_title']; ?></td>
                    <td style="text-align:left;"><?php echo $value['LookupLicenseApplicationRejectionReason']['rejection_reason']; ?></td>
                    <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['rejection_date'], '%d-%m-%Y', ''); ?></td>
                    <td style="text-align:center; font-weight:bold; color:#fa2413;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['deadline_date'], '%d-%m-%Y', ''); ?></td>

                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleApplicationRejectionDetails', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            
                            
                            if($isAdmin)
                                echo $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'rejection', $value['BasicModuleBasicInformation']['id'], 7, 21, 50), 
                                                            array_merge($pageLoading, array('class' => 'btnlink')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <?php } ?>
        </fieldset>


        <fieldset style="margin-top:7px;">
            <legend>Waiting for Appeal Review</legend>

            <?php
            if ($values_waiting_for_appealed_review == null || !is_array($values_waiting_for_appealed_review) || count($values_waiting_for_appealed_review) < 1) {
                echo '<p class="error-message">';
                echo 'No data is available !';
                echo '</p>';
            } else {
            ?>

            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:80px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";

                    echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:200px;'>" . $this->Paginator->sort('LicenseModuleStateName.state_title', 'Previous State') . "</th>";
                    //echo "<th style='width:200px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionType.rejection_type', 'Rejection Type') . "</th>";
                    //echo "<th style='width:250px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionCategory.rejection_category', 'Rejection Category') . "</th>";
                    echo "<th style='width:270px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionReason.rejection_reason', 'Rejection Reason') . "</th>";
                    //echo "<th style='width:320px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.rejection_msg', 'Rejection Message') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.rejection_date', 'Rejection Date') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleApplicationRejectionHistoryDetail.deadline_date', 'Deadline Date') . "</th>";
                    echo "<th style='width:80px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values_waiting_for_appealed_review as $value) { ?>
                <tr>
                    <td style="text-align:center;">
                        <?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?>
                    </td>
                    <td>
                        <?php 
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                            
                            echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                        ?>
                    </td>
                    <td style="text-align:left;"><?php echo $value['LicenseModuleStateName']['state_title']; ?></td>
<!--                    <td style="text-align:left;"><?php //echo $value['LookupLicenseApplicationRejectionType']['rejection_type']; ?></td>
                    <td style="text-align:left;"><?php //echo $value['LookupLicenseApplicationRejectionCategory']['rejection_category']; ?></td>-->
                    <td style="text-align:left;"><?php echo $value['LookupLicenseApplicationRejectionReason']['rejection_reason']; ?></td>
<!--                    <td style="text-align:left;"><?php // echo $value['LicenseModuleApplicationRejectionHistoryDetail']['rejection_msg']; ?></td>-->
                    <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['rejection_date'], '%d-%m-%Y', ''); ?></td>
                    <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleApplicationRejectionHistoryDetail']['deadline_date'], '%d-%m-%Y', ''); ?></td>

                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleApplicationRejectionDetails', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            
                            echo $this->Js->link('Appeal Review', array('controller'=>'LicenseModuleApplicationRejectionAppealDetails', 'action'=>'appeal_review', $value['BasicModuleBasicInformation']['id'], $rejection_state_ids, $value['LicenseModuleApplicationRejectionHistoryDetail']['previous_licensing_state_id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table> 
            <?php } ?>


        </fieldset>

    </fieldset>

</div>


<?php } ?>
