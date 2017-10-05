<?php ?>

<div id="basicInfo" title="MFI Basic Information Report" style="margin:0; padding:10px; color:#232428; background-color:#fafdff;"> 

    <!--Basic Information:-->
    <fieldset>
        <legend>
            Basic Information:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0"> 
                <tr>
                    <th style="width:185px;">Attributes</th>
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Type of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['type_of_organization']; ?></td>
                </tr> 
                <tr class="alt">
                    <td style="font-weight:bold;">Licensing Status</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['licensing_status']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Organization's Short Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['short_name_of_org']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Organization's Full Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">MRA Acts</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['mra_act']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Authorized Person</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['name_of_authorized_person']; ?></td>
                </tr>         
                <tr>
                    <td style="font-weight:bold;">Designation</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['designation_of_authorized_person']; ?></td>
                </tr>               
                <tr class="alt">
                    <td style="font-weight:bold;">Signature of CEO</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['applicantSignature']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Date Of Application</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($mfiDetails['ViewReportBasicModuleBasicInfo']['date_of_application'],'%d-%m-%Y',''); ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Registration Authority</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['registration_authority']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Registration No</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['registration_no']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Date Of Registration</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($mfiDetails['ViewReportBasicModuleBasicInfo']['date_of_registration'],'%d-%m-%Y',''); ?></td>
                </tr>
            </table> 
        </div>
    </fieldset>

    <!--Rejection History:-->
    <hr style="margin: 5px auto;" />    
    <fieldset>
        <legend>
            Rejection History:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:185px;">Attributes</th>    <!--Address Type-->
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">First Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($mfiDetails['BasicModuleRejectionHistory']['firstRejectionDate'],'%d-%m-%Y',''); ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Last Final Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($mfiDetails['BasicModuleRejectionHistory']['lastFinalRejectionDate'],'%d-%m-%Y',''); ?></td>
                </tr>         
                <tr>
                    <td style="font-weight:bold;">Rejection Count</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleRejectionHistory']['rejectionCount']; ?></td>
                </tr>               
                <tr class="alt">
                    <td style="font-weight:bold;">Comment on Last Rejection</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleRejectionHistory']['commentOnLastRejection']; ?></td>
                </tr>
            </table> 
        </div>
    </fieldset>

    <?php //} ?>

    <!--Address:-->
    <hr style="margin: 5px auto;" />
    
    <fieldset>
        <legend>
            Address:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="width:2000px;">
                <tr>
                    <th style="width:170px;">Address Type</th>
                    <th></th>
                    <th style="width:100px; text-align:center;">Holding No.</th>
                    <th style="width:100px; text-align:center;">District</th>
                    <th style="width:100px; text-align:center;">Upazila</th>
                    <th style="width:100px; text-align:center;">Union</th>
                    <th style="width:100px; text-align:center;">Mauza</th>
                    <th style="width:100px; text-align:center;">Mahalla/Post Office</th>
                    <th style="width:100px; text-align:center;">Road Name/Village</th>
                    <th style="width:100px; text-align:center;">Phone No.</th>
                    <th style="width:100px; text-align:center;">Mobile No.</th>
                    <th style="width:100px; text-align:center;">Fax</th>
                    <th style="width:100px; text-align:center;">E-mail</th>
                    <th style="width:100px; text-align:center;">Usage of Office Space(sq.ft)</th>
                    <th style="width:100px; text-align:center;">Duration of Rent Agreement</th>
                    <th style="width:100px; text-align:center;">Proposed Monthly Rent</th>
                    <th style="width:100px; text-align:center;">Time Period(Start)</th>
                    <th style="width:100px; text-align:center;">Time Period(End)</th>
                </tr>

                <?php 
                    $rc=0;
                    foreach($allAddDetails as $addDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) echo ' class="alt"'; ?>>
                    <td><b><?php echo $addDetails['LookupBasicAddressType']['address_type']; ?></b></td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleAddress']['holding_no']; ?></td>
                    <td><?php echo $addDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <td><?php echo $addDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                    <td><?php echo $addDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                    <td><?php echo $addDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['mohalla_or_post_office']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['road_name_or_village']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['phone_no']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['mobile_no']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['fax']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['email']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['usage_of_office_space']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['duration_of_proposed_rent_agreement']; ?></td>
                    <td><?php echo $addDetails['BasicModuleAddress']['proposed_monthly_rent']; ?></td>
                    <td><?php echo $this->Time->format($addDetails['BasicModuleAddress']['time_period_start'], '%d-%m-%Y', ''); ?></td>
                    <td><?php echo $this->Time->format($addDetails['BasicModuleAddress']['time_period_end'], '%d-%m-%Y', ''); ?></td>
                </tr>
                <?php } ?>

            </table>
        </div>
    </fieldset>
        
    <!--Branch:-->
    <hr style="margin: 5px auto;" />
    <?php     
        //$allAddDetails = $mfiDetails['BasicModuleAddress'];
    ?>    
    <fieldset>
        <legend>
            Branch:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <!--<th></th>-->
                    <th style="width:170px;">Branch Name</th>
                    <th style="width:150px;">District</th>
                    <th style="width:150px;">Upazila</th>
                    <th style="width:150px;">Union</th>
                    <th style="width:150px;">Mauza</th>
                    <th style="width:150px;">Latitude</th>
                    <th style="width:150px;">Longitude</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allBranchDetails as $branchDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <!--<td class="colons">:</td>-->
                    <td><b><?php echo $branchDetails['BasicModuleBranchInfo']['branch_name']; ?></b></td>
                    <td><?php echo $branchDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <td><?php echo $branchDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                    <td><?php echo $branchDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                    <td><?php echo $branchDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                    <td><?php echo $branchDetails['BasicModuleBranchInfo']['Lat']; ?></td>
                    <td><?php echo $branchDetails['BasicModuleBranchInfo']['Long']; ?></td>
                </tr>
                <?php } ?>
            </table> 
        </div>
    </fieldset>
    
    <!--Non-current Asset:-->
    <hr style="margin: 5px auto;" />
    <?php     
        //$allAddDetails = $mfiDetails['BasicModuleAddress'];
    ?>    
    <fieldset>
        <legend>
            Non-current Asset:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Asset Type</th> 
                    <th style="width:100px;">Monitary Value(BDT)</th>
                    <th style="width:100px;">Fiscal Year</th>
                    <th style="width:100px;">Khatiyan No.</th>
                    <th style="width:100px;">Holding No.</th>
                    <th style="width:100px;">District</th>
                    <th style="width:100px;">Upazila</th>
                    <th style="width:100px;">Union</th>
                    <th style="width:100px;">Mauza</th>
                </tr>
                
                <?php 
                    $rc=0;
                    foreach($allNcAssetDetails as $ncAssetDetails){ 
                    $rc++;
                ?>

                <tr <?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $ncAssetDetails['LookupClassNonCurrentAsset']['non_current_asset_class']; ?></td>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['monetaryValue']; ?></td>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['fiscalYear']; ?></td>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['khatiyanNo']; ?></td>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['holding_no']; ?></td>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                </tr>
                <?php } ?>
            </table> 
        </div>
    </fieldset>
        
    
    <!--Funding Institutions:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Funding Institutions:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="min-width:2000px;">
                <tr>
                    <th style="width:170px;">Institution Name</th>
                    <th style="width:100px;">Fiscal Year</th>
                    <th style="width:100px;">Liability Type</th>
                    <th style="width:100px;">Lender Type</th>
                    <th style="width:150px;">Lender Origin</th>
                    <th style="width:100px;">Local Classification</th>
                    <th style="width:150px;">Others Local Source Name</th>
                    <th style="width:150px;">Foreign Source Name</th>
                    <th style="width:100px;">Taka Received</th>
                    <th style="width:100px;">Loan Date</th>
                    <th style="width:100px;">Loan Duration</th>
                    <th style="width:100px;">Interest Rate</th>
                    <th style="width:100px;">Loan Amount</th>
                    <th style="width:100px;">Grace Period</th>
                    <th style="width:100px;">Ratio in Respect of Total Fund</th>
                    <th style="width:100px;">Cost of Fund</th>
                </tr>

                <?php
                    $rc=0;
                    foreach($allFundInsDetails as $fundInsDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['institutionsName']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['fiscalYear']; ?></td>
                    <td><?php echo $fundInsDetails['LookupLiabilityType']['liability_types']; ?></td>
                    <td><?php echo $fundInsDetails['LookupLenderType']['lender_types']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['lenderOrigin']; ?></td>
                    <td><?php echo $fundInsDetails['LookupLocalClassification']['local_classification']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['othersLocalSourceName']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['foreignSourceName']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['takaReceived']; ?></td>
                    <td><?php echo $this->Time->format($fundInsDetails['BasicModuleFundingInstitution']['loanDate'], '%d-%m-%Y', ''); ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['loanDuration']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['interestRate']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['loanAmount']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['gracePeriod']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['ratioRespectTotalFund']; ?></td>
                    <td><?php echo $fundInsDetails['BasicModuleFundingInstitution']['costOfFund']; ?></td>
                </tr>
                <?php } ?>
            </table> 
        </div>
    </fieldset>

    <!--Bank Information:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Bank Information:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Bank Name</th> 
                    <th style="width:180px;">Bank Address</th> 
                    <th style="width:150px;">Information Type</th> 
                    <th style="width:100px;">Savings A/C No.</th> 
                    <th style="width:100px;">FDR No.</th>
                </tr>
                
                <?php 
                    $rc=0;
                    foreach($allBankDetails as $bankDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $bankDetails['BasicModuleBankInformation']['bankName']; ?></td>
                    <td><?php echo $bankDetails['BasicModuleBankInformation']['bankAddress']; ?></td>
                    <td><?php echo $bankDetails['LookupInfoType']['info_types']; ?></td>
                    <td><?php echo $bankDetails['BasicModuleBankInformation']['savings']; ?></td>
                    <td><?php echo $bankDetails['BasicModuleBankInformation']['fdrNo']; ?></td>
                </tr>
                <?php } ?>
            </table> 

        </div>
    </fieldset>

    
    <!--Renewable Security:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Renewable Security:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:100px;">Fiscal Year</th>
                    <th style="width:100px;">Purchasing Date</th>
                    <th style="width:170px;">Institutions Name</th>
                    <th style="width:100px;">Renewable Security Type</th>
                    <th style="width:100px;">Renewable Security No.</th>
                    <th style="width:100px;">Renewable Security Amount</th>
                    <th style="width:100px;">Renewable Security Duration</th>
                    <th style="width:100px;">Renewable Security Interest Rate</th>
                    <th style="width:170px;">Bank Name</th>
                    <th style="width:170px;">Bank Branch Name</th>
                </tr>
                
                <?php 
                    $rc=0;
                    foreach($allRenewSecDetails as $renewSecDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['fiscalYear']; ?></td>
                    <td><?php echo $this->Time->format($renewSecDetails['BasicModuleRenewableSecurity']['purchasingDate'],'%d-%m-%Y',''); ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['institutionsName']; ?></td>
                    <td><?php echo $renewSecDetails['LookupRenewableSecurityType']['renewable_security_types']; ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['renewableSecurityNo']; ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['renewableSecurityAmount']; ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['renewableSecurityDuration']; ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['renewableSecurityInterestRate']; ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['bankName']; ?></td>
                    <td><?php echo $renewSecDetails['BasicModuleRenewableSecurity']['bankbranch_name']; ?></td>
                </tr>
                <?php } ?>
            </table> 

        </div>
    </fieldset>

        
    
    <!--Payment History:-->
    <?php //if($allPaymentDetails!=null){ ?>
    <hr style="margin: 5px auto;" />    
    <fieldset>
        <legend>
            Payment History:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Payment Type</th>
                    <th style="width:150px;">Payment No.</th>
                    <th style="width:150px;">Payment Amount</th>
                    <th style="width:150px;">Date Of Payment</th>
                    <th style="width:150px;">Payment Doc Number</th>
                </tr>
                
                <?php 
                    $rc=0;
                    foreach($allPaymentDetails as $paymentDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $paymentDetails['LookupPaymentType']['payment_type']; ?></td>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['payment_no']; ?></td>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentAmount']; ?></td>
                    <td><?php echo $this->Time->format($paymentDetails['BasicModulePaymentInfo']['dateOfPayment'],'%d-%m-%Y',''); ?></td>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentDocNumber']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
    <?php //} ?>
    
    <!--Transaction information:-->
    <hr style="margin: 5px auto;" />    
    <fieldset>
        <legend>
            Transaction information:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Bank Name</th> 
                    <th style="width:180px;">Branch Name</th> 
                    <th style="width:150px;">Account Operating Officer Name</th> 
                    <th style="width:100px;">Designation Of Officer</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allTransactionDetails as $transactionDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $transactionDetails['BasicModuleTransactionInfo']['bankName']; ?></td>
                    <td><?php echo $transactionDetails['BasicModuleTransactionInfo']['branch_name']; ?></td>
                    <td><?php echo $transactionDetails['BasicModuleTransactionInfo']['accOperatingOfficerName']; ?></td>
                    <td><?php echo $transactionDetails['BasicModuleTransactionInfo']['designation_of_officer']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
    
    
    <!--Proposed Loan Information:-->
    <hr style="margin: 5px auto;" />    
    <fieldset>
        <legend>
            Proposed Loan Information:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:150px;">Application ID</th> 
                    <th style="width:150px;">Loan type</th> 
                    <th style="width:250px;">Description</th> 
                    <th style="width:100px;">Service Charge Rate</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allPropLoanDetails as $propLoanDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $propLoanDetails['BasicModuleProposedLoanInfo']['application_id']; ?></td>
                    <td><?php echo $propLoanDetails['LookupLoanType']['loan_types']; ?></td>
                    <td><?php echo $propLoanDetails['BasicModuleProposedLoanInfo']['description']; ?></td>
                    <td><?php echo $propLoanDetails['BasicModuleProposedLoanInfo']['serviceChargeRate']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
    
    <!--Proposed Savings Information:-->
    <hr style="margin: 5px auto;" />
    
    <fieldset>
        <legend>
            Proposed Savings Information:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:150px;">Application ID</th> 
                    <th style="width:150px;">Savings Scheme</th> 
                    <th style="width:250px;">Description</th> 
                    <th style="width:100px;">Interest Rate</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allPropSavingDetails as $propSavingDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $propSavingDetails['BasicModuleProposedSavingInfo']['application_id']; ?></td>
                    <td><?php echo $propSavingDetails['LookupSavingsScheme']['savings_schemes']; ?></td>
                    <td><?php echo $propSavingDetails['BasicModuleProposedSavingInfo']['description']; ?></td>
                    <td><?php echo $propSavingDetails['BasicModuleProposedSavingInfo']['interestRate']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
    
    <!--Financial Information(Income):-->
    <hr style="margin: 5px auto;" />

    <fieldset>
        <legend>
            Financial Information(Income):
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:100px;">Fiscal Year</th> 
                    <th style="width:150px;">Reimbursement Income</th> 
                    <th style="width:150px;">Bank Interest</th> 
                    <th style="width:100px;">Membership Fees</th> 
                    <th style="width:100px;">Other Sales</th> 
                    <th style="width:100px;">Others</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allOrgIncomeDetails as $orgIncomeDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $orgIncomeDetails['BasicModuleOrgIncome']['fiscalYear']; ?></td>
                    <td><?php echo $orgIncomeDetails['BasicModuleOrgIncome']['reimbursementIncome']; ?></td>
                    <td><?php echo $orgIncomeDetails['BasicModuleOrgIncome']['bankInterest']; ?></td>
                    <td><?php echo $orgIncomeDetails['BasicModuleOrgIncome']['membershipFees']; ?></td>
                    <td><?php echo $orgIncomeDetails['BasicModuleOrgIncome']['otherSales']; ?></td>
                    <td><?php echo $orgIncomeDetails['BasicModuleOrgIncome']['others']; ?></td>
                </tr>
                <?php } ?>
            </table> 

        </div>
    </fieldset>
    
    
    <!--Financial Information(Expenditure):-->
    <hr style="margin: 5px auto;" />
    
    <fieldset>
        <legend>
            Financial Information(Expenditure):
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="width:2500px;">
                <tr>
                    <th style="width:100px;">Fiscal Year</th> 
                    <th style="width:100px;">Salaries and Allowances</th>
                    <th style="width:100px;">Office Rent</th>
                    <th style="width:100px;">Printing and Stationary</th>
                    <th style="width:100px;">Traveling</th>
                    <th style="width:100px;">Telephone and Postage</th>
                    <th style="width:100px;">Repair and Maintenance</th>                    
                    <th style="width:100px;">Fuel Cost</th>
                    <th style="width:100px;">Gas & Electricity</th>
                    <th style="width:100px;">Entertainment</th>
                    <th style="width:100px;">Advertisement</th>
                    <th style="width:100px;">Newspaper and Periodicals</th>
                    <th style="width:100px;">Bank Charges/DD Charges</th>
                    <th style="width:100px;">Training Expense</th>
                    <th style="width:100px;">Vehicle Maintenance</th>
                    <th style="width:100px;">Legal Expense</th>                    
                    <th style="width:100px;">Registration Fee</th>
                    <th style="width:100px;">Meeting Expense</th>
                    <th style="width:100px;">Other Operating Expense</th>
                    <th style="width:100px;">Audit Fees</th>
                    <th style="width:100px;">Board Members Honorarium</th>
                    <th style="width:100px;">Taxes</th>
                    <th style="width:100px;">LLP</th>
                    <th style="width:100px;">DMFE</th>
                    <th style="width:170px;">Depreciation</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allOrgExpendDetails as $orgExpendDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['fiscalYear']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['salariesAllowances']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['officeRent']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['printingStationary']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['traveling']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['telephonePostage']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['repairMaintenance']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['fuelCost']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['gasElectricity']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['entertainment']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['advertisement']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['newspapersPeriodicals']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['bankChargesDDCharges']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['trainingExpenses']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['vehicleMaintenance']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['legalExpenses']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['registrationFee']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['meetingExpenses']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['otherOperatingExpenses']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['auditFees']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['boardMembersHonorarium']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['taxes']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['llp']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['dmfe']; ?></td>
                    <td><?php echo $orgExpendDetails['BasicModuleOrgExpenditure']['depreciation']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
    
    <!--Balance Sheet:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Balance Sheet:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="width:2500px;">
                <tr>                    
                    <th style="width:100px;">Balance Sheet Type</th>
                    <th style="width:100px;">Balance Sheet Year</th>
                    <th style="width:100px;">Cash In Hand</th>
                    <th style="width:100px;">Cash at Bank</th>
                    <th style="width:100px;">Short Term Investment</th>
                    <th style="width:100px;">Loans To Other MRO</th>
                    <th style="width:100px;">Loans To Member Bad Debt Prov</th>
                    <th style="width:100px;">Other Loans</th>
                    <th style="width:100px;">Other Investments</th>
                    <th style="width:100px;">Land Building Depreciation</th>
                    <th style="width:100px;">Other Fixed Asset Depreciation</th>
                    <th style="width:100px;">Other Assets</th>
                    <th style="width:100px;">Member Deposits</th>
                    <th style="width:100px;">Loans From PKSF</th>
                    <th style="width:100px;">Loans From Housing Fund</th>
                    <th style="width:100px;">Loans Other Government Sources</th>
                    <th style="width:100px;">Loans Other Micro-credit Organizations</th>
                    <th style="width:100px;">Loans Commercial Banks</th>
                    <th style="width:100px;">Other Loan</th>
                    <th style="width:100px;">Other Liabilities</th>
                    <th style="width:100px;">Donor Funds</th>
                    <th style="width:100px;">Cumulative Surplus</th>
                    <th style="width:100px;">Other Funds</th>                  
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allBalanceSheetDetails as $balanceSheetDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $balanceSheetDetails['LookupBalanceSheetType']['balance_sheet_type']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['balanceSheetYear']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['cashInHand']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['cashAtBank']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['shortTermInvestment']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansToOtherMRO']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansToMembBadDebtProv']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherLoans']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherInvestments']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['landBuildingDepreciation']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherFixedAssetDepreciation']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherAssets']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['memberDeposits']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansFromPKSF']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansFromHousingFund']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansOtherGovernmentSources']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansOtherMicrocreditOrganizations']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['loansCommercialBanks']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherLoan']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherLiabilities']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['donorFunds']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['cumulativeSurplus']; ?></td>
                    <td><?php echo $balanceSheetDetails['BasicModuleOrgBalanceSheet']['otherFunds']; ?></td>
                </tr>
                <?php } ?>
            </table> 
        </div>
    </fieldset>

    
    <!--Income & Expenditure Statement:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Income & Expenditure Statement:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="width:2500px;">
                <tr>
                    <th style="width:100px;">Statement Year</th>
                    <th style="width:100px;">Service Charge</th>
                    <th style="width:100px;">Grant</th>
                    <th style="width:100px;">Sale PassBook Forms</th>
                    <th style="width:100px;">Admission Fees</th>
                    <th style="width:100px;">Income Investment</th>
                    <th style="width:100px;">Interest Banks</th>
                    <th style="width:100px;">Other Income</th>
                    <th style="width:100px;">Interest On Savings</th>
                    <th style="width:100px;">Interest On Borrowing</th>
                    <th style="width:100px;">Salaries Allowances</th>
                    <th style="width:100px;">Office Expenses</th>
                    <th style="width:100px;">Depreciation</th>
                    <th style="width:100px;">Training Development</th>
                    <th style="width:100px;">Honorarium Member Of General</th>
                    <th style="width:100px;">Executive Body</th>
                    <th style="width:100px;">Audit Fee</th>
                    <th style="width:100px;">Other Expenses</th>
                    <th style="width:100px;">Bad Debt Provision</th>
                    <th style="width:100px;">Transfer To Funds Reserves</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allIncExpDetails as $incExpDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['statementYear']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['serviceCharge']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['grants']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['salePassBookForms']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['admissionFees']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['incomeInvestment']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['interestBanks']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['otherIncome']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['interestOnSavings']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['interestOnBorrowing']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['salariesAllowances']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['officeExpenses']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['depreciation']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['trainingDevelopment']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['honorariumMemberOfGeneral']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['executiveBody']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['auditFee']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['otherExpenses']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['badDebtProvision']; ?></td>
                    <td><?php echo $incExpDetails['BasicModuleIncomeExpenditureStatement']['transferToFundsReserves']; ?></td>
                </tr>
                <?php } ?>
            </table> 
    </fieldset>

    
    <!--MC Activities Plan:-->
    <hr style="margin: 5px auto;" />

    <fieldset>
        <legend>
            MC Activities Plan:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:100px;">Date of Commencement</th> 
                    <th style="width:100px;">Count of Year</th> 
                    <th style="width:100px;">Male Client Count</th> 
                    <th style="width:100px;">Female Client Count</th> 
                    <th style="width:100px;">Male Borrower Count</th> 
                    <th style="width:100px;">Female Borrower Count</th>
                    <th style="width:100px;">Principal Loan Outstanding</th>
                    <th style="width:100px;">Member Savings</th>
                    <th style="width:100px;">No. of Branches</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allMcActivitiDetails as $mcActivitiDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $this->Time->format($mcActivitiDetails['BasicModuleMCActivitiesPlan']['dateOfCommencement'],'%d-%m-%Y',''); ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['countYear']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['maleClientCount']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['femaleClientCount']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['maleBorrowerCount']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['femaleBorrowerCount']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['principlLoanOutstanding']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['memberSavings']; ?></td>
                    <td><?php echo $mcActivitiDetails['BasicModuleMCActivitiesPlan']['noOfBranches']; ?></td>
                </tr>
                <?php } ?>
            </table> 
        </div>
    </fieldset>

    <!--CEO Details:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            CEO Details:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:185px;">Attributes</th>
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Name of CEO</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOrganizationCEO']['nameOfCEO']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">National ID</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOrganizationCEO']['ceo_nid']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Experience in Micro-credit Activities</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOrganizationCEO']['experienceMicrocreditActivities']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Date of Joining</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($mfiDetails['BasicModuleOrganizationCEO']['dateOfJoining'],'%d-%m-%Y',''); ?></td>
                </tr>
            </table>
        </div>
    </fieldset>
    
    <!--HR Information:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            HR Information:
        </legend>
        <div class="datagrid">

            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <th style="width:185px;">Attributes</th>
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Male Employee Count MC Activities</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['maleEmployeeCountMCActivities']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Male Employee Count Non MC Activities</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['maleEmployeeCountNonMCActivities']; ?></td>
                </tr> 
                <tr>
                    <td style="font-weight:bold;">Male Employee Count Cross Sharing</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['maleEmployeeCountCrossSharing']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Female Employee Count MC Activities</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['femaleEmployeeCountMCActivities']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Female Employee Count Non MC Activities</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['femaleEmployeeCountNonMCActivities']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Female Employee Count Cross Sharing</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['femaleEmployeeCountCrossSharing']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Designation Highest Paid Officer</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['designationHighestPaidOfficer']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Highest Monthly Salary</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['highestMonthlySalary']; ?></td>
                </tr> 
                <tr>
                    <td style="font-weight:bold;">Designation Lowest Paid Staff</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['designationLowestPaidStaff']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Lowest Monthly Salary</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['lowestMonthlySalary']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Other Important Management Information</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleHumanResourcesInfo']['otherImportantManagementInfo']; ?></td>
                </tr>
            </table>
        </div>
    </fieldset>

    <!--Governing Body:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Governing Body:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:185px;">Attributes</th>
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">No. Of Members</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleGoverningBodyInfo']['noOfMembers']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">No. Of Meetings Held Yearly</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleGoverningBodyInfo']['noOfMeetingsHeldYearly']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Date Of Last Meeting</td>
                    <td class="colons">:</td>
                    <td>
                        <?php echo $this->Time->format($mfiDetails['BasicModuleGoverningBodyInfo']['dateOfLastMeeting'],'%d-%m-%Y',''); ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Attendance Last Meeting</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleGoverningBodyInfo']['noOfAttendanceLastMeeting']; ?></td>
                </tr>
            </table>
        </div>
    </fieldset>

    <!--Body Members:-->
    <hr style="margin: 5px auto;" />    
    <fieldset>
        <legend>
            Body Members:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="width:3500px;">
                <tr>
                    <th style="width:120px;">National ID</th>
                    <th style="width:100px; text-align:center;">Member Type</th>
                    <th style="width:100px; text-align:center;">Designation</th>
                    <th style="width:100px; text-align:center;">Name</th>
                    <th style="width:100px; text-align:center;">Father's Name</th>
                    <th style="width:100px; text-align:center;">Mother's Name</th>
                    <th style="width:100px; text-align:center;">Spouse Name</th>
                    <th style="width:100px; text-align:center;">Gender</th>
                    <th style="width:100px; text-align:center;">Present Address</th>
                    <th style="width:100px; text-align:center;">Permanent Address</th>
                    <th style="width:100px; text-align:center;">Phone No</th>
                    <th style="width:100px; text-align:center;">Mobile No</th>
                    <th style="width:100px; text-align:center;">Fax</th>
                    <th style="width:100px; text-align:center;">E-mail</th>
                    <th style="width:100px; text-align:center;">Date Of Birth</th>
                    <th style="width:100px; text-align:center;">Nationality</th>
                    <th style="width:100px; text-align:center;">Religion</th>
                    <th style="width:100px; text-align:center;">Passport No</th>
                    <th style="width:100px; text-align:center;">TIN No</th>
                    <th style="width:100px; text-align:center;">Signature</th>
                    <th style="width:100px; text-align:center;">Date</th>
                    <th style="width:100px; text-align:center;">Photo</th>
                    <th style="width:100px; text-align:center;">Donation Amount</th>
                    <th style="width:100px; text-align:center;">Loan To Org Amount</th>
                    <th style="width:100px; text-align:center;">Loan Org Interest</th>
                    <th style="width:100px; text-align:center;">Loan From Org Amount</th>
                    <th style="width:100px; text-align:center;">Loan From Org Interest</th>
                    <th style="width:100px; text-align:center;">Profession</th>
                    <th style="width:100px; text-align:center;">Professional Designation</th>
                    <th style="width:100px; text-align:center;">Institution Served</th>
                </tr>

                <?php
                    $rc=0;
                    foreach($allBodyMembDetails as $bodyMembDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['member_nid']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['memberType_id']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['memberDesignation']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['name']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['fathersName']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['mothersName']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['spouseName']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['gender']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['presentAddress']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['permanentAddress']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['phone_no']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['mobile_no']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['faxNo']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['emailNo']; ?></td>
                    <td><?php echo $this->Time->format($bodyMembDetails['BasicModuleBodyMemberInfo']['dateOfBirth'],'%d-%m-%Y',''); ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['nationality']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['religion']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['passportNo']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['tinNo']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['signature']; ?></td>
                    <td><?php echo $this->Time->format($bodyMembDetails['BasicModuleBodyMemberInfo']['dateOfSign'],'%d-%m-%Y',''); ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['photo']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['donationAmount']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['loanToOrgAmount']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['loanOrgInterest']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['loanFromOrgAmount']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['loanFromOrgInterest']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['profession']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['professionalDesignation']; ?></td>
                    <td><?php echo $bodyMembDetails['BasicModuleBodyMemberInfo']['institutionServed']; ?></td>
                </tr>
                <?php } ?>
                
            </table>
        </div>
    </fieldset>

    <!--Body Member Affiliations:-->
    <hr style="margin: 5px auto;" />        
    <fieldset>
        <legend>
            Body Member Affiliations:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <th style="width:100px;">Application ID</th>
                    <th style="width:100px;">National ID</th>
                    <th style="width:150px;">Institution Name</th>
                    <th style="width:170px;">Institution Address</th>
                    <th style="width:100px;">Type Of Affiliation</th>
                </tr>

                <?php
                    $rc=0;
                    foreach($allBodyMembAffiDetails as $bodyMembAffiDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $bodyMembAffiDetails['BasicModuleBodyMemberAffiliationInfo']['application_id']; ?></td>
                    <td><?php echo $bodyMembAffiDetails['BasicModuleBodyMemberAffiliationInfo']['member_nid']; ?></td>
                    <td><?php echo $bodyMembAffiDetails['BasicModuleBodyMemberAffiliationInfo']['institutionName']; ?></td>
                    <td><?php echo $bodyMembAffiDetails['BasicModuleBodyMemberAffiliationInfo']['institutionAddress']; ?></td>
                    <td><?php echo $bodyMembAffiDetails['LookupAffiliationType']['affiliation_types']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
    
    <!--Body Member Educational Information:-->
    <hr style="margin: 5px auto;" />    
    <fieldset>
        <legend>
            Body Member Educational Information:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:100px;">National ID</th>
                    <th style="width:100px;">Exam. Name</th>
                    <th style="width:150px;">Institution Name</th>
                    <th style="width:170px;">Board/University</th>
                </tr>

                <?php
                    $rc=0;
                    foreach($allBodyMembEduDetails as $bodyMembEduDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $bodyMembEduDetails['BasicModuleBodyMemberEducationInfo']['member_nid']; ?></td>
                    <td><?php echo $bodyMembEduDetails['BasicModuleBodyMemberEducationInfo']['examName']; ?></td>
                    <td><?php echo $bodyMembEduDetails['BasicModuleBodyMemberEducationInfo']['institutionName']; ?></td>
                    <td><?php echo $bodyMembEduDetails['BasicModuleBodyMemberEducationInfo']['boardUniversity']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>

    <!--Branch HR Information:-->    
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Branch HR Information:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <th style="width:170px;">Branch Name</th>
                    <th style="width:100px;">No. of Male Employee in Reg Office MC Activity</th>
                    <th style="width:100px;">No. of Male Employee in Head Office MC Activity</th>
                    <th style="width:100px;">No. of Female Employee in Regional Office MC Activity</th>
                    <th style="width:100px;">No. of Female Employee in Head Off MC Activity</th>
                    <th style="width:100px;">Total Male Member Count</th>
                    <th style="width:100px;">Total Female Member Count</th>
                    <th style="width:100px;">Total No. Of Male Graduate Members</th>
                    <th style="width:100px;">Total No. Of Female Graduate Members</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allBranchHRDetails as $branchHRDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $branchHRDetails['BasicModuleBranchInfo']['branch_name']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['noMaleEmplRegOfficeMCActivity']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['noMaleEmplHeadOfficeMCActivity']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['noFemaleEmplRegionalOfficeMCActivity']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['noFemaleEmployeeHeadOffMCActivity']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['totalMaleMemberCount']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['totalFemaleMemberCount']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['totalNoOfMaleGraduateMembers']; ?></td>
                    <td><?php echo $branchHRDetails['BasicModuleBranchHRInfo']['totalNoOfFemaleGraduateMembers']; ?></td>
                </tr>
                <?php }?>
            </table>
        </div>
    </fieldset>
    
    <!--Case Information:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Case Information:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <th style="width:100px;">Member National ID</th>
                    <th style="width:100px;">Case No</th>
                    <th style="width:100px;">Case Type</th>
                    <th style="width:185px;">Name The Court</th>
                    <th style="width:100px;">Duration Of Conviction</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allCaseDetails as $caseDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $caseDetails['BasicModuleCaseInformation']['member_nid']; ?></td>
                    <td><?php echo $caseDetails['BasicModuleCaseInformation']['caseNo']; ?></td>
                    <td><?php echo $caseDetails['LookupCaseType']['case_types']; ?></td>
                    <td><?php echo $caseDetails['BasicModuleCaseInformation']['nameOfCourt']; ?></td>
                    <td><?php echo $caseDetails['BasicModuleCaseInformation']['durationOfConviction']; ?></td>
                </tr>
                <?php } ?>
            </table> 

        </div>
    </fieldset>

    <!--Sister Organizations:-->
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Sister Organizations:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <th style="width:185px;">Sister Organization Name</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:100px;">Entity</th>
                    <th style="width:185px;">Address</th>
                    <th style="width:100px;">Phone</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allSisOrgDetails as $sisOrgDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                    <td><?php echo $sisOrgDetails['BasicModuleSisterOrganizationInfo']['sisterOrgName']; ?></td>
                    <td><?php echo $sisOrgDetails['BasicModuleSisterOrganizationInfo']['orgStatus']; ?></td>
                    <td><?php echo $sisOrgDetails['BasicModuleSisterOrganizationInfo']['entity']; ?></td>
                    <td><?php echo $sisOrgDetails['BasicModuleSisterOrganizationInfo']['address']; ?></td>
                    <td><?php echo $sisOrgDetails['BasicModuleSisterOrganizationInfo']['phone']; ?></td>
                </tr>
                <?php }?>
            </table>
        </div>
    </fieldset>
    
    <hr style="margin: 5px auto;" />
    <fieldset>
        <legend>
            Operation Policy:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Policies</th>
                    <th></th>
                    <th>Details</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Service Rules</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOperationPolicy']['serviceRules']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Recruitment Policy</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOperationPolicy']['recruitmentPolicy']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Financial Policy</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOperationPolicy']['financialPolicy']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Savings Credit Policy</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOperationPolicy']['savingsCreditPolicy']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Remittance Rules Policy</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOperationPolicy']['remittanceRulesPolicy']; ?></td>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Other Policies</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiDetails['BasicModuleOperationPolicy']['otherPolicies']; ?></td>
                </tr>        
            </table> 

        </div>
    </fieldset>
        
    <hr style="margin: 5px auto;" />
    
    <fieldset>
        <legend>
            Other Activities:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <th style="width:185px;">Program Area</th>
                    <th style="width:150px;">Source Of Fund</th>
                    <th style="width:100px;">Percent Of Share</th>
                    <th style="width:100px;">Share In BDT</th>
                    <th style="width:150px;">CEO Name In Case Of SepLegal Entity</th>
                    <th style="width:170px;">Address</th>
                    <th style="width:100px;">Phone</th>
                    <th style="width:100px;">Fax</th>
                </tr>
                
                <?php
                    $rc=0;
                    foreach($allOtherActivityDetails as $otherActivityDetails){ 
                    $rc++;
                ?>

                <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>                    
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['programArea']; ?></td>                    
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['sourceOfFund']; ?></td>
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['percentOfShare']; ?></td>
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['shareInBDT']; ?></td>
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['ceoNameInCaseOfSepLegalEntity']; ?></td>
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['address']; ?></td>
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['phone']; ?></td>
                    <td><?php echo $otherActivityDetails['BasicModuleOtherActivity']['fax']; ?></td>
                </tr>
                <?php } ?>
            </table> 

        </div>
    </fieldset>

    <hr style="margin: 5px auto;" />
    
    <a href="#" id="lnkscrollup" class="scrollup" alt="scroll to top of the page" title="scroll to top of the page" ></a>
</div>

<?php echo $this->Html->link('Export', array('controller'=>'ViewReportBasicModuleBasicInfos','action'=>'export_pdf', 'ext'=>'pdf', $mfiDetails['ViewReportBasicModuleBasicInfo']['id'],$filename),
                                                       array('id'=>'lnkExport','style'=>'display:none;')) ?>

<script>
    
    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });

        $('#lnkscrollup').click(function () {
            $("html, body").animate({scrollTop:0}, 1300);
            return false;
        });
        
        $('#basicInfo').animate({ height: 'show', opacity: 'show' }, 2300);

    });
    
    $(function () {      
        
        $( "#basicInfo" ).dialog({
                modal: true, width: 1040, 
                buttons: {                    
                    Export: function() { 
                        window.location=$('#lnkExport').attr('href');
                    },
                    Close: function() {
                        $(this).dialog( "close" );
                    }
                }
            });
        
        $("html, body").animate({scrollTop:0}, 750);
    });
</script>
   


