

<?php 

    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    
    $title = 'Payment Details';
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
    
?>

<div>    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            
        <?php if(!empty($data_count)) { ?>
            <?php if($data_count==1) { ?>
            <table cellpadding="7" cellspacing="8" border="0" style="width:100%;">
                <tr>
                    <td style="min-width:170px">Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="min-width:375px">
                        <span style="float:left; max-width:87%; margin:3px 0;">
                            <?php echo $allDataDetails['BasicModuleBasicInformation']['full_name_of_org']; ?>
                        </span>
                        <span style="float:right;">
                            <?php 
                                
                                echo $this->Js->link('Edit', array('controller'=>'BasicModulePaymentInfos', 
                                                       'action'=>'edit', $allDataDetails['BasicModulePaymentInfo']['id'], $allDataDetails['BasicModulePaymentInfo']['org_id'], 2), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'margin:0; padding:4px;')));
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupPaymentType']['payment_type']; ?></td>
                </tr>
                <tr>
                    <td>Payment No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModulePaymentInfo']['payment_no']; ?></td>
                </tr>
                <tr>
                    <td>Payment Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModulePaymentInfo']['paymentAmount']; ?></td>
                </tr>
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($allDataDetails['BasicModulePaymentInfo']['dateOfPayment'],'%d-%m-%Y',''); ?></td>
                </tr>                
                <tr>
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModulePaymentInfo']['paymentDocNumber']; ?></td>
                </tr>
            </table> 
            
            <?php } else if($data_count=='all') { ?>
            
            <div style="max-width:780px; overflow-x:auto;">
                <table cellpadding="7" cellspacing="8" border="0" class="view">
                    <tr>
                        <th style="width:170px;">Payment Type</th>
                        <th style="width:150px;">Payment No.</th>
                        <th style="width:150px;">Payment Amount</th>
                        <th style="width:150px;">Date Of Payment</th>
                        <th style="width:150px;">Payment Doc Number</th>
                    </tr>

                    <?php 
                        $rc=0;
                        foreach($allDataDetails as $paymentDetails){ 
                        $rc++;
                    ?>

                    <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                        <td><?php echo $paymentDetails['LookupPaymentType']['payment_type']; ?></td>
                        <td><?php echo $paymentDetails['BasicModulePaymentInfo']['payment_no']; ?></td>
                        <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentAmount']; ?></td>
                        <td><?php echo $this->Time->format($paymentDetails['BasicModulePaymentInfo']['dateOfPayment'],'%d-%m-%Y',''); ?></td>
                        <td><?php echo $paymentDetails['BasicModulePaymentInfo']['paymentDocNumber']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>
        <?php        
            }
            else {
                echo '<p class="error-message">';
                echo 'Did not find any data !';
                echo '</p>';
            }
        ?>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                            echo $this->Js->link('Previous', array('controller'=>'BasicModuleRenewableSecurities','action'=>'details'),
                                                                    array_merge($pageLoading, array('success'=>'msc.prev();')));
                        ?>
                    </td>

                    <td>
                        <?php 
                            if(empty($data_count) || $data_count === 0) {
                                echo $this->Js->link('Add New', array('controller'=>'BasicModulePaymentInfos', 'action'=>'add'), $pageLoading);
                            }
                            else {
                                echo $this->Js->link('Close', array('controller'=>'BasicModulePaymentInfos','action'=>'view'), $pageLoading);
                            }
                        ?>
                    </td>

                    <td>
                        <?php
                            echo $this->Js->link('Next', array('controller'=>'BasicModuleTransactionInfos', 'action'=>'details'), 
                                                                array_merge($pageLoading, array('success'=>'msc.next();')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
        
    </fieldset>
</div>

