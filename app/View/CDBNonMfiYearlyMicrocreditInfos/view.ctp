<?php 
    //echo $this->element('contentheader', array("variable_name"=>"current"));
    $title = 'Yearly Microcredit Information of Non-MFI';
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 
            'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
            'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));

    //$this->Paginator->options(array_merge($pageLoading, array('update'=>'#data_div')));
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
                            echo $this->Form->create('CDBNonMfiYearlyMicrocreditInfo');
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0">                           
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search by</td>
                                <td>
                                    <?php 
                                        echo $this->Form->input('search_option', 
                                                array('label'=>false, 'style'=>'width:215px',
                                                    'options'=>
                                                        array('CDBNonMfiBasicInfo.name_of_org'=>'Name of Organization',
                                                            'CDBNonMfiBasicInfo.ministry_or_authority'=>'Ministry/Authority',
                                                            'CDBNonMfiBasicInfo.registration_no'=>'Registration No.')
                                                        ));
                                    ?>
                                </td>
                                <td style="font-weight:bold;">:</td>
                                <td><?php echo $this->Form->input('search_keyword',array('label'=>false,'style'=>'width:250px')); ?></td>
                                <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch'))); ?></td>
                                <td>
                                    <?php 
                                        //if(!empty($org_id) && !empty($user_group_id) && $user_group_id==1){
                                        if(!empty($opt_all) && $opt_all && $isAdmin ) {
                                            echo $this->Js->link('View All', array('controller'=>'CDBNonMfiYearlyMicrocreditInfos', 'action'=>'view', 'all'), 
                                                        array_merge($pageLoading, array('class'=>'mybtns sbtns')));
                                        }
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
                            <table class="view" style="width:780px;font-family:verdana, helvetica, arial;">
                                <tr>        
                                    <th style="width:185px;"><?php echo $this->Paginator->sort('CDBNonMfiBasicInfo.name_of_org','Name of Organization') ?></th>                                    
                                    <th style="width:120px;"><?php echo $this->Paginator->sort('CDBNonMfiYearlyMicrocreditInfo.year_and_month','Month & Year') ?></th> 
                                    <th style="width:80px;"><?php echo $this->Paginator->sort('CDBNonMfiYearlyMicrocreditInfo.maleClient','No. of Male Client') ?></th> 
                                    <th style="width:80px;"><?php echo $this->Paginator->sort('CDBNonMfiYearlyMicrocreditInfo.femaleClient','No. of Female Client') ?></th>
                                    <th style="width:120px;">Action</th>  
                                </tr>
                                <?php foreach($values as $value){ ?>
                                <tr>
                                    <td><?php echo $value['CDBNonMfiBasicInfo']['name_of_org']; ?></td> 
                                    <td><?php echo $this->Time->format($value['CDBNonMfiYearlyMicrocreditInfo']['year_and_month'], '%B, %Y', ''); ?></td>
                                    <td><?php echo $value['CDBNonMfiYearlyMicrocreditInfo']['maleClient']; ?></td>
                                    <td><?php echo $value['CDBNonMfiYearlyMicrocreditInfo']['femaleClient']; ?></td>        
                                    <td style="text-align:center; padding:2px; height:30px;">                             
                                        <?php 
                                            echo $this->Js->link('Edit', array('controller' => 'CDBNonMfiYearlyMicrocreditInfos','action' => 'edit', $value['CDBNonMfiBasicInfo']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Details', array('controller' => 'CDBNonMfiYearlyMicrocreditInfos','action' => 'details', $value['CDBNonMfiBasicInfo']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink')));
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
        <div id="popup_div" style="display:none;">
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
                            echo $this->Js->link('Add New', array('controller'=>'CDBNonMfiYearlyMicrocreditInfos','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td>                        
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
