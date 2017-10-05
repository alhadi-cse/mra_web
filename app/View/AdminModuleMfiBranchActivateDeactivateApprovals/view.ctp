<?php
    $title = 'Approval of Branch Activation/Deactivation';   
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
                        <td style="text-align:justify; font-family: verdana,helvetica,arial;width:800px;">
                            <?php 
                                $message = "";
                                if (!empty($msg)){
                                    $message = $msg;
                                }
                                echo $this->Form->create('AdminModuleMfiBranchActivateDeactivateApprovalCompleted');
                            ?>                        
                            <table cellpadding="0" cellspacing="0" border="0">          
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search By</td>
                                    <td>
                                        <?php
                                            echo $this->Form->input('completed_search_option', 
                                                    array('label' => false, 'style'=>'width:200px',
                                                        'options' => array('BasicModuleBranchInfo.full_name_of_org' => 'Full Name of Organization',                                                                           
                                                                           'LookupAdminBoundaryDistrict.district_name' => 'District'
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
                                if(empty($completed_values) || !is_array($completed_values) || count($completed_values)<1)
                                {
                                    echo '<p class="error-message">';
                                    echo 'No data is available !';
                                    echo '</p>';
                                }
                                else{
                            ?>
                                <table class="view">
                                    <tr>
                                        <?php
                                            echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";                                            
                                            echo "<th style='width:200px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_name', 'Name of Branch') . "</th>";
                                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryUpazila.upazila_name', 'Upazila') . "</th>";
                                            echo "<th style='width:100px;'>Status</th>";
                                            echo "<th style='width:95px;'>Action</th>";
                                        ?>                                    
                                    </tr>
                                   <?php foreach($completed_values as $value){ ?>
                                    <tr>
                                        <td style="text-align:left;"><?php echo $value['BasicModuleBasicInformation']['full_name_of_org']; ?></td>                                        
                                        <td style="text-align: center;"><?php echo $value['BasicModuleBranchInfo']['branch_name']; ?></td>
                                        <td style="text-align: center;"><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                                        <td style="text-align: center;"><?php echo $value['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                        <td style="text-align: center;">
                                        <?php 
                                            $activation_status="";
                                            if($value['BasicModuleBranchInfo']['is_active']=='1') {
                                               $activation_status="Activated";
                                            }
                                            elseif($value['BasicModuleBranchInfo']['is_active']=='0') {
                                               $activation_status="Deactivated"; 
                                            }
                                            echo $activation_status; 
                                        ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php 
                                                echo $this->Js->link('Details', array('controller' => 'BasicModuleBranchInfos', 'action' => 'preview', $value['BasicModuleBranchInfo']['id']), array_merge($pageLoading, array('style'=>'width:95px;','class'=>'btnlink', 'update' => '#popup_div'))); 
                                                echo $this->Js->link('Cancel Approval', array('controller'=>'AdminModuleMfiBranchActivateDeactivateApprovals', 'action'=>'cancel_approval', $value['BasicModuleBranchInfo']['id'], $value['BasicModuleBranchInfo']['org_id'], $value['BasicModuleBranchInfo']['is_approved'],$value['BasicModuleBranchInfo']['is_active']), 
                                                            array_merge($pageLoading, array('confirm' => 'Are you sure to cancel?','class'=>'btnlink','style'=>'width:95px')));                                                    
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
                                echo $this->Form->create('AdminModuleMfiBranchActivateDeactivateApprovalPending');
                            ?>                        
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search By</td>
                                    <td>
                                        <?php
                                            echo $this->Form->input('pending_search_option', 
                                                    array('label' => false, 'style'=>'width:200px',
                                                                'options' => array('BasicModuleBranchInfo.full_name_of_org' => 'Full Name of Organization',                                                                           
                                                                                   'LookupAdminBoundaryDistrict.district_name' => 'District'
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
                                if(empty($pending_values) || !is_array($pending_values) || count($pending_values)<1) {
                                    echo '<p class="error-message">';
                                    echo 'No data is available !';
                                    echo '</p>';
                                }
                                else {                                    
                                ?>
                                <table class="view">
                                    <tr>
                                        <?php
                                            echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";                                            
                                            echo "<th style='width:200px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_with_address', 'Name of Branch') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryUpazila.upazila_name', 'Upazila') . "</th>";
                                            echo "<th style='width:130px;'>Action</th>";
                                        ?>                                    
                                    </tr>
                                   <?php foreach($pending_values as $value){ ?>
                                    <tr>
                                        <td style="text-align:left;"><?php echo $value['BasicModuleBasicInformation']['full_name_of_org']; ?></td>                                        
                                        <td style="text-align: center;"><?php echo $value['BasicModuleBranchInfo']['branch_with_address']; ?></td>
                                        <td style="text-align: center;"><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                                        <td style="text-align: center;"><?php echo $value['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                        <td style="text-align: center;">
                                        <?php 
                                            echo $this->Js->link('Details', array('controller' => 'AdminModuleMfiBranchActivateDeactivateApprovals', 'action' => 'preview', $value['BasicModuleBranchInfo']['id'], $value['BasicModuleBranchInfo']['org_id'],$title,'both'), array_merge($pageLoading, array('style'=>'width:130px;','class'=>'btnlink', 'update' => '#popup_div'))); 
                                            if($value['BasicModuleBranchInfo']['is_active']==0) { 
                                                $txt_activation_deactivation = 'Activation';                                                
                                            }
                                            elseif($value['BasicModuleBranchInfo']['is_active']==1){
                                                $txt_activation_deactivation = 'Deactivation';                                                
                                            }
                                            $btn_approval = 'Approve for '.$txt_activation_deactivation;
                                            echo $this->Js->link($btn_approval, array('controller' => 'AdminModuleMfiBranchActivateDeactivateApprovals','action' => 'approval',$value['BasicModuleBranchInfo']['id'], $value['BasicModuleBranchInfo']['org_id'],$value['BasicModuleBranchInfo']['is_approved'],$value['BasicModuleBranchInfo']['is_active']), array_merge($pageLoading, 
                                                 array('confirm' => 'Are you sure to approve? You can not change once you approve it', 'success' => "msg.init('success', 'Branch $txt_activation_deactivation Approval', 'Branch $txt_activation_deactivation request has been approved successfully.');", 'error' => "msg.init('error', '$title', 'Approval failed!');",'style'=>'width:130px;','class'=>'btnlink')));
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