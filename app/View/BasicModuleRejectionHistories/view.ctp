<?php 
    $title = "Rejection History";
    
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

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
                    <td style="text-align:justify;" colspan="4">
                        <?php                 
                            echo $this->Form->create('BasicModuleRejectionHistory');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search Option</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option', 
                                                array('label'=>false, 'style'=>'width:200px',
                                                    'options'=>
                                                        array('BasicModuleBasicInformation.full_name_of_org'=>"Organization's Full Name",
                                                            'BasicModuleBasicInformation.short_name_of_org'=>"Organization's Short Name"))
                                                    );
                                    ?>
                                </td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width:250px')); ?></td>
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
                                    echo 'Did not match any Organization !';
                                    echo '</p>';
                                }
                                else{
                            ?>
                            
                            <table class="view">
                                <tr>                                    
                                    <th style="width:185px;">Name of Organization</th>
                                    <th style="width:70px;">First Rejection Date</th> 
                                    <th style="width:70px;">Last Final Rejection Date</th> 
                                    <th style="width:70px;">Rejection Count</th>
                                    <th style="width:150px;">Comment On Last Rejection</th>
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
                                    <td style="text-align:center;"><?php echo $value['BasicModuleRejectionHistory']['firstRejectionDate']; ?></td>
                                    <td style="text-align:center;"><?php echo $value['BasicModuleRejectionHistory']['lastFinalRejectionDate']; ?></td>
                                    <td style="text-align:right;"><?php echo $value['BasicModuleRejectionHistory']['rejectionCount']; ?></td> 
                                    <td><?php echo $value['BasicModuleRejectionHistory']['commentOnLastRejection']; ?></td>
                                    <td style="height: 30px; padding: 2px; text-align: center;">
                                       <?php 
                                            echo $this->Js->link('Edit', array('controller'=>'BasicModuleRejectionHistories','action'=>'edit', $value['BasicModuleRejectionHistory']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink')))
                                                 .$this->Js->link('Details', array('controller'=>'BasicModuleRejectionHistories','action'=>'details', $value['BasicModuleRejectionHistory']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
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
            echo $this->Paginator->prev('<<', array('class'=>'PrevPg')).
                    $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                    $this->Paginator->next('>>', array('class'=>'NextPg'));
          ?> 
        </div>
        <?php        
          }
        ?>
        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                            echo $this->Js->link('Previous', array('controller'=>'BasicModuleBasicInformations','action'=>'view'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns', 'success'=>'msc.prev();')));
                        ?>
                    </td>

                    <td>
                        <?php
                            echo $this->Js->link('Add New', array('controller'=>'BasicModuleRejectionHistories','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>

                    <td>
                        <?php 
                            echo $this->Js->link('Next', array('controller'=>'BasicModuleAddresses', 'action'=>'view'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns', 'success'=>'msc.next();')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>

<!--        <p style="margin:5px 0; padding:5px;"> 
           <?php  //echo $this->Js->link('Add New', array('controller'=>'','action'=>'add'), array('update'=>'#ajax_div', 'class'=>'mybtns'));  ?>
        </p>-->
        
    </fieldset>
</div>   
<?php
    //echo $this->element('homefooter', array("variable_name"=>"current")); 
?>