<?php 
    
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

    $this->Paginator->options($pageLoading);
?>

<div id="frmTypeOfOrg_view">
    <fieldset>
        <legend>
          Eligible Districts for License 
        </legend>                
        <div class="form"> 
            <table>
                <tr>        
                    <td style="text-align:justify;">
                        <?php                 
                            echo $this->Form->create('LicenseModuleEligibleDistrict');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">                           
                            <tr>
                                <td class="labelTd" style="width:250px;">Name of District</td>
                                <td class="colons">:</td>
                                <td class="inputTd"  ><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width: 275px'));?></td>
                                <td style="text-align: left;" >
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
                        <div id="searching">
                                                       
                            <table class="view" style="font-family:verdana, helvetica, arial;">
                                <tr>        
                                    <th style="width:85px;">Geocode</th>
                                    <th>Name of District</th>                                     
                                    <th style="width:100px;">Action</th>  
                                </tr>
                                <?php foreach($values as $value){ ?>
                                <tr>                                                                        
                                    <td style="text-align: center;"><?php echo $value['LicenseModuleEligibleDistrict']['district_id']; ?></td>
                                    <td style="text-align: left;"><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>        
                                    <td style="text-align:center; padding:2px; height:30px;">
                                       <?php  echo  $this->Js->link('Edit', array('controller'=>'LicenseModuleEligibleDistricts','action'=>'edit', $value['LicenseModuleEligibleDistrict']['id']), 
                                                       array('class'=>'btnlink', 'update'=>'#ajax_div'))
                                                    .$this->Js->link('Delete', array('controller'=>'LicenseModuleEligibleDistricts','action'=>'delete', $value['LicenseModuleEligibleDistrict']['id']), 
                                                       array('class'=>'btnlink', 'update'=>'#ajax_div'))
                                       ?>     
                                    </td>
                                </tr>
                                <?php  } ?>
                            </table>                                
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
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php  echo $this->Js->link('Add New', array('controller'=>'LicenseModuleEligibleDistricts','action'=>'add'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'')));  ?>     
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>        
    </fieldset>
</div>   
<?php
    //echo $this->element('homefooter', array("variable_name"=>"current")); 
?>

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