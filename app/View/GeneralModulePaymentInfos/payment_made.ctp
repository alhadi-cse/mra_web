
<div>
    <?php
        if (!empty($payment_type_id)) {
            if ($payment_type_id == 1) {
                $title = "License Fee Payment Information";
            } else if ($payment_type_id == 2) {
                $title = "Annual Fee Payment Information";
            } else if ($payment_type_id == 3) {
                $title = "Renewal Fee Payment Information";
            } else if ($payment_type_id == 4) {
                $title = "Others Type of Payment Information";
            }
        }
        else {
            $title = "Payment Information";
        }
        
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
    ?>
    
     <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php
            echo $this->Form->create('GeneralModulePaymentInfo');
        ?>
        <div class="form">
            <table cellpadding="8" cellspacing="8" border="0">
                <tr style="width:170px;">
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                        if (!empty($org_id)) {
                            echo "<strong>$orgNameOptions[$org_id]</strong>" 
                                    . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false, 'div' => false));
                        } else {
                            echo $this->Form->input('org_id', array('type'=>'select','options'=>$orgNameOptions,'empty' => '-----Select-----', 'label' => false, 'div' => false));
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                        if (!empty($payment_type_id)) {
                            echo "<strong>$paymentTypeOptions[$payment_type_id]</strong>" 
                                    . $this->Form->input('payment_type_id', array('type' => 'hidden', 'value' => $payment_type_id, 'label' => false, 'div' => false));
                        } else {
                            echo $this->Form->input('payment_type_id', array('type' => 'select', 'options' => $paymentTypeOptions, 'id' => 'payment_type', 'empty' => '-----Select-----', 'label' => false));
                        }
                        ?>
                    </td>
                </tr>
                <tr id="if_select_other">
                    <td>Other Types/Reasons</td>
                    <td class="colons">:</td>                                
                    <td><?php echo $this->Form->input('payment_reason', array('label' => false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td>Payment Amount (BDT.)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_amount', array('type'=>'text', 'class' => 'decimals', 'style' => 'width:100px; text-align:right;', 'label' => false, 'label' => false, 'div' => false)) . " Tk."; ?></td>
                </tr>
                <tr>
                    <td>Delay Fine (if applicable)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('delay_fine_amount', array('type'=>'text', 'class' => 'decimals', 'style' => 'width:100px; text-align:right;', 'label' => false, 'div' => false)) . " Tk."; ?></td>
                </tr>                                 
                <tr>
                    <td>Date Of Payment</td>
                    <td class="colons">:</td>
                    <td>
                        <?php 
                        echo $this->Time->format(new DateTime('now'), '%d-%m-%Y', '');
                        echo $this->Form->input('date_of_payment', array('type' => 'hidden', 'value' => date("Y-m-d"), 'label' => false, 'div' => false));
                        ?>
                    </td>                    
                </tr>                
                <tr>
                    <td>Payment Doc Number</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_document_no', array('label' => false, 'div' => false)); ?></td>
                </tr>          
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>                        
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'view', $payment_type_id), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php                        
                            echo $this->Js->submit('Done', array_merge($pageLoading, array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                      'error'=>"msg.init('error', '$title', '$title has been failed to add !');")));                       
                        ?>
                    </td>
                </tr>
            </table>
        </div>             
        <?php echo $this->Form->end(); ?>
     </fieldset>
</div>
<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
</script>
<script type="text/javascript">
    $(document).ready(function(){
        hide();
        $('#payment_type').change(function(){
        $('#payment_type option:selected').text();            
        if($(this).val()=="4"){
                show();
            }
            else{
                hide();                
            }
        });
        
        function hide() {            
            $("#if_select_other").hide();            
        }
        function show() {
            $("#if_select_other").show();           
        }        
    });    
</script>
