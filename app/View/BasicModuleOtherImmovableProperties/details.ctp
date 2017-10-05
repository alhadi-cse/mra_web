<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = 'Other immovable property Details';
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
?> 

<div>    
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">
            <?php if (!empty($data_count)) { ?>
                <?php if ($data_count == 1) { ?>
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
                                    if ($isEditable) {
                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleOtherImmovablePropertys',
                                            'action' => 'edit', $allDataDetails['BasicModuleOtherImmovableProperty']['id'], $allDataDetails['BasicModuleOtherImmovableProperty']['org_id'], 2), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'margin:0; padding:4px;')));
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>                                       
                        <tr>
                            <td>Property Description</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['property_description']; ?></td>
                        </tr>
                        <tr>
                            <td>Date of Acquiring</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['date_of_acquiring']; ?></td>
                        </tr>
                        <tr>
                            <td>Monetary Value</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['LookupAdminBoundaryDistrict']['monetary_value']; ?></td>
                        </tr>
                        <tr>
                            <td>Property Size (decimal)</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['LookupAdminBoundaryUpazila']['property_size']; ?></td>
                        </tr>
                        <tr>
                            <td>Holding No.</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['LookupAdminBoundaryUnion']['holding_no']; ?></td>
                        </tr>
                        <tr>
                            <td>Khatiyan No.</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['LookupAdminBoundaryMauza']['khatiyan_no']; ?></td>
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
                            <td>Duration of Rent Agreement</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['duration_of_proposed_rent_agreement']; ?></td>
                        </tr>
                        <tr>
                            <td>Proposed Monthly Rent</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleOtherImmovableProperty']['proposed_monthly_rent']; ?></td>
                        </tr>                        
                    </table>

                <?php } else if ($data_count == 'all') { ?>

                    <div style="max-width:780px; overflow-x:auto;">
                        <table cellpadding="7" cellspacing="8" border="0" class="view">
                            <tr>
                                <th style="width:170px;">Usage Type</th>
                                <th></th>
                                <th style="width:100px; text-align:center;">Holding No.</th>
                                <th style="width:100px; text-align:center;">District</th>
                                <th style="width:100px; text-align:center;">Upazila</th>
                                <th style="width:100px; text-align:center;">Union</th>
                                <th style="width:100px; text-align:center;">Mauza</th>                                
                                <th style="width:100px; text-align:center;">Usage of Office Space(sq.ft)</th>
                                <th style="width:100px; text-align:center;">Duration of Rent Agreement</th>
                                <th style="width:100px; text-align:center;">Proposed Monthly Rent</th>                                
                                <th style="width:100px; text-align:center;">Action</th>
                            </tr>

                            <?php
                            $rc = 0;
                            foreach ($allDataDetails as $addDetails) {
                                $rc++;
                                ?>

                                <tr<?php
                                if ($rc % 2 == 0) {
                                    echo ' class="alt"';
                                }
                                ?>>
                                    <td><b><?php echo $addDetails['LookupBasicOfficeUsageType']['usage_type']; ?></b></td>
                                    <td class="colons">:</td>
                                    <td><?php echo $addDetails['BasicModuleOtherImmovableProperty']['holding_no']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>                                    
                                    <td><?php echo $addDetails['BasicModuleOtherImmovableProperty']['duration_of_proposed_rent_agreement']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleOtherImmovableProperty']['proposed_monthly_rent']; ?></td>
                                    <td><?php
                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleOtherImmovablePropertys',
                                            'action' => 'edit', $addDetails['BasicModuleOtherImmovableProperty']['id'], $addDetails['BasicModuleOtherImmovableProperty']['org_id'], 2), array_merge($pageLoading, array('class' => 'btnlink')));
                                        ?>
                                    </td>
                                </tr>
                    <?php } ?>
                        </table>
                    </div>
                <?php } ?>
                <?php
            } else {
                echo '<p class="error-message">Did not find any data !</p>';
            }
            ?>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Js->link('Previous', array('controller' => 'BasicModuleMCActivitiesPlans', 'action' => 'details'), array_merge($pageLoading, array('success' => 'msc.prev();')));
                        ?>
                    </td>

                    <td>
                        <?php
                        if (empty($data_count) || $data_count === 0) {
                            echo $this->Js->link('Add New', array('controller' => 'BasicModuleOtherImmovablePropertys', 'action' => 'add'), $pageLoading);
                        } else {
                            echo $this->Js->link('Close', array('controller' => 'BasicModuleOtherImmovablePropertys', 'action' => 'view'), $pageLoading);
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $this->Js->link('Next', array('controller' => 'BasicModuleNonCurrentAssets', 'action' => 'details'), array_merge($pageLoading, array('success' => 'msc.next();')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>

    </fieldset>
</div>

