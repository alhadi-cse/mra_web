
<div>
    <?php
    if (!empty($inspection_type_id) && !empty($inspection_type_detail[$inspection_type_id]))
        $title = "$inspection_type_detail[$inspection_type_id] Details";
    else
        $title = 'Field Inspection Details';
    ?>

    <fieldset style="margin-bottom:10px;">
        <legend><?php echo $title; ?></legend>

        <?php
        if (!empty($this->request->data['basic']) || !empty($this->request->data['dynamic'])) {

            echo $this->Form->create('LicenseModuleFieldInspectionDetail');

            $grc = 0;
            if (!empty($this->request->data['dynamic']) && !empty($parameterGroupList)) {

                $posted_dynamic_data = $this->request->data['dynamic'];
                $parameter_slno = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't');
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
                                                echo $this->Form->input("dynamic.$sln.parameter_value", array('type' => $parameter_type, 'value' => $parameter_value, 'options' => $option_values, 'legend' => false, 'disabled' => 'disabled'));
                                                //echo $this->Form->input("dynamic.$sln.parameter_value_int", array('type' => $parameter_type, 'options' => $option_values, 'legend' => false));
                                                break;

                                            case 'text':
                                            case 'textarea':
                                                echo $this->Form->input("dynamic.$sln.parameter_value_text", array('type' => $parameter_type, 'value' => $parameter_value, 'label' => false, 'div' => false, 'disabled' => 'disabled'));
                                                break;

                                            case 'date':
                                                echo $this->Form->input("dynamic.$sln.parameter_value_date", array('type' => 'hidden', 'id' => 'txtInspectionDate_alt', 'label' => false, 'disabled' => 'disabled'))
                                                . "<input type='text' id='txtInspectionDate' class='date_picker' />";
                                                break;

                                            case 'datenow':
                                            case 'datecurrent':
                                                echo $this->Form->input("dynamic.$sln.parameter_value_date", array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false, 'disabled' => 'disabled'));
                                                echo date('d-m-Y');
                                                break;

                                            case 'label':
                                            case 'inspector':
                                                echo!empty($inspector_names) ? $inspector_names : "<p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p>";
                                                break;

                                            default:
                                                echo $this->Form->input("dynamic.$sln.parameter_value", array('type' => 'radio', 'value' => $parameter_value, 'options' => $option_values, 'legend' => false, 'disabled' => 'disabled'));
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

                if (empty($org_id) && !empty($posted_basic_data['org_id']))
                    $org_id = $posted_basic_data['org_id'];
                ?>

                <fieldset>
                    <legend><?php echo ( ++$grc < 10 ? '0' : '') . "$grc"; ?>. Field Inspection Note:</legend>
                    <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                        <tr>
                            <td style="width:230px; max-width:28%;">a. Inspection Date</td>
                            <td class="colons">:</td>
                            <td style="width:70%;">
                                <?php
                                $inspection_date = $posted_basic_data['inspection_date'];
                                echo (!empty($inspection_date) ? date("d-m-Y", strtotime($inspection_date)) : '');
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>b. Submission Date</td>
                            <td class="colons">:</td>
                            <td>
                                <?php
                                $submission_date = $posted_basic_data['submission_date'];
                                echo (!empty($submission_date) ? date("d-m-Y", strtotime($submission_date)) : '');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:5px; vertical-align:top;">c. Inspection Note/Comment</td>
                            <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                            <td style="padding:5px 0;"><?php if (!empty($posted_basic_data['inspection_note'])) echo $posted_basic_data['inspection_note']; ?></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend><?php echo ( ++$grc < 10 ? '0' : '') . "$grc"; ?>. Recommendation:</legend>
                    <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                        <?php if ($inspection_type_id == 1 || $inspection_type_id == 2) { ?>
                        <tr>
                            <td style="width:230px; max-width:28%;">Inspectors Recommendation</td>
                            <td class="colons">:</td>
                            <td style="width:70%; font-weight:bold;">
                                <?php
                                echo $this->Form->input("basic.inspector_recommendation", array('type' => 'radio', 'options' => $recommendation_status_options, 'div' => false, 'legend' => false, 'disabled' => 'disabled'));
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <?php if (!empty($inspector_names)) { ?>
                                <td style="width:230px; max-width:30%; padding-top:5px; vertical-align:top;">Inspectors Name & Designation</td>
                                <td class="colons" style="vertical-align:top;">:</td>
                                <td style="width:68%; padding:5px 5px 10px 10px;"><?php echo $inspector_names; ?></td>
                                <?php
                            } else {
                                echo "<td colspan='3'><p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p></td>";
                            }
                            ?>
                        </tr>
                    </table>
                </fieldset>
                <?php
            }

            echo $this->Form->end();
        } else {
            echo "<p class='error-message'>Field Inspection details not found !</p>";
        }
        ?>
    </fieldset>

    <?php
    $not_approval_details = empty($this->request->query('not_approval_details')) ? false : true;
    if (!$not_approval_details && !empty($org_id))
        echo $this->requestAction(array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'inspection_approval_details', $org_id, $inspection_type_id, $inspection_slno), array('return'));
    ?>

</div>
