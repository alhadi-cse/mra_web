
<div>
    <?php
    
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    
    if (!empty($inspection_type_id) && !empty($inspection_type_detail[$inspection_type_id]))
        $title = "$inspection_type_detail[$inspection_type_id] Details";
    else
        $title = 'Field Inspection Details';

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleFieldInspectionDetail'); ?>

        <div class="form">
            <p style="border-bottom: 2px solid #137387; padding:0 0 0 15px;">
                Name of Organization: 
                <?php
                if (isset($orgName)) {
                    echo '<strong>' . $orgName . '</strong>';
                    echo $this->Js->link('Assess Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'display:inline-block;', 'update' => '#popup_div')));
                }
                ?>
            </p>

            <?php
            $grc = 0;
            if (!empty($this->request->data['dynamic']) && !empty($parameterGroupList)) {
                $posted_dynamic_data = $this->request->data['dynamic'];

                $parameter_slno = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't');
                $grc = 0;
                $sln = -1;
                foreach ($parameterGroupList as $group_id => $parameterGroup) {
                    $parameters = Hash::extract($parameterList, "{n}.LookupLicenseInspectionParameter[parameter_group_id = $group_id]");

                    if (empty($parameters))
                        continue;

                    ++$grc;
                    ?>

                    <fieldset>
                        <legend><?php echo ($grc < 10 ? '0' : '') . "$grc. $parameterGroup:"; ?></legend>

                        <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                            <?php
                            $prc = -1;
                            foreach ($parameters as $parameter) {

                                if (empty($parameter))
                                    continue;

                                ++$prc;
                                $parameter_id = $parameter['id'];
                                $parameter_name = $parameter['parameter_name'];
                                $parameter_type = $parameter['parameter_type'];

                                $parameter_value = isset($posted_dynamic_data[$parameter_id]) ? $posted_dynamic_data[$parameter_id] : null;
                                ?>
                                <tr>
                                    <td><?php echo "$parameter_slno[$prc]. $parameter_name"; ?></td>
                                    <td class="colons">:</td>
                                    <td style="width:25%;">
                                        <?php
                                        ++$sln;
                                        echo $this->Form->input("dynamic.$sln.parameter_id", array('type' => 'hidden', 'value' => $parameter_id, 'label' => false));
                                        echo $this->Form->input("dynamic.$sln.parameter_type", array('type' => 'hidden', 'value' => $parameter_type, 'label' => false));

                                        switch ($parameter_type) {
                                            case 'radio':
                                            case 'select':
                                                echo $this->Form->input("dynamic.$sln.parameter_value", array('type' => $parameter_type, 'value' => $parameter_value, 'options' => $option_values, 'legend' => false));
                                                //echo $this->Form->input("dynamic.$sln.parameter_value_int", array('type' => $parameter_type, 'options' => $option_values, 'legend' => false));
                                                break;

                                            case 'text':
                                            case 'textarea':
                                                echo $this->Form->input("dynamic.$sln.parameter_value_text", array('type' => $parameter_type, 'value' => $parameter_value, 'label' => false, 'div' => false));
                                                break;

                                            case 'date':
                                                echo $this->Form->input("dynamic.$sln.parameter_value_date", array('type' => 'hidden', 'id' => 'txtInspectionDate_alt', 'label' => false))
                                                . "<input type='text' id='txtInspectionDate' class='date_picker' />";
                                                break;

                                            case 'datenow':
                                            case 'datecurrent':
                                                echo $this->Form->input("dynamic.$sln.parameter_value_date", array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false));
                                                echo date('d-m-Y');
                                                break;

                                            case 'label':
                                            case 'inspector':
                                                echo!empty($inspector_names) ? $inspector_names : "<p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p>";
                                                break;

                                            default:
                                                echo $this->Form->input("dynamic.$sln.parameter_value", array('type' => 'radio', 'value' => $parameter_value, 'options' => $option_values, 'legend' => false));
                                                break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </fieldset>
                    <?php
                }
            }

            if (!empty($this->request->data['basic'])) {
                $posted_basic_data = $this->request->data['basic'];
                ?>

                <fieldset>
                    <legend><?php echo ( ++$grc < 10 ? '0' : '') . "$grc"; ?>. Field Inspection Note:</legend>

                    <?php
                    if (empty($inspection_type_id))
                        $inspection_type_id = $posted_basic_data['inspection_type_id'];

                    if (empty($inspection_slno))
                        $inspection_slno = $posted_basic_data['inspection_slno'];

                    echo $this->Form->input('basic.id', array('type' => 'hidden', 'label' => false, 'div' => false))
                    . $this->Form->input('basic.org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false, 'div' => false))
                    . $this->Form->input('basic.inspection_type_id', array('type' => 'hidden', 'value' => $inspection_type_id, 'label' => false, 'div' => false))
                    . $this->Form->input('basic.inspection_slno', array('type' => 'hidden', 'value' => $inspection_slno, 'label' => false, 'div' => false));
                    ?>

                    <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                        <tr>
                            <td style="width:28%;">a. Inspection Date</td>
                            <td class="colons">:</td>
                            <td style="width:70%;">
                                <?php
                                $inspection_date = $posted_basic_data['inspection_date'];
                                $inspection_date = (!empty($inspection_date) ? date("d-m-Y", strtotime($inspection_date)) : '');
                                echo $this->Form->input("basic.inspection_date", array('type' => 'hidden', 'id' => 'txtInspectionDate_alt', 'div' => false, 'label' => false))
                                . "<input type='text' id='txtInspectionDate' value='$inspection_date' class='date_picker' />";
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>b. Submission Date</td>
                            <td class="colons">:</td>
                            <td style="padding:5px;">
                                <?php
                                echo $this->Form->input("basic.submission_date", array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false));
                                echo date('d-m-Y');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:5px; vertical-align:top;">c. Inspection Note/Comment</td>
                            <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                            <td style="padding-top:5px; vertical-align:top;"><?php echo $this->Form->input('basic.inspection_note', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false)); ?></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend><?php echo ( ++$grc < 10 ? '0' : '') . "$grc"; ?>. Recommendation:</legend>
                    <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                        <?php if ($inspection_type_id == 1 || $inspection_type_id == 2) { ?>
                        <tr>
                            <td>Inspectors Recommendation</td>
                            <td class="colons">:</td>
                            <td style="width:65%; font-weight:bold;">
                                <?php
                                echo $this->Form->input("basic.inspector_recommendation", array('type' => 'radio', 'options' => $recommendation_status_options, 'div' => false, 'legend' => false));
                                echo $this->Form->input("basic.is_approved", array('type' => 'hidden', 'value' => '-1', 'label' => false));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <?php if (!empty($inspector_names)) { ?>
                                <td style="padding-top:5px; vertical-align:top;">Inspectors Name & Designation</td>
                                <td class="colons" style="vertical-align:top;">:</td>
                                <td style="width:65%; padding:5px 5px 10px 10px;"><?php echo $inspector_names; ?></td>
                                <?php
                            } else {
                                echo "<td colspan='3'><p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p></td>";
                            }
                            ?>
                        </tr>
                    </table>
                </fieldset>

            <?php } ?>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'view', '?' => array('inspection_type_id' => $inspection_type_id, 'licensed_mfi' => $licensed_mfi));
                        //$redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'view');
                        echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        if (!empty($org_id) && !empty($inspection_type_id) && !empty($inspection_slno)) {
                            echo $this->Js->submit('Update', array_merge($pageLoading, array('url' => "/LicenseModuleFieldInspectionDetails/re_inspection/$org_id/$inspection_type_id/$inspection_slno/0",
                                'success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                                'error' => "msg.init('error', '$title', 'Update failed!');")));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            echo $this->Js->submit('Submit', array_merge($pageLoading, array('url' => "/LicenseModuleFieldInspectionDetails/re_inspection/$org_id/$inspection_type_id/$inspection_slno/1",
                                'confirm' => "Are you sure to Submit ?",
                                'success' => "msg.init('success', '$title', '$title has been submit successfully.');",
                                'error' => "msg.init('error', '$title', 'submit failed!');")));
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
