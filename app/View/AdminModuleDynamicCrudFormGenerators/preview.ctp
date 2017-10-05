<div id="recEvalInfo" title="<?php echo $title = $this->Session->read('LookupModelDefinition.ModelDescription'); ?>" style="margin:0px; padding:8px; background-color:#fafdff;">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Details</a></li>                
        </ul>
        <div id="tabs-1">
            <?php echo $this->requestAction(array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'details', $model_id, $unique_data_id), array('return')); ?>            
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
                $(this).css("minWidth", "970px").css("maxWidth", "1100px");
            }
        });
    });
</script>
