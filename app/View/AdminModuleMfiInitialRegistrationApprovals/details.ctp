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
?>

<div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">              
                <tr>
                    <td>Organization's Short Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['AdminModuleMfiRegistrationForNewLicense']['mfi_short_name']; ?></td>
                </tr>
                <tr>
                    <td>Organization's Full Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['AdminModuleMfiRegistrationForNewLicense']['mfi_full_name']; ?></td>
                </tr>
                <tr>
                    <td>District</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                </tr>
                <tr>
                    <td>Primary Registration No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['AdminModuleMfiRegistrationForNewLicense']['primary_registration_no']; ?></td>
                </tr>
                <tr>
                    <td>Primary Registration Authority</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['LookupBasicRegistrationAuthority']['registration_authority']; ?></td>
                </tr>
                <tr>
                    <td>Mobile No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['AdminModuleMfiRegistrationForNewLicense']['mobile_no']; ?></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td class="colons">:</td>
                    <td><?php echo $mfiInitialDetails['AdminModuleMfiRegistrationForNewLicense']['email']; ?></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>

<?php } ?>
