<div id="frmTypeOfOrg_view">
    <?php 
        $title = "Savings Specification";
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>         
        <div class="form"> 
            <table >
                <tr>        
                    <td>
                        <?php echo $this->Form->create('LookupSavingsSpecification'); ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search By</td>
                                <td>
                                    <?php
                                        echo $this->Form->input('search_option', 
                                                array('label' => false, 'style'=>'width:200px',
                                                    'options' => array('LookupSavingsSpecification.name_of_scheme' => 'Savings Specification',
                                                                        'LookupTypeOfSavingsInstallment.type_of_installment' => 'Type of Installment',
                                                                        'LookupSavingsSpecification.rate_of_interest' => 'Rate of Interest'))
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
                        <div id="searching" style="width:700px;">              
                            <table class="view">
                                <tr>
                                    <?php 
                                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LookupSavingsSpecification.serial_no', 'Serial no.') . "</th>";
                                        echo "<th style='width:170px;'>" . $this->Paginator->sort('LookupSavingsSpecification.name_of_scheme', 'Savings Specification') . "</th>";                                        
                                        echo "<th style='width:130px;'>" . $this->Paginator->sort('LookupTypeOfSavingsInstallment.type_of_installment', 'Type of Installment') . "</th>";                                        
                                        echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupSavingsSpecification.rate_of_interest', 'Rate of Interest') . "</th>";
                                        echo "<th style='width:120px;'>Action</th>";
                                    ?>
                                </tr>
                               <?php foreach($values as $value){ ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $value['LookupSavingsSpecification']['serial_no']; ?></td>
                                    <td style="text-align: justify;"><?php echo $value['LookupSavingsSpecification']['name_of_scheme']; ?></td>
                                    <td style="text-align: justify;"><?php echo $value['LookupTypeOfSavingsInstallment']['type_of_installment']; ?></td>
                                    <td style="text-align: right;"><?php echo $value['LookupSavingsSpecification']['rate_of_interest']; ?></td>
                                    <td style="text-align: center;">                                  
                                        <?php 
                                            echo $this->Js->link('Edit', array('controller' => 'LookupSavingsSpecifications','action' => 'edit', $value['LookupSavingsSpecification']['id']), array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Delete', array('controller' => 'LookupSavingsSpecifications','action' => 'delete', $value['LookupSavingsSpecification']['id']), 
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
          ?>
        </div>
        <?php } ?>
        <div class="btns-div">  
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php  echo $this->Js->link('Add New', array('controller'=>'LookupSavingsSpecifications','action'=>'add'), array_merge($pageLoading, array('class'=>'mybtns', 'title'=>'Add a new Savings Specification'))); ?>     
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>        
    </fieldset>
</div>  

<script>    
    $(document).ready(function(){
        $(".paging a").click(function(){
            $("#ajax_div").load(this.href);
            return false;
        })        
    });     
</script>