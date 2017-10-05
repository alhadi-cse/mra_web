<?php
    $title = 'Cancel Request Verification';   
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            <fieldset>
                <legend>Completed</legend>
                <table >
                    <tr>        
                        <td style="text-align: justify;font-family: verdana,helvetica,arial;">
                            <?php 
                                $message = "";
                                if (!empty($msg)){
                                    $message = $msg;
                                }
                                echo $this->Form->create('BasicModuleBasicInformationCompleted');
                            ?>                        
                            <table cellpadding="0" cellspacing="0" border="0">          
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search By</td>
                                    <td>
                                        <?php
                                            echo $this->Form->input('completed_search_option', 
                                                    array('label' => false, 'style'=>'width:200px',
                                                        'options' => array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                                           'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                                           'BasicModuleBasicInformation.form_serial_no'=>'Form No.'                                                                           
                                                                    )
                                                            )
                                                        );
                                        ?>
                                    </td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('completed_search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
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
                            <div id="searching" style="width:700px;">  
                            <?php 
                                if($completed_values==null || !is_array($completed_values) || count($completed_values)<1)
                                {
                                    echo '<p class="error-message">';
                                    echo 'No data is available !';
                                    echo '</P>';
                                }
                                else{  ?>                                
                                    <table class="view">
                                        <tr>
                                            <?php 
                                            if(!$this->Paginator->param('options'))
                                                echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                                            else 
                                                echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.approval_status', 'Status') . "</th>";
                                                echo "<th style='width:70px;'>" . $this->Paginator->sort('LicenseModuleVerificationOfCancelRequest.approval_date', 'Approval Date') . "</th>";
                                                echo "<th style='width:85px;'>Action</th>";
                                            ?>
                                        </tr>
                                        <?php foreach($pending_values as $value){ ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                                    $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                                    if (isset($mfiName) && trim($mfiName)!='')
                                                        $mfiName = "<strong>".$mfiName.":</strong> ";

                                                    if (isset($mfiFullName) && trim($mfiFullName)!='')
                                                        $mfiName = $mfiName.$mfiFullName;        

                                                    echo $mfiName;
                                                ?>
                                            </td>
                                            <td><?php echo $value['LookupLicenseApprovalStatus']['approval_status']; ?></td>
                                            <td><?php echo $this->Time->format($value['LicenseModuleVerificationOfCancelRequest']['approval_date'], '%d-%m-%Y', ''); ?></td>
                                            <td style="text-align:center; padding:2px; height:30px;">
                                               <?php 
                                                    echo $this->Js->link('Re-Verify', array('controller' => 'LicenseModuleVerificationOfCancelRequests', 'action' => 're_approve', $value['LicenseModuleVerificationOfCancelRequest']['org_id']), 
                                                                                    array_merge($pageLoading, array('class'=>'btnlink')))
                                                        . $this->Js->link('Details', array('controller' => 'LicenseModuleVerificationOfCancelRequests', 'action' => 'preview', $value['LicenseModuleVerificationOfCancelRequest']['org_id']), 
                                                                                    array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                                               ?>     
                                            </td>
                                        </tr>
                                        <?php  } ?>
                                    </table>

                                <?php  } ?>
                            </div>
                        </td>                
                    </tr>
                </table>
        
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
        
            <div style="width:100%; height:auto; overflow-x:auto; margin-top:10px">                
                <fieldset>
                <legend>Pending</legend>
                    <table >
                    <tr>        
                        <td style="text-align: justify;font-family: verdana,helvetica,arial;">
                            <?php 
                                $message = "";
                                if (!empty($msg)){
                                    $message = $msg;
                                }
                                echo $this->Form->create('BasicModuleBasicInformationPending');
                            ?>                        
                            <table cellpadding="0" cellspacing="0" border="0">          
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search By</td>
                                    <td>
                                        <?php
                                            echo $this->Form->input('pending_search_option', 
                                                    array('label' => false, 'style'=>'width:200px',
                                                        'options' => array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                                           'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name'                                                                                                                                                     
                                                                )
                                                        )
                                                    );
                                        ?>
                                    </td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('pending_search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
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
                            <div id="searching" style="width:700px;"> 
                                <?php 
                                    if($pending_values==null || !is_array($pending_values) || count($pending_values)<1)
                                    {
                                        echo '<p class="error-message">';
                                        echo 'No data is available !';
                                        echo '</P>';
                                    }
                                    else{
                                ?>                                   
                                        <table class="view">
                                            <tr>
                                                <?php
                                                if(!$this->Paginator->param('options'))
                                                    echo "<th style='min-width:330px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                                                else 
                                                    echo "<th style='min-width:330px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                                    echo "<th style='width:185px;'>Action</th>";
                                                ?>
                                            </tr>

                                            <?php 
                                            foreach ($pending_values as $value) {
                                            ?>
                                            <tr>
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
                                                <td style="height:30px; padding:2px; text-align:center;"> 
                                                    <?php 
                                                        echo $this->Js->link('Review/Verify', array('controller'=>'LicenseModuleVerificationOfCancelRequests', 'action'=>'verify', $value['BasicModuleBasicInformation']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink')))
                                                            .$this->Js->link('Details', array('controller'=>'LicenseModuleVerificationOfCancelRequests', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));                       
                                                    ?>
                                                </td>
                                            </tr>
                                          <?php } ?>
                                        </table>               
                                    <?php  } ?>
                            </div>
                        </td>
                    </tr>
                </table>                
                </fieldset>                
            </div>
        </div>
    </fieldset>
</div>