<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = 'Proposed Address Details';
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
                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleProposedAddresses',
                                            'action' => 'edit', $allDataDetails['BasicModuleProposedAddress']['id'], $allDataDetails['BasicModuleProposedAddress']['org_id'], 2), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'margin:0; padding:4px;')));
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Address Type</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['LookupBasicProposedAddressType']['address_type']; ?></td>
                        </tr>                
                        <tr>
                            <td>Address</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['address_of_org']; ?></td>
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
                            <td>Mahalla/Post Office</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['mohalla_or_post_office']; ?></td>
                        </tr>				
                        <tr>
                            <td>Road Name/Village</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['road_name_or_village']; ?></td>
                        </tr>
                        <tr>
                            <td>Phone No.</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['phone_no']; ?></td>
                        </tr>
                        <tr>
                            <td>Mobile No.</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['mobile_no']; ?></td>
                        </tr>
                        <tr>
                            <td>Fax</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['fax']; ?></td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDataDetails['BasicModuleProposedAddress']['email']; ?></td>
                        </tr>                        
                    </table>

                <?php } else if ($data_count == 'all') { ?>

                    <div style="max-width:780px; overflow-x:auto;">
                        <table cellpadding="7" cellspacing="8" border="0" class="view">
                            <tr>
                                <th style="width:170px;">Address Type</th>
                                <th></th>
                                <th style="width:100px; text-align:center;">Holding No.</th>
                                <th style="width:100px; text-align:center;">District</th>
                                <th style="width:100px; text-align:center;">Upazila</th>
                                <th style="width:100px; text-align:center;">Union</th>
                                <th style="width:100px; text-align:center;">Mauza</th>
                                <th style="width:100px; text-align:center;">Mahalla/Post Office</th>
                                <th style="width:100px; text-align:center;">Road Name/Village</th>
                                <th style="width:100px; text-align:center;">Phone No.</th>
                                <th style="width:100px; text-align:center;">Mobile No.</th>
                                <th style="width:100px; text-align:center;">Fax</th>
                                <th style="width:100px; text-align:center;">E-mail</th>
                                <th style="width:100px; text-align:center;">Usage of Office Space(sq.ft)</th>
                                <th style="width:100px; text-align:center;">Duration of Rent Agreement</th>
                                <th style="width:100px; text-align:center;">Proposed Monthly Rent</th>
                                <th style="width:100px; text-align:center;">Time Period(Start)</th>
                                <th style="width:100px; text-align:center;">Time Period(End)</th>
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
                                    <td><b><?php echo $addDetails['LookupBasicProposedAddressType']['address_type']; ?></b></td>
                                    <td class="colons">:</td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['holding_no']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryUnion']['union_name']; ?></td>
                                    <td><?php echo $addDetails['LookupAdminBoundaryMauza']['mauza_name']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['mohalla_or_post_office']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['road_name_or_village']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['phone_no']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['mobile_no']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['fax']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['email']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['usage_of_office_space']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['duration_of_proposed_rent_agreement']; ?></td>
                                    <td><?php echo $addDetails['BasicModuleProposedAddress']['proposed_monthly_rent']; ?></td>
                                    <td><?php echo $this->Time->format($addDetails['BasicModuleProposedAddress']['time_period_start'], '%d-%m-%Y', ''); ?></td>
                                    <td><?php echo $this->Time->format($addDetails['BasicModuleProposedAddress']['time_period_end'], '%d-%m-%Y', ''); ?></td>
                                    <td><?php
                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleProposedAddresses',
                                            'action' => 'edit', $addDetails['BasicModuleProposedAddress']['id'], $addDetails['BasicModuleProposedAddress']['org_id'], 2), array_merge($pageLoading, array('class' => 'btnlink')));
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
                        echo $this->Js->link('Previous', array('controller' => 'BasicModuleBasicInformations', 'action' => 'details'), array_merge($pageLoading, array('success' => 'msc.prev();')));
                        ?>
                    </td>

                    <td>
                        <?php
                        if (empty($data_count) || $data_count === 0) {
                            echo $this->Js->link('Add New', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'add'), $pageLoading);
                        } else {
                            echo $this->Js->link('Close', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'view'), $pageLoading);
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $this->Js->link('Next', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'view?model_id=3'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();')));
                        //echo $this->Js->link('Next', array('controller' => 'BasicModuleBranchInfos', 'action' => 'details'), array_merge($pageLoading, array('success' => 'msc.next();')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>

    </fieldset>
</div>

