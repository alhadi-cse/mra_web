
<div>
    <fieldset>
        <legend>Revoke Show cause or Cancel License</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0" style="width:90%;">                    
                    <tr>
                        <td style="width:20%;">Name of Organization</td>
                        <td class="colons">:</td>
                        <td style="width:80%;"><?php if(!empty($cancelOrRevokeShowCauseDetails)) echo $cancelOrRevokeShowCauseDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Status</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                                if(!empty($cancelOrRevokeShowCauseDetails)){
                                    if($cancelOrRevokeShowCauseDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id']=='0'){
                                        echo 'License Cancelled';
                                    }
                                    else if($cancelOrRevokeShowCauseDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id']=='1'){
                                        echo 'Show Cause Revoked';
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <?php 
                        if(!empty($cancelOrRevokeShowCauseDetails) && $cancelOrRevokeShowCauseDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td>Date of Approval</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($cancelOrRevokeShowCauseDetails)) echo $this->Time->format($cancelOrRevokeShowCauseDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['showcause_cancel_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }                        
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php if(!empty($cancelOrRevokeShowCauseDetails)) echo $cancelOrRevokeShowCauseDetails['LicenseModuleCancelByMraCancelOrRevokeShowCauseDetail']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
    </fieldset>
</div>


