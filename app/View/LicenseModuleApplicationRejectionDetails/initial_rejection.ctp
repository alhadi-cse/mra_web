<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = 'Initial Rejection';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>

            <?php echo $this->Form->create('LicenseModuleApplicationReject'); ?>

            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">

                    <tr>
                        <td>Form No.</td>
                        <td class="colons">:</td>
                        <td style="font-weight:bold;">
                            <?php
                            echo $orgDetail['BasicModuleBasicInformation']['form_serial_no'];
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];
                            $mfiShortName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];

                            echo $mfiName . ((!empty($mfiName) && !empty($mfiShortName)) ? " (<strong>" . $mfiShortName . "</strong>)" : $mfiShortName);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Total Marks Obtained</td>
                        <td class="colons">:</td>
                        <td style="font-weight:bold;">
                            <?php
                            $obtained_marks = $orgDetail['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                            if (!empty($total_marks) && $total_marks != 0) {
                                echo '<span style="color:#fa2413;">'
                                . $this->Number->toPercentage(($obtained_marks / $total_marks) * 100) . '</span> ('
                                . $this->Number->precision($obtained_marks, 1) . ')';
                            } else
                                echo $this->Number->precision($obtained_marks, 1);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Rejection Reason</td>
                        <td class="colons">:</td>
                        <td>
                            <?php echo $this->Form->input("rejection_option_id", array('type' => 'select', 'options' => $rejection_options, 'empty' => '-----Select-----', 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="btns-div">
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td style="text-align:right;">
                            <?php
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'view', 4, 21, 50), array_merge($pageLoading, array('class' => 'mybtns')));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            echo $this->Js->submit('Reject and Notify to MFI', array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to reject this MFI ?',
                                'success' => "msg.init('success', '$title', '$title has been successfully completed.');",
                                'error' => "msg.init('error', '$title', '$title failed !');")));
                            ?>
                        </td>
                    </tr>
                </table>
            </div>


            <?php echo $this->Form->end(); ?>

        </fieldset>
    </div>

<?php } ?>
