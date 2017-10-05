<div id="recEvalInfo" title="Detail Information" style="margin:0px; padding:8px; background-color:#fafdff;">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Show cause Details</a></li>
            <li><a href="#tabs-2">Explanation</a></li>
            <li><a href="#tabs-3">Field Inspection Before Verification</a></li> 
            <li><a href="#tabs-4">Explanation Verification</a></li>    
            <li><a href="#tabs-5">Field Inspection After Verification</a></li>
            <li><a href="#tabs-6">Cancel or Revoke Show cause</a></li>            
            <li><a href="#tabs-7">Notification Details</a></li>                
        </ul>
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMraShowCauseDetails', 'action' => 'details', $org_id), array('return')); ?>            
        </div>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller' => 'LicenseModuleCancelByMraMfiExplanationDetails', 'action' => 'details', $org_id), array('return')); ?>
        </div>     
        <div id="tabs-3">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleFieldInspectionDetails', 'action'=>'inspection_details', $org_id), array('return')); ?>
        </div>
        <div id="tabs-4">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleCancelByMraExplanationVerificationDetails', 'action'=>'verification_details', $org_id), array('return')); ?>
        </div>
        <div id="tabs-5">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleFieldInspectionDetails', 'action'=>'inspection_details', $org_id), array('return')); ?>
        </div>
        <div id="tabs-6">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleCancelByMraCancelOrRevokeShowCauseDetails', 'action'=>'approve_details', $org_id), array('return')); ?>    
        </div>  
        <div id="tabs-7">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleCancelByMraShowcauseCancelNotifyDetails', 'action'=>'details', $org_id), array('return')); ?>            
        </div>            
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