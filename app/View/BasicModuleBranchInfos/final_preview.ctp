<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$org_id = $this->Session->read('Org.Id');
$title = "Office Information Preview";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?>
<div title="Preview">    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <?php echo $this->requestAction(array('controller' => 'BasicModuleBranchInfos', 'action' => 'branch_details', $branch_id), array('return')); ?>
        </div>  
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>                   
                    <td><?php echo $this->Js->link('Back to Home', array('controller' => 'Mrahome', 'action' => 'user_info'), $pageLoading); ?></td>
                    <td><?php echo $this->Js->link('Back to Offoce List', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();'))); ?></td>
                    <td><?php echo $this->Js->link('Add Another Office', array('controller' => 'BasicModuleBranchInfos', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns'))); ?></td>
                    <td><?php echo $this->Js->link('Edit', array('controller' => 'BasicModuleBranchInfos', 'action' => 'edit', $branch_id, $org_id), array_merge($pageLoading, array('class' => 'btnlink'))); ?></td>                    
                </tr>
            </table>
        </div>        
    </fieldset>
</div>