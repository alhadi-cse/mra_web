
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
        
        $title = "Payment Information";
        $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
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
                        <?php echo $this->Form->create('BasicModulePaymentInfo'); ?>
                        <table cellpadding="0" cellspacing="0" border="0">                           
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search by</td>
                                <td>
                                    <?php                                     
                                        $options = array('LookupPaymentType.payment_type'=>'Payment Type', 
                                                         'BasicModulePaymentInfo.paymentDocNumber'=>'Document No.');
                                        
                                        if(empty($org_id))
                                        {
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
                                            echo $this->Js->link('View All', array('controller'=>'BasicModulePaymentInfos', 'action'=>'view', 'all'), 
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
                                else{
                            ?>
                            
                            <table class="view">
                                <tr>
                                <?php                                         
                                    if(!$this->Paginator->param('options'))
                                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                                    else 
                                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                                                                
                                    echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModulePaymentInfo.fiscalYear', 'Payment Type') . "</th>";
                                    echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModulePaymentInfo.purchasingDate', 'Payment Document No.') . "</th>";
                                    echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModulePaymentInfo.institutionsName', 'Payment Amount') . "</th>";
                                    echo "<th style='width:115px;'>Action</th>";
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
                                    
                                    <td><?php echo $value['LookupPaymentType']['payment_type']; ?></td>
                                    <td style="text-align: center;"><?php echo $value['BasicModulePaymentInfo']['paymentDocNumber']; ?></td>                                     
                                    <td style="text-align: right;"><?php echo $value['BasicModulePaymentInfo']['paymentAmount']; ?></td>
                                    <td style="height:30px; padding: 2px; text-align: center;"> 
                                        <?php 
                                            echo $this->Js->link('Edit', array('controller'=>'BasicModulePaymentInfos','action'=>'edit', $value['BasicModulePaymentInfo']['id'], $value['BasicModulePaymentInfo']['org_id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink')))
                                                .$this->Js->link('Details', array('controller'=>'BasicModulePaymentInfos','action'=>'preview', $value['BasicModulePaymentInfo']['id']), 
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
                    <td>
                        <?php 
                            echo $this->Js->link('Previous', array('controller'=>'BasicModuleRenewableSecurities','action'=>'view'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns', 'success'=>'msc.prev();')));
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Js->link('Add New', array('controller'=>'BasicModulePaymentInfos','action'=>'add'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $this->Js->link('Next', array('controller'=>'BasicModuleTransactionInfos', 'action'=>'view'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns', 'success'=>'msc.next();')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
    </fieldset>
</div> 

<?php } ?>
