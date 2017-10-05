<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Preview of 'Rejection Status'";
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
                    <td>Has an Application for License Ever been Rejected by MRA</td>                      
                    <td>:</td> 
                    <td><?php echo!empty($allRejectionDetails['QuestionOnRejectionStatus']) ? $allRejectionDetails['QuestionOnRejectionStatus']['yes_no_status'] : ""; ?></td>
                </tr>
                <?php if (!empty($allRejectionDetails) && $allRejectionDetails['id'] == '1') { ?>
                    <tr>
                        <td>Date of Rejection</td>                      
                        <td>:</td> 
                        <td><?php echo $allRejectionDetails['date_of_rejection']; ?></td>
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