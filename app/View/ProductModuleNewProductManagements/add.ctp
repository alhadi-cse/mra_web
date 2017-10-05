<div id="frmStatus_add">
    <?php
        if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
        $title = "Add Other Activities";
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
        $model_options = $this->Session->read('LookupModelDefinition.ModelOptions');
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend>
        <?php  echo $this->Form->create('ProductModuleNewProductManagement'); ?>
        <div class="form">
            <table cellpadding="5" cellspacing="5" border="0">
                <tr>
                    <td colspan="3" style="width: 750px;">
                        <fieldset>
                            <legend>
                                Table Information
                            </legend>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>Module Name</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('module_id', array('type'=>'select', 'options'=>$module_options, 'label'=>false,'style'=>'width:255px;', 'empty'=>'---Select---')); ?></td>
                                </tr>
                                <tr>
                                    <td>Table Type</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('lookup_or_detail_id', array('type'=>'radio', 'options'=>array('1'=>'Lookup','2'=>'Details'), 'label'=>false, 'legend'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Table Name</td>
                                    <td class="colons">:</td>
                                    <td><?php echo $this->Form->input('model_wise_table_name',array('label'=>false,'style'=>'width:250px;')); ?></td>
                                </tr>
                                <tr>
                                    <td>Table Description</td>
                                    <td class="colons">:</td>
                                    <td ><?php echo $this->Form->input('model_description',array('label'=>false,'style'=>'width:250px;')); ?></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top:5px;">
                        <a id="add_button" href="#" class="mybtns">Add New Field</a>
                        <fieldset>
                            <legend>
                                <?php echo 'Field Information&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
                            </legend>
                            <div style="max-height:250px; min-height: 0px; overflow-y: scroll;">
                                <table id='my_table'></table>
                            </div>                            
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                            echo $this->Js->link('Close', array('controller' => 'ProductModuleNewProductManagements','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Save', array_merge($pageLoading,
                                                    array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
     <?php  echo $this->Form->end();?>
    </fieldset>
</div>

<script type="text/javascript">     
    $(document).ready(function () {
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
    
    $(function(){
        var counter = 0;        
        $('#add_button').click(function(event){
            event.preventDefault();
            var field_sorting_order = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_sorting_order', array('type'=>'text','class'=>'integers','label'=>false))); ?>;
            field_sorting_order = field_sorting_order.replace(/replace_with_counter/g,counter);
    
            var field_name = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_name', array('type'=>'text','label'=>false))); ?>;
            field_name = field_name.replace(/replace_with_counter/g,counter);
    
            var field_description = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_description', array('type'=>'text','label'=>false))); ?>;
            field_description = field_description.replace(/replace_with_counter/g,counter);
    
            var data_type = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.data_type',array('type'=>'select','options'=>array('int'=>'Integer','varchar'=>'Text','double'=>'Double','date'=>'Short Date','datetime'=>'Date with time'),'empty'=>'---Select---', 'label'=>false))); ?>;
            data_type = data_type.replace(/replace_with_counter/g,counter);            
    
            var field_size = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.field_size', array('type'=>'text','class'=>'integers','label'=>false))); ?>;
            field_size = field_size.replace(/replace_with_counter/g,counter);
    
            var is_primary_key = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_primary_key', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_primary_key = is_primary_key.replace(/replace_with_counter/g,counter);
    
            var is_auto_increment = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_auto_increment', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_auto_increment = is_auto_increment.replace(/replace_with_counter/g,counter);
            
            //is_exists_validity
            var is_exists_validity = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_exists_validity', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_exists_validity = is_exists_validity.replace(/replace_with_counter/g,counter);
    
            var is_mandatory = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_mandatory', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_mandatory = is_mandatory.replace(/replace_with_counter/g,counter);
    
            var control_type_for_add = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.control_type_for_add', array('type'=>'select', 'options'=>array('text'=>'Text Box','textarea'=>'Text Area','select'=>'Dropdown List','select_or_label'=>'Both Dropdown List and Caption','radio'=>'Radio Button','checkbox'=>'Check Box','date'=>'Date','year_month'=>'Year & Month','hidden'=>"Hidden/Don't Display"), 'label'=>false, 'empty'=>'---Select---'))); ?>;
            control_type_for_add = control_type_for_add.replace(/replace_with_counter/g,counter);
            
            var select_option_sources = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.select_option_sources', array('type'=>'select', 'options'=>$model_options, 'label'=>false, 'empty'=>'---Select---'))); ?>;
            select_option_sources = select_option_sources.replace(/replace_with_counter/g,counter);
             
            //dropdown_display_field
            var dropdown_display_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dropdown_display_field', array('type'=>'text','label'=>false))); ?>;
            dropdown_display_field = dropdown_display_field.replace(/replace_with_counter/g,counter);
            
            //dropdown_value_field
            var dropdown_value_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dropdown_value_field', array('type'=>'text','label'=>false))); ?>;
            dropdown_value_field = dropdown_value_field.replace(/replace_with_counter/g,counter);
            
            //dropdown_condition_field
            var dropdown_condition_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dropdown_condition_field', array('type'=>'text','label'=>false))); ?>;
            dropdown_condition_field = dropdown_condition_field.replace(/replace_with_counter/g,counter);
            
            //containable_model_names            
            var containable_model_names = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.containable_model_names', array('type'=>'text','label'=>false))); ?>;
            containable_model_names = containable_model_names.replace(/replace_with_counter/g,counter);
             
            //parent_model_name_for_select_option
            var parent_model_name_for_select_option = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.parent_model_name_for_select_option', array('type'=>'select', 'options'=>$model_options, 'label'=>false, 'empty'=>'---Select---'))); ?>;
            parent_model_name_for_select_option = parent_model_name_for_select_option.replace(/replace_with_counter/g,counter);
             
            //dependent_dropdown_display_field
            var dependent_dropdown_display_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dependent_dropdown_display_field', array('type'=>'text','label'=>false))); ?>;
            dependent_dropdown_display_field = dependent_dropdown_display_field.replace(/replace_with_counter/g,counter);
                        
            //dependent_dropdown_value_field
            var dependent_dropdown_value_field = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.dependent_dropdown_value_field', array('type'=>'text','label'=>false))); ?>;
            dependent_dropdown_value_field = dependent_dropdown_value_field.replace(/replace_with_counter/g,counter);
            
            //associated_field_name_to_show
            
            //associated_model_name
                                   
            
            var css_class_name = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.css_class_name', array('type'=>'select', 'options'=>array('integers'=>'Integer','decimals'=>'Decimal','text'=>'Text','date'=>'Date'), 'label'=>false, 'empty'=>'---Select---'))); ?>;
            css_class_name = css_class_name.replace(/replace_with_counter/g,counter);
    
            var is_published = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.is_published', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            is_published = is_published.replace(/replace_with_counter/g,counter);
    
            //display_in_search_params  
            var display_in_search_params = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_search_params', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_search_params = display_in_search_params.replace(/replace_with_counter/g,counter);
               
            var display_in_view_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_view_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_view_page = display_in_view_page.replace(/replace_with_counter/g,counter);
    
            var display_in_add_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_add_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_add_page = display_in_add_page.replace(/replace_with_counter/g,counter);
            
            var display_in_edit_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_edit_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_edit_page = display_in_edit_page.replace(/replace_with_counter/g,counter);   
            
            //display_in_details_page            
            var display_in_details_page = <?php echo json_encode($this->Form->input('ProductModuleNewProductManagement.replace_with_counter.display_in_details_page', array('type'=>'radio', 'options'=>array('1'=>'Yes','0'=>'No'), 'label'=>false, 'legend'=>false))); ?>;
            display_in_details_page = display_in_details_page.replace(/replace_with_counter/g,counter);
                        
            var field_no = counter+1;
            
            var newRow = $(
                "<tr><td><fieldset><table>"+
                            "<tr><td colspan='3' style='padding:1px 200px; font-size:16px;'><legend><strong>Enter Field "+field_no+" Information</strong></legend></td></tr>"+
                            "<tr><td>Sorting Order</td><td class='colons'>:</td><td>"+field_sorting_order+"</td></tr>"+
                            "<tr><td>Field Name</td><td class='colons'>:</td><td>"+field_name+"</td></tr>"+
                            "<tr><td>Field Description</td><td class='colons'>:</td><td>"+field_description+"</td></tr>"+
                            "<tr><td>Data Type</td><td class='colons'>:</td><td>"+data_type+"</td></tr>"+
                            "<tr><td>Field Size</td><td class='colons'>:</td><td>"+field_size+"</td></tr>"+
                            "<tr><td>Is it a primary key?</td><td class='colons'>:</td><td>"+is_primary_key+"</td></tr>"+
                            "<tr><td>Does it support auto increment?</td><td class='colons'>:</td><td>"+is_auto_increment+"</td></tr>"+
                            "<tr><td>Does it check Exists Validity?</td><td class='colons'>:</td><td>"+is_exists_validity+"</td></tr>"+
                            "<tr><td>Is it a mandatory field?</td><td class='colons'>:</td><td>"+is_mandatory+"</td></tr>"+
                            "<tr><td>Binding control type</td><td class='colons'>:</td><td>"+control_type_for_add+"</td></tr>"+
                            "<tr><td>Select Option Source</td><td class='colons'>:</td><td>"+select_option_sources+"</td></tr>"+
                            "<tr><td>Display Field</td><td class='colons'>:</td><td>"+dropdown_display_field+"</td></tr>"+
                            "<tr><td>Value Field</td><td class='colons'>:</td><td>"+dropdown_value_field+"</td></tr>"+
                            "<tr><td>Dropdown Condition Field</td><td class='colons'>:</td><td>"+dropdown_condition_field+"</td></tr>"+
                            "<tr><td>Containable Model Names</td><td class='colons'>:</td><td>"+containable_model_names+"</td></tr>"+
                            "<tr><td>Associated Model for Select Option</td><td class='colons'>:</td><td>"+parent_model_name_for_select_option+"</td></tr>"+                
                            "<tr><td>Dependent Dropdown Display Field</td><td class='colons'>:</td><td>"+dependent_dropdown_display_field+"</td></tr>"+
                            "<tr><td>Dependent Dropdown Value Field</td><td class='colons'>:</td><td>"+dependent_dropdown_value_field+"</td></tr>"+
                            "<tr><td>CSS Class Name</td><td class='colons'>:</td><td>"+css_class_name+"</td></tr>" +
                            "<tr><td>Is Published? </td><td class='colons'>:</td><td>"+is_published+"</td></tr>"+ 
                            "<tr><td>Display in Search Parameter? </td><td class='colons'>:</td><td>"+display_in_search_params+"</td></tr>"+ 
                            "<tr><td>Display in View Page? </td><td class='colons'>:</td><td>"+display_in_view_page+"</td></tr>"+ 
                            "<tr><td>Display in Add Page? </td><td class='colons'>:</td><td>"+display_in_add_page+"</td></tr>"+ 
                            "<tr><td>Display in Edit Page? </td><td class='colons'>:</td><td>"+display_in_edit_page+"</td></tr>"+ 
                            "<tr><td>Display in Details Page? </td><td class='colons'>:</td><td>"+display_in_details_page+"</td></tr></table>"+ 
                            "<p style='padding:5px 250px;'><a id='remove_button' href='#' onclick='deleteRow(this);' class='mybtns'>Remove Field "+field_no+"</a></p>"+
                 "</fieldset></td></tr>"
            );
            counter++;
            $('#my_table').append(newRow);
        });
    });

    function deleteRow(obj) {
        $(obj).closest('tr').remove();
    }
</script>
<?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
?>