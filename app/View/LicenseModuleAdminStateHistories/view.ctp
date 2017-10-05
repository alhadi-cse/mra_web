
<?php 
    
    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        
        $title = "Licensing State Histories";
        $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
        
        $this->Paginator->options($pageLoading);
    
?>
    
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form"> 
            <table>
                <tr>
                    <td>
                        <fieldset>
                            <legend>Current Licensing State</legend>
                            <div style="width:780px; height:auto; overflow-x:auto;">
                                <?php 
                                    if($current_year_values==null || !is_array($current_year_values) || count($current_year_values)<1)
                                    {
                                        echo '<p class="error-message">';
                                        echo 'Licensing Process Not Yet Started!';
                                        echo '</p>';
                                    }
                                    else {
                                ?>
                                <table class="view">
                                    <tr>                                        
                                        <th style="width:70px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.licensing_year','Licensing Year') ?></th> 
                                        <th style="width:150px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateName.state_title','Licensing State') ?></th>
                                        <th style="width:80px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.starting_date','State Starting Date') ?></th>
                                        <th style="width:80px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.ending_date','State Ending Date') ?></th>
                                        <th style="width:70px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.state_completion_days','No. of Days for State Completion') ?></th> 
                                        <th style="width:70px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.is_current','Completion Status') ?></th>                                    
    <!--                                    <th style="width:115px;">Action</th>-->
                                    </tr>
                                    </tr>
                                    <?php foreach($current_year_values as $value){ ?>
                                    <tr>                                    
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['licensing_year']; ?></td>
                                        <td><?php echo $value['LicenseModuleAdminStateName']['state_title']; ?></td>
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['starting_date']; ?></td>
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['ending_date']; ?></td>                                    
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['state_completion_days']; ?></td>
                                        <td style="text-align:center;">
                                            <?php 
                                            echo (!empty($value['LicenseModuleAdminStateHistory']['is_current']) && $value['LicenseModuleAdminStateHistory']['is_current']==1)?"Running":"Completed"; 
                                            ?>
                                        </td>                                    

                                    </tr>

                                  <?php } ?>
                                </table>
                                <?php } ?>
                            </div>
                            
                        </fieldset>
                        
                        
                        
                        <fieldset>
                            <legend>Previous Licensing State</legend>
                            <div style="width:780px; height:auto; overflow-x:auto;">
                                <?php 
                                    if($previous_year_values==null || !is_array($previous_year_values) || count($previous_year_values)<1)
                                    {
                                        echo '<p class="error-message">';
                                        echo 'No Previous Information Found !';
                                        echo '</p>';
                                    }
                                    else {
                                ?>
                                <table class="view">
                                    <tr>                                        
                                        <th style="width:70px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.licensing_year','Licensing Year') ?></th> 
                                        <th style="width:150px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateName.state_title','Licensing State') ?></th>
                                        <th style="width:80px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.starting_date','State Starting Date') ?></th>
                                        <th style="width:80px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.ending_date','State Ending Date') ?></th>
                                        <th style="width:70px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.state_completion_days','No. of Days for State Completion') ?></th> 
                                        <th style="width:70px;"><?php echo $this->Paginator->sort('LicenseModuleAdminStateHistory.is_current','Completion Status') ?></th>                                    
    <!--                                    <th style="width:115px;">Action</th>-->
                                    </tr>
                                    </tr>
                                    <?php foreach($previous_year_values as $value){ ?>
                                    <tr>                                    
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['licensing_year']; ?></td>
                                        <td><?php echo $value['LicenseModuleAdminStateName']['state_title']; ?></td>
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['starting_date']; ?></td>
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['ending_date']; ?></td>                                    
                                        <td style="text-align:center;"><?php echo $value['LicenseModuleAdminStateHistory']['state_completion_days']; ?></td>
                                        <td style="text-align:center;">
                                            <?php 
                                            echo (!empty($value['LicenseModuleAdminStateHistory']['is_current']) && $value['LicenseModuleAdminStateHistory']['is_current']==1)?"Running":"Completed"; 
                                            ?>
                                        </td>                                    

                                    </tr>

                                  <?php } ?>
                                </table>
                                <?php } ?>
                            </div>
        
                            <?php if($previous_year_values && $this->Paginator->param('pageCount')>1) { ?>
                            <div class="paginator">
                                <?php 

                                if($this->Paginator->param('pageCount')>10)
                                {
                                   echo $this->Paginator->first('<<', array('class'=>'prevPg', 'title'=>'Goto first page.'), null, array('class'=>'prevPg no_link')).
                                        $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                                        $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                                        $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                                        $this->Paginator->last('>>', array('class' => 'nextPg', 'title' => 'Goto last page.'), null, array('class' => 'nextPg no_link'));
                                }
                                else {
                                   echo $this->Paginator->prev('<<', array('class'=>'prevPg', 'title'=>'Goto previous page.'), null, array('class'=>'prevPg no_link')).
                                        $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                                        $this->Paginator->next('>>', array('class'=>'nextPg', 'title'=>'Goto next page.'), null, array('class'=>'nextPg no_link'));
                                }
                              ?>
                            </div>
                            <?php } ?>
                        </fieldset>                        
                    </td>
                </tr>
            </table>
        </div>        
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                            echo $this->Js->link('Update to Next State', array('controller'=>'LicenseModuleAdminStateHistories','action'=>'update_state'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>        
    </fieldset>
</div> 
<?php } ?>
