<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    ?>

    <div id="basicInfo" title="Rejection Status" style="margin:0px; padding:10px; background-color:#fafdff;"> 
        <fieldset>        
            <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
                <table cellpadding="7" cellspacing="8" border="0">              
                    <tr>
                        <td style="min-width:50%;">Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>                    
                    <tr>
                        <td>Has an Application for License Ever been Rejected by MRA</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['QuestionOnRejectionStatus']['yes_no_status']; ?></td>
                    </tr>
                    <?php if (!empty($allDetails) && $allDetails['QuestionOnRejectionStatus']['id'] == '1') { ?>
                        <tr>
                            <td>Date of Rejection</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['BasicModuleRejectionInformation']['date_of_rejection']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </fieldset>
    </div>
<?php } ?>