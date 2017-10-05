
<?php 
        $title = "Eligible Districts for License"; 
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
                    <td style="text-align:justify;">
                        <?php                 
                            echo $this->Form->create('LookupEligibleDistrict');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search By</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option', 
                                                array('label' => false, 'style'=>'width:200px',
                                                    'options' => array('LookupEligibleDistrict.district_id' => 'Geocode',
                                                            'LookupAdminBoundaryDistrict.district_name' => 'Name of District'))
                                                    );
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
                        <?php  echo $this->Form->end(); ?> 
                    </td>        
                </tr>
                <tr>
                    <td>
                        <div id="searching">
                                                       
                            <table class="view" style="font-family:verdana, helvetica, arial;">
                                <tr>
                                    <?php
                                        echo "<th style='min-width:50px;'>" . $this->Paginator->sort('LookupEligibleDistrict.district_id', 'Geocode') . "</th>";
                                        echo "<th style='width:450px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name.', 'Name of District') . "</th>";                                        
                                        echo "<th style='width:120px;'>Action</th>";
                                    ?>                                    
                                </tr>
                                <?php foreach($values as $value){ ?>
                                <tr>                                                                        
                                    <td style="text-align: center;"><?php echo $value['LookupEligibleDistrict']['district_id']; ?></td>
                                    <td style="text-align: left;"><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>        
                                    <td style="text-align:center; padding:2px; height:30px;">
                                        <?php 
                                            echo $this->Js->link('Edit', array('controller' => 'LookupEligibleDistricts','action' => 'edit', $value['LookupEligibleDistrict']['id']), array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Delete', array('controller' => 'LookupEligibleDistricts','action' => 'delete', $value['LookupEligibleDistrict']['id']), 
                                                    array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');",'class'=>'btnlink')));
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
        
        
        <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
            <?php 
            
            if($this->Paginator->param('pageCount')>5)
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
//            echo $paginator->last('Last', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
          ?>
        </div>
        <?php } ?>
        <div class="btns-div">  
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php  echo $this->Js->link('Add New', array('controller' => 'LookupEligibleDistricts','action' => 'add'), array('update' => '#ajax_div', 'class'=>'mybtns'));  ?>     
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>    
    </fieldset>
</div>   
<?php
    //echo $this->element('homefooter', array("variable_name" => "current")); 
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