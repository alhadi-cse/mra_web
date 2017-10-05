<div>
    <div id="basicInfo" title="Suspension Continue or Discontinue" style="margin:0px; padding:10px; background-color:#fafdff;"> 

        <fieldset>
            <legend>Suspension Status Details</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0">                    
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php                             
                                if($licApprovalDetails['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['status_id']=='0'){
                                    echo 'Suspension Continued';
                                }
                                else if($licApprovalDetails['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['status_id']=='1'){
                                    echo 'Suspension Discontinued';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php 
                        if($licApprovalDetails['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td>Date</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Time->format($licApprovalDetails['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['decision_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }                       
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LicenseModuleDirectSuspensionContinueOrDiscontinueDetail']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
        </fieldset>
    </div>
</div>