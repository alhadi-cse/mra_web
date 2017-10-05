<?php                
    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        if(!empty($current_address_details)) {
?>
<div id="basicInfo" title="Details" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">                
                <tr>
                    <td>Organization's Full Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Address Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['LookupBasicAddressType']['address_type']; ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['address_of_org']; ?></td>
                </tr> 
                <tr>
                    <td>District</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                </tr>
                <tr>
                    <td>Upazila</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                </tr>
                <tr>
                    <td>Union</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['LookupAdminBoundaryUnion']['union_name']; ?></td>
                </tr>
                <tr>
                    <td>Mauza</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                </tr>
                <tr>
                    <td>Mahalla/Post Office</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['mohalla_or_post_office']; ?></td>
                </tr> 
                <tr>
                    <td>Road Name/Village</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['road_name_or_village']; ?></td>
                </tr>
                <tr>
                    <td>Phone No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['phone_no']; ?></td>
                </tr>                
                <tr>
                    <td>Mobile No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['mobile_no']; ?></td>
                </tr>
                <tr>
                    <td>Fax No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['fax']; ?></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td class="colons">:</td>
                    <td><?php echo $current_address_details['BasicModuleBranchInfo']['email']; ?></td>
                </tr>             
            </table>
        </div>
    </fieldset>
</div>
<?php 
    } 
    else {
        echo '<p class="error-message">';
        echo 'No data is available!';
        echo '</P>';
    }
} 
?>