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
                    <td style="width:25%;">Short Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['BasicModuleBasicInformation']['short_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>Full Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>License No</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['BasicModuleBasicInformation']['license_no']; ?></td>
                </tr>
                <tr>
                    <td>License Issue Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['BasicModuleBasicInformation']['license_issue_date']; ?></td>
                </tr>
                <tr>
                    <td>Cancel Request Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LicenseModuleCancelByMfiCancelRequest']['cancel_request_date']; ?></td>
                </tr>
                <tr>
                    <td>Cancel Request Reason</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LicenseModuleCancelByMfiCancelRequest']['cancel_reasons']; ?></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
<?php } ?>