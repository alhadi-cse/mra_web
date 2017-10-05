

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
        <legend>Proposed Address Details</legend>
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="min-width:375px"><?php echo $addDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Address Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['LookupBasicProposedAddressType']['address_type']; ?></td>
                </tr>                
                <tr>
                    <td>Address</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['address_of_org']; ?></td>
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
                    <td>Mahalla/Post Office</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['mohalla_or_post_office']; ?></td>
                </tr>				
                <tr>
                    <td>Road Name/Village</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['road_name_or_village']; ?></td>
                </tr>
                <tr>
                    <td>Phone No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['phone_no']; ?></td>
                </tr>
                <tr>
                    <td>Mobile No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['mobile_no']; ?></td>
                </tr>
                <tr>
                    <td>Fax</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['fax']; ?></td>
                </tr>
                <tr>
                    <td>E-mail</td>
                    <td class="colons">:</td>
                    <td><?php echo $addDetails['BasicModuleProposedAddress']['email']; ?></td>
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