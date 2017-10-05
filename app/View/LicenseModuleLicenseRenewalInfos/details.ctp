
<div>
    <div id="basicInfo" title="License Renewal Information" style="margin:0px; padding:10px; background-color:#fafdff;"> 

        <fieldset>
            <legend>
                License Renewal Information Details
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

                <table cellpadding="7" cellspacing="8" border="0">                    
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $licRenewalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                
                    <tr>
                        <td>Date of Renewal</td>
                        <td class="colons">:</td>
                        <td><?php echo $licRenewalDetails['LicenseModuleLicenseRenewalInfo']['renewal_date']; ?></td>
                    </tr>                
                    <tr>
                        <td>Comment</td>
                        <td class="colons">:</td>
                        <td><?php echo $licRenewalDetails['LicenseModuleLicenseRenewalInfo']['comment']; ?></td>
                    </tr>
                </table> 
            </div>
        </fieldset>
    </div>
    <script>
        $(function() {
            $( "#basicInfo" ).dialog({
                modal: true, minWidth: 870, 
                buttons: {
                    Close: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    </script>

</div>

