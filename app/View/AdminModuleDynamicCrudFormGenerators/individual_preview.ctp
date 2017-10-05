<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Preview of '" .$model_description. "'";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$error_message = '<p class="error-message">No data is available!</p>';
?>
<div id="basicInfo" title="<?php echo $title; ?>"> 
    <?php
    if (!empty($mfiDetails) && !empty($org_id)) { ?>
        <style>
            .datagrid {
                width: 850px;
            }
        </style>
        <fieldset>            
            <?php if($model_id==3) {
                if(!empty($allRegistrationDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:850px;">
                        <tr>                           
                            <th style="width:100px;">Registration Authority</th>
                            <th style="width:80px;">Registration No.</th>
                            <th style="width:50px;">Date of Registration</th>
                            <th style="width:100px;">Date of Expiry of Registration</th>                            
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allRegistrationDetails as $registrationDetails) {
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo !empty($registrationDetails['LookupBasicRegistrationAuthority']) ?  $registrationDetails['LookupBasicRegistrationAuthority']['registration_authority']:""; ?></td>
                                <td><?php echo $registrationDetails['registration_no']; ?></td>
                                <td><?php echo $registrationDetails['date_of_registration']; ?></td>
                                <td><?php echo $registrationDetails['date_of_expiry_of_registration']; ?></td>                                
                            </tr>
                        <?php } ?>
                    </table> 
                </div>
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==5) {
                if(!empty($allBankInfoForTransactionDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>                           
                            <th style="width:170px;">Name of Banks</th>
                            <th style="width:150px;">Name of Bank Branch</th>
                            <th style="width:150px;">Account Operating Officer Name</th>
                            <th style="width:150px;">Designation of Officer</th>                            
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allBankInfoForTransactionDetails as $bankInfo) {
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><b><?php echo $bankInfo['name_of_banks']; ?></b></td>
                                <td><?php echo $bankInfo['name_of_bank_branch']; ?></td>
                                <td><?php echo $bankInfo['account_operating_officer_name']; ?></td>
                                <td><?php echo $bankInfo['designation_of_officer']; ?></td>
                            </tr>
                        <?php } ?>
                    </table> 
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==6) {
                if(!empty($allRevolvingLoanFundDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1200px;">
                        <tr>
                            <th style="width:170px;">Source</th> 
                            <th style="width:100px;">Source Category</th>
                            <th style="width:100px;">Source Sub-Category</th>
                            <th style="width:100px;">Taka Received</th>
                            <th style="width:100px;">Ratio in respect of Total Fund</th>
                            <th style="width:100px;">Cost of Fund (%)</th>
                            <th style="width:100px;">Remarks</th>                            
                        </tr>
                        <?php
                        $rc = 0;
                        foreach ($allRevolvingLoanFundDetails as $revolvingLoanFundDetails) {
                            $rc++;
                            ?>
                            <tr <?php if ($rc % 2 == 0) { echo ' class="alt"'; }?>>
                                <td><?php echo $revolvingLoanFundDetails['LookupBasicFundSource']['fund_sources']; ?></td>
                                <td><?php echo $revolvingLoanFundDetails['LookupBasicFundSourceCategory']['fund_source_category']; ?></td>
                                <td><?php echo $revolvingLoanFundDetails['LookupBasicFundSourceSubCategory']['fund_source_sub_category']; ?></td>
                                <td><?php echo $revolvingLoanFundDetails['taka_received']; ?></td>
                                <td><?php echo $revolvingLoanFundDetails['ratio_in_respect_of_total_fund']; ?></td>
                                <td><?php echo $revolvingLoanFundDetails['cost_of_fund']; ?></td>
                                <td><?php echo $revolvingLoanFundDetails['remarks']; ?></td>                                
                            </tr>
                        <?php } ?>
                    </table> 
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==7) {
                if(!empty($allProposedSavingDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>
                            <th style="width:370px;">Proposed Savings or Deposit Scheme</th>
                            <th style="width:150px;">Proposed Interest Rate</th>                                                       
                        </tr>
                        <?php
                        $rc = 0;
                        foreach ($allProposedSavingDetails as $savingDetails) {
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $savingDetails['LookupBasicProposedSavingsScheme']['proposed_savings_schemes']; ?></td>
                                <td><?php echo $savingDetails['proposed_interest_rate']; ?></td>
                            </tr>
                        <?php } ?>
                    </table> 
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==8) {
                if(!empty($allProposedLoanDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>
                            <th style="width:370px;">Proposed Loan Programs</th>
                            <th style="width:150px;">Proposed Service Charge Rate</th>                                                       
                        </tr>
                        <?php
                        $rc = 0;
                        foreach ($allProposedLoanDetails as $loanDetails) {
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $loanDetails['LookupBasicProposedLoanProgram']['proposed_loan_programs']; ?></td>
                                <td><?php echo $loanDetails['proposed_service_charge_rate']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>                       
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==9) {
                if(!empty($allIncomeExpenditureDetails)) { ?>
                <div class="datagrid">                    
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>
                            <th style="width:200px;">Attribute</th>
                            <?php
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_title = $years['LookupBasicStatementYear']['statement_year'];
                                    echo "<th style='width:70px; text-align:center;'>$year_title</th>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>1. Service Charge</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $service_charge = $incomeExpenditureDetails['service_charge'];
                                            echo "<td style='text-align:center;'>$service_charge</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr> 
                        
                        <tr>
                            <td>2. Grant</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $grant = $incomeExpenditureDetails['grants'];
                                            echo "<td style='text-align:center;'>$grant</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>3. Sale of Pass Book and Forms</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $sale_of_pass_book_forms = $incomeExpenditureDetails['sale_of_pass_book_forms'];
                                            echo "<td style='text-align:center;'>$sale_of_pass_book_forms</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>4. Admission Fees</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $admission_fees = $incomeExpenditureDetails['admission_fees'];
                                            echo "<td style='text-align:center;'>$admission_fees</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>5. Income from Investment</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $income_from_investment = $incomeExpenditureDetails['income_from_investment'];
                                            echo "<td style='text-align:center;'>$income_from_investment</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>6. Interest from Banks</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $interest_from_banks = $incomeExpenditureDetails['interest_from_banks'];
                                            echo "<td style='text-align:center;'>$interest_from_banks</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>7. Other Income</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_income = $incomeExpenditureDetails['other_income'];
                                            echo "<td style='text-align:center;'>$other_income</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;">8. Total Income (Sum of SL. No. 1 - 7)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $service_charge = $incomeExpenditureDetails['service_charge'];
                                            $grant = $incomeExpenditureDetails['grants'];
                                            $sale_of_pass_book_forms = $incomeExpenditureDetails['sale_of_pass_book_forms'];
                                            $admission_fees = $incomeExpenditureDetails['admission_fees'];
                                            $income_from_investment = $incomeExpenditureDetails['income_from_investment'];
                                            $interest_from_banks = $incomeExpenditureDetails['interest_from_banks'];
                                            $other_income = $incomeExpenditureDetails['other_income'];
                                            $total = $service_charge+$grant+$sale_of_pass_book_forms+$admission_fees+$income_from_investment+$interest_from_banks+$other_income;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;font-weight: bold;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>9. Interest on Savings</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $interest_on_savings = $incomeExpenditureDetails['interest_on_savings'];
                                            echo "<td style='text-align:center;'>$interest_on_savings</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>10. Interest on Loans / Borrowings</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $interest_on_loans_or_borrowings = $incomeExpenditureDetails['interest_on_loans_or_borrowings'];
                                            echo "<td style='text-align:center;'>$interest_on_loans_or_borrowings</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;">11. Total Financial Expenses (SL. 9 + 10)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $interest_on_savings = $incomeExpenditureDetails['interest_on_savings'];
                                            $interest_on_loans_or_borrowings = $incomeExpenditureDetails['interest_on_loans_or_borrowings'];
                                            $total = $interest_on_savings+$interest_on_loans_or_borrowings;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>12. Salaries and Allowances</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $salaries_and_allowances = $incomeExpenditureDetails['salaries_and_allowances'];
                                            echo "<td style='text-align:center;'>$salaries_and_allowances</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>13. Office Expenses (Rent, Electricity bill, Telephone bill, Stationary etc.)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $office_expenses = $incomeExpenditureDetails['office_expenses'];
                                            echo "<td style='text-align:center;'>$office_expenses</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>14. Depreciation</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $depreciation = $incomeExpenditureDetails['depreciation'];
                                            echo "<td style='text-align:center;'>$depreciation</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>15. Training and Development</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $training_and_development = $incomeExpenditureDetails['training_and_development'];
                                            echo "<td style='text-align:center;'>$training_and_development</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>16. Honorarium to member of General and Executive Body</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $honorarium_to_member_of_gb_and_eb = $incomeExpenditureDetails['honorarium_to_member_of_gb_and_eb'];
                                            echo "<td style='text-align:center;'>$honorarium_to_member_of_gb_and_eb</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>17. Audit Fee</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $audit_fee = $incomeExpenditureDetails['audit_fee'];
                                            echo "<td style='text-align:center;'>$audit_fee</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td>18. Other Expenses</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_expenses = $incomeExpenditureDetails['other_expenses'];
                                            echo "<td style='text-align:center;'>$other_expenses</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;">19. Total General and Administrative Expenses (Sum of SL. No. 12 -18)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $salaries_and_allowances = $incomeExpenditureDetails['salaries_and_allowances'];
                                            $office_expenses = $incomeExpenditureDetails['office_expenses'];
                                            $depreciation = $incomeExpenditureDetails['depreciation'];
                                            $training_and_development = $incomeExpenditureDetails['training_and_development'];
                                            $honorarium_to_member_of_gb_and_eb = $incomeExpenditureDetails['honorarium_to_member_of_gb_and_eb'];
                                            $audit_fee = $incomeExpenditureDetails['audit_fee'];
                                            $other_expenses = $incomeExpenditureDetails['other_expenses'];
                                            $total = $salaries_and_allowances+$office_expenses+$depreciation+$training_and_development+$honorarium_to_member_of_gb_and_eb+$audit_fee+$other_expenses;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;">20. Total Operational Expenses (SL. 11 + 19)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;                                                                                   
                                            $interest_on_savings = $incomeExpenditureDetails['interest_on_savings'];
                                            $interest_on_loans_or_borrowings = $incomeExpenditureDetails['interest_on_loans_or_borrowings'];
                                            $total_11 = $interest_on_savings+$interest_on_loans_or_borrowings;                                            
                                            $salaries_and_allowances = $incomeExpenditureDetails['salaries_and_allowances'];
                                            $office_expenses = $incomeExpenditureDetails['office_expenses'];
                                            $depreciation = $incomeExpenditureDetails['depreciation'];
                                            $training_and_development = $incomeExpenditureDetails['training_and_development'];
                                            $honorarium_to_member_of_gb_and_eb = $incomeExpenditureDetails['honorarium_to_member_of_gb_and_eb'];
                                            $audit_fee = $incomeExpenditureDetails['audit_fee'];
                                            $other_expenses = $incomeExpenditureDetails['other_expenses'];
                                            $total_19 = $salaries_and_allowances+$office_expenses+$depreciation+$training_and_development+$honorarium_to_member_of_gb_and_eb+$audit_fee+$other_expenses;
                                            $total = $total_11+$total_19;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>                        
                        <tr>
                            <td style="font-weight: bold;">21. Total Income from Operation (SL. 8 minus SL. 20)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;  
                                            $service_charge = $incomeExpenditureDetails['service_charge'];
                                            $grant = $incomeExpenditureDetails['grants'];
                                            $sale_of_pass_book_forms = $incomeExpenditureDetails['sale_of_pass_book_forms'];
                                            $admission_fees = $incomeExpenditureDetails['admission_fees'];
                                            $income_from_investment = $incomeExpenditureDetails['income_from_investment'];
                                            $interest_from_banks = $incomeExpenditureDetails['interest_from_banks'];
                                            $other_income = $incomeExpenditureDetails['other_income'];
                                            $total_8 = $service_charge+$grant+$sale_of_pass_book_forms+$admission_fees+$income_from_investment+$interest_from_banks+$other_income;
                                           
                                            $interest_on_savings = $incomeExpenditureDetails['interest_on_savings'];
                                            $interest_on_loans_or_borrowings = $incomeExpenditureDetails['interest_on_loans_or_borrowings'];
                                            $total_11 = $interest_on_savings+$interest_on_loans_or_borrowings;                                            
                                            
                                            $salaries_and_allowances = $incomeExpenditureDetails['salaries_and_allowances'];
                                            $office_expenses = $incomeExpenditureDetails['office_expenses'];
                                            $depreciation = $incomeExpenditureDetails['depreciation'];
                                            $training_and_development = $incomeExpenditureDetails['training_and_development'];
                                            $honorarium_to_member_of_gb_and_eb = $incomeExpenditureDetails['honorarium_to_member_of_gb_and_eb'];
                                            $audit_fee = $incomeExpenditureDetails['audit_fee'];
                                            $other_expenses = $incomeExpenditureDetails['other_expenses'];
                                            $total_19 = $salaries_and_allowances+$office_expenses+$depreciation+$training_and_development+$honorarium_to_member_of_gb_and_eb+$audit_fee+$other_expenses;
                                            
                                            $total_20 = $total_11+$total_19;
                                            
                                            $total = $total_8-$total_20;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>22. Loan Loss Provision</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loan_loss_provision = $incomeExpenditureDetails['loan_loss_provision'];
                                            echo "<td style='text-align:center;'>$loan_loss_provision</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;">23. Net Earnings (SL. 21 minus SL. 22)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $service_charge = $incomeExpenditureDetails['service_charge'];
                                            $grant = $incomeExpenditureDetails['grants'];
                                            $sale_of_pass_book_forms = $incomeExpenditureDetails['sale_of_pass_book_forms'];
                                            $admission_fees = $incomeExpenditureDetails['admission_fees'];
                                            $income_from_investment = $incomeExpenditureDetails['income_from_investment'];
                                            $interest_from_banks = $incomeExpenditureDetails['interest_from_banks'];
                                            $other_income = $incomeExpenditureDetails['other_income'];
                                            $total_8 = $service_charge+$grant+$sale_of_pass_book_forms+$admission_fees+$income_from_investment+$interest_from_banks+$other_income;
                                           
                                            $interest_on_savings = $incomeExpenditureDetails['interest_on_savings'];
                                            $interest_on_loans_or_borrowings = $incomeExpenditureDetails['interest_on_loans_or_borrowings'];
                                            $total_11 = $interest_on_savings+$interest_on_loans_or_borrowings;                                            
                                            
                                            $salaries_and_allowances = $incomeExpenditureDetails['salaries_and_allowances'];
                                            $office_expenses = $incomeExpenditureDetails['office_expenses'];
                                            $depreciation = $incomeExpenditureDetails['depreciation'];
                                            $training_and_development = $incomeExpenditureDetails['training_and_development'];
                                            $honorarium_to_member_of_gb_and_eb = $incomeExpenditureDetails['honorarium_to_member_of_gb_and_eb'];
                                            $audit_fee = $incomeExpenditureDetails['audit_fee'];
                                            $other_expenses = $incomeExpenditureDetails['other_expenses'];
                                            $total_19 = $salaries_and_allowances+$office_expenses+$depreciation+$training_and_development+$honorarium_to_member_of_gb_and_eb+$audit_fee+$other_expenses;
                                            
                                            $total_20 = $total_11+$total_19;
                                            
                                            $total_21 = $total_8-$total_20;
                                            $total_22 = $incomeExpenditureDetails['loan_loss_provision'];
                                            $total = $total_21-$total_22;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>24. Transfer to various funds and reserves (other than Accumulated Income)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $transfer_to_various_funds_and_reserves = $incomeExpenditureDetails['transfer_to_various_funds_and_reserves'];
                                            echo "<td style='text-align:center;'>$transfer_to_various_funds_and_reserves</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;">25. Transfer to Accumulated Income (SL. 23 minus SL. 24)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allIncomeExpenditureDetails as $incomeExpenditureDetails) { 
                                        $income_expenditure_statement_year_id = $incomeExpenditureDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $service_charge = $incomeExpenditureDetails['service_charge'];
                                            $grant = $incomeExpenditureDetails['grants'];
                                            $sale_of_pass_book_forms = $incomeExpenditureDetails['sale_of_pass_book_forms'];
                                            $admission_fees = $incomeExpenditureDetails['admission_fees'];
                                            $income_from_investment = $incomeExpenditureDetails['income_from_investment'];
                                            $interest_from_banks = $incomeExpenditureDetails['interest_from_banks'];
                                            $other_income = $incomeExpenditureDetails['other_income'];
                                            $total_8 = $service_charge+$grant+$sale_of_pass_book_forms+$admission_fees+$income_from_investment+$interest_from_banks+$other_income;
                                           
                                            $interest_on_savings = $incomeExpenditureDetails['interest_on_savings'];
                                            $interest_on_loans_or_borrowings = $incomeExpenditureDetails['interest_on_loans_or_borrowings'];
                                            $total_11 = $interest_on_savings+$interest_on_loans_or_borrowings;                                            
                                            
                                            $salaries_and_allowances = $incomeExpenditureDetails['salaries_and_allowances'];
                                            $office_expenses = $incomeExpenditureDetails['office_expenses'];
                                            $depreciation = $incomeExpenditureDetails['depreciation'];
                                            $training_and_development = $incomeExpenditureDetails['training_and_development'];
                                            $honorarium_to_member_of_gb_and_eb = $incomeExpenditureDetails['honorarium_to_member_of_gb_and_eb'];
                                            $audit_fee = $incomeExpenditureDetails['audit_fee'];
                                            $other_expenses = $incomeExpenditureDetails['other_expenses'];
                                            $total_19 = $salaries_and_allowances+$office_expenses+$depreciation+$training_and_development+$honorarium_to_member_of_gb_and_eb+$audit_fee+$other_expenses;
                                            
                                            $total_20 = $total_11+$total_19;
                                            
                                            $total_21 = $total_8-$total_20;
                                            $total_22 = $incomeExpenditureDetails['loan_loss_provision'];
                                            $total_23 = $total_21-$total_22;
                                            $total_24 = $incomeExpenditureDetails['transfer_to_various_funds_and_reserves'];
                                            $total = $total_23-$total_24;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>                        
                    </table> 
                </div>                       
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==10) { 
                if(!empty($allBalanceSheetDetails)) { ?>
                <div class="datagrid">                    
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>
                            <th style="width:200px;">Attribute</th>
                            <?php
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_title = $years['LookupBasicStatementYear']['statement_year'];
                                    echo "<th style='width:70px; text-align:center;'>$year_title</th>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>1. Cash in Hand</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $cash_in_hand = $balanceSheetDetails['cash_in_hand'];
                                            echo "<td style='text-align:center;'>$cash_in_hand</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr> 
                        <tr>
                            <td>2. Cash at Bank</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $cash_at_bank = $balanceSheetDetails['cash_at_bank'];
                                            echo "<td style='text-align:center;'>$cash_at_bank</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>3. Short term Investment (FDR)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $short_term_investment = $balanceSheetDetails['short_term_investment'];
                                            echo "<td style='text-align:center;'>$short_term_investment</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>4. Loans to other Microcredit Organizations</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_to_other_mco = $balanceSheetDetails['loans_to_other_mco'];
                                            echo "<td style='text-align:center;'>$loans_to_other_mco</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>5. Loans to Members - Bad Debt Provision</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_to_member_bad_debt_provision = $balanceSheetDetails['loans_to_member_bad_debt_provision'];
                                            echo "<td style='text-align:center;'>$loans_to_member_bad_debt_provision</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>6. Other Loans (Worker Loan)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_loans_in_asset = $balanceSheetDetails['other_loans_in_asset'];
                                            echo "<td style='text-align:center;'>$other_loans_in_asset</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>7. Other Investments</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_investments = $balanceSheetDetails['other_investments'];
                                            echo "<td style='text-align:center;'>$other_investments</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>8. Land and Building net of Depreciation</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $land_and_building_net_of_depreciation = $balanceSheetDetails['land_and_building_net_of_depreciation'];
                                            echo "<td style='text-align:center;'>$land_and_building_net_of_depreciation</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>9. Other Fixed Asset net of Depreciation</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_fixed_asset_net_of_depreciation = $balanceSheetDetails['other_fixed_asset_net_of_depreciation'];
                                            echo "<td style='text-align:center;'>$other_fixed_asset_net_of_depreciation</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>10. Other Assets</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_assets = $balanceSheetDetails['other_assets'];
                                            echo "<td style='text-align:center;'>$other_assets</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">11. Total Assets (Sum of SL. No. 1 - 10)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $cash_in_hand = $balanceSheetDetails['cash_in_hand'];
                                            $cash_at_bank = $balanceSheetDetails['cash_at_bank'];
                                            $short_term_investment = $balanceSheetDetails['short_term_investment'];
                                            $loans_to_other_mco = $balanceSheetDetails['loans_to_other_mco'];
                                            $loans_to_member_bad_debt_provision = $balanceSheetDetails['loans_to_member_bad_debt_provision'];
                                            $other_loans_in_asset = $balanceSheetDetails['other_loans_in_asset'];
                                            $other_investments = $balanceSheetDetails['other_investments'];
                                            $land_and_building_net_of_depreciation = $balanceSheetDetails['land_and_building_net_of_depreciation'];
                                            $other_fixed_asset_net_of_depreciation = $balanceSheetDetails['other_fixed_asset_net_of_depreciation'];
                                            $other_assets = $balanceSheetDetails['other_assets'];
                                            $total = $cash_in_hand+$cash_at_bank+$short_term_investment+$loans_to_other_mco+$loans_to_member_bad_debt_provision+$other_loans_in_asset+$other_investments+$land_and_building_net_of_depreciation+$other_fixed_asset_net_of_depreciation+$other_assets;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>12. Member Deposits</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $member_deposits = $balanceSheetDetails['member_deposits'];
                                            echo "<td style='text-align:center;'>$member_deposits</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr> 
                        <tr>
                            <td>13. Loans from PKSF</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_from_pksf = $balanceSheetDetails['loans_from_pksf'];
                                            echo "<td style='text-align:center;'>$loans_from_pksf</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>14. Loans from Housing Fund</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_from_housing_fund = $balanceSheetDetails['loans_from_housing_fund'];
                                            echo "<td style='text-align:center;'>$loans_from_housing_fund</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>15. Loans from other government sources</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_from_other_government_sources = $balanceSheetDetails['loans_from_other_government_sources'];
                                            echo "<td style='text-align:center;'>$loans_from_other_government_sources</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>16. Loans from other Microcredit Organizations</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_from_other_microcredit_organizations = $balanceSheetDetails['loans_from_other_microcredit_organizations'];
                                            echo "<td style='text-align:center;'>$loans_from_other_microcredit_organizations</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>17. Loans from Commercial Banks</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $loans_from_commercial_banks = $balanceSheetDetails['loans_from_commercial_banks'];
                                            echo "<td style='text-align:center;'>$loans_from_commercial_banks</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>18. Other Loans (Council and Other Personal Loan)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_loans_in_liabilities = $balanceSheetDetails['other_loans_in_liabilities'];
                                            echo "<td style='text-align:center;'>$other_loans_in_liabilities</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>19. Other Liabilities</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_liabilities = $balanceSheetDetails['other_liabilities'];
                                            echo "<td style='text-align:center;'>$other_liabilities</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">20. Total Liabilities (Sum of SL. No. 12 - 19)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $member_deposits = $balanceSheetDetails['member_deposits'];
                                            $loans_from_pksf = $balanceSheetDetails['loans_from_pksf'];
                                            $loans_from_housing_fund = $balanceSheetDetails['loans_from_housing_fund'];
                                            $loans_from_other_government_sources = $balanceSheetDetails['loans_from_other_government_sources'];
                                            $loans_from_other_microcredit_organizations = $balanceSheetDetails['loans_from_other_microcredit_organizations'];
                                            $loans_from_commercial_banks = $balanceSheetDetails['loans_from_commercial_banks'];
                                            $other_loans_in_liabilities = $balanceSheetDetails['other_loans_in_liabilities'];
                                            $other_liabilities = $balanceSheetDetails['other_liabilities'];
                                            $total = $member_deposits+$loans_from_pksf+$loans_from_housing_fund+$loans_from_other_government_sources+$loans_from_other_microcredit_organizations+$loans_from_commercial_banks+$other_loans_in_liabilities+$other_liabilities;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>21. Donor Funds</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $donor_funds = $balanceSheetDetails['donor_funds'];
                                            echo "<td style='text-align:center;'>$donor_funds</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>22. Cumulative Surplus</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $cumulative_surplus = $balanceSheetDetails['cumulative_surplus'];
                                            echo "<td style='text-align:center;'>$cumulative_surplus</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>23. Other Funds</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $other_funds = $balanceSheetDetails['other_funds'];
                                            echo "<td style='text-align:center;'>$other_funds</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">24. Total Equity (Sum of SL. No. 21- 23)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $total_21 = $balanceSheetDetails['donor_funds'];
                                            $total_23 = $balanceSheetDetails['other_funds'];
                                            $total = $total_21-$total_23;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">25. Total Liability and Equity (SL. 20 + 24)</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_income_exp_balance_sheet_years as $years) { 
                                    $year_id = $years['LookupBasicStatementYear']['id'];
                                    foreach ($allBalanceSheetDetails as $balanceSheetDetails) {
                                        $income_expenditure_statement_year_id = $balanceSheetDetails['statement_year_id'];
                                        if($year_id==$income_expenditure_statement_year_id) {
                                            $matched_ids++;
                                            $member_deposits = $balanceSheetDetails['member_deposits'];
                                            $loans_from_pksf = $balanceSheetDetails['loans_from_pksf'];
                                            $loans_from_housing_fund = $balanceSheetDetails['loans_from_housing_fund'];
                                            $loans_from_other_government_sources = $balanceSheetDetails['loans_from_other_government_sources'];
                                            $loans_from_other_microcredit_organizations = $balanceSheetDetails['loans_from_other_microcredit_organizations'];
                                            $loans_from_commercial_banks = $balanceSheetDetails['loans_from_commercial_banks'];
                                            $other_loans_in_liabilities = $balanceSheetDetails['other_loans_in_liabilities'];
                                            $other_liabilities = $balanceSheetDetails['other_liabilities'];
                                            $total_20 = $member_deposits+$loans_from_pksf+$loans_from_housing_fund+$loans_from_other_government_sources+$loans_from_other_microcredit_organizations+$loans_from_commercial_banks+$other_loans_in_liabilities+$other_liabilities;                                    
                                            $total_21 = $balanceSheetDetails['donor_funds'];
                                            $total_23 = $balanceSheetDetails['other_funds'];
                                            $total_24 = $total_21-$total_23;
                                            $total = $total_20+$total_24;
                                            echo "<td style='text-align:center;font-weight: bold;'>$total</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_income_exp_balance_sheet_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style='text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                    </table> 
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==11) { 
                if(!empty($commencementDateDetails)) { ?>
                <table cellpadding="0" cellspacing="8" border="0">
                        <tr class="alt">
                            <td style="font-weight:bold;">Proposed Date of Commencement of Micro credit Operation</td>
                            <td class="colons">:</td>
                            <td style="width:255px;"><?php echo !empty($commencementDateDetails) ? $this->Time->format($commencementDateDetails['proposed_date_of_commencement'],'%d-%m-%Y',''):"";?></td>
                        </tr>
                </table>
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==12) {  
                if(!empty($allMcActivitiDetails)) { ?>
                <div class="datagrid">                    
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>
                            <th style="width:200px;">Attribute</th>
                            <?php
                                foreach ($total_years as $years) { 
                                    $year_title = $years['LookupBasicPlanForMcYear']['plan_year'];
                                    echo "<th style='width:70px; text-align:center;'>$year_title</th>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>No. of Male Clients</td>  
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['no_of_male_client'];
                                            echo "<td style=text-align:center;'>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr> 
                        <tr>
                            <td>No. of Female Clients</td>
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['no_of_female_client'];
                                            echo "<td style=text-align:center;>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>No. of Male Borrowers</td>
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['no_of_male_borrower'];
                                            echo "<td style=text-align:center;'>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>No. of Female Borrowers</td>
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['no_of_female_borrower'];
                                            echo "<td style=text-align:center;'>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>Principal loan outstanding at field level (Taka)</td>
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['principal_loan_outstanding'];
                                            echo "<td style=text-align:center;'>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>Member Savings (Taka)</td>
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['member_savings'];
                                            echo "<td style=text-align:center;'>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>No. of Branches</td> 
                            <?php
                                $matched_ids = 0;
                                foreach ($total_years as $years) { 
                                    $year_id = $years['LookupBasicPlanForMcYear']['id'];
                                    foreach ($allMcActivitiDetails as $mcActivitiDetails) { 
                                        $plan_year_id = $mcActivitiDetails['plan_year_id'];
                                        if($year_id==$plan_year_id) {
                                            $matched_ids++;
                                            $no_of_male_client = $mcActivitiDetails['no_of_branches'];
                                            echo "<td style=text-align:center;'>$no_of_male_client</td>";
                                        }
                                    }                          
                                }
                                $pending_ids = count($total_years)-$matched_ids;
                                for($i=0;$i<$pending_ids;$i++) {
                                    echo "<td style=text-align:center;'></td>";
                                }
                            ?>
                        </tr> 
                    </table> 
                </div>             
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==15) {
                if(!empty($allGeneralBodyMemberDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1250px;">
                        <tr>
                            <th style="width:100px;">Name</th>
                            <th style="width:100px;">Designation</th>  
                            <th style="width:100px;">Father's Name</th> 
                            <th style="width:100px;">Mother's Name</th> 
                            <th style="width:100px;">Spouse Name (where applicable)</th>
                            <th style="width:100px;">Present Address</th>  
                            <th style="width:100px;">Permanent Address</th> 
                            <th style="width:100px;">Phone</th> 
                            <th style="width:100px;">Mobile</th>
                            <th style="width:100px;">Fax</th>    
                            <th style="width:100px;">E-Mail</th>
                            <th style="width:100px;">Date of Birth</th>
                            <th style="width:100px;">Nationality</th>
                            <th style="width:100px;">Religion</th>
                            <th style="width:100px;">National ID No.</th>
                            <th style="width:100px;">Passport No. (if any)</th>
                            <th style="width:100px;">TIN No. (if any)</th>
                        </tr>
                        <?php
                        $rc = 0;
                        foreach ($allGeneralBodyMemberDetails as $generalBodyMemberDetails) {
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $generalBodyMemberDetails['name']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['designation']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['fathers_name']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['mothers_name']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['spouse_name']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['present_address']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['permanent_address']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['phone_no']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['mobile_no']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['fax_no']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['email']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['date_of_birth']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['nationality']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['religion']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['national_id_no']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['passport_no']; ?></td>
                                <td><?php echo $generalBodyMemberDetails['tin_no']; ?></td>                                
                            </tr>
                        <?php } ?>
                    </table> 
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==16) { 
                if(!empty($allGBMemberEducationDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name of GB Member</th>                  
                            <th style="width:100px;">Name of Examination</th>
                            <th style="width:150px;">Name of Institute</th>
                            <th style="width:170px;">Board/University</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allGBMemberEducationDetails as $gbMembEduDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo !empty($gbMembEduDetails['BasicModuleGeneralBodyMemberInfo']) ?  $gbMembEduDetails['BasicModuleGeneralBodyMemberInfo']['name']:""; ?></td>
                                <td><?php echo !empty($gbMembEduDetails['LookupBasicExamType']) ?  $gbMembEduDetails['LookupBasicExamType']['exam_type']:""; ?></td>
                                <td><?php echo $gbMembEduDetails['name_of_institute']; ?></td>
                                <td><?php echo $gbMembEduDetails['board_or_university']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==17) { 
                if(!empty($allGBMemberFinancialInvolvmentDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name of GB Member</th>                  
                            <th style="width:100px;">Amount of Donation</th>
                            <th style="width:100px;">Amount of Loan to Organization</th>
                            <th style="width:100px;">Interest Rate of Loan</th>
                            <th style="width:100px;">Borrowing Amount</th>
                            <th style="width:100px;">Interest Rate on Borrowing Amount</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allGBMemberFinancialInvolvmentDetails as $gbMembFinancialInvolvmentDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo !empty($gbMembEduDetails['BasicModuleGeneralBodyMemberInfo']) ?  $gbMembEduDetails['BasicModuleGeneralBodyMemberInfo']['name']:""; ?></td>
                                <td><?php echo $gbMembFinancialInvolvmentDetails['amount_of_donation']; ?></td>
                                <td><?php echo $gbMembFinancialInvolvmentDetails['amount_of_loan_to_organization']; ?></td>
                                <td><?php echo $gbMembFinancialInvolvmentDetails['interest_rate_of_loan']; ?></td>
                                <td><?php echo $gbMembFinancialInvolvmentDetails['borrowing_amount']; ?></td>
                                <td><?php echo $gbMembFinancialInvolvmentDetails['interest_rate_on_borrowing_amount']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==18) { 
                if(!empty($allGBMemberCaseOrSuitDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name of GB Member</th>                  
                            <th style="width:100px;">Case No.</th>
                            <th style="width:150px;">Type of Suit</th>
                            <th style="width:200px;">Name of the Court</th>
                            <th style="width:100px;">Duration of Conviction (if any)</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allGBMemberCaseOrSuitDetails as $gbMembCaseDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo !empty($gbMembEduDetails['BasicModuleGeneralBodyMemberInfo']) ?  $gbMembEduDetails['BasicModuleGeneralBodyMemberInfo']['name']:""; ?></td>
                                <td><?php echo $gbMembCaseDetails['case_no']; ?></td>
                                <td><?php echo $gbMembCaseDetails['LookupBasicSuitType']['suit_type']; ?></td>
                                <td><?php echo $gbMembCaseDetails['name_of_the_court']; ?></td>
                                <td><?php echo $gbMembCaseDetails['duration_of_conviction']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==19) { 
                if(!empty($allGBMemberOtherBusinessInvolvmentDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name of GB Member</th>                  
                            <th style="width:100px;">Name of NGO/Business Organization</th>
                            <th style="width:150px;">Address of NGO/Business Organization</th>
                            <th style="width:170px;">Nature of Involvement</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allGBMemberOtherBusinessInvolvmentDetails as $gbMemberOtherBusinessInvolvmentDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo !empty($gbMemberOtherBusinessInvolvmentDetails['BasicModuleGeneralBodyMemberInfo']) ?  $gbMemberOtherBusinessInvolvmentDetails['BasicModuleGeneralBodyMemberInfo']['name']:""; ?></td>
                                <td><?php echo $gbMemberOtherBusinessInvolvmentDetails['name_of_the_business_org']; ?></td>
                                <td><?php echo $gbMemberOtherBusinessInvolvmentDetails['address_of_the_business_org']; ?></td>
                                <td><?php echo !empty($gbMemberOtherBusinessInvolvmentDetails['LookupBasicBusinessInvolvementNature']) ? $gbMemberOtherBusinessInvolvmentDetails['LookupBasicBusinessInvolvementNature']['nature_of_involvement']:""; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>             
            <?php }
                else {  
                    echo $error_message;
                }
            } ?>
            <?php if($model_id==20) { 
                if(!empty($allMembersOfCouncilDirectorDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:180px;">Name</th>                  
                            <th style="width:100px;">Address</th>
                            <th style="width:100px;">Occupation</th>
                            <th style="width:100px;">Designation</th>
                            <th style="width:170px;">Name of Organization</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allMembersOfCouncilDirectorDetails as $councilDirectorDetails) {                            
                            $rc++;
                            ?>
                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $councilDirectorDetails['name']; ?></td>
                                <td><?php echo $councilDirectorDetails['address']; ?></td>
                                <td><?php echo $councilDirectorDetails['occupation']; ?></td>
                                <td><?php echo $councilDirectorDetails['designation']; ?></td>
                                <td><?php echo $councilDirectorDetails['name_of_organization']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>            
            <?php }
                else {  
                    echo $error_message;
                }
            } ?> 
            <?php if($model_id==21) { 
                if(!empty($allProposedOrActiveCeoDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name</th>                  
                            <th style="width:100px;">Date of Birth</th>
                            <th style="width:150px;">Nationality</th>
                            <th style="width:170px;">Academic Qualification</th>
                            <th style="width:170px;">Experience of Microcredit Activities (years)</th>
                            <th style="width:170px;">Date of Joining</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allProposedOrActiveCeoDetails as $proposedOrActiveCeoDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $proposedOrActiveCeoDetails['name']; ?></td>
                                <td><?php echo $proposedOrActiveCeoDetails['date_of_birth']; ?></td>
                                <td><?php echo $proposedOrActiveCeoDetails['nationality']; ?></td>
                                <td><?php echo $proposedOrActiveCeoDetails['academic_qualification']; ?></td>
                                <td><?php echo $proposedOrActiveCeoDetails['experience_of_microcredit_activities']; ?></td>
                                <td><?php echo $proposedOrActiveCeoDetails['date_of_joining']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>            
            <?php }
                    else {  
                        echo $error_message;
                    }
            } ?> 
            <?php if($model_id==22) { 
                if(!empty($allEmployeeDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:770px;">
                        <tr>
                            <th style="min-width:365px;">Attribute</th>
                            <th class="colon">:</td> 
                            <th style="min-width:365px;">Values</th>                                                        
                        </tr>
                        <tr>
                            <td>No. of Male in Proposed Microcredit Activities (estimated)</td>
                            <td>:</td> 
                            <td>
                                <?php echo !empty($allEmployeeDetails['no_of_male_in_proposed_mc_activities'])? $allEmployeeDetails['no_of_male_in_proposed_mc_activities']:""; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>No. of Female in Proposed Microcredit Activities (estimated)</td>                        
                            <td>:</td> 
                            <td><?php  echo !empty($allEmployeeDetails['no_of_female_in_proposed_mc_activities'])?  $allEmployeeDetails['no_of_female_in_proposed_mc_activities']:""; ?></td>
                        </tr>
                        <tr>
                            <td>No. of Male in Activities other than Microcredit</td>                        
                            <td>:</td> 
                            <td><?php echo !empty($allEmployeeDetails['no_of_male_in_other_activities'])?  $allEmployeeDetails['no_of_male_in_other_activities']:"";?></td>
                        </tr>
                        <tr>
                            <td>No. of Female in Activities other than Microcredit</td>                        
                            <td>:</td> 
                            <td><?php echo !empty($allEmployeeDetails['no_of_female_in_other_activities'])?  $allEmployeeDetails['no_of_female_in_other_activities']:""; ?></td>
                        </tr>
                    </table>
                </div>            
                <?php }
                    else {  
                        echo $error_message;
                    }
                 } ?> 
            <?php if($model_id==23) { 
                if(!empty($allSisterOrganizationDetails)) { ?>  
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name of the Sister Organization</th>                  
                            <th style="width:100px;">Address</th>
                            <th style="width:100px;">Phone</th>                  
                            <th style="width:100px;">Fax</th>
                            <th style="width:100px;">E-Mail</th>                  
                            <th style="width:100px;">CEO Name in Case of Separate Legal Entity</th>
                            <th style="width:100px;">Head Office</th>                  
                            <th style="width:100px;">Share in Proposed Microcredit Organization</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allSisterOrganizationDetails as $sisterOrganizationDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $sisterOrganizationDetails['name_of_sister_organization'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['address'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['phone'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['fax'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['email'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['ceo_name_in_case_of_separate_legal_entity'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['head_office'] ?></td>
                                <td><?php echo $sisterOrganizationDetails['share_in_proposed_microcredit_org'] ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>            
                <?php }
                    else {  
                        echo $error_message;
                    }
                 } ?> 
            <?php if($model_id==24) { 
                if(!empty($allOtherProgramDetails)) { ?>
                <div class="datagrid">
                    <table cellpadding="7" cellspacing="8" border="0" style="min-width:1000px;">
                        <tr>
                            <th style="width:100px;">Name of Program</th>                  
                            <th style="width:100px;">Working Area</th>
                            <th style="width:100px;">Source of Fund</th>                  
                            <th style="width:100px;">Share in proposed Microcredit Organization (Taka)</th>
                            <th style="width:100px;">Share in proposed Microcredit Organization (Percent)</th>                  
                            <th style="width:100px;">CEO Name in Case of Separate Legal Entity</th>
                            <th style="width:100px;">Address</th>                  
                            <th style="width:100px;">Phone</th>
                            <th style="width:100px;">Fax</th>
                            <th style="width:100px;">E-Mail</th>
                        </tr>

                        <?php
                        $rc = 0;
                        foreach ($allOtherProgramDetails as $otherProgramDetails) {                            
                            $rc++;
                            ?>

                            <tr<?php
                            if ($rc % 2 == 0) {
                                echo ' class="alt"';
                            }
                            ?>>
                                <td><?php echo $otherProgramDetails['name_of_program']; ?></td>
                                <td><?php echo $otherProgramDetails['working_area']; ?></td>
                                <td><?php echo $otherProgramDetails['source_of_fund']; ?></td>
                                <td><?php echo $otherProgramDetails['share_in_taka']; ?></td>
                                <td><?php echo $otherProgramDetails['share_in_percent']; ?></td>
                                <td><?php echo $otherProgramDetails['ceo_name_in_case_of_separate_legal_entity']; ?></td>
                                <td><?php echo $otherProgramDetails['address']; ?></td>
                                <td><?php echo $otherProgramDetails['phone']; ?></td>
                                <td><?php echo $otherProgramDetails['fax']; ?></td>
                                <td><?php echo $otherProgramDetails['email']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <?php }
                    else {  
                        echo $error_message;
                    }
                 } ?>            
        <?php } ?>
    </fieldset>
</div>
<script>
    $(function () {
        $("#basicInfo").dialog({
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