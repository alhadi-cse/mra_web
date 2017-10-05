<div id="recEvalInfo" title="Note Details" style="margin:0px; padding:8px; background-color:#fafdff;">
    <div id="tabs">        
        <div id="tabs-1">
            <?php
                
            ?>
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
                $(this).css("minWidth", "850px").css("maxWidth", "1000px");
            }
        });
    });
</script>
