<?php
    if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
        
        $isAdmin =(!empty($user_group_id) && in_array(1,$user_group_id));
        
        $title = "Hearing Notification";
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <div class="form">
            
            <?php if(empty($org_id)) { 
                echo $this->Form->create('LicenseModuleSuspensionHearingNotificationDetailCompleted'); ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php 
                                echo $this->Form->input('search_option_completed', 
                                        array('label' => false, 'style'=>'width:200px',
                                            'options' => 
                                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                    'BasicModuleBasicInformation.license_no'=>'License No.')
                                                ));
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('search_keyword_completed',array('label' => false,'style'=>'width:250px')); ?></td>
                        <td style="text-align:left;">
                           <?php
                               echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                            ?>
                       </td>                                
                    </tr>
                </table>
            <?php echo $this->Form->end(); 
            } ?> 
            <fieldset>
                <legend>Notification Sent</legend>
                
                <?php 
                    if($completed_values == null || !is_array($completed_values) || count($completed_values)<1) {
                        echo '<p class="error-message">';
                        echo 'No data is available!';
                        echo '</p>';
                    }
                    else {
                ?>
                        <?php if (!empty($completed_values) && is_array($completed_values) && count($completed_values) > 0) { ?>

                            <table class="view">
                                <tr>
                                    <?php
                                        echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                        echo "<th style='min-width:120px;'>Status</th>";
                                        echo "<th style='width:50px;'>Action</th>";
                                    ?>
                                </tr>
                                <?php foreach($completed_values as $value) { ?>                
                                <tr>
                                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>  
                                    <td>
                                        <?php
                                            $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                            $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                            if (!empty($orgName))
                                                $orgName = "<strong>".$orgName.":</strong> ";
                                            if (!empty($orgFullName))
                                                $orgName = $orgName.$orgFullName;
                                            echo $orgName;
                                        ?>
                                    </td>
                                    <td style='padding:2px; text-align:justify;'>
                                        <?php
                                            $licensing_state_id =  $value['BasicModuleBasicInformation']['licensing_state_id'];
                                            if($licensing_state_id==$thisStateIds[1]){
                                                echo "<p style='background-color:#037108;color:#fff;'>Notification sent for Hearing</p>";
                                            }                                            
                                            else{
                                                echo "<p style='background-color:#713002;color:#fff;'>Not yet sent Notification</p>";
                                            }                                            
                                        ?>
                                    </td>
                                    <td style="height:30px; padding:2px; text-align:justify;">
                                        <?php 
                                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleSuspensionHearingNotificationDetails', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));                            
                                       ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>  
                        <?php } ?>                     
                           
                <?php  } ?>                   
        
                <?php if($completed_values && $this->Paginator->param('pageCount')>1) { ?>
                <div class="paginator">
                    <?php 

                    if($this->Paginator->param('pageCount')>10)
                    {
                       echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                            $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                            $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    else {
                       echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                            $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                  ?>
                </div>                
                <?php } ?>
            </fieldset>            
            <fieldset>
                <legend>Notification Pending</legend>
                <?php 
                    if(empty($pending_values) || !is_array($pending_values) || count($pending_values) < 1) {
                        echo '<p class="error-message">';
                        echo 'No data is available !';
                        echo '</p>';
                    }
                    else {
                ?>
                    
                    <?php
                    if ($isAdmin && (!empty($pending_values) && is_array($pending_values) && count($pending_values) > 0 )) {
                        echo $this->Form->create('LicenseModuleSuspensionHearingNotificationDetailPending');
                    ?>

                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="padding-left:15px; text-align:right;">Search by</td>
                            <td>
                                <?php
                                    $options = array('BasicModuleBasicInformation.full_name_of_org'=>'Organization\'s Full Name',
                                                        'BasicModuleBasicInformation.short_name_of_org'=>'Organization\'s Short Name',
                                                        'BasicModuleBasicInformation.license_no'=>'License No.');
                                    echo $this->Form->input('search_option_pending', array('label'=>false, 'style'=>'width:215px', 'options'=>$options));
                                ?>
                            </td>
                            <td style="font-weight:bold;">:</td>
                            <td><?php echo $this->Form->input('search_keyword_pending',array('label'=>false,'style'=>'width:250px')); ?></td>
                            <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch'))); ?></td>
                            <td>
                                <?php
                                    if(!empty($opt_all) && $opt_all) {
                                        echo $this->Js->link('View All', array('controller'=>'LicenseModuleSuspensionHearingNotificationDetails', 'action'=>'view', 'all'),
                                                    array_merge($pageLoading, array('class'=>'mybtns sbtns')));
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>

                    <?php 
                        echo $this->Form->end();             
                    } 
                    ?>

                    <?php if (!empty($pending_values) && is_array($pending_values) && count($pending_values) > 0) { ?>

                        <table class="view">
                            <tr>
                                <?php
                                    echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                                    echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";                        
                                    echo "<th style='min-width:120px;'>Status</th>";
                                    echo "<th>Action</th>";
                                ?>
                            </tr>
                            <?php foreach($pending_values as $value) { ?>                
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>  
                                <td>
                                    <?php
                                        $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                        $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                        if (!empty($orgName))
                                            $orgName = "<strong>".$orgName.":</strong> ";
                                        if (!empty($orgFullName))
                                            $orgName = $orgName.$orgFullName;
                                        echo $orgName;
                                    ?>
                                </td>
                                <td style='padding:2px; text-align:justify;'>
                                    <?php echo "<p style='background-color:#713002;color:#fff;'>Not yet sent any notification</p>"; ?>
                                </td>
                                <?php
                                    $actions = $this->Js->link('Details', array('controller'=>'BasicModuleBasicInformations', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                                    array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));                                                                     

                                    if($thisStateIds[0]==$value['BasicModuleBasicInformation']['licensing_state_id']){
                                        $width = '187px';
                                        $actions = $actions.$this->Js->link('Send Notification', array('controller'=>'LicenseModuleSuspensionHearingNotificationDetails', 'action'=>'notify_for_hearing', $value['BasicModuleBasicInformation']['id']), 
                                                                    array_merge($pageLoading, array('class'=>'btnlink')));     
                                    }
                                    else{
                                        $width = '50px';
                                    }
                                ?>
                                <td style="width:<?php echo $width;?>;height:30px; padding:2px; text-align:justify;">
                                   <?php
                                        echo $actions;
                                   ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    <?php }
                }
                ?>
            </fieldset>
        </div>
    </fieldset>
</div>