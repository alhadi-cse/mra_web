<?php
if (empty($approvalType))
        $approvalType = "Director's";
$title = htmlspecialchars("$approvalType's ".$approval_title ." for Canceling License", ENT_QUOTES);

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

if (!empty($this->request->data['LicenseModuleCancelByMfiApprovalDetail']))
    $existing_values = $this->request->data['LicenseModuleCancelByMfiApprovalDetail'];

$this->Paginator->options($pageLoading);
$this_state_ids = $this->Session->read('Current.StateIds');
if (!empty($msg)) {
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
        <?php echo $this->Form->create('LicenseModuleCancelByMfiApprovalDetail'); ?>
        <div class="form">            
            <table cellpadding="6" cellspacing="7" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;">
                        <?php
                            echo $orgName . $this->Js->link('Details', array('controller' => 'LicenseModuleCancelByMfiApprovalDetails', 'action' => 'preview', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                        ?>
                    </td>
                </tr>          
                <tr>
                    <td valign="top">Notes/Comments</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td>
                        <?php 
                            echo $this->Form->input('tier_wise_comments', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:70px;')); 
                        ?>
                    </td>
                </tr>                
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'SupervisionModuleLetterApprovalDetails', 'action' => 'view?this_state_ids=' . $this_state_ids), array_merge($pageLoading, array('class' => 'mybtns'))); ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php 
                            echo $this->Js->submit($btn_title, array_merge($pageLoading, 
                                                                array('confirm' => "Are you sure to $btn_title?",'success'=>"msg.init('success', '$title', '$success_msg');", 
                                                                      'error'=>"msg.init('error', '$title', '$error_msg');")));
                        ?>
                    </td>                   
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
