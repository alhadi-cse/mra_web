
<div id="rejHistoryDetails" title="Application Rejection History" style="margin:0px; padding:8px; background-color:#fafdff;">
   <?php echo $this->requestAction(array('action'=>'rejection_history_details', $org_id), array('return')); ?>
</div>

<script>
    $(function () {
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
