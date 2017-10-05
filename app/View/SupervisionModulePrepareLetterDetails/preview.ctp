<?php
$this_state_ids = $this->Session->read('Current.StateIds');
if (!empty($this_state_ids)) {
    $thisStateIds = split('_', $this_state_ids);

//    if (!empty($allDetails)) {
//        $supervision_case_id = $allDetails['SupervisionModuleBasicInformation']['supervision_case_id'];
//    }
}
?>
<div id="recEvalInfo" title="Detail Information" style="margin:0px; padding:8px; background-color:#fafdff;">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Basic Info</a></li>
            <li><a href="#tabs-2">Inspection Report</a></li>
            <li><a href="#tabs-3">Findings</a></li>
            <?php if (!empty($supervision_case_id)) { ?>
                <li><a href="#tabs-4">Letter Details</a></li>  
            <?php } ?>
            <?php if ($thisStateIds[0] == 709 || $thisStateIds[1] == 710) { ?>
                <li><a href="#tabs-7">Explanation of MFI</a></li>
            <?php } ?>
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
        <?php if (!empty($supervision_case_id)) { ?>
            <div id="tabs-4">
                <?php echo $this->requestAction(array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'details', $supervision_basic_id), array('return')); ?>            
            </div> 
        <?php } ?>        
        <?php if ($thisStateIds[0] == 709 || $thisStateIds[1] == 710) { ?>
            <div id="tabs-7">
                <?php echo $this->requestAction(array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'details', $supervision_basic_id), array('return')); ?>            
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
            create: function (evt, ui) {
                $(this).css("minWidth", "820px").css("maxWidth", "1000px");
            }
        });
    });
</script>