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
                <legend>Field Inspection/Queries Note:</legend>

                <table cellpadding="3" cellspacing="3" border="0" style="width:95%;">
                    <tr>
                        <td style="width:28%;">a. Inspection Date</td>
                        <td class="colons">:</td>
                        <td style="width:70%;">
                            <?php
                            echo $this->Form->input("inspection_date", array('type' => 'hidden', 'id' => 'txtInspectionDate_alt', 'label' => false, 'div' => false))
                            . "<input type='text' id='txtInspectionDate' class='date_picker' style='width:80px !important;' />";
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
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been save successfully.');",
                            'error' => "msg.init('error', '$title', 'save failed!');")));
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
