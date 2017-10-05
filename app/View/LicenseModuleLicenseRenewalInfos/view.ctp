<?php
    $title = 'License Renewal Information';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div id="frmTypeOfOrg_view">
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend>                
        <div class="form"> 
            <table>
                <tr>
                    <td>
                        <?php                                                        
                            echo $this->Form->create('LicenseModuleLicenseRenewalInfo');
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
                                                            'LicenseModuleLicenseRenewalInfo.renewal_date' => 'Date of Renewal')
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
                                    echo 'No data is available!';
                                    echo '</p>';
                                }
                                else{
                            ?>
                            
                            <table class="view">
                                <tr>
                                    <?php 
                                    if(!$this->Paginator->param('options'))
                                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                                    else 
                                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleLicenseRenewalInfo.renewal_date', 'Date of Renewal') . "</th>";
                                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LicenseModuleLicenseRenewalInfo.comment', 'Comments') . "</th>";
                                        echo "<th style='width:100px;'>Action</th>";
                                    ?>
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
                                    <td><?php echo $value['LicenseModuleLicenseRenewalInfo']['renewal_date']; ?></td>
                                    <td><?php echo $value['LicenseModuleLicenseRenewalInfo']['comment']; ?></td>
                                    <td style="text-align:center; padding:2px; height:30px;">
                                       <?php 
                                            echo $this->Js->link('Edit', array('controller' => 'LicenseModuleLicenseRenewalInfos','action' => 'edit', $value['LicenseModuleLicenseRenewalInfo']['id']), 
                                                                            array_merge($pageLoading, array('class'=>'btnlink')))
                                                    .$this->Js->link('Details', array('controller' => 'LicenseModuleLicenseRenewalInfos','action' => 'details', $value['LicenseModuleLicenseRenewalInfo']['id']), 
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
        </div>
        
        <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
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
        
        <div style="border-top:2px solid #05a5de; width:auto; height:auto; margin-top:10px; padding:8px 0 10px 0; background-color:#f0f5f8;">                
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>                    
                    <td>
                        <?php                             
                            echo $this->Js->link('Add New', array('controller' => 'LicenseModuleLicenseRenewalInfos','action' => 'add'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'Add a new information')));                            
                        ?>
                    </td>                      
                </tr>
            </table>
        </div>        
    </fieldset>
</div>
