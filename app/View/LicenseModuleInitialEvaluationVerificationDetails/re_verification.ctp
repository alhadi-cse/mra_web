<?php

if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = "Initial Evaluation Verification";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
                        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));


if (!empty($approvalDetails)) {
    $verification_id = $approvalDetails['verification_status_id'];
    $comment = $approvalDetails['verification_comment'];
}
 else {
    $verification_id = $comment = null;
}

?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('LicenseModuleInitialEvaluationVerificationDetail'); ?>        
        <div class="form">
            <table style="width:100%;" cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td colspan="3" style="border-bottom:2px solid #137387; padding:0;">
                        <p style="padding:0 0 0 15px;">
                            <?php
                            echo "Name of Organization: <strong>$orgName</strong>";
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                            echo $this->Js->link('Pre. Details', array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'preview', $org_id, 1), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'display:inline-block;', 'update' => '#popup_div')));
                            ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>Initial Evaluation Verification Verification Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("verification_status_id", array('type' => 'radio', 'options' => $verification_status_options, 'default' => $verification_id, 'legend' => false, 'div' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Verification</td>
                    <td class="colons">:</td>                                
                    <td style="padding-left:10px">
                        <?php
                        echo $this->Time->format(new DateTime('now'), '%d-%m-%Y', '');
                        echo $this->Form->input('verification_date', array('type' => 'hidden', 'value' => date("Y-m-d"), 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Comment</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="padding:5px 0 0 10px"><?php echo $this->Form->input('verification_comment', array('type' => 'textarea', 'value' => $comment, 'escape' => false, 'div' => false, 'label' => false)); ?></td>
                </tr>
            </table>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialEvaluationVerificationDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Re-submit', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been update successfully.');",
                            'error' => "msg.init('error', '$title', '$title update failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
