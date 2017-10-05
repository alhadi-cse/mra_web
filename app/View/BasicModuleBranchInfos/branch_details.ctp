<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    ?>
    <div class="datagrid" title="Office Information">
        <table cellpadding="5" cellspacing="5" border="0">
            <tr>
                <td colspan="3">
                    <?php
                    if (!empty($branchDetails['BasicModuleBranchInfo']['image_name'])) {
                        $img_url = $branchDetails['BasicModuleBranchInfo']['image_name'];
                        $rand_number = rand(1000, 99999);
                        echo $img = $this->Html->image('/files/uploads/branches/' . $img_url . "?$rand_number", array('plugin' => false, 'alt' => 'no image', 'style' => 'width:375px; height:250px; text-align:center;display: block;margin: auto;'));
                    } else {
                        echo "<p style='padding:2px 20px; font-weight: bold;'>Image not available</p>";
                    }
                    ?>
                </td>
            </tr>
            <tr class="alt">
                <td>Name of Organization</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
            </tr>
            <tr>
                <td>Office Type</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['LookupBasicOfficeType']['office_type']; ?></td>
            </tr>
            <tr>
                <td>Office Code</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['branch_code']; ?></td>
            </tr>
            <?php if (!empty($branchDetails['BasicModuleBranchInfo']['office_type_id']) && ($branchDetails['BasicModuleBranchInfo']['office_type_id'] > 1)) { ?>
                <tr class="alt">
                    <td>Branch Name</td>
                    <td class="colons" >:</td>
                    <td><?php echo $branchDetails['BasicModuleBranchInfo']['branch_name']; ?></td>
                </tr>        
            <?php } ?>
            <tr>
                <td>Mailing Address</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['mailing_address']; ?></td>
            </tr>
            <tr class="alt">
                <td>District</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
            </tr>
            <tr>
                <td>Upazila</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
            </tr>
            <tr class="alt">
                <td>Union</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
            </tr>
            <tr>
                <td>Mauza</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
            </tr> 
            <tr class="alt">
                <td>Mahalla/Post Office</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['mohalla_or_post_office']; ?></td>
            </tr> 
            <tr>
                <td>Road Name/Village</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['road_name_or_village']; ?></td>
            </tr>
            <tr class="alt">
                <td>E-mail</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['email_address']; ?></td>
            </tr>
            <tr>
                <td>Phone no.</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['phone_no']; ?></td>
            </tr>
            <tr class="alt">
                <td>Mobile no.</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['mobile_no']; ?></td>
            </tr>
            <tr>
                <td>Fax</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['fax']; ?></td>
            </tr>
            <tr class="alt">
                <td>Latitude</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['lat']; ?></td>
            </tr>				
            <tr>
                <td>Longitude</td>
                <td class="colons" >:</td>
                <td><?php echo $branchDetails['BasicModuleBranchInfo']['long']; ?></td>
            </tr>        
            <?php
            if ($branchDetails['BasicModuleBranchInfo']['is_active'] == '1') {
                $activation_status = 'Activated';
            } elseif ($branchDetails['BasicModuleBranchInfo']['is_active'] == '0') {
                $activation_status = 'Deactivated';
            }
            if (!empty($branchDetails['BasicModuleBranchInfo']['is_active'])) {
                ?>
                <tr class="alt">
                    <td>Status</td>
                    <td class="colons" >:</td>
                    <td>
                        <?php
                        echo $activation_status;
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table> 
    </div>

<?php } ?>