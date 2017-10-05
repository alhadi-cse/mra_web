<?php 
    $title = "Rejection/Suspension/Cancellation Histories";
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);    
?>  

<div id="frmTypeOfOrg_view">
    <fieldset>
        <legend>
          <?php echo $title; ?>
        </legend>
        <div class="form"> 
            <table>
                <tr> 
                    <td style="text-align:justify;">
                        <?php                 
                            echo $this->Form->create('LicenseModuleRejectSuspendCancelHistory');
                        ?>
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
                                                            'LookupRejectSuspendCancelHistoryType.reject_suspend_cancel_history_type' => 'Type of History',
                                                            'LookupRejectSuspendCancelStepCategory.reject_suspend_cancel_category' => 'Category/Process Step',))
                                                    );
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
                        <?php  echo $this->Form->end(); ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="searching">
                            <?php 
                                if($values==null || !is_array($values) || count($values)<1)
                                {
                                    echo '<p class="error-message">';
                                    echo 'No data is available!';
                                    echo '</p>';
                                }
                                else{
                            ?>                            
                            <table class="view">
                                <tr>                                    
                                    <th style="width:185px;">Name of Organization</th>
                                    <th style="width:70px;">Type of History</th> 
                                    <th style="width:70px;">Category/Process Step</th> 
                                    <th style="width:70px;">Stepwise Reason</th>
                                    <th style="width:150px;">Date</th>
                                    <th style="width:100px;">Action</th>
                                </tr>
                                <?php foreach($values as $value){ ?>
                                <tr>
                                    <td>
                                        <?php 
                                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                            
                                            if (!empty($mfiName))
                                                $mfiName = "<strong>".$mfiName.":</strong> ";
                                            
                                            if (!empty($mfiFullName))
                                                $mfiName = $mfiName.$mfiFullName;
                                                //$mfiName = $mfiName."<strong>:</strong> ".$mfiFullName;
                                            
                                            echo $mfiName;
                                        ?>
                                    </td>
                                    <td style="text-align:center;"><?php echo $value['LookupRejectSuspendCancelHistoryType']['reject_suspend_cancel_history_type']; ?></td>
                                    <td style="text-align:center;"><?php echo $value['LookupRejectSuspendCancelStepCategory']['reject_suspend_cancel_category']; ?></td>
                                    <td style="text-align:center;"><?php echo $value['LookupRejectSuspendCancelStepwiseReason']['reject_suspend_cancel_reason']; ?></td> 
                                    <td style="text-align:center;"><?php echo $value['LicenseModuleRejectSuspendCancelHistory']['reject_suspend_cancel_date']; ?></td>
                                    <td style="height: 30px; padding: 2px; text-align: center;">
                                       <?php 
                                            echo $this->Js->link('Edit', array('controller' => 'LicenseModuleRejectSuspendCancelHistories','action' => 'edit', $value['LicenseModuleRejectSuspendCancelHistory']['id'],$value['LicenseModuleRejectSuspendCancelHistory']['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink')))
                                                 .$this->Js->link('Details', array('controller' => 'LicenseModuleRejectSuspendCancelHistories','action' => 'details', $value['LicenseModuleRejectSuspendCancelHistory']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                                       ?>
                                    </td>
                                </tr>
                                <?php  } ?>
                            </table>                            
                            <?php } ?>
                        </div>
                    </td> 
                </tr>
            </table>
        </div>      
                
        <?php          
          if($values!=null && $this->Paginator->param('pageCount')>1){
        ?> 
        <div class="paging">
          <?php 
            echo $this->Paginator->prev('<<', array('class' => 'PrevPg')).
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                    $this->Paginator->next('>>', array('class' => 'NextPg'));
          ?> 
        </div>
        <?php        
          }
        ?>
        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>                    
                    <td>
                        <?php
                            echo $this->Js->link('Add New', array('controller' => 'LicenseModuleRejectSuspendCancelHistories','action' => 'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>                    
                </tr>
            </table>
        </div>        
    </fieldset>
</div>   
<?php
    //echo $this->element('homefooter', array("variable_name" => "current")); 
?>