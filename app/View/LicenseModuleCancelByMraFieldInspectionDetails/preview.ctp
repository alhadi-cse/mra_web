
<div id="fldInsInfo" title="Field Inspection Information" style="margin:0px; padding:8px; background-color:#fafdff;">
    
    <div id="tabs">
        <ul>
          <li><a href="#tabs-1">Initial Assessment</a></li>
          <li><a href="#tabs-2">Initial Assessment Review and Verification</a></li>
          <li><a href="#tabs-3">Initial Assessment Administrative Approval</a></li>
          <li><a href="#tabs-4">Initial Field Inspection</a></li>
          <li><a href="#tabs-5">Field Inspection</a></li>
        </ul>
        
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleInitialAssessmentDetails', 'action'=>'assess_details', $org_id), array('return')); ?>            
        </div>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleInitialAssessmentReviewVerificationDetails', 'action'=>'approve_details', $org_id), array('return')); ?>
        </div>        
        <div id="tabs-3">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleInitialAssessmentAdminApprovalDetails', 'action'=>'approve_details', $org_id), array('return')); ?>
        </div>
        <div id="tabs-4">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleFieldInspectionDetails', 'action'=>'inspection_details', $org_id, 1), array('return')); ?>    
        </div>
        <div id="tabs-5">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleCancelByMraFieldInspectionDetails', 'action'=>'inspection_details', $org_id), array('return')); ?>    
        </div>
    </div>
    
    
</div>

<script>
    $(function () {
        
        $("#tabs").tabs({active: -1});
        
        $("#fldInsInfo").dialog({
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
