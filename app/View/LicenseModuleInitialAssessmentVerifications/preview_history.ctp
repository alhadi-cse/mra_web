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

<div id="basicInfo" title="History Details" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">            
            <table cellpadding="7" cellspacing="8" border="0">  
                <tr>
                    <td style="width:25%;">Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['BasicModuleBasicInformation']['full_name_of_org'].'('.$allDetails['BasicModuleBasicInformation']['short_name_of_org'].')'; ?></td>
                </tr>
                <tr>
                    <td style="width:25%;">Name of User</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['AdminModuleUser']['AdminModuleUserProfile']['full_name_of_user'].'('.$allDetails['AdminModuleUser']['user_name'].')'; ?></td>
                </tr>
                <tr>
                    <td>Verification Status</td>
                    <td class="colons">:</td>
                    <td>
                        <?php                      
                            if($allDetails['LicenseModuleInitialAssessmentVerification']['verification_status_id']=='1'){
                                echo 'Verified';
                            }
                            else{
                                echo 'Pending';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Verification Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LicenseModuleInitialAssessmentVerification']['verification_date']; ?></td>
                </tr>                
                <tr>
                    <td>Comments</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LicenseModuleInitialAssessmentVerification']['comments']; ?></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
<script>
    $(function () {
        $("#basicInfo").dialog({
            modal: true, width: 'auto', height: 'auto', 
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