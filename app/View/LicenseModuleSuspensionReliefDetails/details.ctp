<div>
    <div id="basicInfo" title="Relief from Suspension or Continue" style="margin:0px; padding:10px; background-color:#fafdff;"> 

        <fieldset>
            <legend>Suspension Status Details</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0">                    
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php                             
                                if($allDetails['LicenseModuleSuspensionReliefDetail']['status_id']=='0'){
                                    echo 'Suspension Continued';
                                }
                                else if($allDetails['LicenseModuleSuspensionReliefDetail']['status_id']=='1'){
                                    echo 'Relieved from Suspension';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php 
                        if($allDetails['LicenseModuleSuspensionReliefDetail']['status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td>Date</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Time->format($allDetails['LicenseModuleSuspensionReliefDetail']['relief_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }                       
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['LicenseModuleSuspensionReliefDetail']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
        </fieldset>
    </div>
</div>