
<div>
    <div id="basicInfo" title="Administrative Approval of License Cancellation" style="margin:0px; padding:10px; background-color:#fafdff;"> 

        <fieldset>
            <legend>Administrative Approval Details</legend>
            <div class="form">

                <table cellpadding="7" cellspacing="8" border="0">                    
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Approval Status</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LookupLicenseApprovalStatus']['approval_status']; ?></td>
                    </tr>
                    <?php 
                        if($licApprovalDetails['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['approval_status_id']=="1")
                        {
                    ?>
                    <tr>
                        <td>Date of Approval</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Time->format($licApprovalDetails['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['approval_date'],'%d-%m-%Y',''); ?></td>
                    </tr>
                    <?php 
                        }
                        else if($licApprovalDetails['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['approval_status_id']=="2")
                        {
                    ?>
                    <tr>
                        <td>Reason (if not approved)</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['reason_if_not_approved']; ?></td>
                    </tr>
                    <?php 
                        }
                    ?>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $licApprovalDetails['LicenseModuleCancelByMfiAdministrativeApprovalDetail']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
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

</div>

