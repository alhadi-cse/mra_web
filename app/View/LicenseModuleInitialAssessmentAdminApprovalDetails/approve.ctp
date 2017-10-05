<?php
$title = "Administrative Approval of Initial Assessment";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));


if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php         
        $is_not_approved = !empty($this->data['LicenseModuleInitialAssessmentAdminApprovalDetail']['approval_status_id']) 
                                && $this->data['LicenseModuleInitialAssessmentAdminApprovalDetail']['approval_status_id'] == 2;
        
        echo $this->Form->create('LicenseModuleInitialAssessmentAdminApprovalDetail'); 
        ?>

        <div class="form">
            <table style="width:100%;" cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td colspan="3" style="border-bottom:2px solid #137387; padding:0;">
                        <?php
                        echo 'Name of Organization: <strong>' . $orgName . '</strong>' . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                        echo $this->Js->link('Assessment Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'display:inline-block; float:right;', 'update' => '#popup_div')));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:30%;">Administrative Approval Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Form->input("approval_status_id", array('type' => 'radio', 'class' => 'approval_status', 'options' => $approval_status_options, 'legend' => false, 'div' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Approval</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px;">
                        <?php
                        echo $this->Time->format(new DateTime('now'), '%d-%m-%Y', '');
                        echo $this->Form->input('approval_date', array('type' => 'hidden', 'value' => date("Y-m-d"), 'label' => false));
                        ?>
                    </td>
                </tr>
                
                <tr class="if_not_approved" <?php if (!$is_not_approved) echo 'style="display:none;"' ?>>
                    <td>Set the Back State</td>
                    <td class="colons">:</td>
                    <td style="padding-left:10px;">
                        <?php
                        echo $this->Form->input('back_state_id', array('type' => 'select', 'class' => 'rejOption', 'options' => $back_states, 'empty' => '-----Select-----', 'escape' => false, 'div' => false, 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr class="if_not_approved" <?php if (!$is_not_approved) echo 'style="display:none;"' ?>>
                    <td style="vertical-align:top; padding-top:5px;">Reason (if not approved)</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="padding:5px 0 0 10px;">
                        <?php
                        echo $this->Form->input('reason_if_not_approved', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false));
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td style="vertical-align:top; padding-top:5px;">Comment</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="padding:5px 0 0 10px;">
                        <?php
                        echo $this->Form->input('comment', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialAssessmentAdminApprovalDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('confirm' => 'Are you sure to Submit ?',
                            'success' => "msg.init('success', '$title', '$title has been update successfully.');",
                            'error' => "msg.init('error', '$title', '$title update failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $("input:radio.approval_status").click(function () {
            if ($(this).attr("value") != '1') {
                $(".if_not_approved").show();
            }
            else {
                $(".if_not_approved").hide();
            }
        });
    });

</script>
