<?php
$title = "Preparation of Letter";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
/*
  $comments_or_notes_of_inspector = $letters = '';

  if (!empty($this->request->data['SupervisionModulePrepareLetterDetail'])) {
  $existing_values = $this->request->data['SupervisionModulePrepareLetterDetail'];
  }

  if (!empty($existing_values) && isset($existing_values['letters'])) {
  $letters = $existing_values['letters'];
  }
 */$this_state_ids = $this->Session->read('Current.StateIds');

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

        <?php if (isset($orgName) && isset($letter_serial_no) && isset($supervision_basic_id)) { ?>

            <div style="margin:10px 5px; font-weight:bold;">Name of Organization :
                <?php
                echo $orgName . "   ";

                $controller = (!empty($letter_serial_no) && $letter_serial_no > 1) ? 'SupervisionModuleReplyOrExplanationOfMfiDetails' : 'SupervisionModulePrepareLetterDetails';
                echo $this->Js->link('Details', array('controller' => $controller, 'action' => 'preview', $supervision_basic_id), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                ?>
            </div>

            <?php if (!empty($letter_serial_no) && $letter_serial_no > 1) { ?>
                <div class="form">
                    <table style="width:100%;">
                        <tr>
                            <td style="vertical-align:top; width:47%; height:auto; min-height:250px; overflow-y:auto;">
                                <fieldset style="height:100%">
                                    <legend>Last Letter to <strong><?php echo $orgName; ?></strong> for Inspection</legend>
                                    <?php echo $this->requestAction(array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'show_previous_letter', $supervision_basic_id), array('return')); ?>
                                </fieldset>
                            </td>
                            <td style="vertical-align:top; width:47%; height:auto; min-height:250px; overflow-y:auto;">
                                <fieldset style="height:100%">
                                    <legend>Last Explanation from <strong><?php echo $orgName; ?></strong></legend>
                                    <?php echo $this->requestAction(array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'show_explanation_against_previous_letter', $supervision_basic_id), array('return')); ?>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
            <?php echo $this->Form->create('SupervisionModulePrepareLetterDetail'); ?>
            <div class="form">            
                <table style="width:100%;" cellpadding="6" cellspacing="7" border="0">                 
                    <?php if (!empty($letter_serial_no) && $letter_serial_no > 1) { ?>
                        <tr>
                            <td colspan="3" style="padding-top:5px;">
                                <table id='my_table' class="view" style="display:none;">
                                    <tr>
                                        <th style="width:20px;">SL No.</th>
                                        <th style="width:200px;">Findings</th>
                                        <th style="width:200px;">MFI's Reply</th>
                                        <th style="width:200px;">Comments</th> 
                                        <th style="width:50px;">Action</th>
                                    </tr>
                                </table>
                                <br />
                                <a id="add_button" href="#" class="mybtns">Add Comments</a><br />
                            </td>
                        </tr>
                        <?php
                        echo $this->Form->input('SupervisionModulePrepareLetterDetail.comments_or_notes_of_inspector', array('type' => 'hidden', 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:80px;'));
                    } else {
                        ?>
                        <tr>
                            <td valign="top">Inspector's Comments/Notes</td>
                            <td style="font-weight:bold; padding-left:7px;" valign="top">:</td>
                            <td><?php echo $this->Form->input('SupervisionModulePrepareLetterDetail.comments_or_notes_of_inspector', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:80px;')); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td valign="top">Letter No.</td>
                        <td style="font-weight:bold; padding-left:7px;" valign="top">:</td>
                        <td style="font-weight:bold;">
                            <?php
                            $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
                            echo $nf->format($letter_serial_no) . " Letter";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Letter Details</td>
                        <td style="font-weight:bold; padding-left:7px;" valign="top">:</td>
                        <td><?php echo $this->Form->input('SupervisionModulePrepareLetterDetail.letters', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false, 'WRAP' => 'HARD', 'style' => 'width: 580px; height:200px;')); ?></td>
                    </tr>                
                </table>                       
            </div> 
            <div class="btns-div"> 
                <table style="margin:0 auto; padding:5px;" cellspacing="7">
                    <tr>
                        <td>
                            <?php
                            $controller = !empty($back_opt) ? 'SupervisionModuleReplyOrExplanationOfMfiDetails' : 'SupervisionModulePrepareLetterDetails';
                            echo $this->Js->link('Close', array('controller' => $controller, 'action' => 'view?this_state_ids=' . $this_state_ids), array_merge($pageLoading, array('class' => 'mybtns')));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            echo $this->Js->submit('Save', array_merge($pageLoading, array(
                                'url' => "/SupervisionModulePrepareLetterDetails/prepare_letter/$supervision_basic_id/1",
                                'confirm' => "Are you sure to Save ?",
                                'success' => "msg.init('success', '$title', 'Letter has been saved successfully.');",
                                'error' => "msg.init('error', '$title', 'saving failed!');")));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            echo $this->Js->submit('Submit to Section', array_merge($pageLoading, array(
                                'url' => "/SupervisionModulePrepareLetterDetails/prepare_letter/$supervision_basic_id/2",
                                'confirm' => "Are you sure to Submit ?",
                                'success' => "msg.init('success', '$title', 'Letter has been submitted to section successfully.');",
                                'error' => "msg.init('error', '$title', 'submission failed!');")));
                            ?>
                        </td>
                    </tr>
                </table>
            </div> 
            <?php
            echo $this->Form->end();
        }
        ?>

    </fieldset>
</div>

<script type="text/javascript">

    $(function () {
        var counter = 0;
        $('#add_button').click(function (event) {
            event.preventDefault();
            var serial_no = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.serial_no', array('type' => 'text', 'class' => 'integers', 'style' => 'width:25px; padding:4px; text-align:center;', 'label' => false))); ?>;
            serial_no = serial_no.replace(/replace_with_counter/g, counter);

            var findings = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.findings', array('type' => 'text', 'style' => 'width:200px;', 'label' => false))); ?>;
            findings = findings.replace(/replace_with_counter/g, counter);

            var mfis_reply = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.mfis_reply', array('type' => 'text', 'style' => 'width:200px;', 'label' => false))); ?>;
            mfis_reply = mfis_reply.replace(/replace_with_counter/g, counter);

            var comments = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.comments', array('type' => 'text', 'style' => 'width:200px;', 'label' => false))); ?>;
            comments = comments.replace(/replace_with_counter/g, counter);

            var newRow = $(
                    "<tr><td>" + serial_no + "</td>" +
                    "<td>" + findings + "</td>" +
                    "<td>" + mfis_reply + "</td>" +
                    "<td>" + comments + "</td>" +
                    "<td><a id='remove_button' href='#' onclick='deleteRow(this);' class='mybtns'>Remove</a></td></tr>"

                    );
            counter++;
            $('#my_table').append(newRow).show();
        });
    });

    function deleteRow(obj) {
        $(obj).closest('tr').remove();
        if ($('#my_table tr').length < 2) {
            $('#my_table').hide();
        }
    }

</script>