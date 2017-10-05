

<?php 
    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }    
    else { 
?>

<div id="basicInfo" title="General Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 

    <fieldset>
        <legend>Usage of Office Space</legend>
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="min-width:375px"><?php echo $addDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Usage Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['LookupBasicOfficeUsageType']['usage_type']; ?></td>
                </tr>                
                <tr>
                    <td>Holding No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleOfficeSpaceUsage']['holding_no']; ?></td>
                </tr>
                <tr>
                    <td>Khatiyan No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleOfficeSpaceUsage']['khatiyan_no']; ?></td>
                </tr>
                <tr>
                    <td>District</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                </tr>
                <tr>
                    <td>Upazila</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                </tr>
                <tr>
                    <td>Union</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                </tr>
                <tr>
                    <td>Mauza</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                </tr>
                <tr>
                    <td>Duration of Rent Agreement</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleOfficeSpaceUsage']['duration_of_proposed_rent_agreement']; ?></td>
                </tr>
                <tr>
                    <td>Proposed Monthly Rent</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleOfficeSpaceUsage']['proposed_monthly_rent']; ?></td>
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

<?php } ?>