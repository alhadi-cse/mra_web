<div id="recEvalInfo" title="<?php echo $title;?>" style="margin:0px; padding:8px; background-color:#fafdff;">
    <div id="tabs">
        <ul>
          <?php if($status=='both') { ?>  
          <li><a href="#tabs-1">New Address</a></li>
          <li><a href="#tabs-2">Current Address</a></li>
          <?php } elseif($status=='current') {?>
          <li><a href="#tabs-2">Current Address</a></li>
          <?php } ?>
        </ul>
        <?php if($status=='both') { ?>  
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller'=>'AdminModuleMfiAddressChangeApprovals', 'action'=>'new_address_details', $org_id), array('return')); ?>            
        </div>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller'=>'AdminModuleMfiAddressChangeApprovals', 'action'=>'current_address_details', $org_id), array('return')); ?>            
        </div>
        <?php } elseif($status=='current') {?>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller'=>'AdminModuleMfiAddressChangeApprovals', 'action'=>'current_address_details', $org_id), array('return')); ?>            
        </div>
        <?php } ?>        
    </div>
</div>

<script>
    $(function () {        
        $("#tabs").tabs({active: -1});
        $("#recEvalInfo").dialog({
            modal: true, width: 'auto', height: 'auto', resizable: false, dialogClass: 'my-dialog-box',
            buttons: {
                Close: function () {
                    $(this).dialog("close");
                }
            },
            create: function(evt, ui) {
                $(this).css("minWidth", "650px").css("maxWidth", "1000px");
            }
        });
    });
</script>