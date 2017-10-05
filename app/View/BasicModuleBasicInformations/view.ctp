<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {

    $isAdmin = (!empty($user_group_ids) && in_array(1, $user_group_ids));    
    $title = 'Primary Information';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    $user_group_ids = $this->Session->read('User.GroupIds');
    ?>

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend> 
            <?php
            if ($isAdmin) {
                echo "<p style='color:#072fa3;font-weight:bold;'>";
                echo 'Total records found : '.$total;
                echo '</p>';
            }
            ?>
            <div class="dtview"> 
                <table>
                    <tr>
                        <td>
                            <?php
                            if ($isAdmin) {
                                echo $this->Form->create('BasicModuleBasicInformation');
                                ?>
                                <table cellpadding="0" cellspacing="0" border="0">                           
                                    <tr>
                                        <td style="padding-left:15px; text-align:right;">Search by</td>
                                        <td>
                                            <?php 
                                            $options = array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                             'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name');
                                            if(!empty($user_group_ids)&&  in_array(2, $user_group_ids)) {
                                                $options['BasicModuleBasicInformation.license_no'] = 'License No.';
                                            }                                                                                                                              
                                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:215px', 'options' => $options));
                                            ?>
                                        </td>
                                        <td style="font-weight:bold;">:</td>
                                        <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                                        <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                                        <td>
                                            <?php
                                            if (!empty($opt_all) && $opt_all && $isAdmin) {
                                                echo $this->Js->link('View All', array('controller' => 'BasicModuleBasicInformations', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                                echo $this->Form->end();
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="data_div" style="width:780px; height:auto; overflow-x:auto;">
                                <?php
                                if (empty($values) || !is_array($values) || count($values) < 1) {
                                    echo '<p class="error-message">No data is available !</p>';
                                } 
                                else { ?>
                                    <table class="view">
                                        <tr>
                                            <?php
                                            if(!empty($user_group_ids)&&  in_array(2, $user_group_ids)) {
                                                echo "<th style='width:95px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                                            }                                            
                                            echo "<th style='min-width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                            echo "<th style='width:80px;'>Status</th>";
                                            echo "<th style='width:132px;'>Action</th>";
                                            ?>
                                        </tr>
                                        <?php foreach ($values as $value) { ?>
                                            <tr>
                                                <?php if(!empty($user_group_ids)&&  in_array(2, $user_group_ids)) { ?>
                                                    <td style="text-align:justify;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                                <?php } ?>
                                                <td style="text-align:justify;">
                                                    <?php
                                                    $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                                    $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                                    if (!empty($mfiName))
                                                        $mfiName = "<strong>" . $mfiName . ":</strong> ";
                                                    if (!empty($mfiFullName))
                                                        $mfiName = $mfiName . $mfiFullName;

                                                    echo $mfiName;
                                                    ?>
                                                </td>                                    
                                                <td style="text-align:justify;">
                                                    <?php 
                                                    $state_id = $value['BasicModuleBasicInformation']['licensing_state_id'];
                                                    if(!empty($state_id)&&$state_id>=30){
                                                        $status = 'Licensed';
                                                    }
                                                    else {
                                                        $status = 'New';
                                                    }
                                                    echo $status; 
                                                    ?>
                                                </td>
                                                <td style="text-align:center; padding:2px; height:30px;">
                                                    <?php
                                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleBasicInformations', 'action' => 'edit', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                                        echo $this->Js->link('Details', array('controller' => 'BasicModuleBasicInformations', 'action' => 'details', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
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
                    if ($this->Paginator->param('pageCount') > 10) {
                        echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                        $this->Paginator->last('>>', array('class' => 'nextPg', 'title' => 'Goto last page.'), null, array('class' => 'nextPg no_link'));
                    } else {
                        echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    //            echo $paginator->last('Last', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
                    ?>
                </div>
            <?php } ?>

            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <?php
                            if (!empty($user_group_ids) && in_array(2, $user_group_ids)) {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();', 'title' => 'Go to next module.')));
                            }
                            elseif (!empty($user_group_ids) && in_array(5, $user_group_ids)) {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleProposedAddresses', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'success' => 'msc.next();', 'title' => 'Go to next module.')));
                            }
                            ?>
                        </td>
                        <td></td>                    
                    </tr>
                </table>
            </div>

        </fieldset>
    </div> 

<?php } ?>
