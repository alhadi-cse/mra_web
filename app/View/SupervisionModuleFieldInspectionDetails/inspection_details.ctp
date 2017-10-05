
<div>
    <?php
    if (!empty($inspectionDetails)) {
        echo $this->Form->create('SupervisionModuleFieldInspectionDetail');
        ?>
        <fieldset>
            <legend>Field Inspection Report:</legend>
            <table cellpadding="3" cellspacing="3" border="0" style="width:95%;">
                <tr>
                    <td style="width:135px; max-width:25%;">a. Inspection Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        $inspection_date = $inspectionDetails['SupervisionModuleFieldInspectionDetail']['inspection_date'];
                        echo (!empty($inspection_date) ? date("d-m-Y", strtotime($inspection_date)) : '');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>b. Submission Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        $submission_date = $inspectionDetails['SupervisionModuleFieldInspectionDetail']['submission_date'];
                        echo (!empty($submission_date) ? date("d-m-Y", strtotime($submission_date)) : '');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:5px; vertical-align:top;">c. Inspection Details</td>
                    <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                    <td style="padding:5px 0;"><?php if (!empty($inspectionDetails['SupervisionModuleFieldInspectionDetail']['inspection_note'])) echo $inspectionDetails['SupervisionModuleFieldInspectionDetail']['inspection_note']; ?></td>
                </tr>
                <tr>
                    <?php if (!empty($inspector_names)) { ?>
                        <td style="width:135px; max-width:25%; padding-top:5px; vertical-align:top;">d. Inspectors</td>
                        <td class="colons" style="vertical-align:top;">:</td>
                        <td style="padding:6px 0;"><?php echo $inspector_names; ?></td>
                        <?php
                    } else {
                        echo "<td colspan='3'><p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p></td>";
                    }
                    ?>
                </tr>
            </table>
        </fieldset>
        <?php
        echo $this->Form->end();
    } else {
        echo "<p class='error-message'>Field Inspection details not found !</p>";
    }
    ?>

    <?php
    //$no_approval_details = empty($this->request->query('no_approval_details')) ? false : true;
    if (!$no_approval_details && !empty($supervision_basic_id))
        echo $this->requestAction(array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'inspection_approval_details', $supervision_basic_id), array('return'));
    ?>

</div>
