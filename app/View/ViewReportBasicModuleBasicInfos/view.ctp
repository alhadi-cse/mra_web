<?php 
    //echo $this->element('contentheader', array("variable_name"=>"current"));
    //debug($mfiDetailsList);

//    
//    $this->Paginator->options(array('update'=>'#ajax_div', 'evalScripts'=>true, 
//        'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
//        'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false))));

?>

<?php          
    $this->Paginator->options(array('update'=>'#ajax_div', 'evalScripts'=>true, 
        'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
        'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false))));
?>

<div id="frmTypeOfOrg_view">
    <fieldset>
        <legend>
          MFI Basic Information Report
        </legend>                
        <div> 
            <table>
                <tr>        
                    <td>
                        <?php                 
                            echo $this->Form->create('ViewReportBasicModuleBasicInfo');
                        ?>
                        
                        <table cellpadding="0" cellspacing="0" border="0">                           
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search Option</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option', 
                                                array('label'=>false, 'style'=>'width:200px',
                                                    'options'=>
                                                        array('full_name_of_org'=>"Organization's Full Name",
                                                            'short_name_of_org'=>"Organization's Short Name")
                                                        )
                                                    );
                                    ?>
                                </td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width:250px')); ?></td>
                                <td style="text-align:left;">
                                   <?php
                                       echo $this->Js->submit('Search', array('update'=>'#ajax_div', 'evalScripts'=>true, 
                                                                              'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                                                                              'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false))
                                                                               ));
                                    ?>
                               </td>                                
                            </tr>
                        </table>
                        <?php  echo $this->Form->end(); ?> 
                    </td>        
                </tr>
                <tr>
                    <td>
                        <?php
                            if($mfiDetailsList!=null && count($mfiDetailsList)>0)
                            {                    
                        ?>
                            <div id="searching">             
                                <table class="view" style="font-family:verdana, helvetica, arial;">
                                    <tr>        
                                        <th style="width:230px;">Name of Organization</th>
                                        <th style="width:100px;">Short Name</th>
                                        <th style="width:100px;">Action</th>
                                    </tr>
                                    
                                    <?php foreach($mfiDetailsList as $mfiDetails){ ?>
                                    <tr>
                                        <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['full_name_of_org']; ?></td>
                                        <td><?php echo $mfiDetails['ViewReportBasicModuleBasicInfo']['short_name_of_org'] ?></td>
                                        
                                        <td style="text-align:center; padding:2px; height:30px;">
                                            <?php echo $this->Js->link('Show Report', array('action'=>'report', $mfiDetails['ViewReportBasicModuleBasicInfo']['id']),  
                                                            array('class'=>'btnlink', 'update'=>'#popup_div', 'onclick'=>'$("#popup_div").empty();', 'evalScripts'=>true, 
                                                                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                                                                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false))));
                                            ?>     
                                        </td>
                                    </tr>
                                  <?php  } ?>
                                </table> 
                            </div>
                        <?php
                            }
                            else 
                            {
                                echo '<p class="error-message">Did not match any Organization !</p>';
                            }
                        ?>                            
                    </td>                
                </tr>
            </table>
        </div>
        
        <div id="popup_div" style="display:none;">            
        </div>
        
        <?php          
          if($mfiDetailsList!=null && $this->Paginator->param('pageCount')>1){
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
        
    </fieldset>
</div>   
<?php
    //echo $this->element('homefooter', array("variable_name"=>"current")); 
?>
