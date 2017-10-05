

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

<div id="basicInfo" title="Other immovable property" style="margin:0px; padding:10px; background-color:#fafdff;"> 

    <fieldset>
        <legend>Details</legend>
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="min-width:175px"><?php echo $allDataDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Property Description</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['property_description']; ?></td>
                </tr>
                <tr>
                    <td>Date of Acquiring</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['date_of_acquiring']; ?></td>
                </tr>
                <tr>
                    <td>Monetary Value</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['monetary_value']; ?></td>
                </tr>
                <tr>
                    <td>Property Size (decimal)</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['property_size']; ?></td>
                </tr>
                <tr>
                    <td>Holding No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['holding_no']; ?></td>
                </tr>
                <tr>
                    <td>Khatiyan No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['khatiyan_no']; ?></td>
                </tr>
                <tr>
                    <td>District</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                </tr>
                <tr>
                    <td>Upazila</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                </tr>
                <tr>
                    <td>Union</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                </tr>
                <tr>
                    <td>Mauza</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
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
                $(this).css("minWidth", "650px").css("maxWidth", "770px");
            }
        });
    });
</script>

<?php } ?>