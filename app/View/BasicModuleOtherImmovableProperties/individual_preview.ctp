<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } 
    else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Other Immovable Property of the organization";
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
            <table cellpadding="7" cellspacing="8" border="0" style="min-width:1250px;">
                <tr>
                    <th style="width:200px;">Property Description</th>
                    <th style="width:100px;">Date of Acquiring</th>  
                    <th style="width:100px;">Monetary Value</th> 
                    <th style="width:100px;">Property Size (decimal)</th> 
                    <th style="width:100px;">Holding No.</th>
                    <th style="width:100px;">Khatiyan No.</th>  
                    <th style="width:100px;">District</th> 
                    <th style="width:100px;">Upazila</th> 
                    <th style="width:100px;">Union</th>
                    <th style="width:100px;">Mauza</th>                            
                </tr>
                <?php
                $rc = 0;
                foreach ($allImmovablePropertyDetails as $immovablePropertyDetails) {
                    $rc++;
                    ?>

                    <tr<?php
                    if ($rc % 2 == 0) {
                        echo ' class="alt"';
                    }
                    ?>>
                        <td><?php echo $immovablePropertyDetails['property_description']; ?></td>
                        <td><?php echo $immovablePropertyDetails['date_of_acquiring']; ?></td>
                        <td><?php echo $immovablePropertyDetails['monetary_value']; ?></td>
                        <td><?php echo $immovablePropertyDetails['property_size']; ?></td>
                        <td><?php echo $immovablePropertyDetails['holding_no']; ?></td>
                        <td><?php echo $immovablePropertyDetails['khatiyan_no']; ?></td>                                
                        <td><?php echo !empty($immovablePropertyDetails['LookupAdminBoundaryDistrict']) ? $immovablePropertyDetails['LookupAdminBoundaryDistrict']['district_name']:""; ?></td>
                        <td><?php echo !empty($immovablePropertyDetails['LookupAdminBoundaryUpazila']) ? $immovablePropertyDetails['LookupAdminBoundaryUpazila']['upazila_name']:""; ?></td>
                        <td><?php echo !empty($immovablePropertyDetails['LookupAdminBoundaryUnion']) ? $immovablePropertyDetails['LookupAdminBoundaryUnion']['union_name']:""; ?></td>
                        <td><?php echo !empty($immovablePropertyDetails['LookupAdminBoundaryMauza']) ? $immovablePropertyDetails['LookupAdminBoundaryMauza']['mauza_name']:""; ?></td>
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