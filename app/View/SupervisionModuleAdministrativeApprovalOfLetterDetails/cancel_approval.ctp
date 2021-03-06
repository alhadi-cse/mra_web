<?php
$title = "Section Comments on Report, Note and Letter";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$reports = $notes = $letters = '';

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
        <?php echo $this->Form->create(' SupervisionModulePrepareLetterDetail'); ?>
        <div class="form">            
            <table cellpadding="6" cellspacing="7" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;">
                        <?php
                        echo $orgName . $this->Js->link('Details', array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'preview', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                        ?>
                    </td>
                </tr>                
                <tr>
                    <td valign="top">Comments on Report</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('section_comments_on_reports', array('type' => 'textarea', 'value' => $reports, 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:70px;')); ?></td>
                </tr>
                <tr>
                    <td valign="top">Comments on Note</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('section_comments_on_notes', array('type' => 'textarea', 'value' => $notes, 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:70px;')); ?></td>
                </tr>
                <tr>
                    <td valign="top">Comments on Letter</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('section_comments_on_letters', array('type' => 'textarea', 'value' => $letters, 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:70px;')); ?></td>
                </tr>                
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'SupervisionModuleSectionReviewOfReportNoteLetterDetails', 'action' => 'view?this_state_ids=' . $this_state_ids . '&viewable_user_groups=' . $viewable_user_groups), array_merge($pageLoading, array('class' => 'mybtns'))); ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        
                        echo $this->Js->submit('Save', array_merge($pageLoading, array(
                            'url'=>"/SupervisionModuleSectionReviewOfReportNoteLetterDetails/cancel_review/$org_id/$viewable_user_groups", 
                            'confirm' => "Are you sure to save ?",
                            'success' => "msg.init('success', '$title', 'Comments on Report, Note and Letter have been saved successfully.');",
                            'error' => "msg.init('error', '$title', 'saving failed!');")));
                        ?>
                    </td>                    
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
