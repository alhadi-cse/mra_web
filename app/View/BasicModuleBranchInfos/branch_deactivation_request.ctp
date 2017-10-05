<?php
$title = "Branch Deactivation Request";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
?> 
<div id="frmBasicInfo_add">
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('BasicModuleBranchInfo'); ?>           
        <div class="form">            
            <table cellpadding="0" cellspacing="3" border="0">                           
                <tr>
                    <td style="vertical-align: top; padding: 0px; text-align: left;" colspan="3">
                        <?php echo $this->requestAction(array('controller' => 'BasicModuleBranchInfos', 'action' => 'branch_details', $branch_id), array('return')); ?> 
                    </td>
                </tr> 
                <tr>
                    <td>Date of Request</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('', array('type' => 'text', 'value' => $this->Time->format(new DateTime('now'), '%d-%m-%Y', ''), 'disabled' => 'disabled', 'div' => false, 'label' => false));
                        echo $this->Form->input('BasicModuleBranchInfo.deactivation_request_date', array('type' => 'hidden', 'value' => $this->Time->format(new DateTime('now'), '%Y-%m-%d', ''), 'label' => false));
                        ?>
                    </td>
                </tr>                
                <tr>
                    <td style="vertical-align: top;">Reason of Branch Deactivation</td>
                    <td class="colons" style="vertical-align: top;">:</td>
                    <td><?php echo $this->Form->input('BasicModuleBranchInfo.deactivation_reasons', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false)); ?></td>
                </tr>                              
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns'))); ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Send', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'Branch deactivation request sent successfully.');",
                            'error' => "msg.init('error', '$title', 'Request sending failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<script>

    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});
    });

</script>