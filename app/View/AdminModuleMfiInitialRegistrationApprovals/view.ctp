<?php
    $title = 'Approval of Request for License Application';
    
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
                        <td style="text-align:justify; font-family: verdana,helvetica,arial;">
                            <?php 
                                $message = "";
                                if (!empty($msg)){
                                    $message = $msg;
                                }
                                echo $this->Form->create('AdminModuleMfiRegistrationForNewLicenseCompleted');
                            ?>                        
                            <table cellpadding="0" cellspacing="0" border="0">          
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search By</td>
                                    <td>
                                        <?php
                                            echo $this->Form->input('completed_search_option', 
                                                    array('label' => false, 'style'=>'width:200px',
                                                        'options' => array('AdminModuleMfiRegistrationForNewLicense.mfi_full_name' => 'Full Name of Organization',
                                                                           'AdminModuleMfiRegistrationForNewLicense.mfi_short_name' => 'Short Name of Organization',
                                                                           'AdminModuleMfiRegistrationForNewLicense.primary_registration_no' => 'Primary Registration No',
                                                                           'AdminModuleMfiRegistrationForNewLicense.mobile_no' => 'Mobile no.',
                                                                           'AdminModuleMfiRegistrationForNewLicense.email' => 'E-Mail'
                                                                        ))
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
                            <div id="searching">  
                            <?php 
                                if($completed_values==null || !is_array($completed_values) || count($completed_values)<1)
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
                                            echo "<th style='min-width:250px;'>" . $this->Paginator->sort('AdminModuleMfiRegistrationForNewLicense.mfi_full_name', 'Name of Organization') . "</th>";                                            
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('AdminModuleMfiRegistrationForNewLicense.mobile_no', 'Mobile no.') . "</th>";
                                            echo "<th style='width:200px;'>" . $this->Paginator->sort('AdminModuleMfiRegistrationForNewLicense.email', 'E-Mail') . "</th>";
                                            echo "<th style='width:70px;'>Action</th>";
                                        ?>                                    
                                    </tr>
                                   <?php foreach($completed_values as $value){ ?>
                                    <tr>
                                        <td style="text-align:left;">
                                             <?php 
                                                $mfiName = $value['AdminModuleMfiRegistrationForNewLicense']['mfi_short_name'];
                                                $mfiFullName = $value['AdminModuleMfiRegistrationForNewLicense']['mfi_full_name'];
                                                if (!empty($mfiName))
                                                    $mfiName = "<strong>".$mfiName.":</strong> ";
                                                if (!empty($mfiFullName))
                                                    $mfiName = $mfiName.$mfiFullName;                                            
                                                echo $mfiName;
                                            ?>
                                        </td>                                        
                                        <td style="text-align: justify;"><?php echo $value['AdminModuleMfiRegistrationForNewLicense']['mobile_no']; ?></td>
                                        <td style="text-align: justify;"><?php echo $value['AdminModuleMfiRegistrationForNewLicense']['email']; ?></td>
                                        <td style="text-align: center;">
                                            <?php 
                                                echo $this->Js->link('Details', array('controller' => 'AdminModuleMfiInitialRegistrationApprovals', 'action' => 'preview', $value['AdminModuleMfiRegistrationForNewLicense']['id']), array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
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
        
            <div style="height:auto; overflow-x:auto; margin-top:10px">                
                <fieldset>
                <legend>Pending</legend>
                    <table >
                    <tr>        
                        <td style="text-align:left; font-family:verdana,helvetica,arial; width:800px;">
                            <?php 
                                $message = "";
                                if (!empty($msg)){
                                    $message = $msg;
                                }
                                echo $this->Form->create('AdminModuleMfiRegistrationForNewLicensePending');
                            ?>                        
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search By</td>
                                    <td>
                                        <?php
                                            echo $this->Form->input('pending_search_option', 
                                                    array('label' => false, 'style'=>'width:200px',
                                                        'options' => array('AdminModuleMfiRegistrationForNewLicense.mfi_full_name' => 'Full Name of Organization',
                                                                           'AdminModuleMfiRegistrationForNewLicense.mfi_short_name' => 'Short Name of Organization',
                                                                           'AdminModuleMfiRegistrationForNewLicense.primary_registration_no' => 'Primary Registration No',
                                                                           'AdminModuleMfiRegistrationForNewLicense.mobile_no' => 'Mobile no.',
                                                                           'AdminModuleMfiRegistrationForNewLicense.email' => 'E-Mail'
                                                                        ))
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
                            <div id="searching"> 
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
                                            echo "<th style='min-width:200px;'>" . $this->Paginator->sort('AdminModuleMfiRegistrationForNewLicense.mfi_full_name', 'Name of Organization') . "</th>";                                            
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('AdminModuleMfiRegistrationForNewLicense.mobile_no', 'Mobile no.') . "</th>";
                                            echo "<th style='width:150px;'>" . $this->Paginator->sort('AdminModuleMfiRegistrationForNewLicense.email', 'E-Mail') . "</th>";
                                            echo "<th style='width:190px;'>Action</th>";
                                        ?>                                    
                                    </tr>
                                   <?php foreach($pending_values as $value){ ?>
                                    <tr>
                                        <td style="text-align:left;">
                                             <?php 
                                                $mfiName = $value['AdminModuleMfiRegistrationForNewLicense']['mfi_short_name'];
                                                $mfiFullName = $value['AdminModuleMfiRegistrationForNewLicense']['mfi_full_name'];
                                                if (!empty($mfiName))
                                                    $mfiName = "<strong>".$mfiName.":</strong> ";
                                                if (!empty($mfiFullName))
                                                    $mfiName = $mfiName.$mfiFullName;                                            
                                                echo $mfiName;
                                            ?>
                                        </td>                                        
                                        <td style="text-align: center;"><?php echo $value['AdminModuleMfiRegistrationForNewLicense']['mobile_no']; ?></td>
                                        <td style="text-align: center;"><?php echo $value['AdminModuleMfiRegistrationForNewLicense']['email']; ?></td>
                                        <td style="text-align: center;">
                                        <?php 
                                            echo $this->Js->link('Details', array('controller' => 'AdminModuleMfiInitialRegistrationApprovals', 'action' => 'preview', $value['AdminModuleMfiRegistrationForNewLicense']['id']), array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div'))).
                                                 $this->Js->link('Approve Request', array('controller' => 'AdminModuleMfiInitialRegistrationApprovals','action' => 'initial_approval', $value['AdminModuleMfiRegistrationForNewLicense']['id']), array_merge($pageLoading, 
                                                 array('confirm' => 'Are you sure to approve? You can not change once you approve it', 'success' => "msg.init('success', '$title', 'Request for license application has been approved successfully.');", 'error' => "msg.init('error', '$title', 'Approval failed!');",'class'=>'btnlink')));
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
                </fieldset>                
            </div>
        </div>
    </fieldset>
</div>
