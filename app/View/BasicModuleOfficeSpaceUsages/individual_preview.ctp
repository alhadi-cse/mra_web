<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Usage of Office Space";
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
            <table cellpadding="7" cellspacing="8" border="0" style="min-width:850px;">
                <tr>
                    <th style="width:200px;">Attribute</th>
                    <?php
                        foreach ($usage_types as $usage_type) { 
                            $usage_type_title = $usage_type['LookupBasicOfficeUsageType']['usage_type'];
                            echo "<th style='width:150px; text-align:center;'>$usage_type_title</th>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Holding No.</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $holding_no = $officeSpaceUsageDetails['holding_no'];
                                    echo "<td style=text-align:center;'>$holding_no</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Khatiyan No.</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $holding_no = $officeSpaceUsageDetails['khatiyan_no'];
                                    echo "<td style=text-align:center;'>$holding_no</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>District</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $district_name = !empty($officeSpaceUsageDetails['LookupAdminBoundaryDistrict']) ? $officeSpaceUsageDetails['LookupAdminBoundaryDistrict']['district_name']:"";
                                    echo "<td style=text-align:center;'>$district_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Upazila</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $upazila_name = !empty($officeSpaceUsageDetails['LookupAdminBoundaryUpazila']) ? $officeSpaceUsageDetails['LookupAdminBoundaryUpazila']['upazila_name']:"";
                                    echo "<td style=text-align:center;'>$upazila_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Union</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $union_name = !empty($officeSpaceUsageDetails['LookupAdminBoundaryUnion']) ? $officeSpaceUsageDetails['LookupAdminBoundaryUnion']['union_name']:"";
                                    echo "<td style=text-align:center;'>$union_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Mauza</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $mauza_name = !empty($officeSpaceUsageDetails['LookupAdminBoundaryMauza']) ? $officeSpaceUsageDetails['LookupAdminBoundaryMauza']['mauza_name']:"";
                                    echo "<td style=text-align:center;'>$mauza_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Duration of Rent Agreement</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $holding_no = $officeSpaceUsageDetails['duration_of_proposed_rent_agreement'];
                                    echo "<td style=text-align:center;'>$holding_no</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Proposed Monthly Rent</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($usage_types as $usage_type) { 
                            $id = $usage_type['LookupBasicOfficeUsageType']['id'];
                            foreach ($allOfficeSpaceUsageDetails as $officeSpaceUsageDetails) { 
                                $usage_type_id = $officeSpaceUsageDetails['usage_type_id'];
                                if($id==$usage_type_id) {
                                    $matched_ids++;
                                    $holding_no = $officeSpaceUsageDetails['proposed_monthly_rent'];
                                    echo "<td style=text-align:center;'>$holding_no</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($usage_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
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