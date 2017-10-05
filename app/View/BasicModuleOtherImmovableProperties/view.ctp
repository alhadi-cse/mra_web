<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {

    $title = 'Other immovable property';
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
                            <?php echo $this->Form->create('BasicModuleOtherImmovableProperty'); ?>
                            <table cellpadding="0" cellspacing="0" border="0">          
                                <tr>                                
                                    <td style="padding-left:15px; text-align:right;">Search by</td>
                                    <td>
                                        <?php
                                        $options = array('LookupAdminBoundaryDistrict.district_name' => 'District Name',
                                            'LookupAdminBoundaryUpazila.upazila_name' => 'Upazila Name');

                                        if (empty($org_id)) {
                                            $options = array_merge(array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name'), $options);
                                        }

                                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:215px', 'options' => $options));
                                        ?>
                                    </td>
                                    <td style="font-weight:bold;">:</td>
                                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                                    <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                                    <td>
                                        <?php
                                        if (!empty($opt_all) && $opt_all) {
                                            echo $this->Js->link('View All', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
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
                                            echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleOtherImmovableProperty.holding_no', 'Holding No.') . "</th>";
                                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryUpazila.upazila_name', 'Upazila') . "</th>";
                                            echo "<th style='width:75px;'>Action</th>";
                                            ?>
                                        </tr>
                                        <?php foreach ($values as $value) { ?>
                                            <tr>                                                
                                                <td><?php echo $value['BasicModuleOtherImmovableProperty']['holding_no']; ?></td>
                                                <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td> 
                                                <td><?php echo $value['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                                <td style="height:30px; padding:2px; text-align:center;">
                                                    <?php
                                                    $isEditable = $this->Session->read('Form.IsEditable');
                                                    if ($isEditable)
                                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'edit', $value['BasicModuleOtherImmovableProperty']['id'], $value['BasicModuleOtherImmovableProperty']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                                    echo $this->Js->link('Details', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'preview', $value['BasicModuleOtherImmovableProperty']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
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
                        <td> <?php echo $this->Js->link('Previous', array('controller' => 'BasicModuleOfficeSpaceUsages', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.prev();')));?></td>
                        <td><?php echo $this->Js->link('Add New', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns'))); ?></td>
                        <td><?php echo $this->Js->link('Preview', array('controller' => 'BasicModuleOtherImmovableProperties', 'action' => 'individual_preview'), array_merge($pageLoading, array('class' => 'mybtns', 'update' => '#popup_div'))); ?></td>
                        <td> <?php echo $this->Js->link('Next', array('controller' => 'AdminModuleDynamicCrudFormGenerators', 'action' => 'view?model_id=15'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();')));?></td> 
                        <td></td>   
                    </tr>
                </table>
            </div>        
        </div>
    </fieldset>
<?php } ?>
