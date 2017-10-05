<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = 'Proposed Address of Organization';
    $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($values) && !empty($values[0]['BasicModuleBasicInformation'])) {

                $mfiName = $values[0]['BasicModuleBasicInformation']['short_name_of_org'];
                $mfiFullName = $values[0]['BasicModuleBasicInformation']['full_name_of_org'];
                if (!empty($mfiFullName) && !empty($mfiName))
                    $mfiName = $mfiFullName . " (<strong>" . $mfiName . "</strong>)";
                else
                    $mfiName = $mfiName . $mfiFullName;

                echo '<p style="margin:3px;"><strong>Name of Organization : </strong>' . $mfiName . '</p>';
            }
            ?>

            <div class="dtview"> 
                <table>
                    <tr> 
                        <td>
                            <?php echo $this->Form->create('BasicModuleProposedAddress'); ?>
                            <table cellpadding="0" cellspacing="0" border="0">          
                                <tr>                                
                                    <td style="padding-left:15px; text-align:right;">Search by</td>
                                    <td>
                                        <?php
                                        $options = array('LookupBasicProposedAddressType.address_type' => 'Address Type',
                                            'LookupAdminBoundaryDistrict.district_name' => 'District Name',
                                            'LookupAdminBoundaryUpazila.upazila_name' => 'Upazila Name');

                                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:215px', 'options' => $options));
                                        ?>
                                    </td>
                                    <td style="font-weight:bold;">:</td>
                                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                                    <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                                    <td>
                                        <?php
                                        if (!empty($opt_all) && $opt_all) {
                                            echo $this->Js->link('View All', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
                                        }
                                        ?>
                                    </td>               
                                </tr>
                            </table>
                            <?php echo $this->Form->end(); ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width:780px; height:auto; overflow-x:auto;">
                                <?php
                                if (!$values || !is_array($values) || count($values) < 1) {
                                    echo '<p class="error-message">';
                                    echo 'Did not find any data !';
                                    echo '</p>';
                                } else {
                                    ?>

                                    <table class="view">
                                        <tr>                                    
                                            <?php
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupBasicProposedAddressType.address_type', 'Address Type') . "</th>";
                                            echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleProposedAddress.address_of_org', 'Address') . "</th>";
                                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryUpazila.upazila_name', 'Upazila') . "</th>";
                                            echo "<th style='width:85px;'>Action</th>";
                                            ?>
                                        </tr>
                                        <?php foreach ($values as $value) { ?>
                                            <tr>                                                
                                                <td><?php echo $value['LookupBasicProposedAddressType']['address_type']; ?></td>
                                                <td><?php echo $value['BasicModuleProposedAddress']['address_of_org']; ?></td>
                                                <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td> 
                                                <td><?php echo $value['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                                <td style="height:30px; padding:2px; text-align:center;">
                                                    <?php
//                                                    $isEditable = $this->Session->read('Form.IsEditable');
//                                                    if ($isEditable)

                                                    echo $this->Js->link('Edit', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'edit', $value['BasicModuleProposedAddress']['id'], $value['BasicModuleProposedAddress']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                                    echo $this->Js->link('Details', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'preview', $value['BasicModuleProposedAddress']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>

                                <?php } ?>
                            </div>
                        </td> 
                    </tr>
                </table>
            </div>

            <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
                <div class="paginator">
                    <?php
                    echo $this->Paginator->prev('<<', array('class' => 'prevPg'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>>', array('class' => 'nextPg'), null, array('class' => 'nextPg no_link'));
                    ?>
                </div>
            <?php } ?>

            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td><?php echo $this->Js->link('Previous', array('controller' => 'BasicModuleBasicInformations', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();'))); ?></td>
                        <td><?php echo $this->Js->link('Add New', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns'))); ?></td>
                        <td><?php echo $this->Js->link('Preview', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'individual_preview'), array_merge($pageLoading, array('class' => 'mybtns', 'update' => '#popup_div'))); ?></td>
                        <td><?php echo $this->Js->link('Next', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'view?model_id=3'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();'))); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

    </fieldset>
<?php } ?>