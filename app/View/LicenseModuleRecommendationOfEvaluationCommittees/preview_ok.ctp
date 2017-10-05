

<div id="recEvalInfo" title="Recommendation of Evaluation Committee" style="margin:0px; padding:8px; background-color:#fafdff;">
    
    <?php echo $this->requestAction(array('controller'=>'LicenseModuleEvaluationDetailInfos', 'action'=>'evaluate_details', $org_id), array('return')); ?>
    <br/>
    <?php echo $this->requestAction(array('controller'=>'LicenseModuleFieldInspectionDetailInfos', 'action'=>'inspection_details', $org_id), array('return')); ?>
    <br/>
    <?php echo $this->requestAction(array('controller'=>'LicenseModuleRecommendationOfEvaluationCommittees', 'action'=>'recommend_details', $org_id), array('return')); ?>

</div>

<script>
    $(function () {
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
    

