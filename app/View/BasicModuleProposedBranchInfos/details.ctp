<?php 

    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    
    $title = 'Branch Details';
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
    
?>

<div>    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            
        <?php if(!empty($data_count)) { ?>
            <?php if($data_count==1) { ?>
            <table cellpadding="7" cellspacing="8" border="0" style="width:100%;">
                <tr>
                    <td style="min-width:170px">Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="min-width:375px">
                        <span style="float:left; max-width:87%; margin:3px 0;">
                            <?php echo $allDataDetails['BasicModuleBasicInformation']['full_name_of_org']; ?>
                        </span>
                        <span style="float:right;">
                            <?php 
                                $isEditable = $this->Session->read('Form.IsEditable');                                            
                                if($isEditable)
                                    echo $this->Js->link('Edit', array('controller'=>'BasicModuleProposedBranchInfos', 
                                                       'action'=>'edit', $allDataDetails['BasicModuleProposedBranchInfo']['id'], $allDataDetails['BasicModuleProposedBranchInfo']['org_id'], 2), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'margin:0; padding:4px;')));
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Branch Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleProposedBranchInfo']['branch_name']; ?></td>
                </tr>
                <tr>
                    <td>District</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
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
                    <td>Latitude</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleProposedBranchInfo']['lat']; ?></td>
                </tr>				
                <tr>
                    <td>Longitude</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleProposedBranchInfo']['long']; ?></td>
                </tr>
            </table> 
            
            <?php } else if($data_count=='all') { ?>
            
            <div style="max-width:780px; overflow-x:auto;">
                <table cellpadding="7" cellspacing="8" border="0" class="view">
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
                        $rc=0;
                        foreach($allDataDetails as $branchDetails){ 
                        $rc++;
                    ?>

                    <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                        <!--<td class="colons">:</td>-->
                        <td><b><?php echo $branchDetails['BasicModuleProposedBranchInfo']['branch_name']; ?></b></td>
                        <td><?php echo $branchDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                        <td><?php echo $branchDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                        <td><?php echo $branchDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                        <td><?php echo $branchDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                        <td><?php echo $branchDetails['BasicModuleProposedBranchInfo']['Lat']; ?></td>
                        <td><?php echo $branchDetails['BasicModuleProposedBranchInfo']['Long']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
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

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php 
                            if(empty($data_count) || $data_count === 0) {
                                echo $this->Js->link('Add New', array('controller'=>'BasicModuleProposedBranchInfos', 'action'=>'add'), $pageLoading);
                            }
                            else {
                                echo $this->Js->link('Close', array('controller'=>'BasicModuleProposedBranchInfos','action'=>'view'), $pageLoading);
                            }
                        ?>
                    </td>
                    <td></td>
                    <td></td>   
                </tr>
            </table>
        </div>

    </fieldset>
</div>

