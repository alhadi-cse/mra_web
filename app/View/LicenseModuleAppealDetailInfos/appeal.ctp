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
        $title = 'Appeal';
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
        
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php 
        if(!empty($orgDetail) && is_array($orgDetail)){
        echo $this->Form->create('LicenseModuleApplicationAppeal'); ?>

        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">

                <tr>
                    <td style="width:25%;">Form No.</td>
                    <td class="colons">:</td>
                    <td style="font-weight:bold;">
                        <?php 
                            echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; 
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
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
                </tr>
                
                <tr>
                    <td>Rejection Histories</td>
                    <td class="colons">:</td>
                    <td style="padding:7px 0;">
                        <?php 
                        if (!empty($rejection_histories) && is_array($rejection_histories)) {
                            echo '<table class="view">'
                                    . '<tr>' . '<th>Date</th>' . '<th>Category</th>' . '<th>Reason</th>' . '</tr>';

                            foreach ($rejection_histories as $rejection_history) {
                                echo '<tr>'
                                        . '<td>' . $this->Time->format($rejection_history['LicenseModuleRejectSuspendCancelHistory']['reject_suspend_cancel_date'], '%d-%m-%Y', '') . '</td>'
                                        . '<td>' . $rejection_history['LookupRejectSuspendCancelStepCategory']['reject_suspend_cancel_category'] . '</td>'
                                        . '<td>' . $rejection_history['LookupRejectSuspendCancelStepwiseReason']['reject_suspend_cancel_reason'] . '</td>'
                                    . '</tr>';
                            }
                            echo '</table>';
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Application</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("application", array('type' => 'textarea', 'label' => false, 'div' => false)); ?>
                    </td>
                </tr>

            </table>
        </div>

        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td style="text-align:right;">
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleAppealDetailInfos', 'action' => 'view', 22, 23, 21, 50), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php                                               
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to submit ?',
                                                    'success' => "msg.init('success', '$title', '$title has been successfully submited.');",
                                                    'error'=>"msg.init('error', '$title', '$title submition failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php echo $this->Form->end();} ?>

    </fieldset>
</div>

<?php } ?>