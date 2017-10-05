<div>
    <?php
    //$assessor_group_id = $this->Session->read('Assessor.GroupId');
    $title = "Re-assign Assessor for Initial Assessment";
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

        <?php
        if (!empty($form_serial_no) && !empty($form_serial_no["min_form_no"]) && !empty($form_serial_no["max_form_no"]))
            echo '<p style="font-size:11pt; font-weight:bold; text-align:center;">Pending Form No. (<span style="color:#fa4513;">'
            . $form_serial_no["min_form_no"] . '</span> to <span style="color:#fa4513;">'
            . $form_serial_no["max_form_no"] . '</span>)</p>';

//        if (!empty($min_form_serial) && !empty($max_form_serial))
//            echo '<p style="font-size:11pt; font-weight:bold; text-align:center;">Pending Form No. (<span style="color:#fa4513;">' . $min_form_serial . '</span> to <span style="color:#fa4513;">' . $max_form_serial . '</span>)</p>';

        echo $this->Form->create('LicenseModuleInitialAssessmentAssessorDetail');
        if (!empty($values_assigned)) {
            ?>
            <div class="form">

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
                    $rc = 0;
                    foreach ($values_assigned as $value) {
                        $assessor_user_id = $value['AdminModuleUserProfile']['user_id'];
                        $assessor_name_with_desig = $value['LicenseModuleInitialAssessmentAssessorDetail']['name_with_designation_and_dept'];

                        $from_form_no = $value['LicenseModuleInitialAssessmentAssessorDetail']['from_form_no'];
                        $to_form_no = $value['LicenseModuleInitialAssessmentAssessorDetail']['to_form_no'];
                        ?>

                        <tr <?php echo ($rc % 2 != 0) ? ' class="alt"' : ''; ?>>
                            <td style="text-align:center;">
                                <?php
                                ++$rc;
                                echo "$rc.";
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $assessor_name_with_desig;
                                echo $this->Form->input("$rc.assessor_user_id", array('type' => 'hidden', 'label' => false, 'value' => $assessor_user_id));
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $this->Form->input("$rc.from_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'value' => $from_form_no, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                            <td style="text-align:center;"><?php echo $this->Form->input("$rc.to_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'value' => $to_form_no, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                        </tr>
                    <?php } ?>

                    <?php
                    //$rc = 0;
//                    foreach ($assessor_list as $assessorDetails) {
//                        $assessor_user_id = $assessorDetails['AdminModuleUserProfile']['user_id'];
//                        $assessor_name = $assessorDetails['AdminModuleUserProfile']['full_name_of_user'];
//
//                        $assessor_desig_dept = $assessorDetails['AdminModuleUserProfile']['designation_of_user']
//                                . ((!empty($assessorDetails['AdminModuleUserProfile']['designation_of_user']) && !empty($assessorDetails['AdminModuleUserProfile']['div_name_in_office'])) ? ', ' : '')
//                                . $assessorDetails['AdminModuleUserProfile']['div_name_in_office'];

                    foreach ($assessor_list as $assessor_user_id => $assessor_name_with_desig) {
                        ?>

                        <tr <?php echo ($rc % 2 != 0) ? ' class="alt"' : ''; ?>>
                            <td style="text-align:center;">
                                <?php
                                ++$rc;
                                echo "$rc.";
                                ?>
                            </td>
                            <td style="padding-left:5px;">
                                <?php
                                echo $assessor_name_with_desig;
                                echo $this->Form->input("$rc.assessor_user_id", array('type' => 'hidden', 'label' => false, 'value' => $assessor_user_id));
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $this->Form->input("$rc.from_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                            <td style="text-align:center;"><?php echo $this->Form->input("$rc.to_form_no", array('type' => 'text', 'class' => 'integers', 'div' => false, 'label' => false, 'style' => 'width:100px; padding:3px; text-align:center;')); ?></td>
                        </tr>
                    <?php } ?>

                </table>
            </div>

        <?php } ?>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialAssessmentAssessorDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        if (!empty($values_assigned))
                            echo $this->Js->submit('Update', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been successfully.');",
                                'error' => "msg.init('error', '$title', 'Re-assign failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>

<script>
    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
        //$('.decimals').numeric({ decimal: ".", negative: false });
    });
</script>
