
<div id="recEvalInfo" title="Recommendation of Evaluation Committee" style="margin:0px; padding:8px; background-color:#fafdff;">
    
    <div id="tabs">
        <ul>
          <li><a href="#tabs-1">Initial Evaluation</a></li>
          <li><a href="#tabs-2">Field Inspection</a></li>
          <li><a href="#tabs-3">Recommendation Of Evaluation Committee</a></li>
          <li><a href="#tabs-4">Final Approval</a></li>
        </ul>
        
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleEvaluationDetailInfos', 'action'=>'evaluate_details', $org_id), array('return')); ?>            
        </div>
        
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleFieldInspectionDetailInfos', 'action'=>'inspection_details', $org_id, 2), array('return')); ?>    
        </div>
        
        <div id="tabs-3">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleRecommendationOfEvaluationCommittees', 'action'=>'recommend_details', $org_id), array('return')); ?>
        </div>
        
        <div id="tabs-4">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleAdministrativeApprovals', 'action'=>'approve_details', $org_id), array('return')); ?>
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
