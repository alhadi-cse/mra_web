<?php
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    $isAdmin =(!empty($user_group_id) && in_array(1,$user_group_id));        
    $title = "License Cancel Request";
//    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
//        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
//        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
//    $this->Paginator->options($pageLoading);
?>
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMfiCancelRequests', 'action' => 'show_completed'), array('return')); ?>
        <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMfiCancelRequests', 'action' => 'show_pending'), array('return')); ?>
    </fieldset>
</div>