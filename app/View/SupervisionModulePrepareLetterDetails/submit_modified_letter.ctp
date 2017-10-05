<?php
$title = "Modification of Letter Before Submit";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
$comments_or_notes_of_inspector = $letters = '';

if (!empty($this->request->data['SupervisionModulePrepareLetterDetail'])) {
    $comments_or_notes_of_inspector = $this->request->data['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_inspector'];
    $letters = $this->request->data['SupervisionModulePrepareLetterDetail']['letters'];
}

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
        <?php if (!empty($letter_serial_no) && $letter_serial_no > 1) { ?>
            <div class="form">
                <table>
                    <tr>
                        <td style="vertical-align: top; width: 400px; height: 200px;">
                            <fieldset>
                                <legend>Last Letter to <strong><?php echo $orgName; ?></strong> on <strong><?php echo $this->Session->read($org_id); ?></strong></legend>
                                <?php echo $this->requestAction(array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'show_previous_letter', $supervision_basic_id), array('return')); ?>
                            </fieldset>
                        </td>
                        <td style="vertical-align: top; width: 400px; height: 200px;">
                            <fieldset>
                                <legend>Last Explanation of <strong><?php echo $orgName; ?></strong></legend>
                                <?php echo $this->requestAction(array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'show_explanation_against_previous_letter', $supervision_basic_id), array('return')); ?>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        }
        echo $this->Form->create('SupervisionModulePrepareLetterDetail');
        ?>
        <div class="form">            
            <table cellpadding="6" cellspacing="7" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="font-weight: bold;">
                        <?php echo $orgName . $this->Js->link('Details', array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'preview', $supervision_basic_id), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div'))); ?>
                    </td>
                </tr>
                <?php if (!empty($letter_serial_no) && $letter_serial_no > 1) { ?>
                    <tr>
                        <td colspan="3" style="padding-top:5px;">                        
                            <table id='my_table' class="view">
                                <tr>
                                    <th style="width:20px;">SL No.</th>
                                    <th style="width:200px;">Findings</th>
                                    <th style="width:200px;">MFI's Reply</th>
                                    <th style="width:200px;">Comments</th>
                                </tr>
                                <?php
                                if (!empty($existing_findings_values)) {
                                    $counter = 0;
                                    foreach ($existing_findings_values as $existing_findings_value) {
                                        $serial_no = $existing_findings_value['SupervisionModuleFindingsDetail']['serial_no'];
                                        $findings = $existing_findings_value['SupervisionModuleFindingsDetail']['findings'];
                                        $mfis_reply = $existing_findings_value['SupervisionModuleFindingsDetail']['mfis_reply'];
                                        $comments = $existing_findings_value['SupervisionModuleFindingsDetail']['comments'];
                                        $counter++;
                                        ?>  
                                        <tr>
                                            <td><?php echo $this->Form->input('SupervisionModuleFindingsDetail.' . $counter . '.serial_no', array('type' => 'text', 'value' => $serial_no, 'class' => 'integers', 'style' => 'width:20px;', 'label' => false)); ?></td>
                                            <td><?php echo $this->Form->input('SupervisionModuleFindingsDetail.' . $counter . '.findings', array('type' => 'text', 'value' => $findings, 'class' => 'integers', 'style' => 'width:200px;', 'label' => false)); ?></td>
                                            <td><?php echo $this->Form->input('SupervisionModuleFindingsDetail.' . $counter . '.mfis_reply', array('type' => 'text', 'value' => $mfis_reply, 'class' => 'integers', 'style' => 'width:200px;', 'label' => false)); ?></td>
                                            <td><?php echo $this->Form->input('SupervisionModuleFindingsDetail.' . $counter . '.comments', array('type' => 'text', 'value' => $comments, 'class' => 'integers', 'style' => 'width:200px;', 'label' => false)); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </table><br />
                            <a id="add_button" href="#" class="mybtns">Add Comments</a><br />
                        </td>
                    </tr>
                    <?php
                    echo $this->Form->input('SupervisionModulePrepareLetterDetail.comments_or_notes_of_inspector', array('type' => 'hidden', 'value' => $comments_or_notes_of_inspector, 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:80px;'));
                } else {
                    ?>
                    <tr>
                        <td valign="top">Inspector's Comments/Notes</td>
                        <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                        <td><?php echo $this->Form->input('SupervisionModulePrepareLetterDetail.comments_or_notes_of_inspector', array('type' => 'textarea', 'value' => $comments_or_notes_of_inspector, 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:80px;')); ?></td>
                    </tr>
                <?php } ?>                
                <tr>
                    <td valign="top">Letter No.</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td style="font-weight:bold;">
                        <?php
                        //echo $letter_captions;

                        $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
                        echo $nf->format($letter_serial_no) . " Letter";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Letter</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('SupervisionModulePrepareLetterDetail.letters', array('type' => 'textarea', 'value' => $letters, 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 580px; height:200px;')); ?></td>
                </tr>                
            </table>                       
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'view?this_state_ids=' . $this_state_ids), array_merge($pageLoading, array('class' => 'mybtns'))); ?> 
                    </td>
                    <td>
                        <?php echo $this->Js->submit('Submit to Section', array_merge($pageLoading, array('success' => "msg.init('success', '$title', 'Letter has been submitted to section successfully.');", 'error' => "msg.init('error', '$title', 'save failed!');"))); ?>                       
                    </td>                    
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
    });

    $(function () {
        var counter = 0;
        $('#add_button').click(function (event) {
            event.preventDefault();
            var serial_no = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.serial_no', array('type' => 'text', 'class' => 'integers', 'style' => 'width:20px;', 'label' => false))); ?>;
            serial_no = serial_no.replace(/replace_with_counter/g, counter);

            var findings = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.findings', array('type' => 'text', 'style' => 'width:200px;', 'label' => false))); ?>;
            findings = findings.replace(/replace_with_counter/g, counter);

            var mfis_reply = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.mfis_reply', array('type' => 'text', 'style' => 'width:150px;', 'label' => false))); ?>;
            mfis_reply = mfis_reply.replace(/replace_with_counter/g, counter);

            var comments = <?php echo json_encode($this->Form->input('SupervisionModuleFindingsDetail.replace_with_counter.comments', array('type' => 'text', 'style' => 'width:150px;', 'label' => false))); ?>;
            comments = comments.replace(/replace_with_counter/g, counter);

            var field_no = counter + 1;

            var newRow = $(
                    "<tr><td>" + serial_no + "</td>" +
                    "<td>" + findings + "</td>" +
                    "<td>" + mfis_reply + "</td>" +
                    "<td>" + comments + "</td>" +
                    "<td><a id='remove_button' href='#' onclick='deleteRow(this);' class='mybtns'>Remove</a></td></tr>"
                    );
            counter++;
            $('#my_table').append(newRow);
        });
    });

    function deleteRow(obj) {
        $(obj).closest('tr').remove();
    }
</script>