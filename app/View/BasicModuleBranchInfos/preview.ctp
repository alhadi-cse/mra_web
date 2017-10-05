<div id="recEvalInfo" title="Branch Information" style="margin:0px; padding:8px; height:300px; background-color:#fafdff;">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Details</a></li>           
        </ul>
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller' => 'BasicModuleBranchInfos', 'action' => 'branch_details', $branch_id), array('return')); ?>            
        </div>       
    </div>        
</div>

<script>
    $(function () {
        $("#tabs").tabs({active: -1});

        $("#recEvalInfo").dialog({
            modal: true, width: 'auto', height: "auto", resizable: false, dialogClass: 'my-dialog-box',
            buttons: {
                Close: function () {
                    $(this).dialog("close");
                }
            },
            create: function (evt, ui) {
                $(this).css("minWidth", "650px").css("maxWidth", "700px");
            }
        });
    });
</script>