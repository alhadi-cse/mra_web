<?php 
    //echo $this->element('contentheader', array("variable_name"=>"current"));
    //debug($mfiDetailsList);
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
                                       echo $this->Js->submit('Search', array(
                                        'before'=>$this->Js->get('#searching')->effect('fadeIn'),
                                        'success'=>$this->Js->get('#searching')->effect('fadeOut'),
                                        'update'=>'#ajax_div'
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
                            if($mfiDetailsList!=null)
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
                                            <?php  echo $this->Js->link('Show Report', array('action'=>'report', $mfiDetails['ViewReportBasicModuleBasicInfo']['id']),  
                                                            array('class'=>'modalPreview btnlink', 'update'=>'#popup_div', 'onclick'=>'$("#popup_div").empty();'))
                                            ?>     
                                        </td>
                                    </tr>
                                  <?php  } ?>
                                </table> 
                            </div>
                        <?php
                            }              
                        ?>                            
                    </td>                
                </tr>
            </table>
        </div>
        
        <div id="popup_div" style="display:none;">
            
        </div>
        
        <?php
            if($mfiDetailsList!=null && $this->Paginator->param('pageCount')>1)
            {                    
        ?>
        <div class="paging">
          <?php            
            echo $this->Paginator->prev(
            '<< Previous',
            array(
              'class'=>'PrevPg'
            ),
            null,
            array(
              'class'=>'PrevPg DisabledPgLk'
            )
          ).
          $this->Paginator->numbers(
            array(
              'class'=>'numbers'
            )
          ).
          $this->Paginator->next(
            'Next >>',
            array(
              'class'=>'NextPg'
            ),
            null,
            array(
              'class'=>'NextPg DisabledPgLk'
            )
          );
          ?>
        </div>
        <?php
            }                    
        ?>
    </fieldset>
</div>   

<script>    
    $(document).ready(function(){
        $(".paging a").click(function(){
            $("#ajax_div").load(this.href);
            return false;
        });
    });   
</script>
<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer();
    // Writes cached scripts
?>