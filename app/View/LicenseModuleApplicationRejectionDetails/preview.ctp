

<div id="rejHistoryDetails" title="Application Rejection History" style="margin:0px; padding:8px; background-color:#fafdff;">
    
    <div id="tabs">
        <ul>
          <li><a href="#tabs-1">Initial Assessment</a></li>
          <li><a href="#tabs-2">Initial Assessment Review and Verification</a></li>
          <li><a href="#tabs-3">Application Rejection History</a></li>
        </ul>
        
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleInitialAssessmentDetails', 'action'=>'assess_details', $org_id), array('return')); ?>            
        </div>
        <div id="tabs-2">
            <?php echo $this->requestAction(array('controller' => 'LicenseModuleInitialAssessmentReviewVerificationDetails', 'action' => 'verification_details', $org_id), array('return')); ?>
        </div>        
        <div id="tabs-3">
            <?php echo $this->requestAction(array('controller'=>'LicenseModuleApplicationRejectionDetails', 'action'=>'rejection_history_details', $org_id), array('return')); ?>
        </div>
    </div>


</div>

<script>
    $(function () {

        $("#tabs").tabs({active: -1});

        $("#rejHistoryDetails").dialog({
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
