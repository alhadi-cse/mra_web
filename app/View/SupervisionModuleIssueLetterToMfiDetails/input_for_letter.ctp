<?php
$title = "Issue Letter";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
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

        <?php echo $this->Form->create('SupervisionModuleIssueLetterToMfiDetail'); ?>
        <div>
            <table cellpadding="6" cellspacing="5" border="0">
                <tr>
                    <td style="width:165px;">Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;"><?php echo $orgName; ?></td>
                </tr>
                <tr>
                    <td>Date of Issue</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('issue_date', array('type' => 'hidden', 'id' => 'issue_date_alt', 'label' => false, 'div' => false))
                        . " <input type='text' id='issue_date' class='date_picker' />";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Memo No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('memo_no', array('type' => 'text', 'div' => false, 'label' => false)); ?></td>                   
                </tr>
                <tr>
                    <td>Subject</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('msg_subject', array('type' => 'text', 'div' => false, 'label' => false)); ?></td>                   
                </tr>                
                <tr>
                    <td>Name of Recipient</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('msg_to', array('type' => 'text', 'div' => false, 'label' => false)); ?></td>                   
                </tr>
                <tr>
                    <td>Designation of Recipient</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('to_designation', array('type' => 'text', 'div' => false, 'label' => false)); ?></td>                   
                </tr>
                <tr>
                    <td>Organization of Recipient</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('to_organization', array('type' => 'text', 'div' => false, 'label' => false)); ?></td>                   
                </tr>
                <tr>
                    <td style="vertical-align:top;">Address of Organization</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td style="vertical-align:top;"><?php echo $this->Form->input('org_address', array('type' => 'textarea', 'escape' => false, 'rows' => '3', 'cols' => '5', 'div' => false, 'label' => false)); ?></td>                   
                </tr>
                <tr>
                    <td>Authorized Person of MRA</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('mra_authority_id', array('type' => 'select', 'options' => $mra_authority_name_options, 'id' => 'mra_authority_names', 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td>Letter No.</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        $letter_serial_no = $this->request->data['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no'];
                        $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
                        echo $nf->format($letter_serial_no) . " Letter";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Letter Details</td>
                    <td class="colons" style="vertical-align:top;">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('letter_serial_no', array('type' => 'hidden', 'label' => false, 'div' => false))
                        . $this->Form->input('letter_details', array('type' => 'textarea', 'escape' => false, 'rows' => '3', 'cols' => '5', 'WRAP' => 'HARD', 'div' => false, 'label' => false));
                        ?>
                    </td>
                </tr>                
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'SupervisionModuleIssueLetterToMfiDetails', 'action' => 'view?this_state_ids=' . $this_state_ids), array_merge($pageLoading, array('class' => 'mybtns'))); ?> 
                    </td>                   
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Preview Letter', array(
                            'url' => array('controller' => 'SupervisionModuleIssueLetterToMfiDetails', 'action' => 'preview_letter', $supervision_basic_id),
                            'update' => '#letter_content',
                            'class' => 'mybtns',
                            'div' => false,
                            'async' => true,
                            'method' => 'post',
                            'dataExpression' => true,
                            'data' => '$(this).closest("form").serialize()',
                            'beforeSend' => '$("#busy-indicator").fadeIn();',
                            'complete' => '$("#busy-indicator").fadeOut();',
                            'confirm' => 'Are you sure to preview letter ?',
                            'success' => "modal_close('letter_opt'); modal_open('letter_viewer', 0 ,'Letter Preview');",
                            'error' => 'msg.init("error", "Letter Preview", "Letter preview failed !");'));
                        ?>
                    </td>                    
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div id="letter_viewer_bg" class="modal-bg">
    <div id="letter_viewer" class="modal-content" style="max-width:1020px; min-width:1000px; margin:25px auto;">

        <div id="letter_viewer_title" class="modal-title">
            <span class="modal-title-txt modal-title-letter-bg">Letter Preview</span>
            <button class="close" onclick="if (confirm('Are you sure to Close ?'))
                        modal_close('letter_viewer');
                    return false;">✖</button>
        </div>

        <div id="letter_content" style="width:auto; height:auto; max-height:500px; max-height:80vh; margin:0; padding:5px; overflow:auto; cursor:default;">

        </div>

        <div class="btns-div" style="margin-top:0; padding:5px; text-align: center;">
            <button class="modal-close" onclick="if (confirm('Are you sure to Close ?'))
                        modal_close('letter_viewer');
                    return false;" title="Close Letter Preview.">Close</button>

            <?php
            $viewable_user_groups = $this->Session->read('User.ViewableGroups');
            echo $this->Js->submit('Send', array_merge($pageLoading, array(
                'url' => "/SupervisionModuleIssueLetterToMfiDetails/send_letter/$supervision_basic_id/$viewable_user_groups",
                'class' => 'modal-close',
                'div' => false,
                'confirm' => "Are you sure to Submit?",
                'success' => "msg.init('success', 'Issue of Letter', 'Letter has been issued successfully');",
                'error' => "msg.init('error', 'Issue of Letter', 'Letter issue failed!');")));
            ?>
        </div>

    </div>
</div>

<script>
    $(function () {
        draggable_modal('letter_viewer_title', 'letter_viewer', 'letter_viewer_bg');

        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        $('.date_picker').each(function () {
            $(this).datepicker({
                yearRange: 'c-5:c+5',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
    });
</script>