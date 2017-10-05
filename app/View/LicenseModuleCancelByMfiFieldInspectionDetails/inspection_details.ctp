
<div>
    <fieldset>
        <legend>Field Inspection Details</legend>
        <?php 
        if(!empty($this->request->data['LicenseModuleCancelByMfiFieldInspectionDetail'])) {
            $posted_data = $this->request->data['LicenseModuleCancelByMfiFieldInspectionDetail'];
            echo $this->Form->create('LicenseModuleCancelByMfiFieldInspectionDetail');
        ?>
        
        <div class="form">
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
                <legend>09. Recommendation:</legend>
                <table cellpadding="3" cellspacing="3" border="0" style="width:85%;">
                    <tr>
                        <td>a. Inspectors Recommendation</td>
                        <td class="colons">:</td>
                        <td style="width:70%; font-weight:bold;">
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
        
        <?php  echo $this->Form->end(); } ?>
     </fieldset>
</div>
