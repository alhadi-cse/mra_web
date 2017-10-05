<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = "Verification of Explanation Against Show Cause";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php echo $this->Form->create('LicenseModuleCancelByMraExplanationVerificationDetail'); ?>
        <div class="form">
            <table style="width:100%;" cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td colspan="3" style="border-bottom:2px solid #137387; padding:0;">
                        <p style="padding:0 0 0 15px;">
                            <?php  
                            echo "Name of Organization: <strong>$orgName</strong>";
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                            echo $this->Js->link('Previous Details', array('controller' => 'LicenseModuleCancelByMraExplanationVerificationDetails', 'action' => 'preview', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'display:inline-block;', 'update' => '#popup_div')));
                            ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>Verification of Explanation Against Show Cause Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("verification_status_id", array('type' => 'radio', 'options' => $verification_status_options, 'legend' => false, 'div' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Verification</td>
                    <td class="colons">:</td>                                
                    <td style="padding-left:10px">
                        <?php 
                            echo $this->Time->format(new DateTime('now'),'%d-%m-%Y','');
                            echo $this->Form->input('verification_date', array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Comment</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="padding:5px 0 0 10px"><?php echo $this->Form->input('verification_comment', array('type' => 'textarea', 'escape' => false, 'div'=>false, 'label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleCancelByMraExplanationVerificationDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns'))); 
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array('confirm' => "Are you sure to Submit ?", 
                                                          'success' => "msg.init('success', '$title', '$title has been submitted successfully.');",
                                                          'error' => "msg.init('error', '$title', 'submit failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>
