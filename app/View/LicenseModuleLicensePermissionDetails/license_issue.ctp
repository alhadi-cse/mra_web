<?php 

    if (isset($msg) && !empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    
    $title = "License Permission Notification";    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        <?php echo $this->Form->create('LicenseModuleLicensePermissionDetail'); ?>
        
        <div class="form">
            <table style="width:90%;" cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td colspan="3" style="border-bottom:2px solid #137387; font-size:10pt; line-height:30px;">
                    <?php 
                        echo 'Name of Organization: <strong>' . $orgName . '</strong>' 
                                . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                        echo $this->Js->link('Pre. Details', array('controller'=>'LicenseModuleLicenseEvaluationAdminApprovalDetails','action'=>'preview', $org_id), array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'display:inline-block; float:right;', 'update'=>'#popup_div')));
                    ?>
                    </td>
                </tr>
                <tr>
                    <td>License No.</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("license_no", array('type' => 'text', 'div'=>false, 'label' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:5px; vertical-align:top;">Message (Terms and Conditions)</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="padding-top:5px; vertical-align:top;"><?php echo $this->Form->input('msg_terms_conditions', array('type' => 'textarea', 'escape' => false, 'div'=>false, 'label' => false)); ?></td>
                </tr>
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
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Send', array_merge($pageLoading, 
                                                        array('confirm' => 'Are you sure to send notification with terms and conditions ?', 
                                                                'success'=>"msg.init('success', '$title', '$title has been send successfully.');", 
                                                                'error'=>"msg.init('error', '$title', '$title send failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>
