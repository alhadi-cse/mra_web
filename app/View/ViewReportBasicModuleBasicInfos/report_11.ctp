
<?php                 
    //echo $this->Form->create('BasicModuleBasicInformation'); 

    //debug($mfiDetails['BasicModuleRejectionHistory'][0]);
    debug($mfiDetails);
?>

<div id="basicInfo" title="MFI Basic Information Preview" style="margin:0; padding:10px; color:#232428; background-color:#fafdff;"> 

    <fieldset>
        <legend>
            Basic Information:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0"> 
                <tr>
                    <th style="width:185px;">Attributes</th>    <!--Address Type-->
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




    <hr style="margin: 5px auto;" />

    <?php    
        //$allAddDetails = $mfiDetails['BasicModuleAddress'];
    ?>

    <fieldset>
        <legend>
            Address:
        </legend>

        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" >
                <tr>
                    <th style="width:170px;">Attributes</th>    <!--Address Type-->
                    <th></th>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <th><?php echo $addDetails['LookupBasicAddressType']['address_type']; ?></th>
                    <?php } ?>
                </tr>                
                <tr>
                    <td style="font-weight:bold;">Holding No.</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['holding_no']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">District</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Upazila</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Union</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Mauza</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                    <?php } ?>
                </tr> 
                <tr class="alt">
                    <td style="font-weight:bold;">Mahalla/Post Office</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['mohalla_or_post_office']; ?></td>
                    <?php } ?>
                </tr>				
                <tr>
                    <td style="font-weight:bold;">Road Name/Village</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['road_name_or_village']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Phone No.</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['phone_no']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Mobile No.</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['mobile_no']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Fax</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['fax']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">E-mail</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['email']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Usage of Office Space(sq.ft)</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['usage_of_office_space']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Duration of Rent Agreement</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['duration_of_proposed_rent_agreement']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Proposed Monthly Rent</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $addDetails['BasicModuleAddress']['proposed_monthly_rent']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Time Period(Start)</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td><?php echo $this->Time->format($addDetails['BasicModuleAddress']['time_period_start'], '%d-%m-%Y', ''); ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Time Period(End)</td>
                    <td class="colons">:</td>
                    <?php foreach($allAddDetails as $addDetails){ ?>
                    <td>
                        <?php echo $this->Time->format($addDetails['BasicModuleAddress']['time_period_end'], '%d-%m-%Y', ''); ?>
                    </td>
                    <?php } ?>
                </tr>
            </table>
        </div>
    </fieldset>
    
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
                    <!--<td>Branch Name</td>-->
                    <th style="width:170px;">Attributes</th>
                    <th></th>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <th><?php echo $branchDetails['BasicModuleBranchInfo']['branch_name']; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">District</td>
                    <td class="colons">:</td>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <td><?php echo $branchDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Upazila</td>
                    <td class="colons">:</td>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <td><?php echo $branchDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Union</td>
                    <td class="colons">:</td>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <td><?php echo $branchDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Mauza</td>
                    <td class="colons">:</td>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <td><?php echo $branchDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                    <?php } ?>
                </tr> 
                <tr>
                    <td style="font-weight:bold;">Latitude</td>
                    <td class="colons">:</td>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <td><?php echo $branchDetails['BasicModuleBranchInfo']['Lat']; ?></td>
                    <?php } ?>
                </tr>				
                <tr class="alt">
                    <td style="font-weight:bold;">Longitude</td>
                    <td class="colons">:</td>
                    <?php foreach($allBranchDetails as $branchDetails){ ?>
                    <td><?php echo $branchDetails['BasicModuleBranchInfo']['Long']; ?></td>
                    <?php } ?>
                </tr>
            </table> 
        </div>
    </fieldset>
        
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
                    <!--<td>Asset Type</td>-->
                    <th style="width:170px;">Attributes</th>
                    <th></th>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <th><?php echo $ncAssetDetails['LookupClassNonCurrentAsset']['non_current_asset_class']; ?></th>
                    <?php } ?>
                </tr> 
                <tr>
                    <td style="font-weight:bold;">Monitary Value(BDT)</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['monetaryValue']; ?></td>
                    <?php } ?>
                </tr> 
                <tr class="alt">
                    <td style="font-weight:bold;">Fiscal Year</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['fiscalYear']; ?></td>
                    <?php } ?>
                </tr>                    
                <tr>
                    <td style="font-weight:bold;">Khatiyan no.</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['khatiyanNo']; ?></td>
                    <?php } ?>
                </tr>                    
                <tr class="alt">
                    <td style="font-weight:bold;">Holding No.</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['BasicModuleNonCurrentAsset']['holding_no']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">District</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Upazila</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Union</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td style="font-weight:bold;">Mauza</td>
                    <td class="colons">:</td>
                    <?php foreach($allNcAssetDetails as $ncAssetDetails){ ?>
                    <td><?php echo $ncAssetDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                    <?php } ?>
                </tr>
            </table> 
        </div>
    </fieldset>
    
    <?php if($allPaymentDetails!=null){ ?>
    <hr style="margin: 5px auto;" />
    
    <fieldset>
        <legend>
            Payment History:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <!--<th>Payment Type</th>-->
                    <th style="width:170px;">Attributes</th>
                    <th></th>
                    <?php foreach($allPaymentDetails as $paymentDetails){ ?>
                    <th><?php echo $paymentDetails['LookupPaymentType']['payment_type']; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Payment No.</td>
                    <td class="colons">:</td>
                    <?php foreach($allPaymentDetails as $paymentDetails){ ?>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['payment_no']; ?></td>
                    <?php } ?>
                </tr>
                <tr class="alt">
                    <td>Payment Amount</td>
                    <td class="colons">:</td>
                    <?php foreach($allPaymentDetails as $paymentDetails){ ?>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentAmount']; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <?php foreach($allPaymentDetails as $paymentDetails){ ?>
                    <td><?php echo $this->Time->format($paymentDetails['BasicModulePaymentInfo']['dateOfPayment'],'%d-%m-%Y',''); ?></td>
                    <?php } ?>
                </tr>                
                <tr class="alt">
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <?php foreach($allPaymentDetails as $paymentDetails){ ?>
                    <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentDocNumber']; ?></td>
                    <?php } ?>
                </tr>
            </table>
        </div>
    </fieldset>
    <?php } ?>



    <?php 
        
        $rejectHistDetails = $mfiDetails['BasicModuleRejectionHistory'][0];
        //if($rejectHistDetails!=null){ 
    
    ?>
    <hr style="margin: 5px auto;" />
        
    <fieldset>
        <legend>
            Rejection History:
        </legend>
        
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Attributes</th>    <!--Address Type-->
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">First Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($rejectHistDetails['firstRejectionDate'],'%d-%m-%Y',''); ?></td>
                </tr>                
                <tr class="alt">
                    <td style="font-weight:bold;">Last Final Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($rejectHistDetails['lastFinalRejectionDate'],'%d-%m-%Y',''); ?></td>
                </tr>         
                <tr>
                    <td style="font-weight:bold;">Rejection Count</td>
                    <td class="colons">:</td>
                    <td><?php echo $rejectHistDetails['rejectionCount']; ?></td>
                </tr>               
                <tr class="alt">
                    <td style="font-weight:bold;">Comment on Last Rejection</td>
                    <td class="colons">:</td>
                    <td><?php echo $rejectHistDetails['commentOnLastRejection']; ?></td>
                </tr>
            </table> 
        </div>
    </fieldset>

    <?php //} ?>
    
    
    <?php 
        $renewSecDetails = $mfiDetails['BasicModuleRenewableSecurity'][0];
        //if($mfiDetails['BasicModuleRenewableSecurity']!=null){ 
    ?>
    <hr style="margin: 5px auto;" />

    <fieldset>
        <legend>
            Renewable Security:
        </legend>
        <div class="datagrid">

            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <th style="width:170px;">Attributes</th>
                    <th></th>
                    <th>Details Information</th>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Fiscal Year</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['fiscalYear']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Purchasing Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($renewSecDetails['purchasingDate'],'%d-%m-%Y',''); ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Institutions Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['institutionsName']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Renewable Security Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['LookupRenewableSecurityType']['renewable_security_types']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Renewable Security No</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['renewableSecurityNo']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Renewable Security Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['renewableSecurityAmount']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Renewable Security Duration</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['renewableSecurityDuration']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Renewable Security Interest Rate</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['renewableSecurityInterestRate']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Bank Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['bankName']; ?></td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Bank Branch Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $renewSecDetails['bankbranch_name']; ?></td>
                </tr>
            </table> 

        </div>
    </fieldset>

    <?php //} ?>
    
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
</div>

<?php
    //echo ; 
?>



    <script>
        $(function() {
            $( "#basicInfo" ).dialog({
                modal: true, width: 1040, 
                buttons: {
                    Close: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    </script>



