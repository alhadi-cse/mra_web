<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    ?>

    <div id="basicInfo" title="Audit Information" style="margin:0px; padding:10px; background-color:#fafdff;"> 
        <fieldset>        
            <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
                <table cellpadding="7" cellspacing="8" border="0">              
                    <tr>
                        <td style="min-width:50%;">Name of Organization</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                    </tr>
                    <tr>
                        <td>Has External Audit been Carried Out Previously</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['QuestionOnExternalAudit']['yes_no_status']; ?></td>
                    </tr>
                    <?php if (!empty($allDetails) && $allDetails['QuestionOnExternalAudit']['id'] == '1') { ?>
                        <tr>
                            <td>Date of the Last External Audit</td>                        
                            <td>:</td> 
                            <td><?php echo $allDetails['BasicModuleAuditInformation']['date_of_last_external_audit']; ?></td>
                        </tr>
                        <tr>
                            <td>Name of the Audit Firm</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['BasicModuleAuditInformation']['name_of_the_audit_firm']; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['BasicModuleAuditInformation']['address']; ?></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['BasicModuleAuditInformation']['phone']; ?></td>
                        </tr>
                        <tr>
                            <td>Fax</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['BasicModuleAuditInformation']['fax']; ?></td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['BasicModuleAuditInformation']['email']; ?></td>
                        </tr>
                    <?php } ?>                    
                </table>
            </div>
        </fieldset>
    </div>
<?php } ?>