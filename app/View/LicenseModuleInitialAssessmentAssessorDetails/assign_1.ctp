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
                            <th>Name of Assessor</th>
                            <th>Designation & Department</th>
                            <th>From Form No.</th>
                            <th>To Form No.</th>
                        </tr>
                        <?php
                        $rc = 0;
                        foreach ($values_assigned as $value) {
                            ?>
                            <tr <?php echo ($rc % 2 != 0) ? ' class="alt"' : ''; ?>>
                                <td style="padding:7px;"><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                                <td style="text-align:center;">
                                    <?php
                                    echo $value['AdminModuleUserProfile']['designation_of_user']
                                    . ((!empty($value['AdminModuleUserProfile']['designation_of_user']) && !empty($value['AdminModuleUserProfile']['div_name_in_office'])) ? ', ' : '')
                                    . $value['AdminModuleUserProfile']['div_name_in_office'];
                                    ?>
                                </td>
                                <td style="text-align:center; font-weight:bold;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['from_form_no']; ?></td>  
                                <td style="text-align:center; font-weight:bold;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['to_form_no']; ?></td>                                      
                            </tr>

                            <?php
                            $rc++;
                        }
                        ?>
                    </table>
                </fieldset>
            <?php } ?>

            <?php
            $total_no_of_form = 0;
            $total_no_of_assessor = 0;

            echo $this->Form->create('LicenseModuleInitialAssessmentAssessorDetail');

            if (!empty($min_form_serial) && !empty($max_form_serial)) {
                $rc = 0;
                $total_no_of_form = $max_form_serial - $min_form_serial + 1;
                $total_no_of_assessor = count($assessor_list);

                if ($total_no_of_form > 0 && $total_no_of_assessor > 0) {

                    if ($total_no_of_assessor > 1) {
                        $form_distribution_factor = floor($total_no_of_form / $total_no_of_assessor);
                        $pending_form_no_distributed_to_last = $total_no_of_form % $total_no_of_assessor;
                    } else {
                        $form_distribution_factor = 0;
                        $pending_form_no_distributed_to_last = 0;
                    }

                    $min_form_no = $min_form_serial;
                    $max_form_no = 0;
                    $counter_of_assessor = 0;
                    ?>

                    <fieldset>
                        <legend>Pending</legend>
                        <table cellpadding="0" cellspacing="0" border="0" class="view">
                            <tr>
                                <th style="padding:5px;">Name of Assessor</th>
                                <th>Designation & Department</th>
                                <th>From Form No.</th>
                                <th>To Form No.</th>
                            </tr>

                            <?php
                            foreach ($assessor_list as $assessorDetails) {
                                $assessor_user_id = $assessorDetails['AdminModuleUserProfile']['user_id'];
                                $assessor_name = $assessorDetails['AdminModuleUserProfile']['full_name_of_user'];

                                $assessor_desig_dept = $assessorDetails['AdminModuleUserProfile']['designation_of_user']
                                        . ((!empty($assessorDetails['AdminModuleUserProfile']['designation_of_user']) && !empty($assessorDetails['AdminModuleUserProfile']['div_name_in_office'])) ? ', ' : '')
                                        . $assessorDetails['AdminModuleUserProfile']['div_name_in_office'];


                                if (is_numeric($max_form_no)) {
                                    if ($max_form_no > 0)
                                        $min_form_no = $max_form_no + 1;

                                    if ($form_distribution_factor > 1)
                                        $max_form_no = $min_form_no + $form_distribution_factor - 1;
                                    else
                                        $max_form_no = $min_form_no + $form_distribution_factor;

                                    if ($max_form_no > $max_form_serial)
                                        $min_form_no = $max_form_no = "";
                                    else if ($total_no_of_assessor - 1 == $counter_of_assessor)
                                        $max_form_no = $max_form_no + $pending_form_no_distributed_to_last;
                                } else {
                                    $min_form_no = $max_form_no = "";
                                }
                                ?>

                                <tr <?php echo ($rc % 2 != 0) ? ' class="alt"' : ''; ?>>
                                    <td style="text-align:left; padding-left:5px;">
                                        <?php
                                        echo $assessor_name;
                                        echo $this->Form->input("$rc.assessor_user_id", array('type' => 'hidden', 'label' => false, 'value' => $assessor_user_id));
                                        ?>
                                    </td>
                                    <td style="text-align:center;"><?php echo $assessor_desig_dept; ?></td>
                                    <td style="text-align:center;"><?php echo $this->Form->input("$rc.from_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'value' => $min_form_no, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                                    <td style="text-align:center;"><?php echo $this->Form->input("$rc.to_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'value' => $max_form_no, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                                </tr>
                                <?php
                                $rc++;
                                ++$counter_of_assessor;
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
                                    'error' => "msg.init('error', '$title', 'Insertion failed !');")));
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
