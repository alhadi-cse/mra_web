<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Preview of 'Audit Information'";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?>
<div id="basicInfo" title="<?php echo $title; ?>"> 
    <?php if (!empty($mfiDetails) && !empty($org_id)) { ?>
        <style>
            .datagrid {
                width: 850px;
            }
        </style>
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="min-width:850px;">
                <tr>
                    <th style="min-width:365px;">Attribute</th>
                    <th class="colon">:</td> 
                    <th style="min-width:365px;">Values</th>                                                        
                </tr>
                <tr>
                    <td>Has External Audit been Carried out Previously</td>
                    <td>:</td> 
                    <td>
                        <?php echo!empty($allAuditDetails['QuestionOnExternalAudit']) ? $allAuditDetails['QuestionOnExternalAudit']['yes_no_status'] : ""; ?>
                    </td>
                </tr>
                <?php if ($allAuditDetails['QuestionOnExternalAudit']['id'] == '1') { ?>
                    <tr>
                        <td>Date of the Last External Audit</td>                        
                        <td>:</td> 
                        <td><?php echo $allAuditDetails['date_of_last_external_audit']; ?></td>
                    </tr>
                    <tr>
                        <td>Name of the Audit Firm</td>                        
                        <td>:</td> 
                        <td><?php echo $allAuditDetails['name_of_the_audit_firm']; ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>                        
                        <td>:</td> 
                        <td><?php echo $allAuditDetails['address']; ?></td>
                    </tr>                                               
                    <tr>
                        <td>Phone No.</td>                        
                        <td>:</td> 
                        <td><?php echo $allAuditDetails['phone']; ?></td>
                    </tr>                        
                    <tr>
                        <td>Fax</td>                        
                        <td>:</td> 
                        <td><?php echo $allAuditDetails['fax']; ?></td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>                        
                        <td>:</td> 
                        <td><?php echo $allAuditDetails['email']; ?></td>
                    </tr>
                <?php } ?>
                
            </table>
        </div>

    <?php } ?>    
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
            create: function (evt, ui) {
                $(this).css("minWidth", "850px").css("maxWidth", "1000px");
            }
        });
    });
</script>