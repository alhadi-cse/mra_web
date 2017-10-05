<?php
    $title = 'Accept/Reject Review Application';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <div class="form">
            
            <?php if(empty($org_id)) { 
                echo $this->Form->create('LicenseModuleSuspensionAcceptanceOfReviewDetail'); ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php 
                                echo $this->Form->input('search_option', 
                                        array('label' => false, 'style'=>'width:200px',
                                            'options' => 
                                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                    'BasicModuleBasicInformation.license_no'=>'License No.')
                                                ));
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
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
                <legend>Completed</legend>
                
                <?php 
                    if(empty($values_approved) || !is_array($values_approved) || count($values_approved)<1) {
                        echo '<p class="error-message">';
                        echo 'No data is available!';
                        echo '</p>';
                    }
                    else { 
                ?>

                <table class="view">
                    <tr>
                        <?php 
                        
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:70px;'>Status</th>";
                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleSuspensionAcceptanceOfReviewDetail.acceptance_date', 'Date') . "</th>";
                        echo "<th style='width:85px;'>Action</th>";
                        
                        ?>
                    </tr>
                    <?php foreach($values_approved as $value){ ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;        

                                echo $mfiName;
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            if($value['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='0'){
                                echo 'Review Rejected';
                            }
                            else if($value['LicenseModuleSuspensionAcceptanceOfReviewDetail']['status_id']=='1'){
                                echo 'Review Accepted';
                            }                                
                            ?>
                        </td>                        
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleSuspensionAcceptanceOfReviewDetail']['acceptance_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                           <?php 
                                echo $this->Js->link('Details', array('controller' => 'LicenseModuleSuspensionAcceptanceOfReviewDetails', 'action' => 'preview', $value['LicenseModuleSuspensionAcceptanceOfReviewDetail']['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                           ?>     
                        </td>
                    </tr>
                    <?php  } ?>
                </table>
                
                <?php
                    $user_group_id = $this->Session->read('User.GroupIds');                    
                ?>
                
                <?php  } ?>
                            
        
                <?php if(!empty($values_approved) && $this->Paginator->param('pageCount')>1) { ?>
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
                
                <div class="btns-div" style="padding:7px; text-align:center;">
                    <?php
                        echo $this->Js->link('Update All', array('controller' => 'LicenseModuleSuspensionAcceptanceOfReviewDetails','action' => 'approve_edit_all'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'Administrative Approval Update(From EVC)')));                            
                    ?>
                </div>                
                <?php } ?>
            </fieldset>
            
            <fieldset>
                <legend>Pending</legend>
                <?php 
                    if(empty($values_not_approved) || !is_array($values_not_approved) || count($values_not_approved) < 1) {
                        echo '<p class="error-message">';
                        echo 'No data is available !';
                        echo '</p>';
                    }
                    else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:220px;'>Action</th>";
                        ?>
                    </tr>

                    <?php 
                    foreach ($values_not_approved as $value) {
                    ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
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
                        <td style="height:30px; padding:2px; text-align:justify;"> 
                            <?php 
                                echo $this->Js->link('Details', array('controller' => 'LicenseModuleSuspensionReviewApplicationDetails','action' => 'preview', $value['BasicModuleBasicInformation']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')))
                                     .$this->Js->link('Accept/Reject Review', array('controller'=>'LicenseModuleSuspensionAcceptanceOfReviewDetails','action'=>'approve', $value['BasicModuleBasicInformation']['id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>                
                <?php                
                }
                ?>
            </fieldset>
        </div>
    </fieldset>
</div>
