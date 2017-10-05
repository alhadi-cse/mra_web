
<div>
    <div id="licenseInfo" title="Recommendation of Evaluation Committee" style="margin:0px; padding:10px; background-color:#fafdff;">
        
        <fieldset>
            <legend>
                Recommendation of Evaluation Committee Details
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

                <table cellpadding="7" cellspacing="8" border="0">                    
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Recommendation</td>
                        <td class="colons">:</td>
                        <td><?php echo  $licApprovalDetails['LookupLicenseRecommendationStatus']['recommendation_status']; ?></td>
                    </tr>
                    <?php 
                        if($licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['recommendation_status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td>Date of Recommendation</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['recommendation_date']; ?></td>
                    </tr>
                    <?php 
                        }
                        else if($licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['recommendation_status_id']=="2")
                        {
                    ?>
                    <tr>
                        <td>Reason (if not recommend)</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['reason_if_not_recommended']; ?></td>
                    </tr>
                    <?php 
                        }
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
        </fieldset>

        <fieldset style="margin-top:15px;">
            <legend>
                Field Inspection Details
            </legend>
            <div class="form">

                <table cellpadding="5" cellspacing="5" border="0">                    
                    <tr>
                        <td style="font-weight:bold; text-align:right; padding-right:5px;">1.</td>
                        <td>Inspector's Recommendation</td>
                        <td class="colons">:</td>
                        <td><?php echo  $licApprovalDetails['LookupLicenseRecommendationStatus']['recommendation_status']; ?></td>
                    </tr>
                    <?php 
                        if($licFieldInspectionDetails['LicenseModuleFieldInspection']['recommendation_status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td style="font-weight:bold; text-align:right; padding-right:5px;">2.</td>
                        <td>Date of Approval</td>
                        <td class="colons">:</td>
                        <td><?php echo $licFieldInspectionDetails['LicenseModuleFieldInspection']['approval_date']; ?></td>
                    </tr>
                    <?php 
                        }
                        else if($licFieldInspectionDetails['LicenseModuleFieldInspection']['recommendation_status_id']=="2")
                        {
                    ?>
                    <tr>
                        <td style="font-weight:bold; text-align:right; padding-right:5px;">3.</td>
                        <td>Reason (if not recommend)</td>
                        <td class="colons">:</td>
                        <td><?php echo $licFieldInspectionDetails['LicenseModuleFieldInspection']['reason_if_not_recommended']; ?></td>
                    </tr>
                    <?php 
                        }
                    ?>
                    <tr>
                        <td style="font-weight:bold; text-align:right; padding-right:5px;">4.</td>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $licFieldInspectionDetails['LicenseModuleFieldInspection']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
        </fieldset>
        
        
        <fieldset style="margin-top:15px;">
            <legend>Initial Evaluation Details</legend>
            <div class="form">
                <table cellpadding="5" cellspacing="5" border="0">
                    <?php 
                    
                        $rc = 0;
                        foreach($licenseEvaluationMandatory as $evaluationDetails) {
                            $parameter_name = $evaluationDetails['LookupLicenseInitialAssessmentParameter']['parameter'];
                            $parameter_value = $evaluationDetails['LookupLicenseInitialAssessmentParameterOption']['parameter_option'];
                    ?>
                    <tr>
                        <td style="font-weight:bold; text-align:right; padding-right:5px;">
                            <?php echo ++$rc.'.'; ?>
                        </td>
                        <td>
                            <?php echo $parameter_name; ?>
                        </td>
                        <td class="colons">:</td>
                        <td><strong><?php echo $parameter_value; ?></strong></td>
                        <td></td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td style="font-weight:bold; text-align:right; padding-right:5px;">
                            <?php echo ++$rc.'.'; ?>
                        </td>
                        <td>Total Marks Obtained</td>
                        <td class="colons">:</td>
                        <td colspan="2">
                            <strong>
                            <?php 
                                $obtained_marks = $basicEvaluationOptions[0]['LicenseModuleEvaluationDetailInfo']['total_assessment_marks'];
                                $total_marks = $basicEvaluationOptions[0]['LicenseModuleEvaluationDetailInfo']['total_marks'];

                                if(!empty($total_marks) && $total_marks!=0) {
                                    $obtained_marks_in_percentage = ($obtained_marks / $total_marks) * 100;

                                    if($obtained_marks_in_percentage>59)
                                        echo '<span style="color:#13af24; font-size:14px;">';
                                    else if($obtained_marks_in_percentage>49)
                                        echo '<span style="color:#fa8713; font-size:14px;">';
                                    else
                                        echo '<span style="color:#fa2413; font-size:14px;">';

                                    echo $this->Number->toPercentage($obtained_marks_in_percentage).'</span> ('.
                                            $this->Number->precision($obtained_marks, 1).
                                            '<span style="font-weight:normal;"> out of </span>'.
                                            $this->Number->precision($total_marks, 1).')';
                                }
                                else
                                    echo '<span style="font-size:14px;">'.$this->Number->precision($obtained_marks, 1).'</span>';
                            ?>
                            </strong>
                        </td>
                    </tr>                
                </table> 
            </div>
        </fieldset>
    </div>
    
    
    
    <script>
        $(function () {
            $("#licenseInfo").dialog({
                modal: true, width: 'auto', height: 'auto', resizable: false, dialogClass: 'my-dialog-box', 
                buttons: {
                    Close: function () {
                        $(this).dialog("close");
                    }
                },
                create: function(evt, ui) {
                    $(this).css("minWidth", "850px").css("maxWidth", "1000px");
                }
            });
        });
    </script>

</div>





