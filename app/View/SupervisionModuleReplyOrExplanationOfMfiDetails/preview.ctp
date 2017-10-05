
<div id="recEvalInfo" title="Detail Information" style="margin:0px; padding:8px; background-color:#fafdff;">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Basic Info</a></li>
            <li><a href="#tabs-2">Inspection Report</a></li>
            <li><a href="#tabs-3">Findings</a></li>
            <li><a href="#tabs-4">Letter Details</a></li>
            <li><a href="#tabs-5">Explanation of MFI</a></li>
        </ul>
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller' => 'SupervisionModuleOrgSelectionDetails', 'action' => 'details', $supervision_case_id), array('return')); ?>            
        </div>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'inspection_details', $supervision_basic_id), array('return')); ?>
        </div>
        <div id="tabs-3">
            <?php echo $this->requestAction(array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'findings_details', $supervision_basic_id), array('return')); ?>            
        </div>
        <div id="tabs-4">
            <?php echo $this->requestAction(array('controller' => 'SupervisionModuleIssueLetterToMfiDetails', 'action' => 'details', $supervision_basic_id), array('return')); ?>            
        </div>
        <div id="tabs-5">
            <?php echo $this->requestAction(array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'details', $supervision_basic_id), array('return')); ?>            
        </div>
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
            create: function (evt, ui) {
                $(this).css("minWidth", "820px").css("maxWidth", "1000px");
            }
        });
    });
</script>
