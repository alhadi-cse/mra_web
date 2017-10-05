
<div id="recEvalInfo" title="Revoke Show cause or Cancel License Information" style="margin:0px; padding:8px; background-color:#fafdff;">
    
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Show cause Details</a></li>
            <li><a href="#tabs-2">Explanation Details</a></li>
            <li><a href="#tabs-3">Explanation Verification</a></li>
            <?php if($pending_status==1){ ?>
            <li><a href="#tabs-4">Cancel or Revoke Show cause</a></li>
            <?php } ?>
            
        </ul>
        
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMraShowCauseDetails', 'action' => 'details', $org_id), array('return')); ?>            
        </div>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMraMfiExplanationDetails', 'action' => 'details', $org_id), array('return')); ?>            
        </div>
        <div id="tabs-3">
            <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMraExplanationVerificationDetails', 'action' => 'verification_details', $org_id), array('return')); ?>
        </div>
        <?php if($pending_status==1){ ?>
        <div id="tabs-4">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetails', 'action'=>'approve_details', $org_id), array('return')); ?>    
        </div>
        <?php } ?>
                
    </div>
    
</div>

<script>
    $(function () {        
        $("#tabs").tabs({active: -1});
        //$("#tabs").tabs({event: "mouseover"});
        
        $("#recEvalInfo").dialog({
            modal: true, width: 'auto', height: 'auto', resizable: false, dialogClass: 'my-dialog-box', 
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
