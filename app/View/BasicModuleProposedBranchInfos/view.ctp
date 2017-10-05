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
        $title = "Proposed Branch List";
        $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true,
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
        $this->Paginator->options($pageLoading);
?>
<fieldset>
    <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($values)) {
                echo "<p style='color:#072fa3;font-weight:bold;'>";
                echo "Total records found : ".$total;
                echo "</p><p style='color:#072fa3;font-weight:bold;'>Last $page_limit records have been shown below. Please use search option to view previous records.";
                echo "</p>";
                
                if($user_group_id=='2') {                    
                    $mfiName = $values[0]['BasicModuleBasicInformation']['short_name_of_org'];
                    $mfiFullName = $values[0]['BasicModuleBasicInformation']['full_name_of_org'];
                    if (!empty($mfiFullName) && !empty($mfiName))
                        $mfiName = $mfiFullName . " (<strong>" . $mfiName . "</strong>)";
                    else
                        $mfiName = $mfiName . $mfiFullName;
                    echo '<p style="margin:3px;"><strong>Name of Organization : </strong>' . $mfiName . '</p>';
                }
            }
            ?>       
            <div class="form"> 
                <table>
                    <?php if(!empty($values)) { ?>
                    <tr> 
                        <td>
                            <?php echo $this->Form->create('BasicModuleProposedBranchInfo'); ?>
                            <table cellpadding="0" cellspacing="0" border="0">                           
                                <tr>
                                    <td style="padding-left:15px; text-align:right;">Search by</td>
                                    <td>
                                        <?php                                     
                                            $options = array('BasicModuleProposedBranchInfo.branch_code'=>'Branch Code',
                                                             'BasicModuleProposedBranchInfo.branch_name'=>'Branch Name',
                                                             'LookupBasicProposedOfficeType.office_type'=>'Office Type',
                                                             'LookupAdminBoundaryDistrict.district_name'=>'District Name',
                                                             'LookupAdminBoundaryUpazila.upazila_name'=>'Upazila Name');

                                            if(empty($org_id)) {
                                                $options = array_merge(array('BasicModuleBasicInformation.full_name_of_org'=>'Organization\'s Full Name',
                                                                'BasicModuleBasicInformation.short_name_of_org'=>'Organization\'s Short Name'), $options);
                                            }

                                            echo $this->Form->input('search_option', array('label'=>false, 'style'=>'width:215px', 'options'=>$options));

                                        ?>
                                    </td>
                                    <td style="font-weight:bold;">:</td>
                                    <td><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width:250px')); ?></td>
                                    <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch'))); ?></td>
                                    <td>
                                        <?php                                    
                                            if(!empty($opt_all) && $opt_all) {
                                                echo $this->Js->link('View All', array('controller'=>'BasicModuleProposedBranchInfos', 'action'=>'view', 'all'), 
                                                            array_merge($pageLoading, array('class'=>'mybtns sbtns')));
                                            }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <?php echo $this->Form->end(); ?> 
                        </td>
                    </tr>                               
                    <?php } ?>
                    <tr>
                        <td>
                            <div style="width:780px; height:auto; overflow-x:auto;">
                                <?php 
                                    if(empty($values) || count($values)<1) {
                                        echo '<p class="error-message">No branch information available!</p>';
                                        $btn_title = 'Add an Office';
                                    }
                                    else {
                                        $btn_title = 'Add Another Office';
                                ?>
                                <table class="view">
                                    <tr>
                                        <?php
                                            $width_th = 'width:55px;';                                            
                                            if(!empty($org_id)) {
                                                $width_th = 'width:120px;';
                                            }
                                            if(empty($org_id)) {
                                                echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                            }
                                            echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleProposedBranchInfo.branch_name', 'Branch Name') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupBasicProposedOfficeType.office_type', 'Office Type') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryUpazila.upazila_name', 'Upazila') . "</th>";
                                            echo "<th style='width:75px;'>Picture</th>";                                            
                                            echo "<th style=$width_th>Action</th>";
                                        ?>                                        
                                    </tr>
                                    <?php foreach($values as $value){
                                        $style="";
                                        if($value['BasicModuleProposedBranchInfo']['is_active']=='1'){
                                           $style="style='background-color:#fff;'";
                                        }
                                        elseif($value['BasicModuleProposedBranchInfo']['is_active']=='0'){
                                           $style="style='background-color:#fe2e2b;'"; 
                                        }
                                    ?>
                                    <tr>
                                        <?php if(empty($org_id)) { ?>
                                        <td <?php echo $style?>><?php echo $value['BasicModuleBasicInformation']['full_name_of_org']; ?></td>    
                                        <?php } ?>
                                        <td <?php echo $style?>><?php echo $value['BasicModuleProposedBranchInfo']['branch_name']; ?></td>
                                        <td <?php echo $style?>><?php echo $value['LookupBasicProposedOfficeType']['office_type']; ?></td>
                                        <td <?php echo $style?>><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td> 
                                        <td <?php echo $style?>><?php echo $value['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                        <td style="text-align:center;">
                                            <?php 
                                            if(!empty($value['BasicModuleProposedBranchImage']['file_name'])) {
                                                $img_url = $value['BasicModuleProposedBranchImage']['file_name'];
                                                $rand_number = rand(1000, 99999);
                                                echo $this->Html->image('/files/uploads/proposed_branches/'.$img_url."?$rand_number", array('plugin' => false,'alt'=>'no image','style'=>'width:75px; height:50px; text-align:center;display: block;margin: auto;'));
                                            }
                                            else {
                                                echo 'no image';
                                            }
                                            ?>
                                        </td>                                        
                                        <td style="height:30px; padding:2px; text-align:justify;">
                                            <?php                                            
                                            if(!empty($org_id)) {
                                                echo $this->Js->link('Edit', array('controller' => 'BasicModuleProposedBranchInfos', 'action' => 'edit', $value['BasicModuleProposedBranchInfo']['id'], $value['BasicModuleProposedBranchInfo']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                            }                                            
                                            echo $this->Js->link('Details', array('controller' => 'BasicModuleProposedBranchInfos', 'action' => 'preview', $value['BasicModuleProposedBranchInfo']['id']), array_merge($pageLoading, array('class' => 'btnlink','update' => '#popup_div')));                                            
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
            <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
            <div class="paginator">
              <?php 
                echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
                        $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                        $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
              ?>
            </div>
            <?php } ?>
        
            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <?php
                            if(!empty($org_id)) {
                                echo $this->Js->link($btn_title, array('controller'=>'BasicModuleProposedBranchInfos','action'=>'add'), 
                                                        array_merge($pageLoading, array('class'=>'mybtns')));                                
                            }
                            else {
                                echo $this->Js->link('Summary of Data Submission', array('controller'=>'BasicModuleProposedBranchInfos','action'=>'submission_summary'), 
                                                        array_merge($pageLoading, array('class'=>'mybtns')));
                            }
                            ?>
                        </td>
                        <td>
                            <?php /*
                            if(!empty($org_id)) {
                                echo $this->Js->link('Export', array('controller'=>'BasicModuleProposedBranchInfos','action'=>'download',$org_id), 
                                                        array_merge($pageLoading, array('class'=>'mybtns')));                                
                            }
                            else {
                                echo $this->Js->link('Export', array('controller'=>'BasicModuleProposedBranchInfos','action'=>'download'), 
                                                        array_merge($pageLoading, array('class'=>'mybtns')));
                            } */
                            ?>
                        </td>
                        <td></td>   
                    </tr>
                </table>
            </div>    
    </div> 
</fieldset>
<?php } ?>
