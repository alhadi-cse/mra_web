<div>
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    $title = 'Field Inspection/Queries Details';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php //echo $this->requestAction(array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'inspection_details', $supervision_basic_id), array('return')); ?>

        <?php echo $this->Form->create('SupervisionModuleFieldInspectionApprovalDetail'); ?>
        <fieldset style="margin-top:15px;">
            <legend>Field Inspection/Queries Verification Approval</legend>

            <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                <tr>
                    <td>a. Inspection Approval</td>
                    <td class="colons">:</td>
                    <td style="width:58%; font-weight:bold;">
                        <?php
                        echo $this->Form->input('supervision_basic_id', array('type' => 'hidden', 'value' => $supervision_basic_id, 'label' => false));
                        echo $this->Form->input('submission_date', array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false));
                        echo $this->Form->input('inspection_approval_id', array('type' => 'radio', 'options' => $approval_status_options, 'div' => false, 'legend' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:5px; vertical-align:top;">b. Comment or Message (if not approved)</td>
                    <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                    <td style="padding:5px 0;"><?php echo $this->Form->input('inspection_comment', array('type' => 'textarea', 'div' => false, 'label' => false)); ?></td>
                </tr>
            </table>

            <div class="btns-div"> 
                <table style="margin:0 auto; padding:5px; min-width:auto;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'view');
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
                        <td></td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>