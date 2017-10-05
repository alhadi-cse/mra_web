
<div id="addDetailsInfo" title="Other immovable property Details" style="margin:0px; padding:8px; background-color:#fafdff;">
    
    <?php if(!empty($data_count)) { ?>
            <?php if($data_count==1) { ?>
            <table cellpadding="7" cellspacing="8" border="0" style="width:100%;">
                <tr>
                    <td style="width:20%;">District</td>
                    <td class="colons">:</td>
                    <td style="width:78%;"><?php echo $allDataDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                </tr>
                <tr>
                    <td>Upazila</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                </tr>
                <tr>
                    <td>Union</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                </tr>
                <tr>
                    <td>Mauza</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                </tr>
                <tr>
                    <td>Mahalla/Post Office</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOfficeSpaceUsage']['mohalla_or_post_office']; ?></td>
                </tr>
                <tr>
                    <td>Road Name/Village</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleOfficeSpaceUsage']['road_name_or_village']; ?></td>
                </tr>                
            </table>
            <?php } else if($data_count=='all') { ?>
            
            <div id="tabs">
                <ul>
                <?php 
                    $rc=0;
                    foreach($allDataDetails as $addDetails) {
                        $rc++;
                        echo '<li><a href="#tabs-'.$rc.'">'.$addDetails['LookupBasicOfficeUsageType']['usage_type'].'</a></li>';
                    }
                ?>
                </ul>

                <?php 
                    $rc=0;
                    foreach($allDataDetails as $addDetails) {
                        $rc++;
                        echo '<div id="tabs-'.$rc.'">';
                ?>

                    <table cellpadding="7" cellspacing="8" border="0" style="width:100%;">
                        <tr>
                            <td style="width:20%;">District</td>
                            <td class="colons">:</td>
                            <td style="width:78%;"><?php echo $addDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Upazila</td>
                            <td class="colons">:</td>
                            <td><?php echo $addDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Union</td>
                            <td class="colons">:</td>
                            <td><?php echo $addDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Mauza</td>
                            <td class="colons">:</td>
                            <td><?php echo $addDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Mahalla/Post Office</td>
                            <td class="colons">:</td>
                            <td><?php echo $addDetails['BasicModuleOfficeSpaceUsage']['mohalla_or_post_office']; ?></td>
                        </tr>
                        <tr>
                            <td>Road Name/Village</td>
                            <td class="colons">:</td>
                            <td><?php echo $addDetails['BasicModuleOfficeSpaceUsage']['road_name_or_village']; ?></td>
                        </tr>                        
                    </table>
                <?php 
                    echo '</div>';
                    }
                ?>

                <script>
                    $(function () { $("#tabs").tabs(); });
                </script>

            </div>

            <?php } ?>
        <?php        
            }
        else {
            echo '<p class="error-message">';
            echo 'Did not find any data !';
            echo '</p>';
        }
    ?>
    
    
</div>

<script>
    $(function () {
        $("#addDetailsInfo").dialog({
            modal: true, width: 'auto', height: 'auto', resizable: false, dialogClass: 'my-dialog-box', 
            buttons: {
                Close: function () {
                    $(this).dialog("close");
                }
            },
            create: function(evt, ui) {
                $(this).css("minWidth", "730px").css("maxWidth", "870px");
            }
        });
    });
</script>


