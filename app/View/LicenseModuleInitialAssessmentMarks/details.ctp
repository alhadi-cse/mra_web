
<div>
    <fieldset>
        <legend>Assessment Details</legend>
        <div class="form">
            <table cellpadding="5" cellspacing="5" border="0">
                <?php $rc = 1; ?>
                <tr>
                    <td style="font-weight:bold; text-align:right; padding-right:5px;">
                        <?php echo $rc.'.'; ?>
                    </td>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td colspan="2"><strong><?php echo $basicAssessmentOptions[0]['BasicModuleBasicInformation']['full_name_of_org']; ?></strong></td>
                </tr>
                <?php 
                    foreach($licenseAssessmentMandatory as $evaluationDetails) {
                        $rc++;
                        $parameter_name = $evaluationDetails['LookupLicenseInitialAssessmentParameter']['parameter'];
                        $parameter_value = $evaluationDetails['LookupLicenseInitialAssessmentParameterOption']['parameter_option'];
                ?>
                <tr>
                    <td style="font-weight:bold; text-align:right; padding-right:5px;">
                        <?php echo $rc.'.'; ?>
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
                            $obtained_marks = $basicAssessmentOptions[0]['LicenseModuleInitialAssessmentMark']['total_assessment_marks'];
                            $total_marks = $basicAssessmentOptions[0]['LicenseModuleInitialAssessmentDetail']['total_marks'];
                            
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
                <tr>
                    <td colspan="5" style="border-bottom:2px solid #1378af; padding-top:7px; color:#073453; font-size:14px; font-weight:bold; text-align:center;">
                        Marks Details
                    </td>
                </tr>
                
                <tr style="font-size:13px; color:#234578; text-decoration:underline;">
                    <th></th>
                    <th>Parameter</th>
                    <th></th>
                    <th>Options</th>
                    <th>Marks</th>
                </tr>
                <?php 
                    $rc = 0;
                    foreach($licenseAssessmentDetails as $evaluationDetails) {
                        $rc++;
                        $parameter_name = $evaluationDetails['LookupLicenseInitialAssessmentParameter']['parameter'];
                        $parameter_value = $evaluationDetails['LookupLicenseInitialAssessmentParameterOption']['parameter_option']; 
                        $parameter_marks = $this->Number->precision($evaluationDetails['LookupLicenseInitialAssessmentParameterOption']['assessment_marks'], 1);
                ?>
                <tr>
                    <td style="font-weight:bold; text-align:right; padding-right:5px;">
                        <?php if($rc<10) echo '0'; echo $rc.'.'; ?>
                    </td>
                    <td>
                        <?php echo $parameter_name; ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $parameter_value; ?></td>
                    <td style="text-align:right; padding-right:7px;"><?php echo $parameter_marks; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>        
    </fieldset>           
    <div class="btns-div">
        <table style="margin:0 auto; padding:0;" cellspacing="7">
            <tr>
                <td></td>
                <td>
                    <?php 
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialAssessmentDetails','action' => 'view'), array('update' => '#ajax_div', 'class'=>'mybtns'));
                    ?>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
    
</div>
