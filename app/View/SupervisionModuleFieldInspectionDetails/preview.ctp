
<?php
$title = 'Field Inspection/Queries Details';
?>

<div id="fldInsInfo" title="<?php echo $title; ?>" style="margin:0px; padding:8px; background-color:#fafdff;">

    <p style="border-bottom:2px solid #137387; padding:5px 15px;">
        Name of Organization: <?php echo '<strong>' . $orgName . '</strong>'; ?>
    </p>

    <?php
    echo $this->requestAction(array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'inspection_details', $supervision_basic_id, '?' => array('no_approval_details' => 1)), array('return'));
    ?>
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
            create: function (evt, ui) {
                $(this).css("minWidth", "850px").css("maxWidth", "1000px");
            }
        });
    });
</script>
