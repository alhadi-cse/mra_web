<div>
    <fieldset>
        <legend>Accept/Reject Review Application Details</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0" style="width:90%;">                    
                    <tr>
                        <td style="width:20%;">Name of Organization</td>
                        <td class="colons">:</td>
                        <td style="width:80%;"><?php if(!empty($allDetails)) echo $allDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                            if($allDetails['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='0'){
                                echo 'Review Rejected';
                            }
                            else if($allDetails['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='1'){
                                echo 'Review Accepted';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php 
                        if(!empty($allDetails) && $allDetails['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='1')
                        {
                    ?>
                    <tr>
                        <td>Date</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($allDetails)) echo $this->Time->format($allDetails['LicenseModuleSuspensionAcceptanceOfReviewDetail']['acceptance_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }                       
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($allDetails)) echo $allDetails['LicenseModuleSuspensionAcceptanceOfReviewDetail']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
    </fieldset>
</div>