
<div>
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }


    if (!empty($inspection_type_id) && !empty($inspection_type_detail[$inspection_type_id]))
        $title = "$inspection_type_detail[$inspection_type_id] Details";
    else
        $title = 'Field Inspection Details';

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>


    <?php echo $this->requestAction(array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'inspection_details', $org_id, $inspection_type_id, $inspection_slno), array('return')); ?>

    <?php echo $this->Form->create('LicenseModuleFieldInspectionApprovalDetail'); ?>
    <fieldset style="margin-top:15px;">
        <legend>Field Inspection Verification Approval</legend>

        <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
            <tr>
                <td>a. Inspection Approval</td>
                <td class="colons">:</td>
                <td style="width:58%; font-weight:bold;">
                    <?php
                    echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                    echo $this->Form->input("submission_date", array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false));
                    echo $this->Form->input("inspection_approval_id", array('type' => 'radio', 'options' => $approval_status_options, 'div' => false, 'legend' => false));
                    ?>
                </td>
            </tr>
            <tr>
                <td style="padding-top:5px; vertical-align:top;">b. Comment or Message (if not approved)</td>
                <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                <td style="padding:5px 0;"><?php echo $this->Form->input("inspection_comment", array('type' => 'textarea', 'div' => false, 'label' => false)); ?></td>
            </tr>
        </table>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'view', '?' => array('inspection_type_id' => $inspection_type_id));
                        echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('confirm' => "Are you sure to Submit ?",
                            'success' => "msg.init('success', '$title', '$title has been submit successfully.');",
                            'error' => "msg.init('error', '$title', 'submit failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

    </fieldset>
    <?php echo $this->Form->end(); ?>

</div>
