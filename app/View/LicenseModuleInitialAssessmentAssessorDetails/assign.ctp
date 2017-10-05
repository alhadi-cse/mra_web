<div>
    <?php
    $title = "Assign Assessor for Initial Assessment";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">

            <?php if (!empty($values_assigned)) { ?>
                <fieldset>
                    <legend>Assigned</legend>
                    <table cellpadding="0" cellspacing="0" border="0" class="view">
                        <tr>
                            <th style="width:25px;" rowspan="2">Sl. No.</th>
                            <th style="width:auto;" rowspan="2">Assessors</th>
                            <th style="width:200px;" colspan="2">Form No.</th>
                        </tr>
                        <tr>
                            <th style="width:100px;">From</th>
                            <th style="width:100px;">To</th>
                        </tr>
                        <?php
                        $assessor_count = 0;
                        foreach ($values_assigned as $value) {
                            ?>
                            <tr <?php echo ($assessor_count % 2 != 0) ? ' class="alt"' : ''; ?>>
                                <td style="text-align:center;">
                                    <?php
                                    ++$assessor_count;
                                    echo "$assessor_count.";
                                    ?>
                                </td>
                                <td><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['name_with_designation_and_dept']; ?></td>
                                <td style="text-align:center; font-weight:bold;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['from_form_no']; ?></td>  
                                <td style="text-align:center; font-weight:bold;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['to_form_no']; ?></td>                                      
                            </tr>
                        <?php } ?>
                    </table>
                </fieldset>
            <?php } ?>

            <?php
            $assessor_count = 0;
            $total_no_of_form = 0;
            $total_no_of_assessor = 0;

            echo $this->Form->create('LicenseModuleInitialAssessmentAssessorDetail');

            if (!empty($min_form_serial) && !empty($max_form_serial)) {
                $total_no_of_form = $max_form_serial - $min_form_serial + 1;
                $total_no_of_assessor = count($assessor_list);

                if ($total_no_of_form > 0 && $total_no_of_assessor > 0) {

                    if ($total_no_of_assessor > 0) {
                        $form_distribution_factor = (int) floor($total_no_of_form / $total_no_of_assessor);
                        $pending_form_no_distributed_to_last = $total_no_of_form % $total_no_of_assessor;
                    } else {
                        $form_distribution_factor = 0;
                        $pending_form_no_distributed_to_last = 0;
                    }

                    $min_form_no = $min_form_serial;
                    $max_form_no = 0;
                    ?>

                    <fieldset>
                        <legend>Pending</legend>
                        <table cellpadding="0" cellspacing="0" border="0" class="view">
                            <tr>
                                <th style="width:25px;" rowspan="2">Sl. No.</th>
                                <th style="width:auto;" rowspan="2">Assessors</th>
                                <th style="width:200px;" colspan="2">Form No.</th>
                            </tr>
                            <tr>
                                <th style="width:100px;">From</th>
                                <th style="width:100px;">To</th>
                            </tr>

                            <?php
                            foreach ($assessor_list as $assessor_user_id => $assessor_name_with_desig) {
                                try {
                                    if ($max_form_no > 0)
                                        $min_form_no = $max_form_no + 1;

                                    if ($form_distribution_factor > 1)
                                        $max_form_no = $min_form_no + $form_distribution_factor - 1;
                                    else
                                        $max_form_no = $min_form_no;

                                    if ($max_form_no > $max_form_serial)
                                        $max_form_no = $max_form_serial;
                                    else if ($total_no_of_assessor - 1 == $assessor_count)
                                        $max_form_no = $max_form_no + $pending_form_no_distributed_to_last;

                                    if ($min_form_no > $max_form_no)
                                        break;
                                } catch (Exception $ex) {
                                    $min_form_no = $max_form_no = "";
                                }
                                ?>

                                <tr <?php echo ($assessor_count % 2 != 0) ? ' class="alt"' : ''; ?>>
                                    <td style="text-align:center;">
                                        <?php
                                        ++$assessor_count;
                                        echo "$assessor_count.";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $assessor_name_with_desig;
                                        echo $this->Form->input("$assessor_count.assessor_user_id", array('type' => 'hidden', 'label' => false, 'value' => $assessor_user_id));
                                        ?>
                                    </td>
                                    <td style="text-align:center;"><?php echo $this->Form->input("$assessor_count.from_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'value' => $min_form_no, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                                    <td style="text-align:center;"><?php echo $this->Form->input("$assessor_count.to_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'value' => $max_form_no, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </fieldset>

                    <?php
                } else {
                    if ($total_no_of_form < 1)
                        echo "<p style='font-weight:bold;color:red;'>There is no pending form !</p>";

                    if ($total_no_of_assessor < 1)
                        echo "<p style='font-weight:bold;color:red;'>There is no free Assessor to assign !</p>";
                }
            }
            ?>

            <div class="btns-div"> 
                <table cellspacing="7">
                    <tr>
                        <td>
                            <?php
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialAssessmentAssessorDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            if ($total_no_of_assessor > 0 && $total_no_of_form > 0) {
                                echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                                    'error' => "msg.init('error', '$title', 'Assessor assign failed !');")));
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <?php echo $this->Form->end(); ?>

        </div>
    </fieldset>
</div>

<script>
    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
    });
</script>
