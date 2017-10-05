
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
    $message = 'Inspection report has been updated successfully.';
    if(!$is_inspector_group) {
        $message = $message.' Please login as inspector user to go to the next state';  
    }
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('SupervisionModuleFieldInspectionDetail'); ?>

        <div class="form">
            <p style="border-bottom:2px solid #137387; padding:0 0 0 15px;">
                Name of Organization: <?php if (!empty($orgName)) echo '<strong>' . $orgName . '</strong>'; ?>
            </p>

            <fieldset>
                <legend>Field Inspection Note:</legend>

                <table cellpadding="3" cellspacing="3" border="0" style="width:95%;">
                    <tr>
                        <td style="width:28%;">a. Inspection Date</td>
                        <td class="colons">:</td>
                        <td style="width:70%;">
                            <?php
                            echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'div' => false))
                                    . $this->Form->input("inspection_date", array('type' => 'hidden', 'id' => 'txtInspectionDate_alt', 'div' => false, 'label' => false));

                            if (!empty($this->request->data['SupervisionModuleFieldInspectionDetail']['inspection_date'])) {
                                $inspection_date = $this->request->data['SupervisionModuleFieldInspectionDetail']['inspection_date'];
                                $inspection_date = date("d-m-Y", strtotime($inspection_date));
                            } else {
                                $inspection_date = '';
                            }

                            echo "<input type='text' id='txtInspectionDate' value='$inspection_date' class='date_picker' />";
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>b. Submission Date</td>
                        <td class="colons">:</td>
                        <td style="padding:5px;">
                            <?php
                            echo $this->Form->input("submission_date", array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false));
                            echo date('d-m-Y');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:5px; vertical-align:top;">c. Inspection Note/Comment</td>
                        <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                        <td style="padding-top:5px; vertical-align:top;"><?php echo $this->Form->input('inspection_note', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <?php if (!empty($inspector_names)) { ?>
                            <td style="padding-top:5px; vertical-align:top;">Inspectors Name & Designation</td>
                            <td class="colons" style="vertical-align:top;">:</td>
                            <td style="width:65%; padding:5px 5px 10px 5px;"><?php echo $inspector_names; ?></td>
                            <?php
                        } else {
                            echo "<td colspan='3'><p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p></td>";
                        }
                        ?>
                    </tr>
                </table>
            </fieldset>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'view');
                        echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        if (!empty($supervision_basic_id)) {
                            echo $this->Js->submit('Update', array_merge($pageLoading, array('url' => "/SupervisionModuleFieldInspectionDetails/re_inspection/$supervision_basic_id/0",
                                'success' => "msg.init('success', '$title', '$message');",
                                'error' => "msg.init('error', '$title', 'Update failed!');")));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php                            
                            echo $this->Js->submit('Submit', array_merge($pageLoading, array('url' => "/SupervisionModuleFieldInspectionDetails/re_inspection/$supervision_basic_id/1",
                                'confirm' => "Are you sure to Submit ?",
                                'success' => "msg.init('success', '$title', '$message');",
                                'error' => "msg.init('error', '$title', 'submition failed !');")));
                        }
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

        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();

        $('.date_picker').each(function () {
            $(this).datepicker({
                showAnim: 'show',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
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
