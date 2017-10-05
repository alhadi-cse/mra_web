<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$title = "Preview of Proposed Address";
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
                        foreach ($proposed_address_types as $address_type) { 
                            $address_type_title = $address_type['LookupBasicProposedAddressType']['address_type'];
                            echo "<th style='width:150px; text-align:center;'>$address_type_title</th>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Address</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];                                    
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];                                        
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $address_of_org = $proposedAddressDetails['address_of_org'];
                                    echo "<td style=text-align:justify;'>$address_of_org</td>";
                                }
                            }                                    
                        }                                
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }                                
                    ?>
                </tr>                        
                <tr>                            
                    <td>District</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $district_name = !empty($proposedAddressDetails['LookupAdminBoundaryDistrict']) ? $proposedAddressDetails['LookupAdminBoundaryDistrict']['district_name']:"";
                                    echo "<td style=text-align:justify;'>$district_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }                                
                    ?>
                </tr>
                <tr>                            
                    <td>Upazila</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $upazila_name = !empty($proposedAddressDetails['LookupAdminBoundaryUpazila']) ? $proposedAddressDetails['LookupAdminBoundaryUpazila']['upazila_name']:"";
                                    echo "<td style=text-align:justify;'>$upazila_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }                                 
                    ?>
                </tr>
                <tr>                            
                    <td>Union</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $union_name = !empty($proposedAddressDetails['LookupAdminBoundaryUnion']) ? $proposedAddressDetails['LookupAdminBoundaryUnion']['union_name']:"";
                                    echo "<td style=text-align:justify;'>$union_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        } 
                    ?>
                </tr>
                <tr>                            
                    <td>Mauza</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $mauza_name = !empty($proposedAddressDetails['LookupAdminBoundaryMauza']) ? $proposedAddressDetails['LookupAdminBoundaryMauza']['mauza_name']:"";
                                    echo "<td style=text-align:justify;'>$mauza_name</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }                                 
                    ?>
                </tr> 
                <tr>                            
                    <td>Mahalla/Post Office</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $mohalla_or_post_office = $proposedAddressDetails['mohalla_or_post_office'];
                                    echo "<td style=text-align:justify;'>$mohalla_or_post_office</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        } 
                    ?>
                </tr>
                <tr>                            
                    <td>Road Name/Village</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $road_name_or_village = $proposedAddressDetails['road_name_or_village'];
                                    echo "<td style=text-align:justify;'>$road_name_or_village</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        } 
                    ?>
                </tr>
                <tr>                            
                    <td>Phone No.</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $phone_no = $proposedAddressDetails['phone_no'];
                                    echo "<td style=text-align:justify;'>$phone_no</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:justify;'></td>";
                        }
                    ?>
                </tr> 
                <tr>                            
                    <td>Mobile No.</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $mobile_no = $proposedAddressDetails['mobile_no'];
                                    echo "<td style=text-align:justify;'>$mobile_no</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>Fax</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $fax = $proposedAddressDetails['fax'];
                                    echo "<td style=text-align:justify;'>$fax</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
                        for($i=0;$i<$pending_ids;$i++) {
                            echo "<td style=text-align:center;'></td>";
                        }
                    ?>
                </tr>
                <tr>                            
                    <td>E-Mail</td>
                    <?php
                        $matched_ids = 0;
                        foreach ($proposed_address_types as $address_type) { 
                            $id = $address_type['LookupBasicProposedAddressType']['id'];
                            foreach ($allProposedAddressDetails as $proposedAddressDetails) { 
                                $address_type_id = $proposedAddressDetails['address_type_id'];
                                if($id==$address_type_id) {
                                    $matched_ids++;
                                    $email = $proposedAddressDetails['email'];
                                    echo "<td style=text-align:justify;'>$email</td>";
                                }
                            }                          
                        }
                        $pending_ids = count($proposed_address_types)-$matched_ids;
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