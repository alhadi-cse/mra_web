<?php
$title = "MFI Explanation against Letter";
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

//debug($letter_details);
//$letter_details
?>
<div id="frmBasicInfo_add">
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('SupervisionModuleReplyOrExplanationOfMfiDetail'); ?>           
        <div class="form">            
            <table cellpadding="4" cellspacing="6" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($letter_details['BasicModuleBasicInformation'])) {
                            $orgDetail = $letter_details['BasicModuleBasicInformation'];
                            echo $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Issue Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']; ?></td>
                </tr>
                <tr>
                    <td>Memo No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['memo_no']; ?></td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td class="colons">:</td>
                    <td><?php echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['msg_subject']; ?></td>
                </tr>
                <tr>
                    <td style="vertical-align:top; padding-top:5px;">Letter Details</td>
                    <td style="vertical-align:top;" class="colons">:</td>
                    <td style="padding-top:5px;"><?php echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['letter_details']; ?></td>
                </tr>
                <tr>
                    <td>Date of Explanation</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('explanation_giving_date', array('type' => 'hidden', 'id' => 'explanation_giving_date_alt', 'label' => false, 'div' => false))
                        . " <input type='text' id='explanation_giving_date' class='date_picker' />";

                        echo $this->Form->input('letter_id', array('type' => 'hidden', 'value' => $letter_details['SupervisionModuleIssueLetterToMfiDetail']['id'], 'label' => false, 'div' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top; padding-top:5px;">Explanation Details</td>
                    <td style="vertical-align:top;" class="colons">:</td>
                    <td style="padding-top:5px;"><?php echo $this->Form->input('explanation_details', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'view?this_state_ids=' . $this_state_ids), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'MFI Explanation against letter has been given successfully.');",
                            'error' => "msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<script>

    $(function () {
        $('.date_picker').each(function () {
            $(this).datepicker({
                showAnim: 'show',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                yearRange: '-1:+0',
                maxDate: '0',
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
    });

</script>
