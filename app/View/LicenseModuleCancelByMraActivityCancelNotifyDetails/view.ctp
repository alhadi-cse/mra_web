<div>    
    <?php        
        if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
        $isAdmin =(!empty($user_group_id) && $user_group_id == 1);
        $title = "Activity Closing Notification";
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php 
        if ($isAdmin && (!empty($values_licensed) && is_array($values_licensed) && count($values_licensed) > 0 && !$opt_all)) { 
            echo $this->Form->create('LicenseModuleCancelByMraActivityCancelNotifyDetail'); 
        ?>

        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding-left:15px; text-align:right;">Search by</td>
                <td>
                    <?php
                        $options = array('BasicModuleBasicInformation.full_name_of_org'=>"Organization's Full Name",
                                            'BasicModuleBasicInformation.short_name_of_org'=>"Organization's Short Name",
                                            'BasicModuleBasicInformation.license_no'=>'License No.');
                        echo $this->Form->input('search_option', array('label'=>false, 'style'=>'width:215px', 'options'=>$options));
                    ?>
                </td>
                <td style="font-weight:bold;">:</td>
                <td><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width:250px')); ?></td>
                <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch'))); ?></td>
                <td>
                    <?php 
                        if(!empty($opt_all) && $opt_all) {
                            echo $this->Js->link('View All', array('controller'=>'LicenseModuleLicenseCancellationInfos', 'action'=>'view', 'all'),
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

        <?php if (!empty($values_licensed) && is_array($values_licensed) && count($values_licensed) > 0) { ?>
        <fieldset>
            <legend>Organization with Canceled License</legend>            
            <table class="view">
                <tr>
                    <?php
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='min-width:150px;'>Status</th>";
                        echo "<th>Action</th>";
                    ?>
                </tr>
                <?php foreach($values_licensed as $value) { ?>                
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
                        <?php if($thisStateIds[1]==$value['BasicModuleBasicInformation']['licensing_state_id']){
                                echo "<p style='background-color:#037108;color:#fff;'>";
                                echo 'Notified about Activities Closing';
                                echo '</p>';
                            }
                            else{
                                echo "<p style='background-color:#713002;color:#fff;'>";
                                echo 'Not yet notified';
                                echo '</p>';
                            }
                        ?>
                    </td>
                    <?php
                        $actions = $this->Js->link('Details', array('controller'=>'LicenseModuleCancelByMraActivityCancelNotifyDetails', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));                                                                     
                        
                        if($thisStateIds[0]==$value['BasicModuleBasicInformation']['licensing_state_id']){
                            $width = '175px';
                            $actions = $actions.$this->Js->link('Notify to MRA', array('controller'=>'LicenseModuleCancelByMraActivityCancelNotifyDetails', 'action'=>'notify_about_activity_closing', $value['BasicModuleBasicInformation']['id'],$thisStateIds[1]), 
                                                        array_merge($pageLoading, array('class'=>'btnlink')));     
                        }
                        else{
                            $width = '80px';
                        }
                   ?>
                    <td style="width:<?php echo $width;?>;height:30px; padding:2px; text-align:center;">
                       <?php 
                            echo $actions;
                       ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </fieldset>
        <?php }
        else{
            echo '<p class="error-message">';
            echo 'No data is available !';
            echo '</p>';
            }
        ?>
    </fieldset>
</div>