
<div>
    <?php 
        $title = "Field Inspection Information"; 
        
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>
    <fieldset>
        <legend><?php echo $title;?></legend>
        <?php 
        if(!empty($this->request->data['LicenseModuleCancelByMraFieldInspectionDetail'])) {
            $posted_data = $this->request->data['LicenseModuleCancelByMraFieldInspectionDetail'];
            
            echo $this->Form->create('LicenseModuleCancelByMraFieldInspectionDetail');
        ?>
        
        <div class="form">
            <p style="border-bottom:2px solid #137387; padding:0 0 0 15px;">
                Name of Organization: 
                <?php
                if (!empty($this->request->data['BasicModuleBasicInformation'])) {
                    $orgDetail = $this->request->data['BasicModuleBasicInformation'];
                    $orgName = $orgDetail['full_name_of_org'];
                    echo '<strong>' . $orgName . (!empty($orgDetail['short_name_of_org']) ? ' (' . $orgDetail['short_name_of_org'] . ')' : '') . '</strong>';
                }

                echo $this->Js->link('Assess Details', array('controller' => 'LicenseModuleInitialAssessmentDetails', 'action' => 'preview', $org_id), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'display:inline-block;', 'update' => '#popup_div')));
                ?>
            </p>
            
            <fieldset>
                <legend>01. Deposition of TK. 10 Lakh in Bank:</legend>                           
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">  
                    <tr>
                        <td>a. Deposit Against Organization Name</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_deposit_against_org_name", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr> 
                    <tr>
                        <td>b. Was the Money Withdrawn?</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("was_the_money_withdrawn", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>c. Existence in the Time of Inspection</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("is_exist_during_inspection", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>d. Bank Statement</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_bank_statement", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>e. Deed of Fund/Loan</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_deed_of_fund_or_loan", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <fieldset>
                <legend>02. Office Signboard Information:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Signboard</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_signboard", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>b. Certificate/Rent Agreement</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_certificate_or_rent_agreement", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>                 
                </table>
            </fieldset>
            
            <fieldset>
                <legend>03. Manpower Appointment Information:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Decision of the Board</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("is_decision_of_board_on_appointment", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>b. Under process/Processed</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("is_under_process", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>                
                </table>
            </fieldset>

            <fieldset>
                <legend>04. Preliminary Registration:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Registration Certificate</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_preliminary_reg_certificate", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>b. Organizational Structure</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_organizational_structure", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>                
                </table>
            </fieldset>
            
            <fieldset>
                <legend>05. Asset Liability Statement (10-B Form):</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Displayed Main Copy</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_shown_main_copy", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>b. Liabilities and Assets Compatibility</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_liabilities_and_assets_compatibility", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <fieldset>
                <legend>06. Update Approved Board:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. List of General Body</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_list_of_general_body", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>            
                    <tr>
                        <td>b. List of Executive Body</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_list_of_executive_body", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <fieldset>
                <legend>07. Others:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Compatibility with the Rules and Regulations of MRA</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_compatibility_with_mra_rules", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>            
                    <tr>
                        <td>b. Approved Operation Area</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("is_approved_operation_area", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>c. Micro-credit Excluded Activities</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_microcredit_excluded_activities", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                    <tr>
                        <td>d. Evidence of Special Group/Class(if applicable)</td>
                        <td class="colons">:</td>
                        <td style="width:20%;"><?php echo $this->Form->input("has_evidence_of_special_group_or_class", array('type' => 'radio', 'options' => $option_values, 'legend' => false, 'disabled'=>'disabled')); ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <fieldset>
                <legend>08. Field Inspection Note:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td style="width:30%;">a. Inspection Date</td>
                        <td class="colons">:</td>
                        <td style="width:70%;">
                            <?php 
                            $inspection_date = $posted_data['inspection_date'];
                            if(!empty($inspection_date)) echo date("d-m-Y", strtotime($inspection_date));
                            ?>
                        </td>
                    </tr>            
                    
                    <tr>
                        <td>b. Submission Date</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                            $submission_date = $posted_data['submission_date'];
                            if(!empty($submission_date)) echo date('d-m-Y', strtotime($submission_date));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:5px; vertical-align:top;">c. Inspection Note/Comment</td>
                        <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                        <td style="padding:5px 0;"><?php if (!empty($posted_data)) echo $posted_data['inspection_note']; ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <fieldset>
                <legend>08. Field Inspection Note:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td style="width:30%;">a. Inspection Date</td>
                        <td class="colons">:</td>
                        <td style="width:70%;">
                            <?php 
                            $inspection_date = $posted_data['inspection_date'];
                            if(!empty($inspection_date)) echo date("d-m-Y", strtotime($inspection_date));
                            ?>
                        </td>
                    </tr>            
                    
                    <tr>
                        <td>b. Submission Date</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                            $submission_date = $posted_data['submission_date'];
                            if(!empty($submission_date)) echo date('d-m-Y', strtotime($submission_date));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:5px; vertical-align:top;">c. Inspection Note/Comment</td>
                        <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                        <td style="padding:5px 0;"><?php if (!empty($posted_data)) echo $posted_data['inspection_note']; ?></td>
                    </tr>
                </table>
            </fieldset>
            
            <fieldset>
                <legend>09. Recommendation:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Inspectors Recommendation</td>
                        <td class="colons">:</td>
                        <td style="width:60%; font-weight:bold;">
                            <?php
                            echo $this->Form->input("inspector_recommendation", array('type' => 'radio', 'options' => $recommendation_status_options, 'div' => false, 'legend' => false, 'disabled' => 'disabled'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <?php if (!empty($inspector_names)) { ?>
                            <td style="padding-top:5px; vertical-align:top;">b. Inspectors Name & Designation</td>
                            <td class="colons" style="vertical-align:top;">:</td>
                            <td style="padding:5px 5px 10px 10px;"><?php echo $inspector_names; ?></td>
                        <?php
                        } else {
                            echo "<td colspan='3'><p style='color:#DF0507; font-weight:bold; text-align:center;'>Inspector Not Assigned for this Organization</p></td>";
                        }
                        ?>
                    </tr>
                </table>
            </fieldset>
        </div>
        
        <?php echo $this->Form->end(); ?>
        
        
        <?php echo $this->Form->create('LicenseModuleFieldInspectionApprovalDetail'); ?>
        
        <fieldset>
            <legend>10. Field Inspection Approval:</legend>
            <table cellpadding="3" cellspacing="3" border="0" style="width:90%;">
                <tr>
                    <td>a. Inspection Approval</td>
                    <td class="colons">:</td>
                    <td style="width:58%; font-weight:bold;">
                        <?php
                        echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false));
                        echo $this->Form->input("submission_date", array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false));
                        echo $this->Form->input("inspection_approval_id", array('type' => 'radio', 'options' => $approval_status_options, 'div' => false, 'legend' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:5px; vertical-align:top;">b. Comment or Message (if not approved)</td>
                    <td class="colons" style="padding-top:5px; vertical-align:top;">:</td>
                    <td style="padding:5px 0;"><?php echo $this->Form->input("inspection_comment", array('type' => 'textarea', 'div' => false, 'label' => false)); ?></td>
                </tr>
            </table>
        </fieldset>
        
        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleCancelByMraFieldInspectionDetails', 'action' => 'view'), array_merge($pageLoading, array('class'=>'mybtns'))); 
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                            echo $this->Js->submit('Submit', array_merge($pageLoading, 
                                                    array(//'url'=>"/LicenseModuleCancelByMraFieldInspectionDetails/inspection_approval/$org_id/1", 
                                                          'confirm'=>"Are you sure to Submit ?",
                                                          'success'=>"msg.init('success', '$title', '$title has been submit successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'submit failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); } ?>
     </fieldset>
</div>

<script>
    
    $(function() {
        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        
        $('.date_picker').each(function() {
            $(this).datepicker({
                yearRange: 'c-5:c+5',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                //defaultDate: new Date(),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
        
    });
    
</script>
