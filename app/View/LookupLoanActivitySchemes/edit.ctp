<div id="frmStatus_add">
    <?php 
        $title = "Loan Activity Scheme Information";     
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend> 
        
        <?php  echo $this->Form->create('LookupLoanActivityScheme'); ?>
        <div class="form">           
            <table cellpadding="0" cellspacing="0" border="0"> 
                <tr>
                    <td>Category</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('loan_activity_category_id',array('type'=>'select','options'=>$loan_activity_category_options,'id'=>'loan_activity_categories','empty' => '-----Select-----', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Sub-Category</td>
                    <td class="colons">:</td>                    
                    <td><?php echo $this->Form->input('loan_activity_subcategory_id',array('type'=>'select','options'=>$loan_activity_subcategory_options, 'id'=>'subcategories','empty' => '-----Select-----', 'label' => false)); ?></td>
                </tr> 
                <tr>
                    <td>Serial no</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('serial_no',array('type'=>'text','class' => 'integers','label' => false)); ?></td>
                </tr>                 
                <tr>
                    <td>Scheme</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('loan_activity_scheme',array('type'=>'text','label' => false)); ?></td>
                </tr>
            </table>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupLoanActivitySchemes','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been updated successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Update failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
     <?php  echo $this->Form->end(); ?>
    </fieldset>    
</div>
<script>
    
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
</script>

<?php
$this->Js->get('#loan_activity_categories')->event('change', 
    $this->Js->request(array(
        'controller'=>'LookupLoanActivitySchemes',
        'action'=>'subcategory_select'
        ), array(
        'update'=>'#subcategories',            
        'async' => true,
        'method' => 'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
    );  
?>
<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer();
    // Writes cached scripts
?>