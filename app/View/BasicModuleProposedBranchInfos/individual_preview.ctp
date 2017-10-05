<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Preview of Proposed Branches";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?>
<div id="basicInfo" title="<?php echo $title;?>"> 
    <?php
    if (!empty($mfiDetails) && !empty($org_id)) { ?>
        <style>
            .datagrid {
                width: 850px;
            }
        </style>
        <div class="datagrid">
            <table cellpadding="7" cellspacing="8" border="0" style="min-width:1200px;">
                <tr>                           
                    <th style="width:170px;">Branch Name</th>
                    <th style="width:150px;">District</th>
                    <th style="width:150px;">Upazila</th>
                    <th style="width:150px;">Union</th>
                    <th style="width:150px;">Mauza</th>
                    <th style="width:150px;">Latitude</th>
                    <th style="width:150px;">Longitude</th>
                </tr>

                <?php
                $rc = 0;
                foreach ($allProposedBranchDetails as $branchDetails) {
                    $rc++;
                    ?>

                    <tr<?php
                    if ($rc % 2 == 0) {
                        echo ' class="alt"';
                    }
                    ?>>
                        <td><b><?php echo $branchDetails['branch_name']; ?></b></td>
                        <td><?php echo !empty($branchDetails['LookupAdminBoundaryDistrict']) ? $branchDetails['LookupAdminBoundaryDistrict']['district_name']:""; ?></td>
                        <td><?php echo !empty($branchDetails['LookupAdminBoundaryUpazila']) ?  $branchDetails['LookupAdminBoundaryUpazila']['upazila_name']:""; ?></td>
                        <td><?php echo !empty($branchDetails['LookupAdminBoundaryUnion']) ?  $branchDetails['LookupAdminBoundaryUnion']['union_name']:""; ?></td>
                        <td><?php echo !empty($branchDetails['LookupAdminBoundaryMauza']) ?  $branchDetails['LookupAdminBoundaryMauza']['mauza_name']:""; ?></td>
                        <td><?php echo $branchDetails['Lat']; ?></td>
                        <td><?php echo $branchDetails['Long']; ?></td>
                    </tr>
                <?php } ?>
            </table> 
        </div>
        <?php } ?>    
</div>
<script>
    $(function () {
        $("#basicInfo").dialog({
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