<?php 
    //echo $this->element('contentheader', array("variable_name" => "current"));
?> 
<div id="frmBasicInfo_add">
    <?php 
        $title = "Assessment Parameter Options/Criteria";   
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
                echo $this->Form->create('LookupLicenseInitialAssessmentParameterOption');
            ?>            
        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0" id="tableForm">            
                <tr>
                    <td class="labelTd">Name of Parameter</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('parameter_id',array('type'=>'select','options'=>$parameterOptions,'empty' => '-----Select-----','id'=>'parameter_names','label' => false)); ?></td>
                </tr> 
                <tr>
                    <td class="labelTd" id="parameter_type_title">Type of Parameter</td>
                    <td class="colons" id="parameter_type_colon">:</td>
                    <td class="inputTd" id="parameter_types"><?php echo "&nbsp;&nbsp;".$parameter_type;?></td>
                </tr>
                <tr>
                    <td class="labelTd">Option</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('parameter_option',array('type'=>'text','label' => false));?></td>
                </tr> 
                <tr>
                    <td colspan="3" id="max_min_val">
                        <table cellpadding="0" cellspacing="0" border="0">            
                            <tr>
                                <td class="labelTd">Maximum Value</td>
                                <td class="colons" style="padding-left: 30px;">:</td>
                                <td class="inputTd" style="padding-left: 0px;"><?php echo $this->Form->input('maximum_value',array('type'=>'text','label' => false,'id'=>'max_val', 'class'=>'decimals'));?></td>
                            </tr> 
                            <tr>
                                <td class="labelTd">Minimum Value</td>
                                <td class="colons" style="padding-left: 30px;">:</td>
                                <td class="inputTd" style="padding-left: 0px;"><?php echo $this->Form->input('minimum_value',array('type'=>'text','label' => false,'id'=>'min_val', 'class'=>'decimals'));?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="labelTd">Marks</td>
                    <td class="colons">:</td>
                    <td class="inputTd"><?php echo $this->Form->input('assessment_marks',array('type'=>'text','label' => false, 'class'=>'decimals'));?></td>
                </tr>         
            </table>            
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LookupLicenseInitialAssessmentParameterOptions','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
$this->Js->get('#parameter_names')->event('change', 
    $this->Js->request(array(
        'controller'=>'LookupLicenseInitialAssessmentParameterOptions',
        'action'=>'parameter_type_select'
        ), array(
        'update'=>'#parameter_types',            
        'async' => false,
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
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
             
        selectedVal = $( "#parameter_names option:selected" ).val();
        if(selectedVal==""){
            hide();  
            hide_max_min();
        }
        else{
            show();   
        }
         
        $('#parameter_names').change(function(){            
             if($(this).val()==""){
               hide(); 
               hide_max_min();
            }
            else{               
                $('#tableForm tr').each(function (){
                    var str_parameter_type = $(this).find('#parameter_types').html();
                    if(str_parameter_type != null)
                    {                        
                        if(str_parameter_type.contains("Dynamic")){                                      
                            show_max_min(); 
                        }
                        else{                            
                            hide_max_min();
                        }
                    }
                });
                show();               
            }
          });           
                
        function hide() {
            $("#parameter_type_title").hide();
            $("#parameter_type_colon").hide();            
        }         
        function show() {
            $("#parameter_type_title").show();
            $("#parameter_type_colon").show();            
        }
        
        function hide_max_min() {
            $("#max_min_val").hide();
            $("#max_val").val("");
            $("#min_val").val("");            
        }         
        function show_max_min() {            
            $("#max_min_val").show();                      
        }
    });    
</script>