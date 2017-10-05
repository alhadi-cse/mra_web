
<div>
    <div id="basicInfo" title="Rejection/Suspension/Cancellation Histories" style="margin:0px; padding:10px; background-color:#fafdff;"> 
        <fieldset>
            <legend>
                Rejection/Suspension/Cancellation Histories
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

                <table cellpadding="7" cellspacing="8" border="0">
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $rejectHistDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>                        
                        <td>Type of History</td>                                     
                        <td class="colons">:</td>
                        <td><?php echo $rejectHistDetails['LookupRejectSuspendCancelHistoryType']['reject_suspend_cancel_history_type']; ?></td>
                    </tr>                
                    <tr>
                        <td>Category/Process Step</td>                                     
                        <td class="colons">:</td>
                        <td><?php echo $rejectHistDetails['LookupRejectSuspendCancelStepCategory']['reject_suspend_cancel_category']; ?></td>
                    </tr>         
                    <tr>
                        <td>Stepwise Reason</td>                                    
                        <td class="colons">:</td>
                        <td><?php echo $rejectHistDetails['LookupRejectSuspendCancelStepwiseReason']['reject_suspend_cancel_reason']; ?></td>
                    </tr>               
                    <tr>
                        <td>Date</td>
                        <td class="colons">:</td>
                        <td><?php echo $rejectHistDetails['LicenseModuleRejectSuspendCancelHistory']['reject_suspend_cancel_date']; ?></td>
                    </tr>
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $rejectHistDetails['LicenseModuleRejectSuspendCancelHistory']['comment']; ?></td>
                    </tr>
                </table> 

            </div>
        </fieldset>
    </div>

    <script>
        $(function () {
            $("#basicInfo").dialog({
                modal: true, width: 870,
                buttons: {
                    Close: function () {
                        $(this ).dialog("close");
                    }
                }
            });
        });
    </script>

</div>

