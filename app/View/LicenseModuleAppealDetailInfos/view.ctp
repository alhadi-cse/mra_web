
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
        $title = 'Appeal Information';
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
                        echo "<th style='width:120px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";

                        echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Deadline Date') . "</th>";
                        echo "<th style='width:70px;'>Action</th>";
                    ?>
                </tr>
                <?php
                    $rc=-1;
                    foreach($values_waiting_for_appealed as $orgDetail) { 
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?>
                    </td>
                    <td>
                        <?php 
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>".$mfiName.":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName.$mfiFullName;        

                            echo $mfiName;
                        ?>
                    </td>
                    <td style="font-weight:bold; text-align:center;">
                        <?php echo $this->Time->format($orgDetail['LicenseModuleStateHistory']['date_of_deadline'], '%d-%m-%Y', ''); ?>
                    </td>
                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos', 'action'=>'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            
                            
                            echo $this->Js->link('Appeal', array('controller'=>'LicenseModuleAppealDetailInfos', 'action'=>'appeal', $orgDetail['BasicModuleBasicInformation']['id'], 21, 22), 
                                                            array_merge($pageLoading, array('class'=>'btnlink')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </fieldset>
        
        
        <fieldset>
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
                        echo "<th style='width:120px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";

                        echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Deadline Date') . "</th>";
                        echo "<th style='width:70px;'>Action</th>";
                    ?>
                </tr>
                <?php
                    $rc=-1;
                    foreach($values_appeal_deadline_over as $orgDetail) { 
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?>
                    </td>
                    <td>
                        <?php 
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>".$mfiName.":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName.$mfiFullName;        

                            echo $mfiName;
                        ?>
                    </td>
                    <td style="font-weight:bold; text-align:center; color:#fa2413;">
                        <?php echo $this->Time->format($orgDetail['LicenseModuleStateHistory']['date_of_deadline'], '%d-%m-%Y', ''); ?>
                    </td>
                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos', 'action'=>'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            
                            
                            if($isAdmin)
                                echo $this->Js->link('Reject and Notify', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'final_rejection', $orgDetail['BasicModuleBasicInformation']['id'], 7, 21, 50), 
                                                            array_merge($pageLoading, array('class' => 'btnlink')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </fieldset>
        
        
        <fieldset>
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

                        echo "<th style='width:120px;'>"
                            . (!$this->Paginator->param('options')? $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class'=>'asc'))
                                :$this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'))
                            //. (count($values_waiting_for_appealed_review) > 1 ? " <br /><span style='padding:0; color:#fa8713;'>Select All <input type='checkbox' id='chkbSelectAll'/></span>" : "")
                            . "</th>";

                        echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:110px;'>Action</th>";
                    ?>
                </tr>
                <?php
                    $rc=-1;
                    foreach($values_waiting_for_appealed_review as $orgDetail) { 
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php 
                            echo $orgDetail['BasicModuleBasicInformation']['form_serial_no'];
                        ?>
                    </td>
                    <td>
                        <?php 
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>".$mfiName.":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName.$mfiFullName;        

                            echo $mfiName;
                        ?>
                    </td>

                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleEvaluationDetailInfos', 'action'=>'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            
                            echo $this->Js->link('Appeal Review', array('controller'=>'LicenseModuleAppealDetailInfos', 'action'=>'appeal_review', $orgDetail['BasicModuleBasicInformation']['id'], 21, 23, 5, 50), 
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