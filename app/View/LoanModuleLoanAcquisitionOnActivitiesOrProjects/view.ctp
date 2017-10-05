
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
        
        $title = "Loan Acquisition on Activities or Projects";
        $isAdmin = !empty($user_group_id) && $user_group_id==1;
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
                        <?php echo $this->Form->create('LoanModuleLoanAcquisitionOnActivitiesOrProject'); ?>
                        <table cellpadding="0" cellspacing="0" border="0">           
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search by</td>
                                <td>
                                    <?php 
                                        $options = array('BasicModuleBranchInfo.branch_name'=>'Branch Name',
                                                         'LookupLoanActivityCategory.loan_activity_category'=>'Activity/Project Category',
                                                         'LookupLoanActivitySubcategory.loan_activity_subcategory'=>'Activity/Project Sub-Category',
                                                         'LookupLoanActivityScheme.loan_activity_scheme'=>'Activity/Project Scheme');
                                        
                                        if(empty($org_id))
                                        {
                                            $options = array_merge(array('BasicModuleBasicInformation.full_name_of_org'=>"Organization's Full Name",
                                                            'BasicModuleBasicInformation.short_name_of_org'=>"Organization's Short Name"), $options);
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
                                            echo $this->Js->link('View All', array('controller'=>'LoanModuleLoanAcquisitionOnActivitiesOrProjects', 'action'=>'view', 'all'), 
                                                        array_merge($pageLoading, array('class'=>'mybtns sbtns')));
                                        }
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <?php echo $this->Form->end(); ?> 
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div style="width:780px; height:auto; overflow-x:auto;">
                            <?php 
                                if($values==null || !is_array($values) || count($values)<1)
                                {
                                    echo '<p class="error-message">';
                                    echo 'Did not find any data !';
                                    echo '</p>';
                                }
                                else {
                            ?>
                            <table class="view">
                                <tr>
                                    <th style="min-width:170px;">
                                    <?php 
                                        if(!$this->Paginator->param('options'))
                                            echo $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc'));
                                        else
                                            echo $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization');
                                    ?>
                                    </th>
                                    <th style="width:120px;"><?php echo $this->Paginator->sort('BasicModuleBranchInfo.branch_name','Branch Name') ?></th> 
                                    <th style="width:100px;"><?php echo $this->Paginator->sort('LookupLoanActivityCategory.loan_activity_category','Activity/Project Category') ?></th>
                                    <th style="width:100px;"><?php echo $this->Paginator->sort('LookupLoanActivitySubcategory.loan_activity_subcategory','Activity/Project Sub-Category') ?></th>
                                    <th style="width:100px;"><?php echo $this->Paginator->sort('LookupLoanActivityScheme.loan_activity_scheme','Activity/Project Scheme') ?></th>
                                    <th style="width:80px;"><?php echo $this->Paginator->sort('LoanModuleLoanAcquisitionOnActivitiesOrProject.year_and_month','Year & Month') ?></th> 
                                    <th style="width:80px;"><?php echo $this->Paginator->sort('LoanModuleLoanAcquisitionOnActivitiesOrProject.no_of_borrowers','No. Of Borrowers') ?></th>
                                    <th style="width:80px;"><?php echo $this->Paginator->sort('LoanModuleLoanAcquisitionOnActivitiesOrProject.total_loan_disbursed','Total Loan Disbursed') ?></th>
                                    <th style="width:115px;">Action</th>
                                </tr>
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
                                            
                                            echo $mfiName;
                                        ?>
                                    </td>
                                    <td><?php echo $value['BasicModuleBranchInfo']['branch_name']; ?></td>
                                    <td><?php echo $value['LookupLoanActivityCategory']['loan_activity_category']; ?></td>
                                    <td><?php echo $value['LookupLoanActivitySubcategory']['loan_activity_subcategory']; ?></td>
                                    <td><?php echo $value['LookupLoanActivityScheme']['loan_activity_scheme']; ?></td>
                                    <td style="text-align:center;"><?php echo $this->Time->format($value['LoanModuleLoanAcquisitionOnActivitiesOrProject']['year_and_month'], '%B, %Y', ''); ?></td>
                                    <td style="text-align:center;"><?php echo $value['LoanModuleLoanAcquisitionOnActivitiesOrProject']['no_of_borrowers']; ?></td>
                                    <td style="text-align:right;"><?php echo $value['LoanModuleLoanAcquisitionOnActivitiesOrProject']['total_loan_disbursed']; ?></td>
                                    <td style="height:30px; padding:2px; text-align:center;">
                                        <?php 
                                            echo $this->Js->link('Edit', array('controller'=>'LoanModuleLoanAcquisitionOnActivitiesOrProjects','action'=>'edit', $value['LoanModuleLoanAcquisitionOnActivitiesOrProject']['id'], $value['LoanModuleLoanAcquisitionOnActivitiesOrProject']['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Details', array('controller'=>'LoanModuleLoanAcquisitionOnActivitiesOrProjects','action'=>'preview', $value['LoanModuleLoanAcquisitionOnActivitiesOrProject']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
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
        
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
<!--                    <td>
                        <?php 
//                            echo $this->Js->link('Previous', array('controller'=>'BasicModuleBranchHRInfos','action'=>'view'), 
//                                                    array_merge($pageLoading, array('class'=>'mybtns', 'success'=>'msc.prev();')));
                        ?>
                    </td>-->
                    <td>
                        <?php 
                            echo $this->Js->link('Add New', array('controller'=>'LoanModuleLoanAcquisitionOnActivitiesOrProjects','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
<!--                    <td>
                        <?php 
//                            echo $this->Js->link('Next', array('controller'=>'BasicModuleSisterOrganizationInfos', 'action'=>'view'), 
//                                                    array_merge($pageLoading, array('class'=>'mybtns', 'success'=>'msc.next();')));
                        ?>
                    </td>-->
                    <td></td>   
                </tr>
            </table>
        </div>
        
    </fieldset>
</div> 

<?php } ?>
