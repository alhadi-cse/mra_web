<?php 
    //echo $this->element('contentheader', array("variable_name" => "current"));
?> 
<div id="frmBasicInfo_add">
    <?php 
        $title = "Assessment Parameter"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>
     <fieldset style="border:1px solid #9ebfe8">
        <legend>
          <?php echo $title;?>   
        </legend>
        <?php
            echo $this->Form->create('LookupLicenseInitialAssessmentParameter');
        ?>
        <div class="form">            
            <table cellpadding="0" cellspacing="0" border="0">  
                <tr>
                    <td class="labelTd">Serial no.</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('sorting_order',array('type'=>'text','label' => false)); ?></td>
                </tr>
                <tr>
                    <td class="labelTd">Name of Parameter</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('parameter',array('label' => false)); ?></td>
                </tr>   
                <tr>
                    <td class="labelTd">Type of Parameter</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input("parameter_type_id", array('type' => 'radio','options' => $parameterTypeOptions,'legend' => false)); ?></td>
                </tr> 
                <tr>
                    <td colspan="3" id="dynamic_parameter_val">
                        <table cellpadding="0" cellspacing="0" border="0">            
                            <tr>
                                <td class="labelTd">Source Name</td>
                                <td class="colons" style="padding-left: 20px;">:</td>
                                <td class="inputTd" style="padding-left: 3px;"><?php echo $this->Form->input("model_id", array('type' => 'select','options' => $modelNameOptions,'empty'=>'-----Select-----','id'=>'model_names','label' => false)); ?></td>
                            </tr> 
                            <tr>
                                <td class="labelTd">Field Name</td>
                                <td class="colons" style="padding-left: 20px;">:</td>
                                <td class="inputTd" style="padding-left: 3px;"><?php echo $this->Form->input("field_id", array('type' => 'select','options'=>$fieldNameOptions,'empty'=>'-----Select-----','id'=>'field_names','label' => false)); ?></td>
                            </tr>                                               
                            <tr>
                                <td class="labelTd">Operation Types</td>
                                <td class="colons" style="padding-left: 20px;">:</td>
                                <td class="inputTd" style="padding-left: 3px;"><?php echo $this->Form->input("operation_type_id", array('type' => 'select','options'=>$operation_type_options,'empty'=>'-----Select-----','id'=>'operation_types','label' => false)); ?></td>
                            </tr>                            
                        </table>
                    </td>
                </tr> 
                <tr>
                    <td colspan="3" id="group_name_val">
                        <table cellpadding="0" cellspacing="0" border="0">                     
                            <tr>
                                <td class="labelTd">Group Name</td>
                                <td class="colons" style="padding-left: 47px;">:</td>
                                <td class="inputTd" style="padding-left: 1px;"><?php echo $this->Form->input("operation_group_field_id", array('type' => 'select','options'=>$fieldNameOptions,'empty'=>'-----Select-----','id'=>'group_names','label' => false)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="labelTd">Year</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('declaration_year',array('label' => false));?></td>
                </tr>               
            </table>            
        </div> 
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupLicenseInitialAssessmentParameters','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
<?php
$this->Js->get('#model_names')->event('change', 
    $this->Js->request(array(
        'controller'=>'LookupLicenseInitialAssessmentParameters',
        'action'=>'field_select'
        ), array(
        'update'=>'#field_names',            
        'async' => true,
        'method' => 'post',
        'dataExpression'=>true,
        'data'=> $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))  
    );

$this->Js->get('#model_names')->event('change',    
    $this->Js->request(array(
        'controller'=>'LookupLicenseInitialAssessmentParameters',
        'action'=>'field_select'
        ), array(
        'update'=>'#group_names',            
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
<?php if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer(); // Writes cached scripts ?>

<script type="text/javascript">
    $(document).ready(function(){        
        selectedParameterTypeVal = $('input[name$="data[LookupLicenseInitialAssessmentParameter][parameter_type_id]"]:checked').val();
        if(selectedParameterTypeVal=="1"){
            show();
        }
        else{
            hide();
            hide_group(); 
        }

        $('input[name$="data[LookupLicenseInitialAssessmentParameter][parameter_type_id]"]').click(function(){
            if($(this).attr("value")=="1"){
                show();
            }
            else{
                hide();
            }
        });
        
        function hide() {
            $("#dynamic_parameter_val").hide();
            $("#model_names").val("");
            $("#field_names").val("");
        }
        function show() {
            $("#dynamic_parameter_val").show();            
        }
                
        $('#operation_types').change(function(){
        $('#operation_types option:selected').text();            
        if(($(this).val()!="")&&($(this).val()!="1")){
                show_group();
            }
            else{
                hide_group();
            }
        });
        
        function hide_group() {
            $('#group_name_val').hide();  
            $("#operation_types").val("");
            $("#group_names").val("");
        }         
        function show_group() {            
            $('#group_name_val').show();                      
        }
    });    
</script>